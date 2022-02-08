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
            <div class="card-body"  style="overflow-x: auto">
                <form id="form1" >
                    <input id="program_id" name="task[program_id]"  type="hidden" value="<?php echo $program_id; ?>">
                    <div>
                        <table id="qrTable" class="table-bordered"  align="center">
                            <thead>
                            <tr>
                                <th style="width: 45%;text-align: center">Name</th>
                                <th style="width: 45%;text-align: center">Fixed Value/Property Name</th>
                                <th style="width: 10%;text-align: center">Corresponding Property</th>
                            </tr>
                            </thead>
                            <?php if(!empty($detail_list)) { ?>
                                <?php foreach ($detail_list as $i => $j) { ?>
                                    <tr id="row<?php echo $i ?>">
                                        <td align="center">
                                            <input type="text" id="name" class="form-control none_input" name="Model[name]" style="width:90%" value="<?php echo $j['name'] ?>" />
                                        </td>
                                        <td align="center">
                                            <input type="text" id="fixed" class="form-control none_input" name="Model[fixed]"  style="width:90%" value="<?php echo $j['fixed'] ?>"/>
                                        </td>
                                        <td align="center">
                                            <?php if($j['status'] == '1'){ ?>
                                                <input type="checkbox" id="status"  name="Model[status]" style="width:90%"  checked />
                                            <?php }else{ ?>
                                                <input type="checkbox" id="status"  name="Model[status]" style="width:90%"  />
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if($i < 5){ ?>
                                    <?php for($i;$i<=5;$i++){ ?>
                                        <tr id="row<?php echo $i ?>">
                                            <td align="center">
                                                <input type="text" id="name" class="form-control none_input" name="Model[name]" style="width:90%"/>
                                            </td>
                                            <td align="center">
                                                <input type="text" id="fixed" class="form-control none_input" name="Model[fixed]"  style="width:90%"/>
                                            </td>
                                            <td align="center">
                                                <input type="checkbox" id="status"  name="Model[status]" style="width:90%"  />
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php }else{ ?>
                                <tr id="row0">
                                    <td align="center">
                                        <input type="text" id="name" class="form-control none_input" name="Model[name]" style="width:90%" value="Block" />
                                    </td>
                                    <td align="center">
                                        <input type="text" id="fixed" class="form-control none_input" name="Model[fixed]"  style="width:90%" value="Block"/>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" id="status" name="Model[status]" style="width:90%" checked />
                                    </td>
                                </tr>
                                <tr id="row1">
                                    <td align="center">
                                        <input type="text" id="name" class="form-control none_input" name="Model[name]" style="width:90%" value="Level" />
                                    </td>
                                    <td align="center">
                                        <input type="text" id="fixed" class="form-control none_input" name="Model[fixed]"  style="width:90%" value="Level" />
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" id="status"  name="Model[status]" style="width:90%" checked />
                                    </td>
                                </tr>
                                <tr id="row2">
                                    <td align="center">
                                        <input type="text" id="name" class="form-control none_input" name="Model[name]" style="width:90%" value="Part/Zone" />
                                    </td>
                                    <td align="center">
                                        <input type="text" id="fixed" class="form-control none_input" name="Model[fixed]"  style="width:90%" value="Part/Zone" />
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" id="status"  name="Model[status]" style="width:90%" checked />
                                    </td>
                                </tr>
                                <tr id="row3">
                                    <td align="center">
                                        <input type="text" id="name" class="form-control none_input" name="Model[name]" style="width:90%" value="Unit" />
                                    </td>
                                    <td align="center">
                                        <input type="text" id="fixed" class="form-control none_input" name="Model[fixed]"  style="width:90%" value="Unit"/>
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" id="status"  name="Model[status]" style="width:90%" checked />
                                    </td>
                                </tr>
                                <tr id="row4">
                                    <td align="center">
                                        <input type="text" id="name" class="form-control none_input" name="Model[name]" style="width:90%" value="PBU Type" />
                                    </td>
                                    <td align="center">
                                        <input type="text" id="fixed" class="form-control none_input" name="Model[fixed]"  style="width:90%" value="PBU Type" />
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" id="status"  name="Model[status]" style="width:90%" checked />
                                    </td>
                                </tr>
                                <tr id="row5">
                                    <td align="center">
                                        <input type="text" id="name" class="form-control none_input" name="Model[name]" style="width:90%" value="QR Code ID" />
                                    </td>
                                    <td align="center">
                                        <input type="text" id="fixed" class="form-control none_input" name="Model[fixed]"  style="width:90%" value="QR Code ID" />
                                    </td>
                                    <td align="center">
                                        <input type="checkbox" id="status"  name="Model[status]" style="width:90%" checked />
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </form>
                <div class="form-group" style="margin-top: 10px;">
                    <div class="col-3" >
                    </div>
                    <div class="col-6" style="text-align: center">
                        <div class="dataTables_filter" >
                            <?php
                            $pro_model = Program::model()->findByPk($program_id);
                            $root_proid = $pro_model->root_proid;
                            if($root_proid == $program_id){
                                ?>
                                <label style="margin-left: 10px">
                                    <button type="button" class="btn btn-primary" onclick="GetValue()">Save</button>
                                </label>
                                <label>
                                    <button type="button" class="btn btn-default" onclick="Reload()">Reload</button>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-3" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/JavaScript">
    $(document).ready(function() {
        $('.colors').hide();

        document.getElementsByName("Task[order_id]").onkeyup = function(evt) {
            alert(123);
        }
    })
    function Reload() {
        location.reload();
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

    function insertNewRow() {
        //获取表格有多少行
        var rowLength = $("#orderTable tr").length;
        //这里的rowId就是row加上标志位的组合。是每新增一行的tr的id。
        var rowId = "row" + rowLength;
//        alert(rowLength);
        //每次往下标为flag+1的下面添加tr,因为append是往标签内追加。所以用 after
        var insertStr = "<tr id=" + rowId + " >" + "<td style='width: 30%' align='center'><input id="+'stage_name'+rowLength+" name='task[stage_name]' class='form-control' style='width:90%'></td><td style='width: 10%' align='center'><input id="+'stage_color'+rowLength+" name='task[stage_color]' readonly style='width:90%' class='form-control'></td><td style='width: 10%' align='center'><input id="+'order_id'+rowLength+" name='task[order_id]'  style='width:90%'  class='form-control'></td><td align='center'><select name='task[clt_type]' id="+'clt_type'+rowLength+" class='form-control input-sm'><option value='A'>Site</option><option value='B'>Fitting Out</option><option value='C'>Carcass</option></select></td><td align='center'><input type='button' name='delete' class='btn btn-primary btn-sm' value='Delete' style='width:80px'  onclick='deleteSelectedRow(\"" + rowId + "\")' /> <input type='button' name='color' class='btn btn-primary btn-sm' value='Color' style='width:80px'  onclick='select_color(\"" + flag + "\")' />"; + "</td></tr>";
        //这里的行数减2，是因为要减去底部的一行和顶部的一行，剩下的为开始要插入行的索引
//        alert(rowLength);
        $("#orderTable tr:eq(" + (rowLength - 1) + ")").after(insertStr); //将新拼接的一行插入到当前行的下面
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
        $("#qrTable tr").each(function(i) {
            if (i >= 1) {
                console.log(2222222222);
                $(this).children().each(function(j) {
                    if($(this).children().eq(0)[0].type == 'checkbox'){
                        console.log(4444444);
                        console.log($(this).children().eq(0)[0].checked);
                        if($(this).children().eq(0)[0].checked == true){
                            console.log('选中');
                            value +='1' + ",";
                        }
                        if($(this).children().eq(0)[0].checked == false){
                            value +='0' + ",";
                        }
                    }else{
                        value += $(this).children().eq(0).val() + "," //获取每个单元格里的第一个控件的值
                        console.log(value);
                        if ($(this).children().length > 1) {
                            console.log(333333333333);
                            value += $(this).children().eq(1).val() + "," //如果单元格里有两个控件，获取第二个控件的值
                        }
                    }
                });
                value = value.substr(0, value.length - 1) + "@"; //每个单元格的数据以“，”分割，每行数据以“#”号分割
            }
        });
        // value = value.substr(0, value.length-1);

        ReceiveValue(value);
        //             $("#formvalue").val(value);
        //             $("formvalue").submit();
    }
    //-------------------接收表单中的值-----------------------------

    function ReceiveValue(str) {
        var Str = str.split('#');
        var program_id = $('#program_id').val();
//        var myType = document.getElementById("clt_type");//获取select对象
//        var index = myType.selectedIndex; //获取选项中的索引，selectIndex表示的是当前所选中的index
//        var clt_type = myType.options[index].value;//获取选项中options的value值
        $.ajax({
            data:{json_data:str,program_id:program_id},
            url: "index.php?r=task/model/saveqr",
            type: "POST",
            dataType: "json",
            async:false,             //false表示同步
            beforeSend: function () {

            },
            success: function (data) {
                alert('Set Success');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
    }

    //返回
    var back = function (program_id) {
        window.location = "index.php?r=task/template/list&program_id="+program_id;
    }


</script>