

<?php
$task_model = TaskTemplate::model()->findByPk($template_id);
$template_name = $task_model->template_name;
?>

<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <form id="form1" >
                    <?php if($stage_id == ''){ ?>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1 label-rignt">Stage Name</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <input id="stage_name" class="form-control" name="TaskStage[stage_name]" type="text" >
                                    <input id="program_id" name="TaskStage[program_id]"  type="hidden" value="<?php echo $project_id; ?>">
                                    <input id="template_id" name="TaskStage[template_id]"  type="hidden" value="<?php echo $template_id; ?>">
                                    <input id="stage_id" name="TaskStage[stage_id]"  type="hidden" value="<?php echo $stage_id; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1 label-rignt">Order</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <input type="text" id="order_id" class="form-control" name="TaskStage[order_id]"  />
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1 label-rignt">Stage Color</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <input type="text" id="stage_color" onclick="bt1()" class="form-control stage_color" name="TaskStage[stage_color]" readonly    onclick="select_color()"  />
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1 label-rignt">Type</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <select name="TaskStage[clt_type]" id="clt_type" class="form-control input">
                                        <option value="A">Site</option>
                                        <option value="B">Fitting Out</option>
                                        <option value="C">Carcass</option>
                                        <option value="D">Site Inspection</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php }else{ ?>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1 label-rignt">Stage Name</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <input id="stage_name" class="form-control" name="TaskStage[stage_name]" type="text"  value="<?php echo $stage_model->stage_name ?>">
                                    <input id="program_id" name="TaskStage[program_id]"  type="hidden" value="<?php echo $project_id; ?>">
                                    <input id="template_id" name="TaskStage[template_id]"  type="hidden" value="<?php echo $template_id; ?>">
                                    <input id="stage_id" name="TaskStage[stage_id]"  type="hidden" value="<?php echo $stage_id; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1 label-rignt">Order</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <input type="text" id="order_id" class="form-control" name="TaskStage[order_id]"  value="<?php echo $stage_model->order_id ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1 label-rignt">Stage Color</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <input type="text" id="stage_color" onclick="bt1()" class="form-control stage_color" name="TaskStage[stage_color]" readonly  style="background-color: <?php echo $stage_model->stage_color; ?>"  onclick="select_color()"  value="<?php echo $stage_model->stage_color ?>"/>
                                </div>
                            </div>
                        </div>

                        <div class="form-group group-space-between" style="margin-top: 10px;">
                        
                            <label for="template_name" class="col-2 control-label offset-md-1 label-rignt">Type</label>
                            <div class="input-group col-8">
                                <div class="col-9 padding-lr5">
                                    <select name="TaskStage[clt_type]" id="clt_type" class="form-control input">
                                        <?php if($stage_model->clt_type == 'A'){ ?>
                                            <option value="A" selected>Site</option>
                                            <option value="B">Fitting Out</option>
                                            <option value="C">Carcass</option>
                                            <option value="D">Site Inspection</option>
                                        <?php } ?>

                                        <?php if($stage_model->clt_type == 'B'){ ?>
                                            <option value="A">Site</option>
                                            <option value="B" selected>Fitting Out</option>
                                            <option value="C">Carcass</option>
                                            <option value="D">Site Inspection</option>
                                        <?php } ?>

                                        <?php if($stage_model->clt_type == 'C'){ ?>
                                            <option value="A">Site</option>
                                            <option value="B">Fitting Out</option>
                                            <option value="C" selected>Carcass</option>
                                            <option value="D">Site Inspection</option>
                                        <?php } ?>

                                        <?php if($stage_model->clt_type == ''){ ?>
                                            <option value="A">Site</option>
                                            <option value="B">Fitting Out</option>
                                            <option value="C">Carcass</option>
                                            <option value="D" selected>Site Inspection</option>
                                        <?php } ?>

                                    </select>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                </form>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-12" style="text-align: center">
                        <button type="button" class="btn btn-primary" style="margin-left: 10px" onclick="GetValue()">Save</button>
                        <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back('<?php echo $project_id ?>','<?php echo $template_id ?>')">Back</button>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>


<script type="text/JavaScript" src="js/jquery.colorpicker.js"></script>
<script type="text/JavaScript">
    $(document).ready(function() {
        // $('.colors').hide();
    })

    //点击空白处,下拉框消失的代码
    $(document).click(function(e) {
        var target = $(e.target);
        if(target.closest("#colorpanel").length == 0 && target.closest(".stage_color").length == 0) {
            $("#colorpanel").hide();
        };
    });

    function bt1(id) {
        $("#stage_color").colorpicker({
            success:function(o,color){
                $('#stage_color').css('backgroundColor', color);
                $('#stage_color').val(color);
                $('#stage_color').css("color","#000000");
            },
        });
        $("#colorpanel").hide();
    }

    function alterColor(rgb, type, percent) {
        rgb = rgb.replace('rgb(', '').replace(')', '').split(',');

        var red = $.trim(rgb[0]);
        var green = $.trim(rgb[1]);
        var blue = $.trim(rgb[2]);

        //If rgb is black set it to gray
        if (red == 0 && green == 0 && blue == 0) {
            red = 100;
            green = 100;
            blue = 100;
        }

        if (type === "darken") {
            red = parseInt(red * (100 - percent) / 100, 10);
            green = parseInt(green * (100 - percent) / 100, 10);
            blue = parseInt(blue * (100 - percent) / 100, 10);
        } else {
            red = parseInt(red * (100 + percent) / 100, 10);
            green = parseInt(green * (100 + percent) / 100, 10);
            blue = parseInt(blue * (100 + percent) / 100, 10);
        }

        rgb = 'rgb(' + red + ', ' + green + ', ' + blue + ')';

        return rgb;
    }

    function isSavedColor() {
//        if (localStorage.mySavedColor != null) {
//
//            $('.darker').css('color', localStorage.darkerColor);
//            $('.lighter').css('color', localStorage.lighterColor);
//
//
//        }
        var selected = sessionStorage.getItem("selected");
        if(selected){
//            alert(selected);
            $("ul.colors li:nth-child(" + selected + ")")
                .toggleClass('selected')
                .siblings()
                .removeClass('selected');
        }
    }

    function select_color(){
        isSavedColor();
        $('.colors').slideToggle();
        $(this).toggleClass('transform');
    }

    $('.colors li').click(function() {
        var color = $('.color', this).css('background-color');

        var strHex = "#";
        // 把RGB的3个数值变成数组
        var colorArr = color.replace(/(?:\(|\)|rgb|RGB)*/g, "").split(",");
        // 转成16进制
        for (var i = 0; i < colorArr.length; i++) {
            var hex = Number(colorArr[i]).toString(16).toUpperCase();
            if (hex === "0") {
                hex += hex;
            }
            strHex += hex;
        }
//        alert(strHex);
        $('#stage_color').css('backgroundColor', strHex);
        $('#stage_color').val(strHex);
//        alert(color);
        var darkerColor = alterColor(color, 'darken', 30);
        var lighterColor = alterColor(color, 'lighten', 50);
        var selected = '';

        $(this)
            .toggleClass('selected')
            .siblings()
            .removeClass('selected');

        $('ul.colors li').each(function(i) {
            if ($(this).hasClass('selected')) {
                selected = i + 1;
            }
        })
        sessionStorage.setItem("selected", selected);
        $('.darker').css('color', darkerColor);
        $('.lighter').css('color', lighterColor);

    });

    //-----------------获取表单中的值----start--------------

    function GetValue() {
        stage_id = $('#stage_id').val();
        if(stage_id != ''){
            var mode = 'edit';
            $.ajax({
                data:$('#form1').serialize(),
                url: "index.php?r=task/stage/newstage",
                type: "POST",
                dataType: "json",
                async:false,             //false表示同步
                beforeSend: function () {

                },
                success: function (data) {
                    alert('Set Success');
                    back('<?php echo $project_id ?>','<?php echo $template_id ?>');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                },
            });
        }else{
            var mode = 'add';
            $.ajax({
                data:$('#form1').serialize(),
                url: "index.php?r=task/stage/newstage",
                type: "POST",
                dataType: "json",
                async:false,             //false表示同步
                beforeSend: function () {

                },
                success: function (data) {
                    alert('Set Success');
                    back('<?php echo $project_id ?>','<?php echo $template_id ?>');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                },
            });
        }
    }

    //返回
    var back = function (program_id,template_id) {
        window.location = "index.php?r=task/template/stagelist&id="+template_id+"&project_id="+program_id;
    }

</script>