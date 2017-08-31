<style>
    .my_page_scrolling_button_down{
    background: url(/img/scroll_arrow_down.png) no-repeat #333;

}
.my_page_scrolling_button_up{
    background: url(/img/scroll_arrow_up.png) no-repeat #333;
}

#my_page_scrolling_button {
    background-position: center center;
    border-radius:3px;
    width:60px;
    height:60px;
    opacity:0.8;
    position:fixed;
    bottom:10px; /* отступ кнопки от нижнего края страницы*/
    right:10px;
    font-size:21px;
    cursor:pointer;
    display:none;
}
#my_page_scrolling_button:hover{
    transition: 0.5s;
    opacity:1;
}
</style>
<script type="text/javascript">
    $(document).ready(function () {
        my_page_scrolling = (function () {
            var scroll_started = false;
            var scroll_down_coords = 0;
            var scroll_down_status = 0;

            var button_pressed = false;



            var arrow_down = function () {

                //стрелка вниз
                $('#my_page_scrolling_button').addClass('my_page_scrolling_button_down');
                $('#my_page_scrolling_button').removeClass('my_page_scrolling_button_up');
            }
            var arrow_up = function () {
                //стрелка вверх
                $('#my_page_scrolling_button').addClass('my_page_scrolling_button_up');
                $('#my_page_scrolling_button').removeClass('my_page_scrolling_button_down');
            }
            var interface = {
                init: function () {
                    $(window).scroll(function () {
                        var scroll = $(this).scrollTop();
                        if ((scroll <= 500) && (!button_pressed)) {
                            scroll_down_status = 0;
                            if (scroll_started === false) {
                                $('#my_page_scrolling_button').fadeOut();
                            } else {
                                arrow_down();
                            }
                        }
                        else {
                            $('#my_page_scrolling_button').fadeIn();
                        }
                    });

                    $('#my_page_scrolling_button').click(function () {
                        button_pressed = true;
                        if (scroll_started === true) {

                            if (scroll_down_status === 0) {

                                $('body,html').animate({scrollTop: scroll_down_coords}, 200, function () {
                                    // Animation complete.
                                    button_pressed = false;
                                    arrow_up();

                                });
                                scroll_down_status = 1;
                            } else if (scroll_down_status === 1) {
                                $('body,html').animate({scrollTop: 0}, 200, function () {
                                    // Animation complete.
                                    button_pressed = false;
                                    arrow_down();
                                });
                                scroll_down_coords = $(window).scrollTop();
                                scroll_down_status = 0;
                            }
                        } else {
                            $('body,html').animate({scrollTop: 0}, 200, function () {
                                // Animation complete.
                                button_pressed = false;
                                arrow_down();
                            });
                            scroll_started = true;
                            scroll_down_coords = $(window).scrollTop();
                        }
                    });
                }
            }
            return interface;
        })();
        my_page_scrolling.init();
    });
</script>

<div id="my_page_scrolling_button" class="my_page_scrolling_button_up"></div>
























