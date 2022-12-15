<script>
    class App {
        showSidebar(){
            if ($('.left_col').is(':hidden')) {
                $('.left_col').show(100);
            }
        }

        init(){
            $(".datatable").each(function() {
                try {
                    $(this).DataTable(); 
                } catch (error) {
                    console.log(error);
                }
            });
            $('input.timepicker').each(function() {
                $(this).on("keyup", function(e) {
                    e.target.value = '';
                })
                $(this).timepicker({ timeFormat: 'H:mm' });
            })

            $('.right_col').css('min-height', '95vh');

            $('.modal').each(function() {
                $(this).addClass('fade');
            });
            this.watchSidebar();
        }

        watchSidebar() {
            $(window).on('resize', function() {
                if ($(window).width() > 991) {
                    $('.left_col').show();
                } else {
                    $('.left_col').hide(100);
                }
            })

            $(document).click(function(e) {
                if ($(window).width() < 991) {
                    if(!$(e.target).closest('.left_col').length && !$(e.target).closest('#menu_toggle').length && $('.left_col').is(':visible')) {
                        $('.left_col').hide(100);
                    }
                } else {
                    $('.left_col').show();
                }
            })
        }
    }
    
    $(document).ready(function() {
        const app = new App();
        app.init();
        $('#menu_toggle').click(function() {
            app.showSidebar();
        });
    })
</script>