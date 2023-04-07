<script>
    $(document).ready(function() {
        $('#menu_toggle').on('click', function() {
            // if ($('body').hasClass('nav-md')) {
            //     $('body').removeClass('nav-md').addClass('nav-sm').
            //     $('.left_col').removeClass('scroll-view').removeAttr('style');
            //     $('.sidebar-footer').hide();
    
            //     if ($('.sidebar').hasClass('menu_fixed')) {
            //         $('.sidebar').removeClass('menu_fixed').addClass('menu_absolute');
            //     }
            // } else {
            //     $('body').removeClass('nav-sm').addClass('nav-md');
            //     $('.sidebar-footer').show();
    
            //     if ($('.sidebar').hasClass('menu_absolute')) {
            //         $('.sidebar').removeClass('menu_absolute').addClass('menu_fixed');
            //     }
            // }
    
            if ($('body').hasClass('nav-md')) {
                $('body').removeClass('nav-md').addClass('nav-sm');
                $('.left_col').removeClass('scroll-view').removeAttr('style');
                $('.sidebar-footer').hide();
            } else {
                $('body').removeClass('nav-sm').addClass('nav-md');
                $('.sidebar-footer').show();
            }
        });

        const navHeight = $('.nav_menu').height();
        $('.right_col[role="main"]').css('min-height', $(window).height() - navHeight);
    })
</script>