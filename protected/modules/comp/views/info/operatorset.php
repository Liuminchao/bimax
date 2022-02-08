
<link rel="stylesheet" type="text/css" href="css/power_css/common.css">
<link rel="stylesheet" type="text/css" href="css/power_css/select.css">

<!--    <form class="layui-form" action="">-->
<!--        <div class="layui-form-item">-->
<!--            <div class="layui-input-inline">-->
<!--                <input type="tel" name="phone" id="area_btn_y2" onClick="show_lay(2)"  lay-verify="phone" autocomplete="off" class="layui-input">-->
<!--                <em>查询人员2</em>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="layui-form-item">-->
<!--            <div class="layui-input-inline">-->
<!--                <input type="tel" id="area_btn_y1" onClick="show_lay(1)" name="phone" lay-verify="phone" autocomplete="off" class="layui-input">-->
<!--                <em>查询人员1</em>-->
<!--            </div>-->
<!--        </div>-->
<!--    </form>-->
<div class="fade_layer">
    <input type="hidden" id = 'operator_id' value="<?php echo $operator_id ?>">
    <input type="hidden" id = 'contractor_id' value="<?php echo $contractor_id ?>">
    <input type="hidden" id = 'contractor_name' value="<?php echo $contractor_name ?>">
</div>
<div class="detail_layer select_peo">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div class="title"><?php  echo Yii::t('common','permissionsset') ?>
        <div class="select_peo_con">
            <div class="left">
                <div class="areas_list">
                    <ul class="yiji">
                        <li class="areas_list_one"><a><?php  echo Yii::t('common','module') ?></a></li>
                        <ul class="areas_list_two">
                            <?php
                            $c_model = Contractor::model()->findByPk($contractor_id);
                            $menu_list = Menu::appMenuList();
                            $operator_menu = OperatorMenu::appMenuList($operator_id);
                            if(!empty($operator_menu)){
                                foreach($menu_list as $menu_id => $menu_name) {
                                    if ($operator_menu[$menu_id]) {
                                        ?>
                                        <li><span id="<?php echo $menu_id ?>" class="hover"><?php echo $menu_name ?></span>
                                        </li>
                                    <?php } else { ?>
                                        <li><span id="<?php echo $menu_id ?>"><?php echo $menu_name ?></span></li>
                                        <?php
                                    }
                                }
                            }else {
                                foreach ($menu_list as $menu_id => $menu_name) {
                                    ?>
                                    <li><span id="<?php echo $menu_id ?>"><?php echo $menu_name ?></span></li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                    </ul>
                </div>
            </div>
            <div class="center">
                <a id="list_add"><img src="img/power_img/addicon.jpg"></a>
            </div>
            <div class="right">
                <ul class="send_to">
                    <?php
                    if(!empty($operator_menu)) {
                        foreach ($operator_menu as $menu_id => $menu_name) {
                            ?>
                            <li>
                                <span id="<?php echo $menu_id ?>" class="hover"><?php echo $menu_name ?></span>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="clear"></div>
            <div class="bot_btn">
                <a onClick="do_add(this)" class="a_con submit_btn do_add"><?php  echo Yii::t('common','button_save') ?></a><a onClick="back()" class="a_con close_btn"><?php  echo Yii::t('common','button_back') ?></a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var id =1;
        $('.detail_layer').show();
        $('.detail_layer').attr('id','y'+id);
        $('.fade_layer').fadeIn();


        //点击下拉人员效果
        $('.detail_layer').find('.left .areas_list .areas_list_one > a').click(function(){
            if($(this).parent().hasClass('on')){
                $(this).parent().removeClass('on');
                $(this).parent().next('.areas_list_two').css('height','0');
                //隐藏下一级目录
                $(this).siblings('dl').hide();

                //初始化
                $(this).siblings('dl').find('dl').hide();
                $(this).siblings('dl').find('a').removeClass('on');
                //console.log($(this).siblings('dl').find('ul.areas_list_two').length);
                $(this).siblings('dl').find('ul.areas_list_two').css('height','0');
            }else {
                //解绑子级分类点击事件
                $(this).siblings('dl').children('dd').children('a').unbind('click');

                $(this).parent().addClass('on');
                //如果下面还有下一级
                if($(this).siblings('dl').length>0){
                    //显示子类
                    $(this).siblings('dl').show();
                    //子级分类点击事件
                    $(this).siblings('dl').children('dd').children('a').click(function(){
                        //解绑子级分类点击事件
                        $(this).siblings('dl').children('dd').children('a').unbind('click');
                        //切换隐藏
                        if($(this).hasClass('on')){
                            $(this).removeClass('on');
                            $(this).parent().next('.areas_list_two').css('height','0');
                            //隐藏下一级目录
                            $(this).siblings('dl').hide();
                        }else{

                            $(this).addClass('on');
                            $(this).parent().next('.areas_list_two').css('height','auto');
                            //如果还有下一级
                            if($(this).siblings('dl').length>0){
                                $(this).siblings('dl').show().css('padding-left','30px');
                                $(this).siblings('dl').children('dd').children('a').click(function(){
                                    //解绑子级分类点击事件
                                    $(this).siblings('dl').children('dd').children('a').unbind('click');
                                    if($(this).hasClass('on')){
                                        $(this).removeClass('on');
                                        $(this).parent().next('.areas_list_two').css('height','0');
                                        //隐藏下一级目录
                                        $(this).siblings('dl').hide();
                                    }else{
                                        $(this).addClass('on');
                                        $(this).parent().next('.areas_list_two').css('height','auto');
                                        //如果还有下一级
                                        if($(this).siblings('dl').length>0){
                                            $(this).siblings('dl').show().css('padding-left','30px');
                                            $(this).siblings('dl').children('dd').children('a').click(function(){
                                                console.log(1233);
                                                if($(this).hasClass('on')){
                                                    $(this).removeClass('on');
                                                    $(this).parent().next('.areas_list_two').css('height','0');
                                                    //隐藏下一级目录
                                                    $(this).siblings('dl').hide();
                                                }else{
                                                    $(this).addClass('on');
                                                    $(this).parent().next('.areas_list_two').css('height','auto');
                                                }
                                            });
                                        }else{
                                            //console.log($(this).parent().next('.areas_list_two').html());

                                            $(this).parent().next('.areas_list_two').css('height','auto');
                                        }
                                    }
                                });
                            }else{
                                //console.log($(this).parent().next('.areas_list_two').html());

                                $(this).parent().next('.areas_list_two').css('height','auto');
                            }
                        }



                    });
                }else{
                    $(this).parent().next('.areas_list_two').css('height','auto');
                }

            }

        });
        $('.detail_layer').find('.left .areas_list .areas_list_one > a').trigger("click");
    });

    //返回
    function back(){
        var id = $('#contractor_id').val();
        var name = $('#contractor_name').val();
        window.location = "index.php?r=comp/info/operatorlist&id="+id+'&name='+name;
//        window.location = "index.php?r=comp/staff/list";
    }

    //显示弹窗效果
    function show_lay(id) {
        // alert(id);
        $('.detail_layer').show();
        $('.detail_layer').attr('id','y'+id);
        $('.fade_layer').fadeIn();

    }
    //插入元素
    $('.detail_layer').find('.areas_list_two li').click(function(){
        //对勾切换效果
        if(!$(this).find('span').hasClass('hover')){
            $(this).find('span').addClass('hover');
        }else{
            $(this).find('span').removeClass('hover');
        }
        //获取选中元素html
        var val_prev = $(this).find('span').attr('id');
        // alert(val_prev);
        $message_peo = $(this).html();

        //获取添加后的数组
        var attrid =$(this).parents('.select_peo_con').find('.right ul.send_to li').map(function(){
            return $(this).find('span').attr('id');
        });
        //判断数字是否存在数组里
        if( jQuery.inArray(val_prev, attrid) ==-1){
            $(this).parents('.select_peo_con').find('.right ul.send_to').append("<li>"+$message_peo +"</li>");
        }else{
            // alert('已存在列表中');
            while ($(this).parents('.select_peo_con').find('.right ul.send_to').find("#" + val_prev).length > 0)
            {
                $(this).parents('.select_peo_con').find('.right ul.send_to').find("#" + val_prev).parent().remove();
            }
        }

    });

    //选择人员插入当前点击的input里面
    function do_add(elm){
        var id = $(elm).parents('div.detail_layer').attr('id');
        var temp='';
        var nruid='' ;
        var temp1='';
        var eid='' ;
        $(elm).parents('#'+id).find('ul.send_to li').each(function(i,e){
            text = $(e).find('span');
            for (var i = 0; i < text.length; i++) {
                var val=$(text[i]).html();
                if(val!=''){
                    temp+=val+',';
                }
                var id = $(text[i]).attr('id');
                if(id!=''){
                    nruid+=id+',' ;
                }
            };
        });

        var temps=temp.substring(0,temp.length-1);
        var nruid=nruid.substring(0,nruid.length-1);
        $('#area_btn_'+id).val(nruid);
        $('#nruid_'+id).val(nruid) ;
        $('#eid').val(eid) ;
//        $(elm).parents('.detail_layer').fadeOut();
        //提交数据
        var operator_id = $('#operator_id').val();
        var contractor_id = $('#contractor_id').val();
        $.ajax({
            url: "index.php?r=comp/info/updateapp",
            type: "POST",
            data: {operator_id:operator_id,contractor_id:contractor_id,menu_id:nruid},
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                alert(data.msg);
                back();
//                $('#msgbox').addClass(data.class);
//                $('#msginfo').html(data.msg);
//                $('#msgbox').show();
            },
            error: function () {
                alert('error');
                back();
//                $('#msgbox').addClass('alert-danger fa-ban');
//                $('#msginfo').html('系统错误');
//                $('#msgbox').show();
            }
        });
        //清空选择人员
        $('.detail_layer .right ul li').remove();
        $(elm).parents('.select_peo_con').find('ul.areas_list_two').css('height','0');
        $(elm).parents('.select_peo_con').find('.areas_list_one').removeClass('on');
        $(elm).parents('.select_peo_con').find('.areas_list_two li span').removeClass('hover');
//        $('.fade_layer').fadeOut();
    };
    //取消按钮关闭事件
    $('a.close_btn').click(function(){
        $(this).parents('.detail_layer').fadeOut();
        //清空选择人员
        $('.detail_layer .right ul li').remove();
        $(this).parents('.select_peo_con').find('ul.areas_list_two').css('height','0');
        $(this).parents('.select_peo_con').find('.areas_list_one').removeClass('on');
        $(this).parents('.select_peo_con').find('.areas_list_two li span').removeClass('hover');
//        $('.fade_layer').fadeOut();
    });
</script>


