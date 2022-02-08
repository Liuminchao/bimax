<?php
/**
 * Created by PhpStorm.
 * User: minchao
 * Date: 2017-05-17
 * Time: 15:25
 */
$device_list = Device::model()->findByPk($primary_id);//设备信息
//    $deviceinfo_list = DeviceInfo::model()->findByPk($primary_id);//设备详情信息
$aptitude_list =DeviceInfo::queryAll($device_id);//设备证书
$device_type = DeviceType::deviceList();//设备类型
$contractor_id = Yii::app()->user->getState('contractor_id');
$self_info = ProgramDevice::SelfInfo($contractor_id,$primary_id);//设备所在项目的信息
$ptw_type = ApplyBasic::typelanguageList();//许可证类型表(双语)
$inspection_type = SafetyLevel::levelText();//安全检查安全等级
//    var_dump($ptw_set_cnt);
//    exit;
?>
<!--<img alt=""  src="--><?php //echo $device_list['device_img']; ?><!--" width="130"  height="180"/>-->
<div class="row" ></div>
<br><br>

<div>
    <input type="hidden" id="device_id" value="<?php echo $device_id; ?>">
    <input type="hidden" id="primary_id" value="<?php echo $primary_id; ?>">
</div>

<table border="1" width="100%">
    <tr>
        <td colspan="4">
            <h3 class="form-section">&nbsp;
                <?php echo Yii::t('comp_statistics', 'device_information'); ?></h3>
        </td>
    </tr>
    <tr style="background-color: rgb(243, 244, 245);">
        <td><?php echo Yii::t('comp_statistics', 'name'); ?></td>
        <td><?php echo Yii::t('comp_statistics', 'type'); ?></td>
        <td><?php echo Yii::t('comp_statistics', 'no'); ?></td>
        <td><?php echo Yii::t('comp_staff', 'Face_img'); ?></td>
    </tr>
    <tr>
        <td><?php echo $device_list['device_name'] ?></td>
        <td><?php echo $device_type[$device_list['type_no']] ?></td>
        <td><?php echo $device_list['device_id'] ?></td>
        <td rowspan="<?php $count = count($aptitude_list);  $count=2*$count+1; echo $count; ?>"><img alt=""  src="<?php echo $device_list['device_img']; ?>" width="130"  height="90"/></td>
    </tr>
    <?php if($aptitude_list){  ?>
        <?php  foreach($aptitude_list as $cnt => $list){  ?>
            <tr>
                <td><?php echo Yii::t('comp_statistics', 'certificate_content'); ?></td>
                <td><?php echo Yii::t('comp_statistics', 'permit_start_date'); ?></td>
                <td><?php echo Yii::t('comp_statistics', 'permit_end_date'); ?></td>
            </tr>
            <tr>
                <td><?php echo $list['certificate_content'] ?></td>
                <td><?php echo $list['permit_startdate'] ?></td>
                <td><?php echo $list['permit_enddate'] ?></td>
            </tr>
        <?php } ?>
    <?php } ?>
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
    <tr>
        <td colspan="3">
            <h3 class="form-section">&nbsp;
                <?php echo Yii::t('comp_statistics', 'program_information'); ?></h3>
        </td>
    </tr>
</table>
<!--    --><?php //if($self_info){  ?>
<!--        --><?php // foreach($self_info as $num => $info){
//            $ptw_member_cnt = ApplyBasic::deviceByType($device_id,$info['program_id']);//按PTW类别统计次数
//            $ptw_all_cnt = ApplyBasic::deviceByAll($device_id,$info['program_id']);//PTW统计总次数
//            //        $inspection_apply_cnt = SafetyCheck::findByApply($user_id,$info['program_id']);//Inspection按申请权限统计次数
//            //        $inspection_charge_cnt = SafetyCheck::findByCharge($user_id,$info['program_id']);//Inspection按负责人权限统计次数
//            $inspection_member_cnt = SafetyCheck::deviceByMember($user_id,$info['program_id']);//Inspection按设备统计次数
//            $inspection_all_cnt = SafetyCheck::deviceByAll($user_id,$info['program_id']);//Inspection统计总次数
//            $entrance_leave = ProgramDevice::SelfByDate($device_id,$info['program_id']);//进出场记录
//            ?>
<!--            <tr>-->
<!--                <td>--><?php //echo Yii::t('comp_statistics', 'Project');?><!--</td>-->
<!--                <td>--><?php //echo $info['program_name'] ?><!--</td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td colspan="3"><h4 class="form-section">&nbsp;-->
<!--                        --><?php //echo Yii::t('comp_statistics', 'out_history'); ?><!--</h4></td>-->
<!--            </tr>-->
<!--            --><?php //if($entrance_leave){  ?>
<!--                --><?php // foreach($entrance_leave as $i => $j){  ?>
<!--                    <tr>-->
<!--                        <td>--><?php //echo Yii::t('comp_statistics', 'entrance'); ?><!--</td>-->
<!--                        <td>--><?php //echo Yii::t('comp_statistics', 'out'); ?><!--</td>-->
<!--                        <td></td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>--><?php //echo $j['entrance_time'] ?><!--</td>-->
<!--                        <td>--><?php //echo $j['leave_time'] ?><!--</td>-->
<!--                        <td></td>-->
<!--                    </tr>-->
<!--                --><?php //} ?>
<!--            --><?php //} ?>
<!--            <tr>-->
<!--                <td><h4 class="form-section">&nbsp;-->
<!--                        PTW</h4></td>-->
<!--            </tr>-->
<!--            --><?php //if($ptw_member_cnt){  ?>
<!--                --><?php // foreach($ptw_member_cnt as $i => $j){  ?>
<!--                    <tr>-->
<!--                        <td>--><?php //echo Yii::t('comp_statistics', 'type'); ?><!--</td>-->
<!--                        <td>--><?php //echo Yii::t('comp_statistics', 'cnt'); ?><!--</td>-->
<!--                        <td></td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>--><?php //echo $ptw_type[$j['type_id']]['type_name_en'] ?><!--</td>-->
<!--                        <td>--><?php //echo $j['cnt'] ?><!--</td>-->
<!--                        <td></td>-->
<!--                    </tr>-->
<!--                --><?php //} ?>
<!--            --><?php //} ?>
<!--            <tr>-->
<!--                <td>--><?php //echo Yii::t('comp_statistics', 'total'); ?><!--</td>-->
<!--                <td>--><?php //echo $ptw_all_cnt[0]['cnt'] ?><!--</td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td colspan="3"><h4 class="form-section">&nbsp;-->
<!--                        INSPECTION</h4></td>-->
<!--            </tr>-->
<!--            --><?php //if($inspection_member_cnt){  ?>
<!--                --><?php // foreach($inspection_member_cnt as $i => $j){  ?>
<!--                    <tr>-->
<!--                        <td>--><?php //echo Yii::t('comp_statistics', 'type'); ?><!--</td>-->
<!--                        <td>--><?php //echo Yii::t('comp_statistics', 'cnt'); ?><!--</td>-->
<!--                        <td></td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td>--><?php //echo $inspection_type[$j['safety_level']] ?><!--</td>-->
<!--                        <td>--><?php //echo $j['cnt'] ?><!--</td>-->
<!--                        <td></td>-->
<!--                    </tr>-->
<!--                --><?php //} ?>
<!--            --><?php //} ?>
<!--            <tr>-->
<!--                <td>--><?php //echo Yii::t('comp_statistics', 'total'); ?><!--</td>-->
<!--                <td>--><?php //echo $inspection_all_cnt[0]['cnt'] ?><!--</td>-->
<!--                <td></td>-->
<!--            </tr>-->
<!--        --><?php //} ?>
<!--    --><?php //} ?>


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
        window.location = "./?<?php echo Yii::app()->session['list_url']['device/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
//    //查询
//    var itemQuery = function (id) {
//        var device_id = $("#device_id").val();
//        window.location = "index.php?r=device/equipment/selfgrid&device_id="+device_id+"&program_id="+id;
//    }
    //查询
    var itemQuery = function (id) {
        var device_id = $("#primary_id").val();
        $.ajax({
            data:{device_id:device_id,program_id:id},
            url: "index.php?r=device/equipment/selfbydate",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#pro_info').empty();
                var title = "<tr ><td colspan='3'> <h3 class='form-section'>&nbsp;<?php echo Yii::t('comp_statistics', 'employment_information'); ?></h3></td></tr>";
                var title_1 = "<tr ><td colspan='3'><h4 class='form-section'>&nbsp;<?php echo Yii::t('comp_statistics', 'out_history'); ?></h4></td></tr>";
                var title_2 = "<tr style='background-color: rgb(243, 244, 245);'><td><?php echo Yii::t('comp_statistics', 'entrance'); ?></td><td><?php echo Yii::t('comp_statistics', 'out'); ?></td><td></td></tr>"
                $('#pro_info').append(title);
                $('#pro_info').append(title_1);
                $('#pro_info').append(title_2);
                $.each(data, function (name, value) {
                    var program_content = "<tr><td>"+value.entrance_time+"</td><td>"+value.leave_time+"</td><td></td><tr>";
                    $('#pro_info').append(program_content);
                })
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
        ptw_cnt(id);
        ptw_totalcnt(id);
        inspection_cnt(id);
        inspection_totalcnt(id);
    }
    //PTW权限
    var ptw_cnt = function (id) {
        var device_id = $("#device_id").val();
        $.ajax({
            data:{device_id:device_id,program_id:id},
            url: "index.php?r=device/equipment/ptwrole",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                var ptw_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;PTW</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('comp_statistics', 'type'); ?></td> <td><?php echo Yii::t('comp_statistics', 'cnt'); ?></td><td></td></tr>";
                $('#pro_info').append(ptw_title);
                $.each(data, function (name, value) {
                    var ptw_content = "<tr><td>"+value.type_name+"</td><td>"+value.cnt+"</td><td></td><tr>";
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
        var device_id = $("#device_id").val();
        $.ajax({
            data:{device_id:device_id,program_id:id},
            url: "index.php?r=device/equipment/ptwcnt",
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
    //INSPECTION权限
    var inspection_cnt = function (id) {
        var device_id = $("#device_id").val();
        $.ajax({
            data:{device_id:device_id,program_id:id},
            url: "index.php?r=device/equipment/inspectionrole",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
//                var inspection_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;INSPECTION</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php //echo Yii::t('comp_safety', 'safety_level'); ?>//</td><td><?php //echo Yii::t('comp_safety', 'safety_type'); ?>//</td><td><?php //echo Yii::t('comp_statistics', 'role'); ?>//</td> <td><?php //echo Yii::t('comp_statistics', 'cnt'); ?>//</td></tr>";
                var inspection_title = "<tr><td colspan='3'><h4 class='form-section'>&nbsp;INSPECTION</h4></td></tr><tr style='background-color: rgb(243, 244, 245);'> <td><?php echo Yii::t('comp_safety', 'safety_type'); ?></td><td><?php echo Yii::t('comp_statistics', 'cnt'); ?></td><td></td></tr>";
                $('#pro_info').append(inspection_title);
                $.each(data, function (name, value) {
                    var inspection_content = "<tr><td>"+value.type_name+"</td><td>"+value.cnt+"</td><td></td><tr>";
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
        var device_id = $("#device_id").val();
        $.ajax({
            data:{device_id:device_id,program_id:id},
            url: "index.php?r=device/equipment/inspectioncnt",
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
</script>