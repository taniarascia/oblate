<?php
/*
Plugin Name: WP Power Stats
Plugin URI: http://www.websivu.com/wp-power-stats/
Description: Powerful real-time statistics of your visitors for your WordPress site.
Version: 2.1.4
Author: Igor Buyanov
Text Domain: power-stats
Author URI: http://www.websivu.com
License: GPL2
*/

if (!empty(PowerStats::$options)) return true;

class PowerStats
{
    public static $version = '2.1.4';
    public static $options = array();
    public static $wpdb = '';
    protected static $options_hash = '';
    protected static $data = array();
    protected static $data_js = array();
    protected static $vendors = array();

	const HTML_ENCODING_QUOTE_STYLE = ENT_QUOTES;

    /**
     * Initialize variables and actions
     */
    public static function init()
    {
        // Load all the settings
        self::$options = get_option('power_stats_options', array());
        if (empty(self::$options)) {

            self::$options = self::init_options();

            // Save WP Power Stats options in the database
            add_option('power_stats_options', self::$options, '', 'no');

        } else {

            self::$options = array_merge(self::init_options(), self::$options);

        }

        // Allow other plugins to edit the options
        self::$options = apply_filters('power_stats_options', self::$options);

        // Set DB connection
        self::$wpdb = $GLOBALS['wpdb'];

        // Hook a DB clean-up routine to the daily cron job
        add_action('power_stats_purge', array(__CLASS__, 'power_stats_purge'));

        // Set the options' hash for tracking changes in the options
        self::$options_hash = md5(serialize(self::$options));

        // Include and initialize vendor libraries
        if (!class_exists('Browser') && !class_exists('UserAgent') && !class_exists('BrowserDetection')) require_once POWER_STATS_DIR . '/admin/vendor/browser-os/Browser.php';
        if (!class_exists('Mobile_Detect')) require_once POWER_STATS_DIR . '/admin/vendor/mobile-detect/Mobile_Detect.php';
        require_once POWER_STATS_DIR . '/admin/vendor/search-terms/SearchEngines.php';
        require_once POWER_STATS_DIR . '/admin/vendor/search-terms/Countries.php';
        require_once POWER_STATS_DIR . '/admin/vendor/tabgeo_country_v4/tabgeo_country_v4.php';

        self::$vendors['browser'] = new Browser($_SERVER['HTTP_USER_AGENT']);
        self::$vendors['device'] = new Mobile_Detect();

        // Add the server and client-side tracker
        if (self::$options['tracker_active'] == 'yes' && (!is_admin() || (is_admin() && self::$options['ignore_admin_pages'] == 'no'))) {

            // Add additional custom exclusion filters
            $track_filter = apply_filters('power_stats_filter_pre_tracking', true);
            $action_to_hook = is_admin() ? 'admin_init' : 'wp';

            // Hook the tracker script
            if ($track_filter) {
                add_action($action_to_hook, array(__CLASS__, 'power_stats_enqueue_tracking_script'), 15);
                if (self::$options['track_users'] == 'yes') add_action('login_enqueue_scripts', array(__CLASS__, 'power_stats_enqueue_tracking_script'), 10);
            }
        }

        // Update the options before shutting down
        add_action('shutdown', array(__CLASS__, 'power_stats_save_options'));
    }

    /**
     * Initialize plugin options
     */
    public static function init_options()
    {
        $options = array(
            // Internal
            'version' => self::$version,
            'secret' => get_option('power_stats_secret', md5(time())),
            'show_admin_notice' => 1,

            // General
            'tracker_active' => 'yes',
            'track_users' => 'yes',
            'auto_purge' => 150,
            'dashboard_widget' => 'no',
            'replace_dashboard' => 'no',

            // Exclusions
            'ignore_bots' => 'yes',
            'ignore_spam_visitors' => 'yes',
            'ignore_admin_pages' => 'yes',
            'ignore_ip' => '',
            'ignore_browser' => '',
            'ignore_country' => '',
            'ignore_referrer' => '',
            'ignore_user' => '',
            'ignore_pages' => '',
            'ignore_do_not_track' => 'no',

            // Permissions
            'view_roles' => '',
            'admin_roles' => '',

            // Advanced
            'outbound_tracking' => 'yes',
            'session_duration' => '1800',
            'extend_session' => 'no',
        );

        return $options;
    }

    /**
     * Saves the options in the database, if necessary
     */
    public static function power_stats_save_options()
    {
        if (self::$options_hash == md5(serialize(self::$options))) return true;
        return update_option('power_stats_options', PowerStats::$options);
    }

    /**
     * Enqueue a javascript to track users' screen resolution and other browser-based information
     */
    public static function power_stats_enqueue_tracking_script()
    {
        wp_register_script('wp_power_stats', plugins_url('/wp-power-stats.js', __FILE__), array(), null, true);

        // Pass some information to Javascript
        $params = array(
            'ajaxurl' => admin_url('admin-ajax.php')
        );

        $encoded_ci = base64_encode(serialize(self::get_resource()));
        $params['ci'] = $encoded_ci . '.' . md5($encoded_ci . self::$options['secret']);

        if (self::$options['outbound_tracking'] == 'no') {
            $params['outbound_tracking'] = 'false';
        }

        $params = apply_filters('power_stats_js_params', $params);

        wp_enqueue_script('wp_power_stats');
        wp_localize_script('wp_power_stats', 'PowerStatsParams', $params);
    }

    /**
     * Remove old entries from the database
     */
    public static function power_stats_purge()
    {
        $purge_interval = intval(self::$options['auto_purge']);

        if ($purge_interval <= 0) return;

        // Delete old entries
        $days_ago = strtotime(date_i18n('Y-m-d H:i:s')." -$purge_interval days");
        self::$wpdb->query("DELETE FROM {$GLOBALS['wpdb']->prefix}power_stats_visits WHERE `date` < $days_ago");

        // Optimize table
        self::$wpdb->query("OPTIMIZE TABLE {$GLOBALS['wpdb']->prefix}power_stats_visits");
    }

    /**
     * Client-side tracking
     */
    public static function power_stats_track()
    {

        if (self::$options['tracker_active'] == 'yes') {

            // Get JavaScript data
            self::$data_js['referer'] = urldecode($_POST['ref']);

            list(self::$data_js['ci'], $nonce) = explode('.', $_POST['ci']);

            if ($nonce !== md5(self::$data_js['ci'] . self::$options['secret'])) {
                exit;
            }

            // Get back-end data
            self::get_visitor_data();

            // Send the ID back to Javascript to track future interactions
            exit(self::$data['id'].'.'.md5(self::$data['id'].self::$options['secret']));
        }
    }

    /**
     * Core tracking functionality
     */
    public static function get_visitor_data()
    {
        // Get date and time
        self::$data['date'] = self::get_date();
        self::$data['time'] = self::get_time();

        // Get device
        self::$data['device'] = self::get_device();

        // Get client IP
        self::$data['ip'] = self::get_ip();

        // IP exclusion check
        if (!empty(self::$data['ip']) && !empty(self::$options['ignore_ip'])) {
            $ip_exclusions = explode("\n", self::$options['ignore_ip']);
            foreach ($ip_exclusions as $ip_exclusion) {
                $ip_exclusion = trim($ip_exclusion);
                if (strlen($ip_exclusion) > 6) {
                    if (self::ip_match($ip_exclusion, self::$data['ip'])) {
                        return false;
                    }
                }
            }
        }

        // Get client's country
        self::$data['country'] = self::get_country();

        // Country exclusion
        if (!empty(self::$options['ignore_country']) && is_string(self::$options['ignore_country']) && stripos(self::$options['ignore_country'], self::$data['country']) !== false) {
            return false;
        }

        // Do not track header
        if (!empty(self::$options['ignore_do_not_track']) && isset($_SERVER['HTTP_DNT']) && $_SERVER['HTTP_DNT'] == 1) {
            return false;
        }

        // Ignore prefetch requests
        if ((isset($_SERVER['HTTP_X_MOZ']) && (strtolower($_SERVER['HTTP_X_MOZ']) == 'prefetch')) || (isset($_SERVER["HTTP_X_PURPOSE"]) && (strtolower($_SERVER['HTTP_X_PURPOSE']) == 'preview'))) {
            return false;
        }

        // Get client's language
        self::$data['language'] = self::get_language();

        // Get client's referer
        self::$data['referer'] = self::get_referer();

        // Get client's browser name
        self::$data['browser'] = self::get_browser();

        // Get client's browser version
        self::$data['browser_version'] = self::get_browser_version();

        // Get client's user agent
        self::$data['user_agent'] = self::get_user_agent();

        // Get client's OS name
        self::$data['os'] = self::get_os();

        // Get search engine data
        self::$data['search_engine'] = self::get_search_engine_data();

        // Get search engine keywords
        self::$data['keywords'] = (is_array(self::$data['search_engine'])) ? self::$data['search_engine']['keywords'] : "";

        // Bot exclusion
        $needles = array('Googlebot','DoCoMo','YandexBot','bingbot','ia_archiver','AhrefsBot','Ezooms','GSLFbot','WBSearchBot','Twitterbot','TweetmemeBot','Twikle','PaperLiBot','Wotbox','UnwindFetchor','facebookexternalhit');
        if (self::stripos_array(self::$data['user_agent'], $needles)) return false;

        // User Agent Exclusion
        foreach (self::string_to_array(self::$options['ignore_browser']) as $filter) {
            $pattern = str_replace(array('\*', '\!'), array('(.*)', '.'), preg_quote($filter, '/'));
            if (preg_match("~^$pattern$~i", self::$data['browser']['name'] . '/' . self::$data['browser']['version']) || preg_match("~^$pattern$~i", self::$data['browser']['name']) || preg_match("~^$pattern$~i", self::$data['browser']['user_agent'])) {
                return false;
            }
        }

        // Get client's language
        self::$data['language'] = self::get_language();

        // Get WordPress specific information
        self::$data['user'] = self::get_user();

        // User Exclusion
        if (!empty($GLOBALS['current_user']->ID)) {

            // Don't track logged-in users, if the corresponding option is enabled
            if (self::$options['track_users'] == 'no') {
                return false;
            }

            if (!empty(self::$options['ignore_user']) && is_string(self::$options['ignore_user']) && strpos(self::$options['ignore_user'], $GLOBALS['current_user']->data->user_login) !== false) {
                return false;
            }

            self::$data['user'] = $GLOBALS['current_user']->data->user_login;

        } elseif (isset($_COOKIE['comment_author_' . COOKIEHASH])) {

            $spam_comment = self::$wpdb->get_row("SELECT comment_author, COUNT(*) comment_count FROM {$GLOBALS['wpdb']->prefix}comments WHERE INET_ATON(comment_author_IP) = '" . sprintf("%u", self::$data['ip']) . "' AND comment_approved = 'spam' GROUP BY comment_author LIMIT 0,1", ARRAY_A);
            if (isset($spam_comment['comment_count']) && $spam_comment['comment_count'] > 0) {
                if (self::$options['ignore_spam_visit'] == 'yes') {
                    return false;
                } else {
                    self::$data['user'] = $spam_comment['comment_author'];
                }
                self::$data['is_spam_visit'] = true;
            } else {
                self::$data['user'] = $_COOKIE['comment_author_' . COOKIEHASH];
            }
        }

        // Resource Information
        self::$data['resource'] = (isset(self::$data_js['ci']) && is_array(self::$data_js)) ? unserialize(base64_decode(self::$data_js['ci'])) : self::get_resource();

        // Page Exclusion
        if (!empty(self::$data['resource'])) {
            foreach (self::string_to_array(self::$options['ignore_pages']) as $filter) {
                $pattern = str_replace(array('\*', '\!'), array('(.*)', '.'), preg_quote($filter, '/'));
                if (preg_match("@^$pattern$@i", self::$data['resource'])) {
                    return false;
                }
            }
        }

        // Assign a visit id if set in the cookies
        self::insert_data();

        // Check if this is a new visitor
        $cookie_filter = apply_filters('power_stats_set_visit_cookie', true);
        if ($cookie_filter) {
            if (self::$data['id'] > 0 || self::$options['extend_session'] == 'yes') {
                @setcookie('power_stats_tracking_code', self::$data['id'].'.'.md5(self::$data['id'].self::$options['secret']), time() + self::$options['session_duration'], COOKIEPATH);
            }
        }

    }

    /**
     * Converts a series of comma separated values into an array
     */
    public static function string_to_array($option = '')
    {
        if (empty($option) || !is_string($option))
            return array();
        else
            return array_map('trim', explode(',', $option));
    }

    /**
     * Returns an array of values to be inserted into DB
     */
    protected static function get_insert_data()
    {
        return array("ip" => self::$data['ip'], "date" => self::$data['date'], "time" => self::$data['time'], "country" => self::$data['country'], "device" => self::$data['device'], "referer" => self::$data['referer'], "browser" => self::$data['browser'], "browser_version" => self::$data['browser_version'], "os" => self::$data['os'], "user_agent" => self::$data['user_agent'], "language" => self::$data['language'], "user" => self::$data['user'], "is_search_engine" => self::is_search_engine());
    }

    /**
     * Increment keyword count, if the keyword is not found, return an array with data to insert
     * @return array - array with keywords to insert
     */
    protected static function get_insert_search_data()
    {
        if (!empty(self::$data['keywords'])) {

            $rows_updated = self::$wpdb->query(self::$wpdb->prepare("UPDATE ". $GLOBALS['wpdb']->prefix ."power_stats_searches SET `count` = count + 1 WHERE `terms` = '%s'", self::$data['keywords']));

            if ($rows_updated === 0 && $rows_updated !== false) {
                return array('terms' => self::$data['keywords'], 'count' => 1);
            }

        }

        return array();
    }

    protected static function get_insert_referer_data()
    {
        if (!empty(self::$data['referer'])) {

            $rows_updated = self::$wpdb->query(self::$wpdb->prepare("UPDATE ". $GLOBALS['wpdb']->prefix ."power_stats_referers SET `count` = `count` + 1 WHERE `referer` = '%s'", self::$data['referer']));

            if ($rows_updated === 0 && $rows_updated !== false) {
                return array('referer' => self::$data['referer'], 'count' => 1);
            }

        }

        return array();
    }

    protected static function get_insert_post_data()
    {

        if (!empty(self::$data['resource']) && !empty(self::$data['resource']['content_id'])) {

            $rows_updated = self::$wpdb->query(self::$wpdb->prepare("UPDATE ". $GLOBALS['wpdb']->prefix ."power_stats_posts SET `hits` = `hits` + 1 WHERE `date` = '%s' AND  `post_id` = '%d'", self::$data['date'], self::$data['resource']['content_id']));

            if ($rows_updated === 0 && $rows_updated !== false) {
                return array("post_id" => self::$data['resource']['content_id'], "date" => self::$data['date'], "hits" => 1);
            }

        }

        return array();
    }

    protected static function get_insert_pageview_data()
    {
        $rows_updated = self::$wpdb->query(self::$wpdb->prepare("UPDATE ". $GLOBALS['wpdb']->prefix ."power_stats_pageviews SET `hits` = `hits` + 1 WHERE `date` = '%s'", self::$data['date']));

        if ($rows_updated === 0 && $rows_updated !== false) {
            return array("date" => self::$data['date'], "hits" => 1);
        }

        return array();
    }

    protected static function get_insert_os_data()
    {
        if (!empty(self::$data['os'])) {

            $rows_updated = self::$wpdb->query(self::$wpdb->prepare("UPDATE ". $GLOBALS['wpdb']->prefix ."power_stats_os SET `count` = count + 1 WHERE `os` = '%s'", self::$data['os']));

            if ($rows_updated === 0 && $rows_updated !== false) {
                return array("os" => self::$data['os'], "count" => 1);
            }

        }

        return array();
    }

    protected static function get_insert_browser_data()
    {
        if (!empty(self::$data['browser'])) {

            $rows_updated = self::$wpdb->query(self::$wpdb->prepare("UPDATE ". $GLOBALS['wpdb']->prefix ."power_stats_browsers SET `count` = count + 1 WHERE `browser` = '%s'", self::$data['browser']));

            if ($rows_updated === 0 && $rows_updated !== false) {
                return array("browser" => self::$data['browser'], "count" => 1);
            }

        }

        return array();
    }

    /**
     * @return int
     */
    protected static function get_visitor_id()
    {
        if (isset($_COOKIE['power_stats_tracking_code'])) {
            list($identifier, $control_code) = explode('.', $_COOKIE['power_stats_tracking_code']);

            // Make sure only authorized information is recorded
            if ($control_code != md5($identifier . self::$options['secret'])) return 0;

            return intval($identifier);
        }
    }

    /**
     * Inserts data in the DB and assigns the visitor id
     */
    protected static function insert_data()
    {
        $visitor_cookie = self::get_visitor_id();

        // Old visitor
        if ($visitor_cookie > 0) {

            self::$data['id'] = $visitor_cookie;
            self::insert_row(self::get_insert_data(), $GLOBALS['wpdb']->prefix . 'power_stats_visits');
            self::insert_row(self::get_insert_pageview_data(), $GLOBALS['wpdb']->prefix . 'power_stats_pageviews');

        } else {

            self::$data['id'] = self::insert_row(self::get_insert_data(), $GLOBALS['wpdb']->prefix . 'power_stats_visits', true);
            self::insert_row(self::get_insert_pageview_data(), $GLOBALS['wpdb']->prefix . 'power_stats_pageviews');
            self::insert_row(self::get_insert_search_data(), $GLOBALS['wpdb']->prefix . 'power_stats_searches');
            self::insert_row(self::get_insert_referer_data(), $GLOBALS['wpdb']->prefix . 'power_stats_referers');
            self::insert_row(self::get_insert_post_data(), $GLOBALS['wpdb']->prefix . 'power_stats_posts');
            self::insert_row(self::get_insert_os_data(), $GLOBALS['wpdb']->prefix . 'power_stats_os');
            self::insert_row(self::get_insert_browser_data(), $GLOBALS['wpdb']->prefix . 'power_stats_browsers');

        }

        if (!isset(self::$data['id']) || empty(self::$data['id'])) self::$data['id'] = substr(md5(rand()), 0, 7);
    }

    /**
     * Get an IP address from the server variables
     * @return mixed|string
     */
    protected static function get_ip()
    {
        $ip = "";
        $header_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');

        foreach ($header_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = filter_var(trim($ip), FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
                }
            }
        }

        return $ip;
    }

    /**
     * Checks if $ip_exclusion matches $ip
     * @param $ip_exclusion
     * @param $ip
     * @return bool if ip_exclusion matches ip
     */
    protected function ip_match($ip_exclusion, $ip)
    {
        $ip_arr = explode('/', $ip_exclusion);

        if (!isset($ip_arr[1])) $ip_arr[1] = 32;
        $network_long = ip2long($ip_arr[0]);

        $x = ip2long($ip_arr[1]);
        $mask = long2ip($x) == $ip_arr[1] ? $x : 0xffffffff << (32 - $ip_arr[1]);
        $ip_long = ip2long($ip);

        return ($ip_long & $mask) == ($network_long & $mask);
    }

    /**
     * Get client's browser version
     * @return string
     */
    protected static function get_browser_version()
    {

        $version = self::$vendors["browser"]->getVersion();
        return (empty($version) || $version == 'unknown') ? '' : $version;

    }

    /**
     * Get the user agent string
     * @return mixed
     */
    protected static function get_user_agent()
    {
        return self::$vendors["browser"]->getUserAgent();
    }

    protected static function get_os($unknown_is_empty = true) {

        $user_agent = self::get_user_agent();
        $os_list = array(
            'windows' => 'Windows',
            'mac' => 'Mac',
            'linux|sun|bsd|beos' => 'Linux'
        );

        $matched = 0;

        foreach($os_list as $match => $os) {
            if (preg_match("/$match/i", $user_agent)) {
                $matched = 1;
                break;
            }
        }

        return (($matched && !empty($os)) ? $os : (($unknown_is_empty) ? '' : 'Unknown'));
    }

    protected static function get_search_engine_data() {

        return self::parse_search_engine(self::$data['referer']);

    }

    /**
     * Get client device type
     * @return string
     */
    protected static function get_device()
    {
        if (self::$vendors["device"]->isTablet()) {
            return "tablet";
        } else if (self::$vendors["device"]->isMobile()) {
            return "mobile";
        } else {
            return "desktop";
        }
    }

    /**
     * Get current time
     * @return string time in UTC
     */
    protected static function get_time() {
        return current_time('mysql');
    }

    /**
     * Get current date
     * @return string date in UTC
     */
    protected static function get_date() {
        return substr(self::get_time(), 0, strpos(self::get_time(), " "));
    }

    /**
     * Get the country by IP
     * @return mixed|string
     */
    protected static function get_country()
    {
        $ip = self::get_ip();
        $country = (!empty($ip)) ? tabgeo_country_v4($ip) : "";
        return $country;
    }

    /**
     * Extracts the accepted language from browser headers
     * @return mixed|string
     */
    protected static function get_language()
    {
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            // Get the language before the first delimiter, comma is the delimiter in Safari
            preg_match("/([^,;]*)/", $_SERVER["HTTP_ACCEPT_LANGUAGE"], $array_languages);

            // Fix some codes, the correct syntax is with minus (-) not underscore (_)
            return str_replace( "_", "-", strtolower( $array_languages[0] ) );
        }
        return '';  // Unknown language
    }

    /**
     * @return mixed|string
     */
    protected static function get_referer()
    {
        return (!empty(self::$data_js['referer'])) ? self::$data_js['referer'] : self::$data['referer'] = $_SERVER['HTTP_REFERER'];
    }

    /**
     * @return mixed|string
     */
    protected static function get_browser()
    {
        return self::$vendors["browser"]->getBrowser();
    }

    /**
     * Get the username of currently logged in user
     */
    protected static function get_user()
    {
        return (isset($GLOBALS['current_user']->data->user_login)) ? $GLOBALS['current_user']->data->user_login : "";
    }

    protected static function is_search_engine() {

        return (self::$data['search_engine']['name'] === false);

    }


    /**
     * Extracts a keyword from a raw not encoded URL.
     * Will only extract keyword if a known search engine has been detected.
     * Returns the keyword:
     * - in UTF8: automatically converted from other charsets when applicable
     * - strtolowered: "QUErY test!" will return "query test!"
     * - trimmed: extra spaces before and after are removed
     *
     * Lists of supported search engines can be found in /core/DataFiles/SearchEngines.php
     * The function returns false when a keyword couldn't be found.
     *     eg. if the url is "http://www.google.com/partners.html" this will return false,
     *       as the google keyword parameter couldn't be found.
     *
     * @param string $referrerUrl  URL referer URL, eg. $_SERVER['HTTP_REFERER']
     * @return array|false false if a keyword couldn't be extracted,
     *                        or array(
     *                            'name' => 'Google',
     *                            'keywords' => 'my searched keywords')
     */
    protected static function parse_search_engine($referrerUrl) {

        global $PowerStats_SearchEngines, $PowerStats_SearchEngines_NameToUrl;

        $refererParsed = @parse_url($referrerUrl);
        $refererHost = '';
        if (isset($refererParsed['host'])) {
            $refererHost = $refererParsed['host'];
        }
        if (empty($refererHost)) {
            return false;
        }
        // some search engines (eg. Bing Images) use the same domain
        // as an existing search engine (eg. Bing), we must also use the url path
        $refererPath = '';
        if (isset($refererParsed['path'])) {
            $refererPath = $refererParsed['path'];
        }

        // no search query
        if (!isset($refererParsed['query'])) {
            $refererParsed['query'] = '';
        }
        $query = $refererParsed['query'];

        // Google Referrers URLs sometimes have the fragment which contains the keyword
        if (!empty($refererParsed['fragment'])) {
            $query .= '&' . $refererParsed['fragment'];
        }

        $searchEngines = $PowerStats_SearchEngines;

        $hostPattern = self::getLossyUrl($refererHost);
        if (array_key_exists($refererHost . $refererPath, $searchEngines)) {
            $refererHost = $refererHost . $refererPath;
        } elseif (array_key_exists($hostPattern . $refererPath, $searchEngines)) {
            $refererHost = $hostPattern . $refererPath;
        } elseif (array_key_exists($hostPattern, $searchEngines)) {
            $refererHost = $hostPattern;
        } elseif (!array_key_exists($refererHost, $searchEngines)) {
            if (!strncmp($query, 'cx=partner-pub-', 15)) {
                // Google custom search engine
                $refererHost = 'google.com/cse';
            } elseif (!strncmp($refererPath, '/pemonitorhosted/ws/results/', 28)) {
                // private-label search powered by InfoSpace Metasearch
                $refererHost = 'wsdsold.infospace.com';
            } elseif (strpos($refererHost, '.images.search.yahoo.com') != false) {
                // Yahoo! Images
                $refererHost = 'images.search.yahoo.com';
            } elseif (strpos($refererHost, '.search.yahoo.com') != false) {
                // Yahoo!
                $refererHost = 'search.yahoo.com';
            } else {
                return false;
            }
        }
        $searchEngineName = $searchEngines[$refererHost][0];
        $variableNames = null;
        if (isset($searchEngines[$refererHost][1])) {
            $variableNames = $searchEngines[$refererHost][1];
        }
        if (!$variableNames) {
            $searchEngineNames = $PowerStats_SearchEngines_NameToUrl;
            $url = $searchEngineNames[$searchEngineName];
            $variableNames = $searchEngines[$url][1];
        }
        if (!is_array($variableNames)) {
            $variableNames = array($variableNames);
        }

        $key = null;
        if ($searchEngineName === 'Google Images'
            || ($searchEngineName === 'Google' && strpos($referrerUrl, '/imgres') !== false)
        ) {
            if (strpos($query, '&prev') !== false) {
                $query = urldecode(trim(self::getParameterFromQueryString($query, 'prev')));
                $query = str_replace('&', '&amp;', strstr($query, '?'));
            }
            $searchEngineName = 'Google Images';
        } else if ($searchEngineName === 'Google'
            && (strpos($query, '&as_') !== false || strpos($query, 'as_') === 0)
        ) {
            $keys = array();
            $key = self::getParameterFromQueryString($query, 'as_q');
            if (!empty($key)) {
                array_push($keys, $key);
            }
            $key = self::getParameterFromQueryString($query, 'as_oq');
            if (!empty($key)) {
                array_push($keys, str_replace('+', ' OR ', $key));
            }
            $key = self::getParameterFromQueryString($query, 'as_epq');
            if (!empty($key)) {
                array_push($keys, "\"$key\"");
            }
            $key = self::getParameterFromQueryString($query, 'as_eq');
            if (!empty($key)) {
                array_push($keys, "-$key");
            }
            $key = trim(urldecode(implode(' ', $keys)));
        }

        if ($searchEngineName === 'Google') {
            // top bar menu
            $tbm = self::getParameterFromQueryString($query, 'tbm');
            switch ($tbm) {
                case 'isch':
                    $searchEngineName = 'Google Images';
                    break;
                case 'vid':
                    $searchEngineName = 'Google Video';
                    break;
                case 'shop':
                    $searchEngineName = 'Google Shopping';
                    break;
            }
        }

        if (empty($key)) {
            foreach ($variableNames as $variableName) {
                if ($variableName[0] == '/') {
                    // regular expression match
                    if (preg_match($variableName, $referrerUrl, $matches)) {
                        $key = trim(urldecode($matches[1]));
                        break;
                    }
                } else {
                    // search for keywords now &vname=keyword
                    $key = self::getParameterFromQueryString($query, $variableName);
                    $key = trim(urldecode($key));

                    // Special case: Google & empty q parameter
                    if (empty($key)
                        && $variableName == 'q'

                        && (
                            // Google search with no keyword
                            ($searchEngineName == 'Google'
                                && ( // First, they started putting an empty q= parameter
                                    strpos($query, '&q=') !== false
                                    || strpos($query, '?q=') !== false
                                    // then they started sending the full host only (no path/query string)
                                    || (empty($query) && (empty($refererPath) || $refererPath == '/') && empty($refererParsed['fragment']))
                                )
                            )
                            // search engines with no keyword
                            || $searchEngineName == 'Google Images'
                            || $searchEngineName == 'DuckDuckGo')
                    ) {
                        $key = false;
                    }
                    if (!empty($key)
                        || $key === false
                    ) {
                        break;
                    }
                }
            }
        }

        // $key === false is the special case "No keyword provided" which is a Search engine match
        if ($key === null
            || $key === ''
        ) {
            return false;
        }

        if (!empty($key)) {
            if (function_exists('iconv')
                && isset($searchEngines[$refererHost][3])
            ) {
                // accepts string, array, or comma-separated list string in preferred order
                $charsets = $searchEngines[$refererHost][3];
                if (!is_array($charsets)) {
                    $charsets = explode(',', $charsets);
                }

                if (!empty($charsets)) {
                    $charset = $charsets[0];
                    if (count($charsets) > 1
                        && function_exists('mb_detect_encoding')
                    ) {
                        $charset = mb_detect_encoding($key, $charsets);
                        if ($charset === false) {
                            $charset = $charsets[0];
                        }
                    }

                    $newkey = @iconv($charset, 'UTF-8//IGNORE', $key);
                    if (!empty($newkey)) {
                        $key = $newkey;
                    }
                }
            }

            $key = self::mb_strtolower($key);
        }

        return array(
            'name'     => $searchEngineName,
            'keywords' => $key,
        );
    }

    /**
     * Returns details about the resource being accessed
     */
    protected static function get_resource()
    {
        $content_info = array('content_type' => 'unknown', 'category' => '');

        // Mark 404 pages
        if (is_404()) {
            $content_info['content_type'] = '404';
        } // Type
        elseif (is_single()) {
            if (($post_type = get_post_type()) != 'post') $post_type = 'cpt:' . $post_type;
            $content_info['content_type'] = $post_type;
            $content_info_array = array();
            foreach (get_object_taxonomies($GLOBALS['post']) as $a_taxonomy) {
                $terms = get_the_terms($GLOBALS['post']->ID, $a_taxonomy);
                if (is_array($terms)) {
                    foreach ($terms as $a_term) $content_info_array[] = $a_term->term_id;
                    $content_info['category'] = implode(',', $content_info_array);
                }
            }
            $content_info['content_id'] = $GLOBALS['post']->ID;
        } elseif (is_page()) {
            $content_info['content_type'] = 'page';
            $content_info['content_id'] = $GLOBALS['post']->ID;
        } elseif (is_attachment()) {
            $content_info['content_type'] = 'attachment';
        } elseif (is_singular()) {
            $content_info['content_type'] = 'singular';
        } elseif (is_post_type_archive()) {
            $content_info['content_type'] = 'post_type_archive';
        } elseif (is_tag()) {
            $content_info['content_type'] = 'tag';
            $list_tags = get_the_tags();
            if (is_array($list_tags)) {
                $tag_info = array_pop($list_tags);
                if (!empty($tag_info)) $content_info['category'] = "$tag_info->term_id";
            }
        } elseif (is_tax()) {
            $content_info['content_type'] = 'taxonomy';
        } elseif (is_category()) {
            $content_info['content_type'] = 'category';
            $list_categories = get_the_category();
            if (is_array($list_categories)) {
                $cat_info = array_pop($list_categories);
                if (!empty($cat_info)) $content_info['category'] = "$cat_info->term_id";
            }
        } elseif (is_date()) {
            $content_info['content_type'] = 'date';
        } elseif (is_author()) {
            $content_info['content_type'] = 'author';
        } elseif (is_archive()) {
            $content_info['content_type'] = 'archive';
        } elseif (is_search()) {
            $content_info['content_type'] = 'search';
        } elseif (is_feed()) {
            $content_info['content_type'] = 'feed';
        } elseif (is_home()) {
            $content_info['content_type'] = 'home';
        } elseif (in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'))) {
            $content_info['content_type'] = 'login';
        } elseif (is_admin()) {
            $content_info['content_type'] = 'admin';
        }

        if (is_paged()) {
            $content_info['content_type'] .= ',paged';
        }

        // Author
        if (is_singular()) {
            $content_info['author'] = get_the_author_meta('user_login', $GLOBALS['post']->post_author);
        }

        return $content_info;
    }

    /**
     * Stores the information (array) in the appropriate table and returns the corresponding ID
     *
     * @param array $data
     * @param string $table
     * @param bool $showInsertedRowID
     * @return bool|int
     */
    protected static function insert_row($data = array(), $table = '', $showInsertedRowID = false)
    {
        if (empty($data) || empty($table)) return false;
        self::$wpdb->query(self::$wpdb->prepare("INSERT IGNORE INTO $table (". implode(", ", array_keys($data)) .') VALUES ('. substr(str_repeat('%s,', count($data)), 0, -1) . ")", $data));
        if ($showInsertedRowID) return intval(self::$wpdb->insert_id);
    }

    /**
     * Returns list of valid country codes
     *
     * @see vendor/search-terms/Countries.php
     *
     * @param bool $includeInternalCodes
     * @return array  Array of (2 letter ISO codes => 3 letter continent code)
     */
    public static function getCountriesList($includeInternalCodes = false) {

        $countriesList = $GLOBALS['PowerStats_CountryList'];
        $extras = $GLOBALS['PowerStats_CountryList_Extras'];

        if ($includeInternalCodes) {
            return array_merge($countriesList, $extras);
        }

        return $countriesList;
    }


    /**
     * Reduce URL to more minimal form.  2 letter country codes are
     * replaced by '{}', while other parts are simply removed.
     *
     * Examples:
     *   www.example.com -> example.com
     *   search.example.com -> example.com
     *   m.example.com -> example.com
     *   de.example.com -> {}.example.com
     *   example.de -> example.{}
     *   example.co.uk -> example.{}
     *
     * @param string $url
     * @return string
     */
    public static function getLossyUrl($url) {
        static $countries;
        if (!isset($countries)) {
            $countries = implode('|', array_keys(self::getCountriesList(true)));
        }

        return preg_replace(
            array(
                '/^(w+[0-9]*|search)\./',
                '/(^|\.)m\./',
                '/(\.(com|org|net|co|it|edu))?\.(' . $countries . ')(\/|$)/',
                '/(^|\.)(' . $countries . ')\./',
            ),
            array(
                '',
                '$1',
                '.{}$4',
                '$1{}.',
            ),
            $url);
    }

    /**
     * Returns the value of a GET parameter $parameter in an URL query $urlQuery
     *
     * @param string $urlQuery  result of parse_url()['query'] and htmlentitied (& is &amp;) eg. module=test&amp;action=toto or ?page=test
     * @param string $parameter
     * @return string|bool  Parameter value if found (can be the empty string!), null if not found
     */
    public static function getParameterFromQueryString($urlQuery, $parameter) {
        $nameToValue = self::getArrayFromQueryString($urlQuery);
        if (isset($nameToValue[$parameter])) {
            return $nameToValue[$parameter];
        }
        return null;
    }

    /**
     * Sanitize a single input value
     *
     * @param string $value
     * @return string  sanitized input
     */
    public static function sanitizeInputValue($value) {

        // $_GET and $_REQUEST already urldecode()'d
        // decode
        // note: before php 5.2.7, htmlspecialchars() double encodes &#x hex items
        $value = html_entity_decode($value, self::HTML_ENCODING_QUOTE_STYLE, 'UTF-8');

        // filter
        $value = str_replace(array("\n", "\r", "\0"), '', $value);

        // escape
        $tmp = @htmlspecialchars($value, self::HTML_ENCODING_QUOTE_STYLE, 'UTF-8');

        // note: php 5.2.5 and above, htmlspecialchars is destructive if input is not UTF-8
        if ($value != '' && $tmp == '') {
            // convert and escape
            $value = utf8_encode($value);
            $tmp = htmlspecialchars($value, self::HTML_ENCODING_QUOTE_STYLE, 'UTF-8');
        }
        return $tmp;
    }

    /**
     * Returns an URL query string in an array format
     *
     * @param string $urlQuery
     * @return array  array( param1=> value1, param2=>value2)
     */
    public static function getArrayFromQueryString($urlQuery) {

        if (strlen($urlQuery) == 0) {
            return array();
        }
        if ($urlQuery[0] == '?') {
            $urlQuery = substr($urlQuery, 1);
        }
        $separator = '&';

        $urlQuery = $separator . $urlQuery;
        //		$urlQuery = str_replace(array('%20'), ' ', $urlQuery);
        $refererQuery = trim($urlQuery);

        $values = explode($separator, $refererQuery);

        $nameToValue = array();

        foreach ($values as $value) {
            $pos = strpos($value, '=');
            if ($pos !== false) {
                $name = substr($value, 0, $pos);
                $value = substr($value, $pos + 1);
                if ($value === false) {
                    $value = '';
                }
            } else {
                $name = $value;
                $value = false;
            }
            if (!empty($name)) {
                $name = self::sanitizeInputValue($name);
            }
            if (!empty($value)) {
                $value = self::sanitizeInputValue($value);
            }

            // if array without indexes
            $count = 0;
            $tmp = preg_replace('/(\[|%5b)(]|%5d)$/i', '', $name, -1, $count);
            if (!empty($tmp) && $count) {
                $name = $tmp;
                if (isset($nameToValue[$name]) == false || is_array($nameToValue[$name]) == false) {
                    $nameToValue[$name] = array();
                }
                array_push($nameToValue[$name], $value);
            } else if (!empty($name)) {
                $nameToValue[$name] = $value;
            }
        }
        return $nameToValue;
    }

    public static function stripos_array($haystack, $needles=array(), $offset=0) {

        $chr = array();
        foreach($needles as $needle) {
            $res = stripos($haystack, $needle, $offset);
            if ($res !== false) $chr[$needle] = $res;
        }

        if(empty($chr)) return false;
        return min($chr);
    }

    /**
     * multi-byte strtolower() - UTF-8
     *
     * @param string $string
     * @return string
     */
    public static function mb_strtolower($string) {

        if (function_exists('mb_strtolower')) {
            return mb_strtolower($string, 'UTF-8');
        }

        return strtolower($string);
    }

    /**
     * Show dashboard widget
     */
    public static function dashboard_widget() {
        if (PowerStats::$options['dashboard_widget'] == "yes") {
            require_once('dashboard-widget.php');
            PowerStatsDashboardWidget::init();
        }
    }

}

/**
 * Initialize widget
 */
function power_stats_init_widget() {
    require_once('widget.php');
    register_widget('PowerStatsWidget');
}

/**
 * Initialize dashboard widget
 */
function power_stats_init_dashboard_widget() {
    add_action('wp_dashboard_setup', array('PowerStats', 'dashboard_widget'));
}

if (function_exists('add_action')) {

    if (!defined('POWER_STATS_DIR')) define('POWER_STATS_DIR', untrailingslashit(dirname(__FILE__)));

    // Ajax listener
    if (!empty($_POST['action']) && $_POST['action'] == 'power_stats_track') {

        add_action('wp_ajax_power_stats_track', array('PowerStats', 'power_stats_track')); // user logged in
        add_action('wp_ajax_nopriv_power_stats_track', array('PowerStats', 'power_stats_track')); // user not logged in

    } else {

        if (is_admin()) {
            include_once(POWER_STATS_DIR . '/admin/wp-power-stats-admin.php');
            add_action('plugins_loaded', array('PowerStatsAdmin', 'init'), 15);
            add_action('wp_dashboard_setup', array('PowerStatsAdmin', 'replace_dashboard'));
            register_activation_hook(__FILE__, array('PowerStatsAdmin', 'activate'));
            register_deactivation_hook(__FILE__, array('PowerStatsAdmin', 'deactivate'));
        }

    }

    add_action('plugins_loaded', array('PowerStats', 'init'), 10);
    add_action('widgets_init', 'power_stats_init_widget');
    power_stats_init_dashboard_widget();

}