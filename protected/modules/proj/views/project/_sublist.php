<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }

    $status_list = Program::statusText(); //状态text
    $status_css = Program::statusCss(); //状态css
    $compList = Contractor::compAllList(); //所有承包商
    $operator_id = Yii::app()->user->id;
    $authority_list = OperatorProject::authorityList($operator_id);

    foreach ($rows as $i => $row) {
        //判断项目权限
        $value = $authority_list[$row['root_proid']];

        $con_model = Contractor::model()->findByPk($row['contractor_id']);
        $contractor_name = $con_model->contractor_name;
        $pro_model = Program::model()->findByPk($row['root_proid']);
        $pro_name = $pro_model->program_name;
        $staff_cnt = Staff::model()->count('contractor_id=:contractor_id and status = 0', array('contractor_id' => $row['contractor_id']));
        $device_cnt = Device::model()->count('contractor_id=:contractor_id and status = 00', array('contractor_id' => $row['contractor_id']));
        $in_staff_cnt = ProgramUser::model()->count('root_proid=:root_proid and contractor_id=:contractor_id and check_status in(11,20)', array('root_proid' => $row['root_proid'],'contractor_id' =>$row['contractor_id'] ));
        $out_staff_cnt = ProgramUser::model()->count('root_proid=:root_proid and contractor_id=:contractor_id and check_status = 10', array('root_proid' => $row['root_proid'],'contractor_id' =>$row['contractor_id'] ));
        $in_device_cnt = ProgramDevice::model()->count('root_proid=:root_proid and contractor_id=:contractor_id and check_status in(11,20)', array('root_proid' => $row['root_proid'],'contractor_id' =>$row['contractor_id'] ));
        $out_device_cnt = ProgramDevice::model()->count('root_proid=:root_proid and contractor_id=:contractor_id and check_status = 10', array('root_proid' => $row['root_proid'],'contractor_id' =>$row['contractor_id'] ));
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $team_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemTeam(\"{$row['program_id']}\")'  title=\" ".Yii::t('proj_project_user', 'project_team')."\"><i class=\"fa fa-fw fa-user\"></i></a>&nbsp;";
        $device_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDevice(\"{$row['program_id']}\",\"{$row['program_name']}\")' title=\" ".Yii::t('proj_project_user', 'device')."\"><i class=\"fa fa-fw fa-cog\"></i></a>&nbsp;";
        $edit_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemEdit(\"{$row['program_id']}\")' title=\" ".Yii::t('common', 'edit')."\"><i class=\"fa fa-fw fa-edit\"></i></a>";
        //$start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['program_id']}\",\"{$row['program_name']}\")'><i class=\"fa fa-fw fa-check\"></i>" . Yii::t('common', 'start') . "</a>&nbsp;";
        $del_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDel(\"{$row['program_id']}\",\"{$row['program_name']}\")' title=\" ".Yii::t('common', 'delete1')."\"><i class=\"fa fa-fw fa-times\"></i></a>&nbsp;";
        $stop_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemStop(\"{$row['program_id']}\",\"$pro_name\",\"$contractor_name\")' title=\" ".Yii::t('proj_project', 'STATUS_STOP')."\"><i class=\"fa fa-fw fa-check\"></i></a>&nbsp;";
        $workforce_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemWorkforce(\"{$row['program_id']}\")' title='User Sync'><i class=\"fa fa-fw fa-users\"></i></a>&nbsp;";

        $link = '';
        if ($row['status'] == Program::STATUS_NORMAL) {
            if($row['add_conid'] == Yii::app()->user->contractor_id){
                if($value == '0'){
                    $link = "<table><tr><td style='white-space: nowrap' align='left'>$team_link</td><td style='white-space: nowrap' align='left'>$device_link</td></tr></table>";
                }else if($value == '1'){
                    $link = "<table><tr><td style='white-space: nowrap' align='left'>$team_link</td><td style='white-space: nowrap' align='left'>$edit_link</td></tr><tr><td style='white-space: nowrap' align='left'>$del_link</td><td style='white-space: nowrap' align='left'>$stop_link</td></tr><tr><td style='white-space: nowrap' align='left'>$device_link</td></tr></table>";
                }
            }
        }
        
        $t->begin_row("onclick", "getDetail(this,'{$row['program_id']}');");
        //$t->echo_td($num); //序号
        $t->echo_td($row['program_id'],'center'); //项目编号
//        $t->echo_td($row['program_name'],'center'); //Program Name
//        $t->echo_td("<a class='omit'  href='javascript:void(0)' onclick='itemSubStaff(\"{$row['contractor_id']}\")'>".$compList[$row['contractor_id']]."</a>",'center'); //Contractor
        $t->echo_td($compList[$row['contractor_id']],'center'); //Contractor

        if($in_staff_cnt > 0){
            $t->echo_td("<a class='omit'  href='javascript:void(0)' onclick='itemSubStaff(\"{$row['contractor_id']}\",\"{$row['root_proid']}\",\"in\")'>".$in_staff_cnt."</a>",'center'); //Contractor
        }else{
            $t->echo_td($in_staff_cnt,'center');
        }
        if($out_staff_cnt > 0){
            $t->echo_td("<a class='omit'  href='javascript:void(0)' onclick='itemSubStaff(\"{$row['contractor_id']}\",\"{$row['root_proid']}\",\"out\")'>".$out_staff_cnt."</a>",'center'); //Contractor
        }else{
            $t->echo_td($out_staff_cnt,'center');
        }
//        if($in_device_cnt > 0){
//            $t->echo_td("<a class='omit'  href='javascript:void(0)' onclick='itemSubDevice(\"{$row['contractor_id']}\",\"{$row['program_id']}\",\"in\")'>".$in_device_cnt."</a>",'center'); //Contractor
//        }else{
//            $t->echo_td($in_device_cnt,'center');
//        }
//        if($out_device_cnt > 0){
//            $t->echo_td("<a class='omit'  href='javascript:void(0)' onclick='itemSubDevice(\"{$row['contractor_id']}\",\"{$row['program_id']}\",\"out\")'>".$out_device_cnt."</a>",'center'); //Contractor
//        }else{
//            $t->echo_td($out_device_cnt,'center');
//        }

//        $t->echo_td($staff_cnt);
//        $t->echo_td($device_cnt);
        $t->echo_td(substr(Utils::DateToEn($row['record_time']),0,11),'center'); //Record Time
        //$t->echo_td(Utils::DateToEn($row['record_time']));
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status,'center'); //状态
        $link = $team_link.$edit_link.$del_link.$stop_link.$workforce_link;
        $t->echo_td($link,'center'); //操作
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-9">
        <div class="dataTables_paginate paging_simple_numbers">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

