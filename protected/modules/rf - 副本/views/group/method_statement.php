<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <div class="row" >
                    <div class="col-9">
                        <h3 class="box-title">Group</h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="form1" >
                    <div class="form-group" style="margin-top: 10px;" >
                        <label for="template_name" class="col-sm-2 control-label padding-lr5">Group Name</label>
                        <div  class="col-sm-3 padding-lr5">
                            <input id="group_name" class="form-control" name="group[group_name]" type="text" value="<?php echo $group_name; ?>">
                            <input id="program_id" name="group[program_id]"  type="hidden" value="<?php echo $program_id; ?>">
                            <input id="group_id" name="group[group_id]"  type="hidden" value="<?php echo $group_id; ?>">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        <label for="template_name" class="col-sm-2 control-label padding-lr5">Company</label>
                        <div  class="col-sm-3 padding-lr5">
                            <select  id="contractor_id" class="form-control input-sm" onchange="user_data(0)">
                                <?php
                                //                    $contractor_list = Contractor::compList();
                                $contractor_list = Contractor::CompanyListByProgram($program_id);
                                echo "<option value=''>--Select Company--</option>";
                                foreach ($contractor_list as $contractor_id => $contractor_name) {
                                    echo "<option value='{$contractor_id}'>{$contractor_name}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        <label for="template_name" class="col-sm-2 control-label padding-lr5">User</label>
                        <div  class="col-sm-3 padding-lr5">
                            <select  id="user_id" class="form-control input-sm">
                            </select>
                        </div>
                    </div>

                    <div  class="form-group" style="margin-top: 10px;">
                        <button type="button" id="sbtn" class="btn btn-primary btn-sm" onclick="insertNewRow()">Add</button>
                    </div>
                    <div style="margin-top: 10px;">
                        <table id="orderTable" class="table-bordered" width="100%" align="center">
                            <thead>
                            <tr>
                                <th style="width: 40%;text-align: center">Company</th>
                                <th style="width: 40%;text-align: center">User</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            </thead>
                            <?php
                            if($group_id != '' && count($detail_list)>0) {
                                foreach ($detail_list as $i => $j) {
                                    ?>
                                    <tr id="row<?php echo $i; ?>">
                                        <td align="center">
                                            <select name="group[contractor_id]" id="contractor_id<?php echo $i; ?>" class="form-control input-sm" onchange="user_data(0)">
                                                <?php
                                                echo "<option value='{$j['contractor_id']}' selected>{$j['contractor_name']}</option>";
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center">
                                            <select name="group[user_id]" id="user_id<?php echo $i; ?>" class="form-control input-sm">
                                                <?php
                                                echo "<option value='{$j['user_id']}' selected>{$j['user_name']}</option>";
                                                ?>
                                            </select>
                                        </td>
                                        <td align="center">
                                            <input type="button" name="delete" class="btn btn-primary btn-sm" value="Delete"
                                                   style="width:80px" onclick="deleteSelectedRow('row<?php echo $i; ?>')"/>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }else{
                                ?>

                            <?php } ?>
                        </table>
                    </div>
                </form>
                <div class="form-group" style="margin-top: 10px;text-align: center">
                    <?php
                    $pro_model = Program::model()->findByPk($program_id);
                    $root_proid = $pro_model->root_proid;
                    $root_model = Program::model()->findByPk($root_proid);
                    $user_phone = Yii::app()->user->id;
                    $user = Staff::userByPhone($user_phone);
                    $user_model = Staff::model()->findByPk($user[0]['user_id']);
                    $user_contractor_id = $user_model->contractor_id;
                    $contractor_id = $root_model->contractor_id;
                    if($contractor_id == $user_contractor_id){
                        ?>
                        <button type="button" class="btn btn-primary" style="display:block;margin:0 auto" onclick="GetValue()">Save</button>
                    <?php } ?>
                    <button type="button" class="btn btn-primary" style="display:block;margin:0 auto" onclick="back('<?php echo $program_id ?>')">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/JavaScript">
    $(document).ready(function() {
    })

    function user_data() {
        var contractor_id = $('#contractor_id').val();
        var userObj = $('#user_id');
        $.ajax({
            url: "index.php?r=rf/rf/stafflist",
            data: {contractor_id: contractor_id, confirm: 1},
            type: "POST",
            dataType: "json",
            success: function (res) {
                console.log(res);
                userObj.empty();
                $.each(res, function (m, n) {
                    userObj.append("<option value='"+n.id+"'>"+n.text+"</option>");
                })
            }
        })
    }


    // ????????????
    function del_tr(obj){
        var index = $(obj).parents("tr").index(); //?????????????????????tr?????????
        $(obj).parents("tr").remove();
    }

    //??????????????????
    var formvalue = "";
    var flag = 1;
    var index = 1;
    var firstCell = "";
    var secondCell = "";
    var thirdCell = "";

    $(function() {
        //??????????????????
        firstCell = $("#row0 td:eq(0)").html();
        secondCell = $("#row0 td:eq(1)").html();
        thirdCell = $("#row0 td:eq(2)").html();
        fourthCell = $("#row0 td:eq(3)").html();
    });

    //-----------------????????????-----------start---------------

    function insertNewRow() {
        var contractor_id = $("#contractor_id option:selected").val();
        var contractor_name = $("#contractor_id option:selected").text();
        var user_id = $("#user_id option:selected").val();
        var user_name = $("#user_id option:selected").text();
        //????????????????????????
        var rowLength = $("#orderTable tr").length;
        //?????????rowId??????row????????????????????????????????????????????????tr???id???
        var rowId = "row" + rowLength;
//        alert(rowLength);
        //??????????????????flag+1???????????????tr,??????append????????????????????????????????? after
        var insertStr = "<tr id=" + rowId + " >" + "<td align='center'><select name='group[contractor_id]' id="+'contractor_id'+rowLength+" class='form-control input-sm' >"+"<option value="+contractor_id+">"+contractor_name+"</option>"+"</select></td><td align='center'><select name='group[user_id]' id="+'user_id'+rowLength+" class='form-control input-sm'>"+"<option value="+user_id+">"+user_name+"</option>"+"</select></td><td align='center'><input type='button' name='delete' class='btn btn-primary btn-sm' value='Delete' style='width:80px'  onclick='deleteSelectedRow(\"" + rowId + "\")' /> "; + "</td></tr>";
        //??????????????????2????????????????????????????????????????????????????????????????????????????????????????????????
//        alert(rowLength);
        $("#orderTable tr:eq(" + (rowLength - 1) + ")").after(insertStr); //????????????????????????????????????????????????
    }

    //-----------------????????????????????????ID??????-start--------

    function deleteSelectedRow(rowID) {
        if (confirm("Are you sure to delete that line???")) {
            $("#" + rowID).remove();
        }
    }
    //-----------------?????????????????????----start--------------

    function GetValue() {
        var value = "";
        $("#orderTable tr").each(function(i) {
            if (i >= 1) {
                $(this).children().each(function(j) {
                    if ($("#orderTable tr").eq(i).children().length - 1 != j) {
                        value += $(this).children().eq(0).val() + "," //????????????????????????????????????????????????
                        if ($(this).children().length > 1) {
                            value += $(this).children().eq(1).val() + "," //???????????????????????????????????????????????????????????????
                        }
                    }
                });
                value = value.substr(0, value.length - 1) + "@"; //???????????????????????????????????????????????????????????????#????????????
            }
        });
        value = value.substr(0, value.length-1);

        ReceiveValue(value);
        //             $("#formvalue").val(value);
        //             $("formvalue").submit();
    }
    //-------------------?????????????????????-----------------------------

    function ReceiveValue(str) {
        var Str = str.split('#');
        console.log(str);
        var group_name = $('#group_name').val();
        var program_id = $('#program_id').val();
        var group_id = $('#group_id').val();
//        var myType = document.getElementById("clt_type");//??????select??????
//        var index = myType.selectedIndex; //???????????????????????????selectIndex??????????????????????????????index
//        var clt_type = myType.options[index].value;//???????????????options???value???
        if(group_id != ''){
            var mode = 'edit';
            $.ajax({
                data:{mode:mode,json_data:str,group_id:group_id,group_name:group_name,program_id:program_id},
                url: "index.php?r=rf/group/savemethod",
                type: "POST",
                dataType: "json",
                async:false,             //false????????????
                beforeSend: function () {

                },
                success: function (data) {
                    alert('Set Success');
                    window.location = "index.php?r=rf/group/list&program_id="+program_id;
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
                data:{mode:mode,json_data:str,group_name:group_name,program_id:program_id},
                url: "index.php?r=rf/group/savemethod",
                type: "POST",
                dataType: "json",
                async:false,             //false????????????
                beforeSend: function () {

                },
                success: function (data) {
                    alert('Set Success');
                    window.location = "index.php?r=rf/group/list&program_id="+program_id;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert(XMLHttpRequest.status);
                    alert(XMLHttpRequest.readyState);
                    alert(textStatus);
                },
            });
        }
    }

    //??????
    var back = function (program_id) {
        window.location = "index.php?r=rf/group/list&program_id="+program_id;
    }


</script>