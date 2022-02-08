<?php
/**
 * Created by PhpStorm.
 * User: minchao
 * Date: 2017-05-17
 * Time: 15:25
 */
$staff_list = Staff::model()->findByPk($user_id);//人员信息
$staffinfo_list = StaffInfo::model()->findByPk($user_id);//人员详情信息
//var_dump($program_id);
$role_list = Role::roleList();//岗位列表
$aptitude_list =UserAptitude::queryAll($user_id);//人员证书
$contractor_id = Yii::app()->user->getState('contractor_id');
$self_info = ProgramUser::SelfInfo($contractor_id,$user_id);//人员所在项目的信息
//$self_pro = ProgramUser::SelfInpro($contractor_id,$user_id,$program_id);//人员所在指定项目信息
$authority_list = ProgramUser::AllRoleList();//权限列表
$ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
$status_css = CheckApplyDetail::statusText();//PTW执行类型
$inspection_type = SafetyLevel::levelText();//安全检查安全等级
//    var_dump($ptw_set_cnt);
//    exit;
?>
<!--<img alt=""  src="--><?php //echo $staffinfo_list['face_img']; ?><!--" width="130"  height="180"/>-->
<div class="row" ></div>
<br><br>

<div>
    <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
</div>

<table border="1" width="100%" id="info">
    <tr>
        <td colspan="4" ><h3 class="form-section">&nbsp;
    <?php echo Yii::t('comp_statistics', 'personel_information'); ?></h3>
        </td>
    <tr>
    <tr style="background-color: rgb(243, 244, 245);">
        <td><?php echo Yii::t('comp_statistics', 'name'); ?></td>
        <td><?php echo Yii::t('comp_statistics', 'designation'); ?></td>
        <td><?php echo Yii::t('comp_statistics', 'id_pass'); ?></td>
        <td><?php echo Yii::t('comp_staff', 'Face_img'); ?></td>
    </tr>
    <tr>
        <td><?php echo $staff_list['user_name'] ?></td>
        <td><?php echo $role_list[$staff_list['role_id']] ?></td>
        <td><?php echo $staff_list['work_no'] ?></td>
        <td rowspan="<?php $count = count($aptitude_list);  $count=2*$count+1; echo $count; ?>"><img alt=""  src="<?php echo $staffinfo_list['face_img']; ?>" width="130"  height="90"/></td>
    </tr>
    <?php if($aptitude_list){  ?>
    <?php  foreach($aptitude_list as $cnt => $list){  ?>
            <tr style="background-color: rgb(243, 244, 245);">
                <td><?php echo Yii::t('comp_statistics', 'certificate_content'); ?></td>
                <td><?php echo Yii::t('comp_statistics', 'permit_start_date'); ?></td>
                <td><?php echo Yii::t('comp_statistics', 'permit_end_date'); ?></td>
            </tr>
            <tr>
                <td><?php echo $list['aptitude_content'] ?></td>
                <td><?php echo $list['permit_startdate'] ?></td>
                <td><?php echo $list['permit_enddate'] ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
<!--    <tr style="background-color: rgb(243, 244, 245);">-->
<!--        <td colspan="3">-->
<!--            -->
<!--        </td>-->
<!--    </tr>-->
</table>
<select class="form-control input-sm" name="q[program]" style=" width:30%;margin-left: 30px" onchange="itemQuery(this.value);">
    <option value="">--<?php echo Yii::t('comp_statistics', 'Project');?>--</option>
    <?php
    if($self_info) {
        foreach ($self_info as $value => $name) {
            echo "<option value=" . $name['program_id'] . ">" . $name['program_name'] . "</option>";
        }
    }
    ?>
</select>
<table border='1' width='100%' id='pro_info'>
    <tr >
        <td colspan="3">
            <h3 class="form-section">&nbsp;
                <?php echo Yii::t('comp_statistics', 'employment_information'); ?></h3>
        </td>
    </tr>
</table>

<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" class="btn btn-default btn-lg"
                    style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>

<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    };
    //查询
    var itemQuery = function (id) {
        var user_id = $("#user_id").val();
//        window.location = "index.php?r=comp/staff/selfgrid&user_id="+user_id+"&program_id="+id;
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/self",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#pro_info').empty();
                var title = "<tr ><td colspan='3'> <h3 class='form-section'>&nbsp;<?php echo Yii::t('comp_statistics', 'employment_information'); ?></h3></td></tr>";
                var title_1 = "<tr style='background-color: rgb(243, 244, 245);'><td><?php echo Yii::t('comp_statistics', 'Project');?></td> <td><?php echo Yii::t('proj_project_user', 'ra_role');?></td> <td><?php echo Yii::t('proj_project_user', 'ptw_role');?></td></tr>";
                var content_1 = "<tr><td>"+data.program_name+"</td><td>"+data.ra_role+"</td><td>"+data.ptw_role+"</td><tr>";
                var title_2 = "<tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('proj_project_user', 'wsh_mbr_flag');?></td><td><?php echo Yii::t('proj_project_user', 'meeting_flag');?></td> <td><?php echo Yii::t('proj_project_user', 'training_flag');?></td></tr>";
                var content_2 = "<tr><td>"+data.wsh_mbr_flag+"</td><td>"+data.meeting_flag+"</td><td>"+data.training_flag+"</td><tr>";
                var title_3 = "<tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('proj_project_user', 'first_role');?></td> <td><?php echo Yii::t('proj_project_user', 'second_role');?></td><td><?php echo Yii::t('proj_project_user', 'third_role');?></td></tr>";
                var content_3 = "<tr><td>"+data.first_role+"</td><td>"+data.second_role+"</td><td>"+data.third_role+"</td><tr>";
                $('#pro_info').append(title);
                $('#pro_info').append(title_1);
                $('#pro_info').append(content_1);
                $('#pro_info').append(title_2);
                $('#pro_info').append(content_2);
                $('#pro_info').append(title_3);
                $('#pro_info').append(content_3);
                all_power(id);
            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //ajax并发
    var all_power = function (id) {
        program_date(id);
        ptw_cnt(id);
        ptw_totalcnt(id);
        tbm_cnt(id);
        tbm_totalcnt(id);
        train_cnt(id);
        train_totalcnt(id);
        meet_cnt(id);
        meet_totalcnt(id);
        inspection_cnt(id);
        inspection_totalcnt(id);
        accident_cnt(id);
        accident_totalcnt(id);
    }
    //入场出场日期
    var program_date = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/selfbydate",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                var title_1 = "<tr ><td colspan='3'><h4 class='form-section'>&nbsp;<?php echo Yii::t('comp_statistics', 'out_history'); ?></h4></td></tr>";
                var title_2 = "<tr style='background-color: rgb(243, 244, 245);'><td><?php echo Yii::t('comp_statistics', 'entrance'); ?></td><td><?php echo Yii::t('comp_statistics', 'out'); ?></td><td></td></tr>"
                $('#pro_info').append(title_1);
                $('#pro_info').append(title_2);
                $.each(data, function (name, value) {
                    var program_content = "<tr><td>"+value.entrance_time+"</td><td>"+value.leave_time+"</td><td></td><tr>";
                    $('#pro_info').append(program_content);
                })
            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //PTW权限
    var ptw_cnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/ptwrole",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                var ptw_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;PTW</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('comp_statistics', 'type'); ?></td> <td><?php echo Yii::t('comp_statistics', 'role'); ?></td> <td><?php echo Yii::t('comp_statistics', 'cnt'); ?></td></tr>";
                $('#pro_info').append(ptw_title);
                $.each(data, function (name, value) {
                    if(value.deal_type == 'MEMBER'){
                        var ptw_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=license/licensepdf/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&type_id="+value.type_id+"&deal_type=-1\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }else{
                        var ptw_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=license/licensepdf/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&type_id="+value.type_id+"&deal_type="+value.deal_type+"\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }
                    $('#pro_info').append(ptw_content);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //PTW总次数
    var ptw_totalcnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/ptwcnt",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                $.each(data, function (name, value) {
                    var ptw_total = "<tr><td colspan='2'><?php echo Yii::t('comp_statistics', 'total'); ?></td><td>"+value.cnt+"</td><tr>";
                    $('#pro_info').append(ptw_total);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //TBM权限
    var tbm_cnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/tbmrole",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                var tbm_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;TBM</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('comp_statistics', 'role'); ?></td> <td><?php echo Yii::t('comp_statistics', 'cnt'); ?></td> <td></td></tr>";
                $('#pro_info').append(tbm_title);
                $.each(data, function (name, value) {
                    if(value.deal_type == 'MEMBER'){
                        var tbm_content = "<tr><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=tbm/meeting/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type="+value.deal_type+"\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><td></td><tr>";
                    }else{
                        var tbm_content = "<tr><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=tbm/meeting/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type=-1\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><td></td><tr>";
                    }
                    $('#pro_info').append(tbm_content);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //TBM总次数
    var tbm_totalcnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/tbmcnt",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                $.each(data, function (name, value) {
                    var tbm_total = "<tr><td colspan='2'><?php echo Yii::t('comp_statistics', 'total'); ?></td><td>"+value.cnt+"</td><tr>";
                    $('#pro_info').append(tbm_total);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //TRAIN权限
    var train_cnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id,module_type:1},
            url: "index.php?r=comp/staff/trainrole",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                var train_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;TRAINING</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('comp_statistics', 'type'); ?></td><td><?php echo Yii::t('comp_statistics', 'role'); ?></td> <td><?php echo Yii::t('comp_statistics', 'cnt'); ?></td></tr>";
                $('#pro_info').append(train_title);
                $.each(data, function (name, value) {
                    if(value.deal_type == 'MEMBER'){
                        var train_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=train/training/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type="+value.deal_type+"&type_id="+value.type_id+"&module_type=1\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }else{
                        var train_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=train/training/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type=-1&type_id="+value.type_id+"&module_type=1\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }
                    $('#pro_info').append(train_content);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //TRAIN总次数
    var train_totalcnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id,module_type:1},
            url: "index.php?r=comp/staff/traincnt",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                $.each(data, function (name, value) {
                    var train_total = "<tr><td colspan='2'><?php echo Yii::t('comp_statistics', 'total'); ?></td><td>"+value.cnt+"</td><tr>";
                    $('#pro_info').append(train_total);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //MEETING权限
    var meet_cnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id,module_type:2},
            url: "index.php?r=comp/staff/trainrole",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                var meet_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;MEETING</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('comp_statistics', 'type'); ?></td><td><?php echo Yii::t('comp_statistics', 'role'); ?></td> <td><?php echo Yii::t('comp_statistics', 'cnt'); ?></td></tr>";
                $('#pro_info').append(meet_title);
                $.each(data, function (name, value) {
                    if(value.deal_type == 'MEMBER'){
                        var meet_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=train/training/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type="+value.deal_type+"&type_id="+value.type_id+"&module_type=1\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }else{
                        var meet_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=train/training/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type=-1&type_id="+value.type_id+"&module_type=1\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }
                    $('#pro_info').append(meet_content);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //MEETING总次数
    var meet_totalcnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id,module_type:2},
            url: "index.php?r=comp/staff/traincnt",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                $.each(data, function (name, value) {
                    var meet_total = "<tr><td colspan='2'><?php echo Yii::t('comp_statistics', 'total'); ?></td><td>"+value.cnt+"</td><tr>";
                    $('#pro_info').append(meet_total);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //INSPECTION权限
    var inspection_cnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/inspectionrole",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
//                var inspection_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;INSPECTION</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php //echo Yii::t('comp_safety', 'safety_level'); ?>//</td><td><?php //echo Yii::t('comp_safety', 'safety_type'); ?>//</td><td><?php //echo Yii::t('comp_statistics', 'role'); ?>//</td> <td><?php //echo Yii::t('comp_statistics', 'cnt'); ?>//</td></tr>";
                var inspection_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;INSPECTION</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('comp_safety', 'safety_type'); ?></td><td><?php echo Yii::t('comp_statistics', 'role'); ?></td> <td><?php echo Yii::t('comp_statistics', 'cnt'); ?></td></tr>";
                $('#pro_info').append(inspection_title);
                $.each(data, function (name, value) {
                    if(value.deal_type == 'Apply User'){
//                        var inspection_content = "<tr><td>"+value.safety_level+"</td><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=wsh/wshinspection/list&user_id=<?php //echo $user_id; ?>//&program_id=<?php //echo $program_id; ?>//&deal_type='1'&safety_level="+value.safety_level+"\" target=\"view_window\">[<?php //echo Yii::t('common','check'); ?>//]</a></td><tr>";
                        var inspection_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=wsh/wshinspection/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type=1&safety_level="+value.safety_level+"\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }else if (value.deal_type == 'Person In Charge'){
                        var inspection_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=wsh/wshinspection/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type=2&safety_level="+value.safety_level+"\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }else{
                        var inspection_content = "<tr><td>"+value.type_name+"</td><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=wsh/wshinspection/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type=1&safety_level="+value.safety_level+"\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><tr>";
                    }
                    $('#pro_info').append(inspection_content);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //INSPECTION总次数
    var inspection_totalcnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/inspectioncnt",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                $.each(data, function (name, value) {
                    var inspection_total = "<tr><td colspan='2'><?php echo Yii::t('comp_statistics', 'total'); ?></td><td>"+value.cnt+"</td><tr>";
                    $('#pro_info').append(inspection_total);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //ACCIDENT权限
    var accident_cnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/accidentrole",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                var accident_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp; Incident</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('comp_statistics', 'role'); ?></td> <td><?php echo Yii::t('comp_statistics', 'cnt'); ?></td> <td></td></tr>";
                $('#pro_info').append(accident_title);
                $.each(data, function (name, value) {
                    if(value.deal_type == 'MEMBER'){
                        var accident_content = "<tr><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=accidents/accident/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type="+value.deal_type+"\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><td></td><tr>";
                    }else{
                        var accident_content = "<tr><td>"+value.deal_type+"</td><td>"+value.cnt+"<a href=\"index.php?r=accidents/accident/list&user_id=<?php echo $user_id; ?>&program_id="+id+"&deal_type='1'\" target=\"view_window\">[<?php echo Yii::t('common','check'); ?>]</a></td><td></td><tr>";
                    }
                    $('#pro_info').append(accident_content);
                })

            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //Accident总次数
    var accident_totalcnt = function (id) {
        var user_id = $("#user_id").val();
        $.ajax({
            data:{user_id:user_id,program_id:id},
            url: "index.php?r=comp/staff/accidentcnt",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                $.each(data, function (name, value) {
                    var tbm_total = "<tr><td colspan='2'><?php echo Yii::t('comp_statistics', 'total'); ?></td><td>"+value.cnt+"</td><tr>";
                    $('#pro_info').append(tbm_total);
                })
            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>

