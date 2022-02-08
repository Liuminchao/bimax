
<?php
$task_model = TaskTemplate::model()->findByPk($template_id);
$template_name = $task_model->template_name;
?>
<form id="form1" >
    <div class="row" style="margin-left: 10%;">
        <div class="form-group">
<!--            <label for="template_name" class="col-sm-2 control-label padding-lr5">Template Name</label>-->
            <div  class="col-sm-3 padding-lr5">
                <input id="program_id" name="Model[program_id]"  type="hidden" value="<?php echo $program_id; ?>">
            </div>
        </div>
    </div>

<!--    <div style="margin-left:10%;margin-bottom: 10px;" class="row">-->
<!--        <button type="button" id="sbtn" class="btn btn-primary btn-sm" onclick="insertNewRow()">Add</button>-->
<!--    </div>-->
    <div>
        <table id="orderTable" class="table-bordered" width="80%" align="center">
            <thead>
            <tr>
                <th style="width: 30%;text-align: center">Key</th>
                <th style="width: 10%;text-align: center">Value</th>
<!--                <th style="text-align: center">Action</th>-->
            </tr>
            </thead>
            <tr id="row0">
                <td align="center" width="30%">
                    <input type="text"  class="form-control" readonly value="Unit No." style="width:90%"/>
                </td>
                <td align="center" width="70%">
                    <input type="text" id="unit_no" class="form-control" name="Model[unit_no]"  style="width:90%"/>
                </td>
            </tr>
            <tr id="row1">
                <td align="center" width="30%">
                    <input type="text"  class="form-control" readonly value="Block" style="width:90%"/>
                </td>
                <td align="center" width="70%">
                    <input type="text" id="block" class="form-control" name="Model[block]"  style="width:90%"/>
                </td>
            </tr>
            <tr id="row2">
                <td align="center">
                    <input type="text"  class="form-control" readonly value="Type" style="width:90%"/>
                </td>
                <td align="center">
                    <input type="text" id="pbu_type" class="form-control" name="Model[pbu_type]"  style="width:90%"/>
                </td>
            </tr>
            <tr id="row3">
                <td align="center">
                    <input type="text"  class="form-control" readonly value="Part" style="width:90%"/>
                </td>
                <td align="center">
                    <input type="text" id="part" class="form-control" name="Model[part]"  style="width:90%"/>
                </td>
            </tr>
        </table>
    </div>
</form>

<div class="row" style="margin-top: 8px;">
    <div class="form-group" >
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" class="btn btn-primary" style="margin-left: 10px" onclick="GetValue()">Save</button>
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


    // 删除一行
    function del_tr(obj){
        var index = $(obj).parents("tr").index(); //这个可获取当前tr的下标
        $(obj).parents("tr").remove();
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
        var insertStr = "<tr id=" + rowId + " >" + "<td style='width: 30%' align='center'><input id="+'key'+rowLength+" name='Model[key]'  style='width:90%' class='form-control'></td><td style='width: 50%' align='center'><input id="+'value'+rowLength+" name='Model[value]'  style='width:90%' class='form-control'></td><td align='center'><input type='button' name='delete' class='btn btn-primary btn-sm' value='Delete' style='width:80px'  onclick='deleteSelectedRow(\"" + rowId + "\")' /> "; + "</td></tr>";
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
        $("#orderTable tr").each(function(i) {
            if (i >= 1) {
                $(this).children().each(function(j) {
                    value += $(this).children().eq(0).val() + "," //获取每个单元格里的第一个控件的值
                    if ($(this).children().length > 1) {
                        value += $(this).children().eq(1).val() + "," //如果单元格里有两个控件，获取第二个控件的值
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
        var str = str.split('#');
        console.log(str);
        var program_id = $('#program_id').val();
        var mode = 'add';
        $.ajax({
            data:{mode:mode,json_data:str,program_id:program_id},
            url: "index.php?r=task/model/addentity",
            type: "POST",
            dataType: "json",
            async:false,             //false表示同步
            beforeSend: function () {
                addcloud();
            },
            success: function (data) {
                ajaxReadData(program_id,data.rowcnt, 0);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
    }

    /*
     * 加载数据
     */
    var ajaxReadData = function (program_id, rowcnt, startrow){//alert('aa');

        jQuery.ajax({
            data: {program_id:program_id, startrow: startrow, per_read_cnt:per_read_cnt},
            type: 'post',
            url: './index.php?r=task/model/readmodeldata',
            dataType: 'json',
            success: function (data, textStatus) {
                // for (var o in data) {
                //     $('#prompt').append("</br>Row "+o+" : "+data[o].msg);
                //     $('#qr_table').append("<tr><td colspan='2' align='center'><h1 style='text-align: center'>"+data[o].type+"</h1></td></tr>");
                //     $('#qr_table').append("<tr><td style='white-space: nowrap;'><span style='font-size: 15px;font-weight:bold;margin-right: 5px '>Model Id:</span><span>"+data[o].model_id+"</span></td><td rowspan='3' align='right'><img src='"+data[o].filename+"'></td> </tr>");
                //     $('#qr_table').append("<tr><td><span style='font-size: 15px;font-weight:bold;margin-right: 5px'>entityId:</span><span>"+data[o].entityId+"</span></td> </tr>");
                //     $('#qr_table').append("<tr><td><span style='font-size: 15px;font-weight:bold;margin-right: 5px'>uuid:</span><span>"+data[o].uuid+"</span></td> </tr>");
                // }
                if (rowcnt > startrow) {
                    ajaxReadData(program_id, rowcnt, startrow+per_read_cnt);
                }else{
                    clearCache(program_id);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },
        });
        return false;
    }

    /*
     * 加载数据
     */
    var clearCache = function(program_id){//alert('aa');

         jQuery.ajax({
             data: {program_id:program_id},
             type: 'post',
             url: './index.php?r=task/model/clearcache',
             dataType: 'json',
             success: function (data, textStatus) {
                 alert('Set Success');
                 removecloud();
             },
             error: function(XMLHttpRequest, textStatus, errorThrown){
                 //alert(XMLHttpRequest);
                 //alert(textStatus);
                 //alert(errorThrown);
             },
         });
        // return false;
    }

    //返回
    var back = function (program_id) {
        window.location = "index.php?r=task/template/list&program_id="+program_id;
    }


</script>