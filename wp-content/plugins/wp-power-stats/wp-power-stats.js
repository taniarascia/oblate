var PowerStats = {

    encodeWrapper: window.encodeURIComponent,
    urldecode: unescape,

    purify: function(url) {
        var targetPattern;
        targetPattern = new RegExp('#.*');
        return url.replace(targetPattern, '');
    },

    utf8_encode: function(argString) {
        return this.urldecode(this.encodeWrapper(argString));
    },

    getRequest: function() {
        var request, referralUrlMaxLength = 1024;
        request = "ci="+PowerStatsParams.ci+"&action=power_stats_track&ref=" + this.encodeWrapper(this.purify(document.referrer.slice(0, referralUrlMaxLength)));
        return request;
    },

    track: function() {
        try {
            if (window.XMLHttpRequest) {
                xhr = new XMLHttpRequest;
            } else if (window.ActiveXObject) {
                xhr = new ActiveXObject("Microsoft.XMLHTTP");
            }
            if (xhr) {
                xhr.open("POST", PowerStatsParams.ajaxurl, true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send(this.getRequest());
            }
        } catch (n) {
            return false;
        }
    }

};

if (typeof PowerStatsParams.ajaxurl != "undefined") {
    if (typeof PowerStats == "object") PowerStats.track();
}

