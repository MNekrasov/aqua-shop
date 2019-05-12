</div>
<div id="footer">
<div class="copyright">
    <div class="container">
        <span>Copyright &copy; 2018<br> </span>
    </div>
</div>
</div>
<a href="#" class="gotop"><i class="icon-double-angle-up"></i></a>
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/jquery.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/jquery.easing-1.3.min.js"></script>
    <script src="/assets/js/jquery.tablesorter.min"></script>
    <script src="/assets/js/jquery.scrollTo-1.4.3.1-min.js"></script>
    <script src="/assets/js/shop.js"></script>
    <script>
    var suggest_count = 0;
    var input_initial_value = '';
    var suggest_selected = 0;

    $(window).load(function(){
        // читаем ввод с клавиатуры
        $("#search_box").keyup(function(I){
            // определяем какие действия нужно делать при нажатии на клавиатуру
            switch(I.keyCode) {
                // игнорируем нажатия на эти клавишы
                case 13: window.location.href='/admin/search/?query=' + $(this).val() // enter
                case 27:  // escape
                case 38:  // стрелка вверх
                case 40:  // стрелка вниз
                break;

                default:
                    // производим поиск только при вводе более 2х символов
                    if($(this).val().length>2){

                        input_initial_value = $(this).val();
                        // производим AJAX запрос к /search/ajaxSearch, передаем ему GET query, в который мы помещаем наш запрос
                        $.get("/admin/search/ajaxSearch/", { "query":$(this).val() },function(data){
                            //php скрипт возвращает нам строку, ее надо распарсить в массив.
                            // возвращаемые данные: ['test','test 1','test 2','test 3']
                            var list = eval("("+data+")");
                            suggest_count = list.length;
                            if(suggest_count > 0){
                                // перед показом слоя подсказки, его обнуляем
                                $("#search_user_advice_wrapper").html("").show();
                                for(var i in list){
                                    if(list[i] != ''){
                                        // добавляем слою позиции
                                        $('#search_user_advice_wrapper').append('<a class="search-item" style="color: black;" href="/admin/user/view/'+list[i].replace(/^(\d+)-(.*)$/, "$1")+'"><div class="advice_variant">'+list[i].replace(/^\d+-(.*)$/, "$1")+'</div></a>');
                                    }
                                }
                            } else {
                                $("#search_user_advice_wrapper").html("").hide();                            
                            }
                        }, 'html');
                    }
                break;
            }
        });

        //считываем нажатие клавишь, уже после вывода подсказки
        $("#search_box").keydown(function(I){
            switch(I.keyCode) {
                // по нажатию клавишь прячем подсказку
                case 13: // enter
                case 27: // escape
                    $('#search_user_advice_wrapper').hide();
                    return false;
                break;
                // делаем переход по подсказке стрелочками клавиатуры
                case 38: // стрелка вверх
                case 40: // стрелка вниз
                    I.preventDefault();
                    if(suggest_count){
                            //делаем выделение пунктов в слое, переход по стрелочкам
                        key_activate( I.keyCode-39 );
                    }
                break;
            }
        });

        // делаем обработку клика по подсказке
    $('.advice_variant').live('click',function(){
            // ставим текст в input поиска
            $('#search_box').val($(this).text());
            // прячем слой подсказки
            $('#search_user_advice_wrapper').fadeOut(350).html('');
        });

        // если кликаем в любом месте сайта, нужно спрятать подсказку
        $('html').click(function(){
            $('#search_user_advice_wrapper').hide();
        });
        // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
        $('#search_box').click(function(event){
            //alert(suggest_count);
            if(suggest_count)
                $('#search_user_advice_wrapper').show();
            event.stopPropagation();
        });
    });

    function key_activate(n){
        $('#search_user_advice_wrapper div').eq(suggest_selected-1).removeClass('active');

        if(n == 1 && suggest_selected < suggest_count){
            suggest_selected++;
        }else if(n == -1 && suggest_selected > 0){
            suggest_selected--;
        }

        if( suggest_selected > 0){
            $('#search_user_advice_wrapper div').eq(suggest_selected-1).addClass('active');
            $("#search_box").val( $('#search_user_advice_wrapper div').eq(suggest_selected-1).text() );
        } else {
            $("#search_box").val( input_initial_value );
        }
    }
</script>
  </body>
</html>