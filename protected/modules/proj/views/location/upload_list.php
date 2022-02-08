<style type="text/css">
    .none_input{
        border:0;​
    outline:medium;
    }
</style>
<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    'autoValidation' => false,
//    "action" => "javascript:formSubmit()",
//    'enableAjaxSubmit' => false,
//    'ajaxUpdateId' => 'content-body',
//    'role' => 'form', //可省略
//    'formClass' => 'form-horizontal', //可省略 表单对齐样式

));
?>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <div class="row" style="margin-bottom: 8px;">
                    <div class="col-9" style="text-align: right;margin-bottom: 0px;">
                        <input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id  ?>">
                        <input type="hidden" id="block" name="block" value="<?php echo $block  ?>">
                        <ul class="nav nav-pills" role="tablist" id="myTab">
                            <?php
                            if($tag == 'level'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=proj/location/uploadlist&project_id=<?php echo $project_id ?>&tag=level&block=<?php echo $block; ?>">Level</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=proj/location/uploadlist&project_id=<?php echo $project_id ?>&tag=unit&block=<?php echo $block; ?>" >Unit</a></li>
                                <?php
                            }else if($tag == 'unit'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=proj/location/uploadlist&project_id=<?php echo $project_id ?>&tag=level&block=<?php echo $block; ?>">Level</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=proj/location/uploadlist&project_id=<?php echo $project_id ?>&tag=unit&block=<?php echo $block; ?>" >Unit</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-3" style="text-align: right;margin-bottom: 0px;">
                        
                    </div>
                </div>
            </div><!-- /.card-header -->
            <?php
                if($tag == 'level'){
            ?>
                <div class="card-body" style="overflow-x: auto">
                <div class="form-group" style="margin-top:10px">
                    <button type="button" id="sbtn" class="btn btn-primary btn-sm" onclick="insertNewRow_1()">Add</button>
                </div>
                <div style="margin-top:10px;">
                    <table id="orderTable" class="table" width="100%" align="center">
                        <thead>
                        <tr>
                            <th style="width: 30%;text-align: center">Attach</th>
                            <th style="width: 15%;text-align: center">Level</th>
                            <th style="width: 5%;text-align: center"></th>
                            <th style="width: 15%;text-align: center">Level</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                        </thead>
                        <?php
                        if(count($level_draw_list)>0) {
                            $draw_index = 0;
                            foreach ($level_draw_list as $drawing_id => $j) {
                                ?>
                                <tr id="row<?php echo $draw_index; ?>">
                                    <td align="center">
                                        <input type="text" id="file_text<?php echo $draw_index; ?>" class="form-control none_input"  style="width:90%" value="<?php echo $j['drawing_name']; ?>" readonly/>
                                        <input class="form-control"  id="file<?php echo $draw_index; ?>" type="file" style="display:none" onchange="raupload(this,0)">
                                        <input type="hidden" id="file_path0"  name="Level[<?php echo $draw_index; ?>][file_path]" value="<?php echo $drawing_id; ?>"/>
                                    </td>
                                    <td align="center">
                                        <select  id="level_from<?php echo $draw_index; ?>  none_input" class="form-control none_input" name="Level[<?php echo $draw_index; ?>][level_from]" style="width:90%" >
                                            <?php
                                                $level_list = ProgramRegion::locationLevel($project_id,$block);
                                                foreach($level_list as $level_num => $level){
                                                    $select_tag = '';
                                                    if($j['from'] == $level['secondary_region']){
                                                        $select_tag = ' selected';
                                                    }
                                                    echo "<option value='{$level['secondary_region']}' $select_tag>{$level['secondary_region']}</option>";
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td align="center">
                                        TO
                                    </td>
                                    <td align="center">
                                        <select  id="level_to<?php echo $draw_index; ?>" class="form-control none_input" name="Level[<?php echo $draw_index; ?>][level_to]" style="width:90%"  >
                                            <?php
                                            $level_list = ProgramRegion::locationLevel($project_id,$block);
                                            foreach($level_list as $level_num => $level){
                                                $select_tag = '';
                                                if($j['to'] == $level['secondary_region']){
                                                    $select_tag = ' selected';
                                                }
                                                echo "<option value='{$level['secondary_region']}' $select_tag>{$level['secondary_region']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td align="center">
                                        <input type="button" name="delete"  class="btn btn-primary btn-sm"  value="Delete" style="width:80px" onclick="deleteSelectedRow_1('row<?php echo $draw_index; ?>')" />
                                        <input type="button" name="upload"  class="btn btn-primary btn-sm"  value="Upload" style="width:80px" onclick="file_click('<?php echo $draw_index; ?>')" />
                                    </td>
                                </tr>
                                <?php
                                $draw_index++;
                            }
                        }else{
                            ?>
                            <tr id="row0">
                                <td align="center">
                                    <input type="text" id="file_text0" class="form-control none_input"  style="width:90%" readonly/>
                                    <input class="form-control"  id="file0" type="file" style="display:none" onchange="raupload(this,<?php echo $draw_index; ?>)">
                                    <input type="hidden" id="file_path0"  name="Level[0][file_path]" value=""/>
                                </td>
                                <td align="center">
                                    <select  id="level_from0  none_input" class="form-control none_input" name="Level[0][level_from]" style="width:90%">
                                        <?php
                                        $level_list = ProgramRegion::locationLevel($project_id,$block);
                                        foreach($level_list as $level_num => $level){
                                            echo "<option value='{$level['secondary_region']}' >{$level['secondary_region']}</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align="center">
                                    TO
                                </td>
                                <td align="center">
                                    <select  id="level_to0" class="form-control none_input" name="Level[0][level_to]" style="width:90%"  >
                                        <?php
                                        $level_list = ProgramRegion::locationLevel($project_id,$block);
                                        foreach($level_list as $level_num => $level){
                                            echo "<option value='{$level['secondary_region']}' >{$level['secondary_region']}</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td align="center">
                                    <input type="button" name="delete"  class="btn btn-primary btn-sm"  value="Delete" style="width:80px" onclick="deleteSelectedRow_1('row0')" />
                                    <input type="button" name="upload"  class="btn btn-primary btn-sm"  value="Upload" style="width:80px" onclick="file_click('0')" />
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="form-group" style="margin-top: 10px;">
                    <div class="col-sm-12" style="text-align: center">
                        <button type="button" class="btn btn-primary" style="margin-left: 10px" onclick="SaveLevelDraw()">Save</button>
                        <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back('<?php echo $program_id ?>')">Back</button>
                    </div>
                </div>
                <!-- /.tab-content -->
            </div><!-- /.card-body -->
            <?php
                }
            ?>
            <?php
            if($tag == 'unit'){
                ?>
                <div class="card-body" style="overflow-x: auto">
                    <div class="form-group" style="margin-top:10px">
                        <button type="button" id="sbtn" class="btn btn-primary btn-sm" onclick="insertNewRow_2()">Add</button>
                    </div>
                    <div style="margin-top:10px;">
                        <table id="orderTable" class="table" width="100%" align="center">
                            <thead>
                            <tr>
                                <th style="width: 30%;text-align: center">Attach</th>
                                <th style="width: 15%;text-align: center">Level</th>
                                <th style="width: 15%;text-align: center">Unit</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>
                            <?php
                            if(count($unit_draw_list)>0) {
                                $draw_index = 0;
                                foreach ($unit_draw_list as $unit_draw_index => $j) {
                                    ?>
                                    <tr id="row<?php echo $draw_index; ?>">
                                        <td align="center">
                                            <input type="text" id="file_text<?php echo $draw_index; ?>" class="form-control none_input"  style="width:90%"  value="<?php echo $j['drawing_name']; ?>" readonly/>
                                            <input class="form-control"  id="file<?php echo $draw_index; ?>" type="file" style="display:none" onchange="raupload(this,<?php echo $draw_index; ?>)">
                                            <input type="hidden" id="file_path<?php echo $draw_index; ?>"  name="Unit[0][file_path]" value="<?php echo $j['drawing_id']; ?>"/>
                                        </td>
                                        <td align="center">
                                            <select  id="level<?php echo $draw_index; ?>  none_input" class="form-control none_input" name="Unit[<?php echo $draw_index; ?>][level]" style="width:90%"  >
                                                <?php
                                                $level_list = ProgramRegion::locationLevel($project_id,$block);
                                                foreach($level_list as $level_num => $level){
                                                    $select_tag = '';
                                                    if($j['level'] == $level['secondary_region']){
                                                        $select_tag = ' selected';
                                                    }
                                                    echo "<option value='{$level['secondary_region']}' $select_tag>{$level['secondary_region']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center">
                                            <select type="text" id="unit<?php echo $draw_index; ?>" class="form-control none_input" name="Unit[<?php echo $draw_index; ?>][unit]" style="width:90%"   >
                                                <?php
                                                $unit_list = ProgramRegion::locationUnit($project_id,$block);
                                                foreach($unit_list as $unit_num => $unit){
                                                    $select_tag = '';
                                                    if($j['unit'] == $unit['unit']){
                                                        $select_tag = ' selected';
                                                    }
                                                    echo "<option value='{$unit['unit']}' $select_tag>{$unit['unit']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center">
                                            <input type="button" name="delete"  class="btn btn-primary btn-sm"  value="Delete" style="width:80px" onclick="deleteSelectedRow_2('row<?php echo $draw_index; ?>')" />
                                            <input type="button" name="upload"  class="btn btn-primary btn-sm"  value="Upload" style="width:80px" onclick="file_click('<?php echo $draw_index; ?>')" />
                                        </td>
                                    </tr>
                                    <?php
                                    $draw_index++;
                                }
                            }else{
                                ?>
                                <tr id="row0">
                                    <td align="center">
                                        <input type="text" id="file_text0" class="form-control none_input"  style="width:90%" readonly/>
                                        <input class="form-control"  id="file0" type="file" style="display:none" onchange="raupload(this,0)">
                                        <input type="hidden" id="file_path0"  name="Unit[0][file_path]" value=""/>
                                    </td>
                                    <td align="center">
                                        <select type="text" id="level0  none_input" class="form-control none_input" name="Unit[0][level]" style="width:90%">
                                            <?php
                                            $level_list = ProgramRegion::locationLevel($project_id,$block);
                                            foreach($level_list as $level_num => $level){
                                                echo "<option value='{$level['secondary_region']}' >{$level['secondary_region']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td align="center">
                                        <select type="text" id="unit0" class="form-control none_input" name="Unit[0][unit]" style="width:90%"  >
                                            <?php
                                            $unit_list = ProgramRegion::locationUnit($project_id,$block);
                                            foreach($unit_list as $unit_num => $unit){
                                                echo "<option value='{$unit['unit']}' >{$unit['unit']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td align="center">
                                        <input type="button" name="delete"  class="btn btn-primary btn-sm"  value="Delete" style="width:80px" onclick="deleteSelectedRow_2('row0')" />
                                        <input type="button" name="upload"  class="btn btn-primary btn-sm"  value="Upload" style="width:80px" onclick="file_click('0')" />
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="form-group" style="margin-top: 10px;">
                        <div class="col-sm-12" style="text-align: center">
                            <button type="button" class="btn btn-primary" style="margin-left: 10px" onclick="SaveUnitDraw()">Save</button>
                            <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back()">Back</button>
                        </div>
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
                <?php
            }
            ?>
        </div>
        <!-- /.card -->
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/loading_upload.js"></script>
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
    var color_index = 0;
    function insertNewRow_1() {
        var project_id = $('#project_id').val();
        var block = $('#block').val();
        color_index++;
        //获取表格有多少行
        var rowLength = $("#orderTable tr").length-1;
        //这里的rowId就是row加上标志位的组合。是每新增一行的tr的id。
        var rowId = "row" + rowLength;
        // alert(rowLength);
        //每次往下标为flag+1的下面添加tr,因为append是往标签内追加。所以用 after
        var insertStr = "<tr id=" + rowId + " >" + "<td  align='center'><input id="+'file_text'+rowLength+"  class='form-control none_input' style='width:90%' readonly><input  id="+'file'+rowLength+" type='file' style='display:none' onchange='raupload(this,"+rowLength+")'><input type='hidden' id="+'file_path'+rowLength+" name='Level["+rowLength+"][file_path]' value=''></td>";
        insertStr+="<td  align='center'>";
        insertStr+="<select  id="+'level_from'+rowLength+"  name='Level["+rowLength+"][level_from]'   style='width:90%' class='form-control none_input'>";
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=proj/location/levellist",
            type: "POST",
            dataType: "json",
            async:false,             //false表示同步
            beforeSend: function () {

            },
            success: function (data) {
                var option ='';
                $.each(data,function(index,value){
                    option+="<option value='"+value.secondary_region+"'>"+value.secondary_region+"</option>";
//                            $("#ca").val(slv);
                })
                insertStr+=option;
                insertStr+="</select>";
                insertStr+="</td>";
                insertStr+="<td align='center'>TO</td>";
                insertStr+="<td style='width: 10%' align='center'>";
                insertStr+="<select id="+'level_to'+rowLength+" name='Level["+rowLength+"][level_to]'  style='width:90%'  class='form-control none_input'>";
                insertStr+=option;
                insertStr+="</select>";
                insertStr+="</td>";
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
        insertStr+="<td align='center'> <input type='button' name='delete' class='btn btn-primary btn-sm' value='Delete' style='width:80px'  onclick='deleteSelectedRow_1(\"row" + flag + "\")' />  <input type='button' name='upload' class='btn btn-primary btn-sm' value='Upload' style='width:80px'  onclick='file_click(" + rowLength + ")' /></td>";
        insertStr+="</tr>";
        //这里的行数减2，是因为要减去底部的一行和顶部的一行，剩下的为开始要插入行的索引
//        alert(rowLength);
        $("#orderTable tr:eq(" + rowLength + ")").after(insertStr); //将新拼接的一行插入到当前行的下面
        //每插入一行，flag自增一次
        flag++;
    }

    function insertNewRow_2() {
        color_index++;
        //获取表格有多少行
        var rowLength = $("#orderTable tr").length-1;
        //这里的rowId就是row加上标志位的组合。是每新增一行的tr的id。
        var rowId = "row" + rowLength;
        // alert(rowLength);
        //每次往下标为flag+1的下面添加tr,因为append是往标签内追加。所以用 after
        var insertStr = "<tr id=" + rowId + " >" + "<td  align='center'><input id="+'file_text'+rowLength+"  class='form-control none_input' style='width:90%' readonly><input  id="+'file'+rowLength+" type='file' style='display:none' onchange='raupload(this,"+rowLength+")'><input type='hidden' id="+'file_path'+rowLength+" name='Unit["+rowLength+"][file_path]' value=''></td>";
        insertStr+="<td  align='center'>";
        insertStr+="<select  id="+'level'+rowLength+"  name='Unit["+rowLength+"][level]'   style='width:90%' class='form-control none_input'>";
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=proj/location/levellist",
            type: "POST",
            dataType: "json",
            async:false,             //false表示同步
            beforeSend: function () {

            },
            success: function (data) {
                var option ='';
                $.each(data,function(index,value){
                    option+="<option value='"+value.secondary_region+"'>"+value.secondary_region+"</option>";
//                            $("#ca").val(slv);
                })
                insertStr+=option;
                insertStr+="</select>";
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
        insertStr+="</td>";
        insertStr+="<td style='width: 10%' align='center'>";
        insertStr+="<select id="+'unit'+rowLength+" name='Unit["+rowLength+"][unit]'  style='width:90%'  class='form-control none_input'>";
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=proj/location/unitlist",
            type: "POST",
            dataType: "json",
            async:false,             //false表示同步
            beforeSend: function () {

            },
            success: function (data) {
                var option ='';
                $.each(data,function(index,value){
                    option+="<option value='"+value.unit+"'>"+value.unit+"</option>";
//                            $("#ca").val(slv);
                })
                insertStr+=option;
                insertStr+="</select>";
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
        insertStr+="</td>";
        insertStr+="<td align='center'> <input type='button' name='delete' class='btn btn-primary btn-sm' value='Delete' style='width:80px'  onclick='deleteSelectedRow_2(\"row" + flag + "\")' />  <input type='button' name='upload' class='btn btn-primary btn-sm' value='Upload' style='width:80px'  onclick='file_click(" + rowLength + ")' /></td>";
        insertStr+="</tr>";
        //这里的行数减2，是因为要减去底部的一行和顶部的一行，剩下的为开始要插入行的索引
//        alert(rowLength);
        $("#orderTable tr:eq(" + rowLength + ")").after(insertStr); //将新拼接的一行插入到当前行的下面
        //为新添加的行里面的控件添加新的id属性。
        // $("#" + rowId + " td:eq(0)").children().eq(0).attr("id", "stage_name" + flag);
        // $("#" + rowId + " td:eq(1)").children().eq(0).attr("id", "stage_color" + flag);
        // $("#" + rowId + " td:eq(2)").children().eq(0).attr("id", "order_id" + flag);
        //每插入一行，flag自增一次
        flag++;
    }

    function file_click(rowLength){
        $('#file'+rowLength).click();
    }

    //-----------------删除一行，根据行ID删除-start--------

    function deleteSelectedRow_1(rowID) {
        if (confirm("Are you sure to delete that line？")) {
            $("#" + rowID).remove();
        }
    }
    function deleteSelectedRow_2(rowID) {
        if (confirm("Are you sure to delete that line？")) {
            $("#" + rowID).remove();
        }
    }

    function SaveLevelDraw() {
        var project_id = $('#project_id').val();
        var block = $('#block').val();

        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=proj/location/saveleveldraw",
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

    function SaveUnitDraw() {
        var project_id = $('#project_id').val();
        var block = $('#block').val();

        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=proj/location/saveunitdraw",
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

    //返回
    var back = function () {
        var project_id = $('#project_id').val();
        var block = $('#block').val();
        window.location = "index.php?r=proj/location/locationlist&program_id="+project_id+"&block="+block;
    }

    var raupload = function(file,rowLength){
        var A=[90,92,93,94,95,96,98];
        var file_list = $('#file'+rowLength)[0].files;
        console.log(file.value);
        $.each(file_list, function (name, file) {
            if (!/\.(jpg|jpeg|png|JPG|PNG|pdf)$/.test(file.name)) {
                alert("Please upload document in either .jpeg, .jpg, .png or .pdf format.");
                return false;
            }
            ra_tag = file.name.lastIndexOf(".");
            ra_length = file.name.length;
            //获取后缀名
            ra_type=file.name.substring(ra_tag,ra_length);
            ra_name = file.name.substr(0,ra_tag);
            var video_src_file = file.name;
            containSpecial = new RegExp(/[\~\%\^\*\[\]\{\}\|\\\;\:\'\"\,\.\/\?]+/);
            status = containSpecial.test(ra_name);
            if(status == 'true'){
                alert('File name contains special characters, please check before uploading');
                return false;
            }
            var newFileName = video_src_file.split('.');

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            formData.append("file1", file);

            $.ajax({
                url: "https://shell.cmstech.sg/appupload",
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // 告诉jQuery不要去处理发送的数据
                contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
                beforeSend: function () {
                    addcloud();
                },
                success: function (data) {
                    removecloud();//去遮罩
                    // drawing_num =  parseInt(drawing_num)+1;
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            var draw_id = add_file(value.file1);
                            var index = Math.ceil(Math.random()*6);
                            $('#file_text'+rowLength).val(file.name);
                            // $('#file_path'+rowLength).val(A[index]);
                            $('#file_path'+rowLength).val(draw_id);
                        }
                    });
                }
            });
        })
    }

    function add_file(file_path){
        var project_id = $('#project_id').val();
        var draw_id;
        $.ajax({
            data: {file_path: file_path,project_id:project_id},
            url: "index.php?r=proj/project/adddraw",
            dataType: "json",
            async: false,
            type: "POST",
            success: function (data) {
                draw_id =  data.drawing_id;
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
        return draw_id;
    }
</script>