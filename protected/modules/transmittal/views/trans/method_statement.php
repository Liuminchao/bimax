<link href="css/select2.css" rel="stylesheet" type="text/css" />
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <div class="row" >
                    <div class="col-12">
                        <h3 class="box-title">Transmittal</h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="col-1">
                </div>
                <div class="col-10">
                    <form id="form1" >
                        <div class="row" style="margin-top: 30px;">
                            <label for="program_name" class="col-1 offset-md-2 control-label padding-lr5" style="padding-top:7px;text-align: left">Form</label>
                            <div class="col-3 padding-lr5">
                                <input  class="form-control"  value="HDB-Transmittal Form" readonly>
                                <input id="form_id" type="hidden" class="form-control" name="Trans[form_id]" value="F00001" >
                            </div>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <label for="program_name" class="col-1 offset-md-2 control-label padding-lr5" style="padding-top:7px;text-align: left">Project</label>
                            <div class="col-3 padding-lr5">
                                <input value="<?php echo $program_name; ?>" class="form-control" readonly>
                                <input id="program_id" value="<?php echo $program_id; ?>" class="form-control" type="hidden" name="Trans[program_id]">
                            </div>
                            <div class="col-1 padding-lr5" >
                            </div>
                            <label for="program_name" class="col-1 control-label padding-lr5" style="padding-top:7px;text-align: left">Reference No.</label>
                            <div class="col-3 padding-lr5">
                                <input value="" class="form-control" id="project_nos" name="Trans[project_nos]">
                            </div>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <label for="program_name" class="col-1 offset-md-2 control-label padding-lr5" style="text-align: left">To<span style="color: #c12e2a">*</span></label>
                            <div class="col-3 padding-lr5">
                                <select id="to_contractor_id" p class="form-control" onchange="to_create_data()"  multiple="multiple" >
                                    <?php
                                    $contractor_list = Contractor::CompanyListByProgram($program_id);
                                    $group_list = RfGroup::groupByProgram($program_id);
                                    if(count($group_list)>0){
                                        foreach ($group_list as $group_id => $group_name) {
                                            $group_id = 'Group'.$group_id;
                                            echo "<option value='{$group_id}'>{$group_name}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-1 padding-lr5" >
                            </div>
                            <div class="col-4 padding-lr5" >
                                <select id="to"  class="form-control" multiple="multiple" >
                                </select>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <label for="program_name" class="col-1 offset-md-2 control-label padding-lr5" style="text-align: left">Cc</label>
                            <div class="col-3 padding-lr5">
                                <select id="cc_contractor_id" class="form-control" onchange="cc_create_data()"  multiple="multiple" >
                                    <?php
                                    $contractor_list = Contractor::CompanyListByProgram($program_id);
                                    $group_list = RfGroup::groupByProgram($program_id);
                                    if(count($group_list)>0){
                                        foreach ($group_list as $group_id => $group_name) {
                                            $group_id = 'Group'.$group_id;
                                            echo "<option value='{$group_id}'>{$group_name}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-1 padding-lr5" >
                            </div>
                            <div class="col-4 padding-lr5" >
                                <select id="cc"  class="form-control" multiple="multiple" class="form-control">
                                </select>
                            </div>
                            <!--                <button type="button" class="btn btn-primary" style="background-color: #169BD5">Directory</button>-->
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <label for="program_name" class="col-1 offset-md-2 control-label padding-lr5" style="padding-top:7px;text-align: left">Subject<span style="color: #c12e2a">*</span></label>
                            <div class="col-8 padding-lr5" style="text-align: left;">
                                <input id="subject" class="form-control" name="Trans[subject]" check-type="required" required-message="Subject can not be empty"  type="text" value="" >
                            </div>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <label for="program_name" class="col-1 offset-md-2 control-label padding-lr5" style="text-align: left">RVO<span style="color: #c12e2a">*</span></label>
                            <div class="col-4 padding-lr5" style="text-align: left;">
                                <input type="radio" id="yes_rvo" name="Trans[rvo]"  value="1"   /> Yes
                                <input type="radio" id="no_rvo" name="Trans[rvo]"  value="2" style="margin-left: 20px;"  /> No
                            </div>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <div class="offset-md-2 padding-lr5">
                                <input id="file" multiple="multiple" class="form-control" check-type="" style="display:none" onchange="raupload(this)" name="File[file_path]" type="file" />
                                <button type="button" class="btn btn-primary btn-sm" style="background-color: #169BD5"  onclick="file.click()">Add</button>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <div class="offset-md-2 col-9  padding-lr5">
                                <table id="orderTable" class="table-bordered" width="100%" align="center">
                                    <thead>
                                    <tr style="height: 32px;">
                                        <th style="width: 5%;text-align: center">S/N</th>
                                        <th style="width: 30%;text-align: center">Attach</th>
                                        <th style="width: 10%;text-align: center">Type</th>
                                        <th style="width: 20%;text-align: center">Purpose of Issue</th>
                                        <th style="text-align: center">Action</th>
                                    </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <label for="program_name" class="col-2 offset-md-2 control-label padding-lr5" style="text-align: left">Remarks</label>
                        </div>

                        <div class="row" style="margin-top: 30px;">
                            <div class="offset-md-2 col-9 padding-lr5">
                                <textarea rows="10" id="remark" name="Trans[remark]" style="width:100%"></textarea>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 30px;margin-bottom: 100px; ">
                            <button id="draft_btn" type="button" class="btn btn-primary" style="background-color: #169BD5;display:block;margin:0 auto" onclick="back()" >Back</button>
                            <button id="draft_btn" type="button" class="btn btn-default" style="background-color: #169BD5;display:block;margin:0 auto" onclick="GetValue()" >Send</button>
                        </div>
                    </form>
                </div>
                <div class="col-1">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group" style="margin-top: 10px;">
    <div class="col-sm-offset-4 col-10">
        <?php
            $pro_model = Program::model()->findByPk($program_id);
            $root_proid = $pro_model->root_proid;
            $root_model = Program::model()->findByPk($root_proid);
            $user_phone = Yii::app()->user->id;
            $user = Staff::userByPhone($user_phone);
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_contractor_id = $user_model->contractor_id;
            $contractor_id = $root_model->contractor_id;
        ?>
    </div>
</div>
<script type="text/javascript" src="js/ajaxfileupload.js"></script>
<script type="text/javascript" src="js/loading_upload.js"></script>
<script type="text/javascript" src="js/select2/select2.js"></script>
<script type="text/javascript" src="js/layui.js" ></script>
<script type="text/JavaScript">
    $(document).ready(function() {
        $('#to_contractor_id').select2({
            placeholder: "Select Group"
        });
        $('#cc_contractor_id').select2({
            placeholder: "Select Group"
        });
        to_create_data();
        cc_create_data();
        showTime();
    })

    function showTime() {
        $.ajax({
            url: "index.php?r=rf/rf/sendheart",
            data: {confirm:'hell world'},
            type: "POST",
            dataType: "json",
            success: function (res) {
                console.log(res.msg);
                setTimeout('showTime()', 600000);
            }
        })
    }

    function to_create_data() {
        $('#to').empty();
        contractor_id = $('#to_contractor_id').val();
        if(contractor_id.indexOf("Group") == -1){
            $.ajax({
                url: "index.php?r=rf/rf/stafflist",
                data: {contractor_id: contractor_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    // $('#to').empty();
                    $('#to').select2({
                        data: res,  //返回的数据
                    });

                }
            })
        }
        for (var item in contractor_id){
            if(contractor_id[item].indexOf("Group") != -1){
                $.ajax({
                    url: "index.php?r=rf/group/userlist",
                    data: {group_id: contractor_id[item], confirm: 1},
                    type: "POST",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        // $('#to').empty();
                        $('#to').select2({
                            data: res,  //返回的数据
                        });
                        // to_arr = [];
                        // $.each(res, function (index, data) {
                        //     to_arr.push(data.id);
                        // })
                        // $("#to").val(to_arr).trigger('change');
                    }
                })
            }
        }
    }
    cc_arr = [];
    function cc_create_data() {
        $('#cc').empty();
        contractor_id = $('#cc_contractor_id').val();
        if(contractor_id.indexOf("Group") == -1){
            $.ajax({
                url: "index.php?r=rf/rf/stafflist",
                data: {contractor_id: contractor_id, confirm: 1},
                type: "POST",
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    // $('#cc').empty();
                    $('#cc').select2({
                        data: res,  //返回的数据
                    });
                }
            })
        }
        for (var item in contractor_id){
            if(contractor_id[item].indexOf("Group") != -1){
                $.ajax({
                    url: "index.php?r=rf/group/userlist",
                    data: {group_id:  contractor_id[item], confirm: 1},
                    type: "POST",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        // $('#cc').empty();
                        $('#cc').select2({
                            data: res,  //返回的数据
                        });
                        $.each(res, function (index, data) {
                            cc_arr.push(data.id);
                        })
                        $("#cc").val(cc_arr).trigger('change');
                    }
                })
            }
        }
    }

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
    var index = 0;
    var raupload = function(file){
        var file_list = $('#file')[0].files;
        console.log(file.value);
        $.each(file_list, function (name, file) {
            if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf|doc|docx|xls|xlsx|dwg|zip|rar)$/.test(file.name)) {
                alert("Please upload document in either .gif, .jpeg, .jpg, .png, .doc, .xls, .dwg, .zip, .rar, .xlsx, .docx or .pdf format.");
                return false;
            }
            ra_tag = file.name.lastIndexOf(".");
            ra_length = file.name.length;
            //获取后缀名
            ra_type=file.name.substring(ra_tag,ra_length);
            ra_name = file.name.substr(0,ra_tag);
            var video_src_file = file.name;
            containSpecial = new RegExp(/[(\~)(\%)(\^)(\&)(\*)(\()(\))(\[)(\])(\{)(\})(\|)(\\)(\;)(\:)(\')(\")(\,)(\.)(\/)(\?)(\)]+/);
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
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            // if(ra_type == '.pdf' || ra_type == '.jpg' || ra_type == '.png' || ra_type == '.jpeg'){
                            //     var $tr = $("<tr> <td  align='left' ><img  src='img/attach.png' ></td><td  align='left' >"+video_src_file+"</td> <td  align='right' width='15%'>"+"<a onclick='previewdoc(\""+value.file1+"\")'>Preview</a></td>"+"<td  align='center' width='15%'><a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                            // }else{
                            //     var $tr = $("<tr> <td  align='left' ><img  src='img/attach.png' ></td><td  align='left' >"+video_src_file+"</td> "+"<td  align='center' width='15%'><a onclick='del_attachment(this)'>Delete</a><input type='hidden' name='rf[attachment][]' value='"+value.file1+"' ></td></tr>");
                            // }
                            // var $table = $("#attach");
                            // $table.append($tr);
                            //获取表格有多少行
                            var rowLength = $("#orderTable tr").length;
                            //这里的rowId就是row加上标志位的组合。是每新增一行的tr的id。
                            var rowId = "row" + rowLength;
                            //        alert(rowLength);
                            //每次往下标为flag+1的下面添加tr,因为append是往标签内追加。所以用 after
                            index = index+1;
                            if(ra_type == '.pdf' || ra_type == '.png' || ra_type == '.jpg' || ra_type == '.jpeg'){
                                var insertStr = "<tr style='height:32px;'  id=" + rowId + " >" + "<td align='center'>"+index+"</td><td align='center'><input  id="+'attach_name'+rowLength+" class='form-control input-sm'  value="+video_src_file+" readonly>"+"</td><td align='center'><input  id="+'type'+rowLength+" class='form-control input-sm'  value="+ra_type+" readonly>"+"</td><td align='center'><select name='Trans[purpose][]' id="+'purpose'+rowLength+" class='form-control input-sm'>"+"<option value='1'>Working drawings</option><option value='2'>For Information</option><option value='3'>Approval drawings</option><option value='4'>Construction drawings</option><option value='5'>Others</option>"+"</select></td>"+"<td align='center'><input type='hidden' name='Trans[attachment][]' value='"+value.file1+"' ><a id='delete'   style='width:80px'  onclick='deleteSelectedRow(\"" + rowId + "\")' >Delete</a><a id='preview'   style='width:80px;margin-left: 10px;'  onclick='previewdoc(\""+value.file1+"\")' >Preview</a> "; + "</td></tr>";
                            }else{
                                var insertStr = "<tr style='height:32px;'  id=" + rowId + " >" + "<td align='center'>"+index+"</td><td align='center'><input  id="+'attach_name'+rowLength+" class='form-control input-sm'  value="+video_src_file+" readonly>"+"</td><td align='center'><input  id="+'type'+rowLength+" class='form-control input-sm'  value="+ra_type+" readonly>"+"</td><td align='center'><select name='Trans[purpose][]' id="+'purpose'+rowLength+" class='form-control input-sm'>"+"<option value='1'>Working drawings</option><option value='2'>For Information</option><option value='3'>Approval drawings</option><option value='4'>Construction drawings</option><option value='5'>Others</option>"+"</select></td>"+"<td align='center'><input type='hidden' name='Trans[attachment][]' value='"+value.file1+"' ><a id='delete'   style='width:80px'  onclick='deleteSelectedRow(\"" + rowId + "\")' >Delete</a> "; + "</td></tr>";
                            }

                            //这里的行数减2，是因为要减去底部的一行和顶部的一行，剩下的为开始要插入行的索引
                            //        alert(rowLength);
                            $("#orderTable tr:eq(" + (rowLength - 1) + ")").after(insertStr); //将新拼接的一行插入到当前行的下面
                        }
                    });
                }
            });
        })
    }

    function insertNewRow() {
        var contractor_id = $("#contractor_id option:selected").val();
        var contractor_name = $("#contractor_id option:selected").text();
        var user_id = $("#user_id option:selected").val();
        var user_name = $("#user_id option:selected").text();
        //获取表格有多少行
        var rowLength = $("#orderTable tr").length;
        //这里的rowId就是row加上标志位的组合。是每新增一行的tr的id。
        var rowId = "row" + rowLength;
//        alert(rowLength);
        //每次往下标为flag+1的下面添加tr,因为append是往标签内追加。所以用 after
        var insertStr = "<tr id=" + rowId + " >" + "<td align='center'><select name='group[contractor_id]' id="+'contractor_id'+rowLength+" class='form-control input-sm' >"+"<option value="+contractor_id+">"+contractor_name+"</option>"+"</select></td><td align='center'><select name='group[user_id]' id="+'user_id'+rowLength+" class='form-control input-sm'>"+"<option value="+user_id+">"+user_name+"</option>"+"</select></td><td align='center'><a id='delete'  style='width:80px'  onclick='deleteSelectedRow(\"" + rowId + "\")' >Delete</a> "; + "</td></tr>";
        //这里的行数减2，是因为要减去底部的一行和顶部的一行，剩下的为开始要插入行的索引
//        alert(rowLength);
        $("#orderTable tr:eq(" + (rowLength - 1) + ")").after(insertStr); //将新拼接的一行插入到当前行的下面
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
        var program_id = $('#program_id').val();
        var form_data = $('#form1').serialize();
        var cc_list = $("#cc").val();
        var to_list = $("#to").val();
        $.ajax({
            data:form_data+"&to="+to_list+"&cc="+cc_list,
            url: "index.php?r=transmittal/trans/savemethod",
            type: "POST",
            dataType: "json",
            async:false,             //false表示同步
            beforeSend: function () {

            },
            success: function (data) {
                alert('Set Success');
                window.location = "index.php?r=transmittal/trans/list&project_id="+program_id;
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
        var program_id = $('#program_id').val();
        window.location = "index.php?r=transmittal/trans/list&project_id="+program_id;
    }

    //预览
    function previewdoc (path) {
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
        }
    }

</script>