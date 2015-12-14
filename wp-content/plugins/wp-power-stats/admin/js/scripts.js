(function($) {

    $(function() {

        $('#power-stats-hide-admin-notice').click(function(e) {

            e.preventDefault();
            $(this).parents('div.power-stats-notice').fadeOut();

            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                async: true,
                data: {
                    action: 'power_stats_hide_admin_notice',
                    security: jQuery('#meta-box-order-nonce').val()
                }
            });

        });

    });

})(jQuery);