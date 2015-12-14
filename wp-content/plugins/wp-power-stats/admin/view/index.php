<?php

$week_start = get_option('start_of_week'); // Get Wordpress option
$now = current_time('mysql');
$week_mode = ($week_start == 1) ? 1 : 0;

$wpdb = $GLOBALS['wpdb'];

function get_delta_visits($delta) {

    if ($delta > 100) {
        $class = 'rises';
        $percent = abs($delta - 100);
        $image = '<img src="'. plugins_url('wp-power-stats/admin/images/delta_arrow_up.png') .'" title="'.$percent.'">';
    } else if ($delta == 0) {
        $class = 'no-change';
        $image = '';
        $percent = 0;
    } else {
        $class = 'falls';
        $percent = abs($delta - 100);
        $image = '<img src="'. plugins_url('wp-power-stats/admin/images/delta_arrow_down.png') .'" title="'.$percent.'">';
    }

    echo '<td class="versus-last '.$class.'"><span>'. $image .' '. $percent .'<span>%</span></span></td>';
}

$today_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE DATE(`date`) = DATE('". $now ."')", ARRAY_N);
$this_week_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE WEEK(`date`, $week_mode) = WEEK('". $now ."', $week_mode)", ARRAY_N);
$this_month_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE MONTH(`date`) = MONTH('". $now ."')", ARRAY_N);

$today_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE DATE(`date`) = DATE('". $now ."')", ARRAY_N);
$this_week_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE WEEK(`date`, $week_mode) = WEEK('". $now ."', $week_mode)", ARRAY_N);
$this_month_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE MONTH(`date`) = MONTH('". $now ."')", ARRAY_N);

$yesterday_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE DATE(`date`) = DATE(DATE_SUB('". $now ."', INTERVAL 1 DAY))", ARRAY_N);
$last_week_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE WEEK(`date`, $week_mode) = WEEK(DATE_SUB('". $now ."', INTERVAL 1 WEEK), $week_mode)", ARRAY_N);
$last_month_visits = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE MONTH(`date`) = MONTH(DATE_SUB('". $now ."', INTERVAL 1 MONTH))", ARRAY_N);

$yesterday_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE DATE(`date`) = DATE(DATE_SUB('". $now ."', INTERVAL 1 DAY))", ARRAY_N);
$last_week_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE WEEK(`date`, $week_mode) = WEEK(DATE_SUB('". $now ."', INTERVAL 1 WEEK), $week_mode)", ARRAY_N);
$last_month_pageviews = $wpdb->get_row("SELECT SUM(`hits`) FROM `{$wpdb->prefix}power_stats_pageviews` WHERE MONTH(`date`) = MONTH(DATE_SUB('". $now ."', INTERVAL 1 MONTH))", ARRAY_N);

$day_delta = ($yesterday_visits[0] == 0) ? 0 : round($today_visits[0] / $yesterday_visits[0], 2) * 100;
$week_delta = ($last_week_visits[0] == 0) ? 0 : round($this_week_visits[0] / $last_week_visits[0], 2) * 100;
$month_delta = ($last_month_visits[0] == 0) ? 0 : round($this_month_visits[0] / $last_month_visits[0], 2) * 100;

$day_pageviews_delta = ($yesterday_pageviews[0] == 0) ? 0 : round($today_pageviews[0] / $yesterday_pageviews[0], 2) * 100;
$week_pageviews_delta = ($last_week_pageviews[0] == 0) ? 0 : round($this_week_pageviews[0] / $last_week_pageviews[0], 2) * 100;
$month_pageviews_delta = ($last_month_pageviews[0] == 0) ? 0 : round($this_month_pageviews[0] / $last_month_pageviews[0], 2) * 100;

$total_visits = $wpdb->get_row("SELECT COUNT(`id`) FROM `{$wpdb->prefix}power_stats_visits`", ARRAY_N);
$desktop_hits = $wpdb->get_row("SELECT COUNT(`id`) FROM `{$wpdb->prefix}power_stats_visits` WHERE `device`='desktop'", ARRAY_N);
$tablet_hits = $wpdb->get_row("SELECT COUNT(`id`) FROM `{$wpdb->prefix}power_stats_visits` WHERE `device`='tablet'", ARRAY_N);
$mobile_hits = $wpdb->get_row("SELECT COUNT(`id`) FROM `{$wpdb->prefix}power_stats_visits` WHERE `device`='mobile'", ARRAY_N);

$desktop = (!empty($total_visits[0])) ? round($desktop_hits[0] / $total_visits[0] * 100) : 0;
$tablet = (!empty($total_visits[0])) ? round($tablet_hits[0] / $total_visits[0] * 100) : 0;
$mobile = (!empty($total_visits[0])) ? round($mobile_hits[0] / $total_visits[0] * 100) : 0;

$visits = $wpdb->get_results("SELECT `v`.`date`, COUNT(`v`.`id`) AS `hits`, `p`.`hits` AS `pageviews` FROM `{$wpdb->prefix}power_stats_visits` AS `v` JOIN `{$wpdb->prefix}power_stats_pageviews` AS `p` ON (`v`.`date` = `p`.`date`) GROUP BY `date` ORDER BY `v`.`date` DESC LIMIT 11", ARRAY_A);

$search_engine_referers = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE `is_search_engine` = '1'", ARRAY_N);
$non_empty_referers = $wpdb->get_row("SELECT COUNT(id) FROM `{$wpdb->prefix}power_stats_visits` WHERE `referer` != '' AND `is_search_engine` != '1'", ARRAY_N);

$search_engines = (!empty($total_visits[0])) ? round($search_engine_referers[0] / $total_visits[0] * 100) : 0;
$links = (!empty($total_visits[0])) ? round($non_empty_referers[0] / $total_visits[0] * 100) : 0;
$direct = 100 - $search_engines - $links;

$browser_data = $wpdb->get_results("SELECT `browser` AS `name`, `count` AS `hits` FROM `{$wpdb->prefix}power_stats_browsers` ORDER BY `count` DESC LIMIT 3", ARRAY_A);
$browser_total_hits = $wpdb->get_row("SELECT SUM(`count`) AS `total_hits` FROM `{$wpdb->prefix}power_stats_browsers`", ARRAY_N);

$browsers = array();
$translations = array("internet explorer" => "ie");
$browser_names = array("Unknown" => __('Unknown','wp-power-stats'));

foreach ($browser_data as $browser) {
    $browsers[] = array("image" => strtr(strtolower($browser['name']), $translations), "percent" => round($browser['hits'] / $browser_total_hits[0] * 100), "name" => strtr(ucfirst($browser['name']), $browser_names));
}

$os_data = $wpdb->get_results("SELECT `os` AS `name`, `count` AS `hits` FROM `{$wpdb->prefix}power_stats_os` ORDER BY `count` DESC LIMIT 3", ARRAY_A);
$os_total_hits = $wpdb->get_row("SELECT SUM(`count`) AS `total_hits` FROM `{$wpdb->prefix}power_stats_os`", ARRAY_N);

$oss = array();
$os_names = array("Unknown" => __('Unknown','wp-power-stats'));

foreach ($os_data as $os) {
    $oss[] = array("image" => strtolower($os['name']), "percent" => round($os['hits'] / $os_total_hits[0] * 100), "name" => strtr(ucfirst($os['name']), $browser_names));
}

$top_posts = $wpdb->get_results("SELECT `s`.`post_id`, `wp`.`post_title` AS `title`, SUM(`s`.`hits`) AS `hits` FROM `{$wpdb->prefix}power_stats_posts` AS `s` LEFT JOIN `{$wpdb->posts}` AS `wp` ON (`s`.`post_id` = `wp`.`id`) GROUP BY `s`.`post_id` ORDER BY `hits` DESC LIMIT 10", ARRAY_A);
$top_links = $wpdb->get_results("SELECT `referer`, `count` FROM `{$wpdb->prefix}power_stats_referers` ORDER BY `count` DESC LIMIT 10", ARRAY_A);
$top_searches = $wpdb->get_results("SELECT `terms`, `count` FROM `{$wpdb->prefix}power_stats_searches` ORDER BY `count` DESC LIMIT 10", ARRAY_A);
$country_data = $wpdb->get_results("SELECT `country` AS `name`, COUNT(`id`) AS `count` FROM `{$wpdb->prefix}power_stats_visits` GROUP BY `country` ORDER BY `count` DESC", ARRAY_A);

?><div class="wrap">

    <h2><?php _e('Overview','wp-power-stats') ?></h2>

<div class="container">
<div class="metabox-holder">

<div class="one-third column">
    <div class="cell">
        <div class="postbox-container">
            <div class="postbox micro">
                <h3><?php _e('Summary','wp-power-stats') ?></h3>

                <div class="inside">

                    <table class="summary">
                        <thead>
                        <tr>
                            <td></td>
                            <td class="value"><?php _e('Visitors','wp-power-stats') ?></td>
                            <td class="value"><?php _e('Pageviews','wp-power-stats') ?></td>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td><?php _e('Today','wp-power-stats') ?></td>
                            <td class="value"><?php echo $today_visits[0] ?></td>
                            <td class="value"><?php echo empty($today_pageviews[0]) ? 0 : $today_pageviews[0] ?></td>
                        </tr>

                        <tr>
                            <td><?php _e('This Week','wp-power-stats') ?></td>
                            <td class="value"><?php echo $this_week_visits[0] ?></td>
                            <td class="value"><?php echo empty($this_week_pageviews[0]) ? 0 : $this_week_pageviews[0] ?></td>
                        </tr>

                        <tr>
                            <td><?php _e('This Month','wp-power-stats') ?></td>
                            <td class="value"><?php echo $this_month_visits[0] ?></td>
                            <td class="value"><?php echo empty($this_month_pageviews[0]) ? 0 : $this_month_pageviews[0] ?></td>
                        </tr>
                        </tbody>
                    </table>

                </div><!-- inside -->
            </div><!-- postbox -->

            <div class="postbox micro">
                <h3><?php _e('Devices','wp-power-stats') ?></h3>

                <div class="inside">

                    <table class="triple">
                        <tbody>
                        <tr>
                            <td><img src="<?php echo plugins_url('wp-power-stats/admin/images/desktop.png') ?>" alt="<?php _e('Desktop','wp-power-stats') ?>"></td>
                            <td><img src="<?php echo plugins_url('wp-power-stats/admin/images/tablet.png') ?>" alt="<?php _e('Tablet','wp-power-stats') ?>"></td>
                            <td><img src="<?php echo plugins_url('wp-power-stats/admin/images/mobile.png') ?>" alt="<?php _e('Mobile','wp-power-stats') ?>"></td>
                        </tr>
                        <tr>
                            <td class="percent"><?php echo $desktop ?><span>%</span></td>
                            <td class="percent"><?php echo $tablet ?><span>%</span></td>
                            <td class="percent"><?php echo $mobile ?><span>%</span></td>
                        </tr>
                        <tr>
                            <td><?php _e('Desktop','wp-power-stats') ?></td>
                            <td><?php _e('Tablet','wp-power-stats') ?></td>
                            <td><?php _e('Mobile','wp-power-stats') ?></td>
                        </tr>
                        </tbody>
                    </table>

                </div><!-- inside -->
            </div><!-- postbox -->
        </div><!-- postbox-container -->
    </div><!-- cell -->
</div><!-- one-third -->

<div class="two-thirds column">
    <div class="cell">

        <div class="postbox-container">
            <div class="postbox double-height">
                <h3><span><?php _e('Visitors & Page Views','wp-power-stats') ?></span></h3>

                <div class="inside">

                    <?php

                    $data_array = "";
                    $visits = array_reverse($visits);

                    if (is_array($visits) && !empty($visits)) : ?>

                        <?php foreach ($visits as $day) {
                            if ($day['hits'] === null) $day['hits'] = 0;
                            if ($day['pageviews'] === null) $day['pageviews'] = 0;
                            $data_array .= "['".$day['date']."', ".$day['pageviews'].", ".$day['hits']."],";
                        } ?>

                        <script type="text/javascript">
                            google.load("visualization", "1", {packages:["corechart"]});
                            google.setOnLoadCallback(drawChart);
                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                    ['<?php _e('Year','wp-power-stats') ?>', '<?php _e('Page Views','wp-power-stats') ?>', '<?php _e('Visitors','wp-power-stats') ?>'],
                                    <?php echo $data_array; ?>
                                ]);

                                var options = {
                                    "title": '',
                                    "backgroundColor": 'transparent',
                                    "animation": {"duration": 1000,"easing": 'out'},
                                    "colors": ['#AAAAAA', '#21759B'],
                                    "vAxis": {"title": '',"baselineColor": '#CCCCCC',"textPosition": "in","textStyle": {"bold":false,"color": '#848484',"fontSize": 9}},
                                    "hAxis": {"title": '',"textStyle": {"color": '#777777'},"showTextEvery": '2'},
                                    "legend": {"position": 'none'},
                                    "lineWidth": '2',
                                    "pointSize": '0',
                                    "focusTarget": 'category',
                                    "chartArea": {"width": '94%', "height": '70%'}
                                };


                                var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
                                chart.draw(data, options);
                            }


                            (function($) {$(document).ready(function() {

                                function debouncer(func, timeout) {
                                    var timeoutID, timeout = timeout || 200;
                                    return function() {
                                        var scope = this,
                                            args = arguments;
                                        clearTimeout(timeoutID);
                                        timeoutID = setTimeout(function() {
                                            func.apply(scope, Array.prototype.slice.call(args));
                                        }, timeout);
                                    }
                                }

                                $(window).resize(debouncer(function(e) {drawChart();drawMap();}));

                            });})(jQuery);

                        </script>

                        <div id="chart_div" style="width: 100%; height: 260px;"></div>

                    <?php endif ?>

                </div><!-- inside -->

            </div><!-- postbox -->
        </div><!-- postbox-container -->
    </div><!-- cell -->
</div><!-- two-thirds -->


<div class="one-third column">
    <div class="cell">
        <div class="postbox-container">

            <div class="postbox micro">
                <h3><?php _e('Traffic Source','wp-power-stats') ?></h3>
                <div class="inside">
                    <table class="triple">
                        <tbody>
                        <tr>
                            <td><img src="<?php echo plugins_url('wp-power-stats/admin/images/search.png') ?>" alt="<?php _e('Search Engine','wp-power-stats') ?>"></td>
                            <td><img src="<?php echo plugins_url('wp-power-stats/admin/images/link.png') ?>" alt="<?php _e('Link','wp-power-stats') ?>"></td>
                            <td><img src="<?php echo plugins_url('wp-power-stats/admin/images/direct.png') ?>" alt="<?php _e('Direct','wp-power-stats') ?>"></td>
                        </tr>
                        <tr>
                            <td class="percent"><?php echo $search_engines ?><span>%</span></td>
                            <td class="percent"><?php echo $links ?><span>%</span></td>
                            <td class="percent"><?php echo $direct ?><span>%</span></td>
                        </tr>
                        <tr>
                            <td><?php _e('Search Engine','wp-power-stats') ?></td>
                            <td><?php _e('Links','wp-power-stats') ?></td>
                            <td><?php _e('Direct','wp-power-stats') ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div><!-- inside -->
            </div><!-- postbox -->

            <div class="postbox micro">
                <h3><?php _e('Browsers','wp-power-stats') ?></h3>
                <div class="inside dense">
                    <table class="triple">
                        <tbody>
                        <tr>
                            <?php foreach ($browsers as $browser) : ?><?php $browser_icon = (file_exists(plugin_dir_path(__FILE__).'../../admin/images/'.$browser['image'].'.png')) ? plugins_url('wp-power-stats/admin/images/'.$browser['image'].'.png') : plugins_url('wp-power-stats/admin/images/unknown.png'); ?><td><img src="<?php echo $browser_icon ?>" alt="<?php echo $browser['name'] ?>"></td><?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach ($browsers as $browser) : ?><td class="percent"><?php echo $browser['percent'] ?><span>%</span></td><?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach ($browsers as $browser) : ?><td><?php echo $browser['name'] ?></td><?php endforeach ?>
                        </tr>
                        </tbody>
                    </table>
                </div><!-- inside -->
            </div><!-- postbox -->

            <div class="postbox micro">
                <h3><?php _e('Operating Systems','wp-power-stats') ?></h3>
                <div class="inside dense">
                    <table class="triple">
                        <tbody>
                        <tr>
                            <?php foreach ($oss as $os) : ?><?php $os_icon = (file_exists(plugin_dir_path(__FILE__).'../../admin/images/'.$os['image'].'.png')) ? plugins_url('wp-power-stats/admin/images/'.$os['image'].'.png') : plugins_url('wp-power-stats/admin/images/unknown.png'); ?><td><img src="<?php echo $os_icon ?>" alt="<?php echo $os['name'] ?>"></td><?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach ($oss as $os) : ?><td class="percent"><?php echo $os['percent'] ?><span>%</span></td><?php endforeach ?>
                        </tr>
                        <tr>
                            <?php foreach ($oss as $os) : ?><td><?php echo $os['name'] ?></td><?php endforeach ?>
                        </tr>
                        </tbody>
                    </table>
                </div><!-- inside -->
            </div><!-- postbox -->


        </div><!-- postbox-container -->
    </div><!-- cell -->
</div><!-- one-third -->

<div class="two-thirds column">
    <div class="cell">

        <div class="postbox-container">
            <div class="postbox triple-height">
                <h3><span><?php _e('Visitor Map','wp-power-stats') ?></span></h3>

                <div class="inside">

                    <?php

                    $country_data_array = "";

                    if (is_array($country_data) && !empty($country_data)) {
                        foreach ($country_data as $country) {
                            $country_data_array .= "['".$country['name']."', ".$country['count']."],";
                        }
                    }

                    ?>

                    <script type="text/javascript">
                        google.load('visualization', '1', {'packages': ['geochart']});
                        google.setOnLoadCallback(drawMap);

                        function drawMap() {
                            var data = google.visualization.arrayToDataTable([
                                ['Country', 'Popularity'],
                                <?php echo $country_data_array ?>
                            ]);

                            var options = {
                                backgroundColor: 'transparent',
                                dataMode: 'regions',
                                colors: ['#C5D8E0','#00618C']
                            };

                            var container = document.getElementById('map_canvas');
                            var geomap = new google.visualization.GeoChart(container);
                            geomap.draw(data, options);

                        };

                    </script><div id="map_canvas" style=" height: 405px; width: 100%; text-align: center; margin: auto;"></div>

                </div><!-- inside -->

            </div><!-- postbox -->
        </div><!-- postbox-container -->
    </div><!-- cell -->
</div><!-- two-thirds -->

<div class="one-third column">
    <div class="cell">
        <div class="postbox-container">

            <div class="postbox fit-height">
                <h3><?php _e('Top Posts','wp-power-stats') ?></h3>
                <div class="inside">
                    <table>
                        <tbody>

                        <?php $i=1; foreach ($top_posts as $post): ?>
                            <tr><td class="order"><?php echo $i ?>.</td><td class="link"><a href="<?php echo get_permalink($post['post_id']) ?>"><?php echo $post['title'] ?></a></td></tr>
                            <?php $i++; endforeach; ?>

                        </tbody>
                    </table>
                </div><!-- inside -->
            </div><!-- postbox -->

        </div><!-- postbox-container -->
    </div><!-- cell -->
</div><!-- one-third -->

<div class="two-thirds column">
    <div class="cell">

        <div class="half column">
            <div class="cell">

                <div class="postbox-container">
                    <div class="postbox fit-height">
                        <h3><span><?php _e('Top Links','wp-power-stats') ?></span></h3>
                        <div class="inside">
                            <table>
                                <tbody>
                                <?php $i=1; foreach ($top_links as $link): ?>
                                    <tr><td class="order"><?php echo $i ?>.</td><td class="link"><a href="<?php echo $link['referer'] ?>" target="_blank"><?php echo $link['referer'] ?></a></td></tr>
                                    <?php $i++; endforeach; ?>
                                </tbody>
                            </table>
                        </div><!-- inside -->
                    </div><!-- postbox -->
                </div><!-- postbox-container -->

            </div><!-- cell -->
        </div><!-- half -->

        <div class="half column last">
            <div class="cell">

                <div class="postbox-container">
                    <div class="postbox fit-height">
                        <h3><span><?php _e('Top Search Terms','wp-power-stats') ?></span></h3>
                        <div class="inside">
                            <table>
                                <tbody>
                                <?php $i=1; foreach ($top_searches as $search): ?>
                                    <tr><td class="order"><?php echo $i ?>.</td><td class="link"><?php echo $search['terms'] ?></td></tr>
                                    <?php $i++; endforeach; ?>
                                </tbody>
                            </table>
                        </div><!-- inside -->
                    </div><!-- postbox -->
                </div><!-- postbox-container -->
            </div><!-- cell -->
        </div><!-- half -->

    </div><!-- cell -->
</div><!-- two-thirds -->

<div class="clear"></div>

</div><!-- metabox-holder -->
</div><!-- container -->

</div><!-- wrap -->