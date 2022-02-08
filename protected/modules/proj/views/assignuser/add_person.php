<div class="container-fluid">
    <form name="_query_form" id="_query_form" role="form">
        <div class="row">
            <!-- <div class="col-9"> -->
                <input id="program_id" value="<?php echo $program_id; ?>" type="hidden">
                <div class=" col-2 padding-lr5" >
                    <input type="text" id="query_user_name" class="form-control input-sm" name="q[user_name]" placeholder="User Name">
                </div>
                <div class=" col-2 padding-lr5" >
                    <input type="text" id="query_phone"  class="form-control input-sm" name="q[phone]" placeholder="Phone">
                </div>
                <div class=" col-2 padding-lr5" >
                    <input type="text" id="query_work_no" class="form-control input-sm" name="q[work_no]" placeholder="Work No">
                </div>
                <a class="tool-a-search" href="javascript:QueryStaff();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                <div class="col-4" style="">
                    <label  class="float-sm-right">
                        <button class="btn btn-primary btn-sm" style="text-align: right;" type='button' onclick="add()">Add New Person</button>
                    </label>
                </div>
            <!-- </div> -->
            <!-- <div class="col-4 padding-lr5" style="float:right;" >
                <button class="btn btn-default" type='button' onclick="add()">Add New Person</button>
            </div> -->
        </div>
    </form>
    <div class="row" id="user_demo" style="margin-top: 10px;">

    </div>
    
</div>

<script type="text/javascript">
    var add =  function(){
        var title = '1';
        var program_id = $('#program_id').val();
        window.location = "index.php?r=proj/staff/tabs&mode=insert&title="+title+"&program_id="+program_id;
    }

    var QueryStaff = function (id) {
        var user_name = $('#query_user_name').val();
        var user_phone = $('#query_phone').val();
        var user_work_no = $('#query_work_no').val();
        var program_id = $('#program_id').val();
        $.ajax({
            data: {user_name: user_name,user_phone: user_phone,user_work_no: user_work_no,program_id:program_id},
            url: "index.php?r=proj/staff/queryuser",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data){
                    var tab='<table class="table table-bordered">';
                    tab+='<tr>';
                    tab+="<th >Id</th><th >User Name</th><th >Phone</th><th >Work No</th><th >Company</th><th >Status</th><th >Action</th>";
                    tab+='</tr>';
                    $.each(data, function (i, field) {
                        tab+='<tr>';
                        tab+="<td >"+field.user_id+"</td><td >"+field.user_name+"</td><td >"+field.user_phone+"</td><td >"+field.work_no+"</td><td >"+field.contractor_name+"</td>";
                        if(field.check_status != '11'){
                            tab+="<td >Pending Admission</td><td style='text-align: center'><a class='a_logo' onclick='apply(\""+field.user_id+"\",\""+program_id+"\")' title='Entrance'><i class='fa fa-fw fa-sign-in-alt'></i></a><a class='a_logo' onclick='itemLogout(\""+field.user_id+"\",\""+field.user_name+"\")' title='Delete'><i class='fa fa-fw fa-times'></i></a></td>";
                        }else{
                            tab+="<td >Normal</td><td ></td>";
                        }
                        tab+='</tr>';
                    });
                    tab+='</table>';
                    $('#user_demo').append(tab);
                }
            }
        })
    }

    var apply =  function(user_id,program_id){
        $.ajax({
            data: {user_id: user_id,program_id:program_id},
            url: "index.php?r=proj/assignuser/applyuser",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1){
                    alert('Apply Sucess');
                }
            }
        })
    }

    //注销
    var itemLogout = function (id, name) {

        if (!confirm('<?php echo Yii::t('common', 'confirm_logout_1'); ?>' + name + '<?php echo Yii::t('common', 'confirm_logout_2'); ?>')) {
            return;
        }
        // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=comp/staff/logout",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_logout'); ?>");
                    $("#modal-close").click();
                    itemQuery();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_logout'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
</script>
