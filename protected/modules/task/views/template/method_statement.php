<style type="text/css">
    .none_input{
        border:0;​
        outline:medium;
    }
</style>
<?php
    $task_model = TaskTemplate::model()->findByPk($template_id);
    $template_name = $task_model->template_name;
?>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div class="form-group" style="margin-top:10px;">
                    <label for="template_name" class="col-2 control-label label-rignt padding-lr5">Template Name</label>
                    <div  class="col-6 padding-lr5">
                        <input id="template_name" class="form-control" name="task[template_name]" type="text" value="<?php echo $template_name; ?>">
                        <input id="program_id" name="task[program_id]"  type="hidden" value="<?php echo $program_id; ?>">
                        <input id="template_id" name="task[template_id]"  type="hidden" value="<?php echo $template_id; ?>">
                    </div>
                </div>
                <div class="form-group" style="margin-top:10px">
                    <button type="button" id="sbtn" class="btn btn-primary btn-sm" onclick="insertNewRow()">Add</button>
                </div>
                <div style="margin-top:10px;">
                    <table id="orderTable" class="table-bordered" width="100%" align="center">
                        <thead>
                        <tr>
                            <th style="width: 30%;text-align: center">Stage Name</th>
                            <th style="width: 10%;text-align: center">Stage Color</th>
                            <th style="width: 10%;text-align: center">Order Id</th>
                            <th style="width: 20%;text-align: center">Type</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                        </thead>
                        <?php
                        if($template_id != '') {
                            foreach ($detail_list as $i => $j) {
                                ?>
                                <tr id="row<?php echo $i; ?>">
                                    <td align="center">
                                        <input type="text" id="stage_name0" class="form-control none_input" name="Task[stage_name]"
                                               style="width:90%" value="<?php echo $j['stage_name'] ?>"/>
                                    </td>
                                    <td align="center">
                                        <input type="text" id="stage_color<?php echo $i; ?>" onclick="bt1("<?php echo $i; ?>")" class="form-control stage_color none_input" name="Task[stage_color]"
                                        readonly style="width:90%;background-color:<?php echo $j['stage_color'] ?>"
                                        value="<?php echo $j['stage_color'] ?>"/>
                                    </td>
                                    <td align="center">
                                        <input type="text" id="order_id0" class="form-control none_input" name="Task[order_id]"
                                               style="width:90%" value="<?php echo $j['order_id'] ?>"/>
                                    </td>
                                    <td align="center">
                                        <select name="task[clt_type]" id="clt_type" class="form-control input-sm none_input">
                                            <option value="A">Site</option>
                                            <option value="B">Fitting Out</option>
                                            <option value="C">Carcass</option>
                                            <option value="D">Site Inspection</option>
                                        </select>
                                    </td>
                                    <td align="center">
                                        <input type="button" name="delete" class="btn btn-primary btn-sm" value="Delete"
                                               style="width:80px" onclick="deleteSelectedRow('row<?php echo $i; ?>')"/>
                                        <input type="button" name="color" class="btn btn-primary btn-sm" value="Color"
                                               style="width:80px" onclick="select_color('<?php echo $i; ?>')"/>
                                    </td>
                                </tr>
                                <?php
                            }
                        }else{
                            ?>
                            <tr id="row0">
                                <td align="center">
                                    <input type="text" id="stage_name0  none_input" class="form-control none_input" name="Task[stage_name]" style="width:90%"/>
                                </td>
                                <td align="center">
                                    <input type="text" id="stage_color0" onclick="bt1(0)" class="form-control stage_color none_input" name="Task[stage_color]" readonly style="width:90%"/>
                                </td>
                                <td align="center">
                                    <input type="text" id="order_id0" class="form-control none_input" name="Task[order_id]" style="width:90%"  />
                                </td>
                                <td align="center">
                                    <select name="task[clt_type]" id="clt_type0" class="form-control input-sm none_input">
                                        <option value="A">Site</option>
                                        <option value="B">Fitting Out</option>
                                        <option value="C">Carcass</option>
                                        <option value="D">Site Inspection</option>
                                    </select>
                                </td>
                                <td align="center">
                                    <input type="button" name="delete"  class="btn btn-primary btn-sm"  value="Delete" style="width:80px" onclick="deleteSelectedRow('row0')" />
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <div class="col-sm-12" style="text-align: center">
                        <button type="button" class="btn btn-primary" style="margin-left: 10px" onclick="GetValue()">Save</button>
                        <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back('<?php echo $program_id ?>')">Back</button>
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

        document.getElementsByName("Task[order_id]").onkeyup = function(evt) {
            //alert(123);
        }
    })

    //点击空白处,下拉框消失的代码
    $(document).click(function(e) {
        var target = $(e.target);
        if(target.closest("#colorpanel").length == 0 && target.closest(".stage_color").length == 0) {
            $("#colorpanel").hide();
        };
    });

    function bt1(id) {
        var color_id = 'stage_color'+id;
        $("#"+color_id).colorpicker({
            success:function(o,color){
                $('#stage_color'+id).css('backgroundColor', color);
                $('#stage_color'+id).val(color);
                $('#stage_color'+id).css("color","#000000");
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
    // 删除一行
    function del_tr(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标
        $(obj).parents("tr").remove();
    }
    function isSavedColor(id) {
//        if (localStorage.mySavedColor != null) {
//
//            $('.darker').css('color', localStorage.darkerColor);
//            $('.lighter').css('color', localStorage.lighterColor);
//
//
//        }
        var selected = sessionStorage.getItem("selected_"+id);
        if(selected){
//            alert(selected);
            $("ul.colors li:nth-child(" + selected + ")")
                .toggleClass('selected')
                .siblings()
                .removeClass('selected');
        }
    }

    function select_color(id){
        isSavedColor(id);
        $('.colors').slideToggle();
        $(this).toggleClass('transform');
        sessionStorage.setItem("color_id", id);
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
        var id = sessionStorage.getItem("color_id");
        $('#stage_color'+id).css('backgroundColor', strHex);
        $('#stage_color'+id).val(strHex);
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
        sessionStorage.setItem("selected_"+id, selected);
        $('.darker').css('color', darkerColor);
        $('.lighter').css('color', lighterColor);

    });

    //声明全局变量
    var formvalue = "";
    var flag = 1;
    var index = 1;
    var firstCell = "";
    var secondCell = "";
    var thirdCell = "";

    $(function() {
        //初始化第一行
        firstCell = $("#row0 td:eq(0)").html();
        secondCell = $("#row0 td:eq(1)").html();
        thirdCell = $("#row0 td:eq(2)").html();
        fourthCell = $("#row0 td:eq(3)").html();
    });

    //-----------------新增一行-----------start---------------
    var color_index = 0;
    function insertNewRow() {
        color_index++;
        //获取表格有多少行
        var rowLength = $("#orderTable tr").length-1;
        //这里的rowId就是row加上标志位的组合。是每新增一行的tr的id。
        var rowId = "row" + rowLength;
        // alert(rowLength);
        //每次往下标为flag+1的下面添加tr,因为append是往标签内追加。所以用 after
        var insertStr = "<tr id=" + rowId + " >" + "<td style='width: 30%' align='center'><input id="+'stage_name'+rowLength+" name='task[stage_name]' class='form-control none_input' style='width:90%'></td><td style='width: 10%' align='center'><input onclick='bt1(\"" + color_index + "\")' id="+'stage_color'+rowLength+"  name='task[stage_color]'  readonly style='width:90%' class='form-control stage_color none_input'></td><td style='width: 10%' align='center'><input id="+'order_id'+rowLength+" name='task[order_id]'  style='width:90%'  class='form-control none_input'></td><td align='center'><select name='task[clt_type]' id="+'clt_type'+rowLength+" class='form-control input-sm none_input'><option value='A'>Site</option><option value='B'>Fitting Out</option><option value='C'>Carcass</option><option value='D'>Site Inspection</option></select></td><td align='center'> <input type='button' name='delete' class='btn btn-primary btn-sm' value='Delete' style='width:80px'  onclick='deleteSelectedRow(\"row" + flag + "\")' />"; + "</td></tr>";
        //这里的行数减2，是因为要减去底部的一行和顶部的一行，剩下的为开始要插入行的索引
//        alert(rowLength);
        $("#orderTable tr:eq(" + rowLength + ")").after(insertStr); //将新拼接的一行插入到当前行的下面
        //为新添加的行里面的控件添加新的id属性。
        $("#" + rowId + " td:eq(0)").children().eq(0).attr("id", "stage_name" + flag);
        $("#" + rowId + " td:eq(1)").children().eq(0).attr("id", "stage_color" + flag);
        $("#" + rowId + " td:eq(2)").children().eq(0).attr("id", "order_id" + flag);
        //每插入一行，flag自增一次
        flag++;
    }

    //-----------------删除一行，根据行ID删除-start--------

    function deleteSelectedRow(rowID) {
        if (confirm("Are you sure to delete that line？")) {
            $("#" + rowID).remove();
        }
    }
    //-----------------获取表单中的值----start--------------

    function GetValue() {
        var value = "";
        $("#orderTable tr").each(function(i) {
            if (i >= 1) {
                $(this).children().each(function(j) {
                    if ($("#orderTable tr").eq(i).children().length - 1 != j) {
                        value += $(this).children().eq(0).val() + "," //获取每个单元格里的第一个控件的值
                        if ($(this).children().length > 1) {
                            value += $(this).children().eq(1).val() + "," //如果单元格里有两个控件，获取第二个控件的值
                        }
                    }
                });
                value = value.substr(0, value.length - 1) + "@"; //每个单元格的数据以“，”分割，每行数据以“#”号分割
            }
        });
        value = value.substr(0, value.length-1);

        ReceiveValue(value);
        //             $("#formvalue").val(value);
        //             $("formvalue").submit();
    }
    //-------------------接收表单中的值-----------------------------

    function ReceiveValue(str) {
        var Str = str.split('#');
        console.log(str);
        var template_name = $('#template_name').val();
        var program_id = $('#program_id').val();
        var template_id = $('#template_id').val();
//        var myType = document.getElementById("clt_type");//获取select对象
//        var index = myType.selectedIndex; //获取选项中的索引，selectIndex表示的是当前所选中的index
//        var clt_type = myType.options[index].value;//获取选项中options的value值
        if(template_id != ''){
            var mode = 'edit';
            $.ajax({
                data:{mode:mode,json_data:str,template_id:template_id,template_name:template_name,program_id:program_id},
                url: "index.php?r=task/task/savemethod",
                type: "POST",
                dataType: "json",
                async:false,             //false表示同步
                beforeSend: function () {

                },
                success: function (data) {
                    alert(data.msg);
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
                data:{mode:mode,json_data:str,template_name:template_name,program_id:program_id},
                url: "index.php?r=task/task/savemethod",
                type: "POST",
                dataType: "json",
                async:false,             //false表示同步
                beforeSend: function () {

                },
                success: function (data) {
                    alert(data.msg);
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
    var back = function (program_id) {
        window.location = "index.php?r=task/template/list&program_id="+program_id;
    }


</script>