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
    //$task_user_list = TaskUser::taskuserList();

    foreach ($rows as $i => $row) {

        //计算计划工作量
        $start_time = strtotime(substr($row['plan_start_time'],0,10));
        $end_time = strtotime(substr($row['plan_end_time'],0,10));
        $day = $end_time-$start_time;
        $time = time();
        $now_time = strtotime(date("y-m-d",$time));
        $now_day = $now_time-$start_time;
        if($now_time>=$start_time&&$now_time<=$end_time){
            $rate = round($now_day/$day*100)."%";
        }else if($now_time<$start_time){
            $rate = '0'."%";
        }else{
            $rate = '100'."%";
        }
        if($row['plan_start_time']=='' && $row['plan_end_time']==''){
            $rate = '0'."%";
        }
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $addsubtask_link = "<a href='javascript:void(0)' onclick='itemAddSubtask(\"{$row['program_id']}\",\"{$row['task_id']}\")'><i class=\"fa fa-fw fa-plus\"></i>" . Yii::t('task', 'add_two_task') . "</a>&nbsp;";
        $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['program_id']}\",\"{$row['task_id']}\",\"{$row['father_taskid']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp";
        $editsubtask_link = "<a href='javascript:void(0)' onclick='itemEditSubtask(\"{$row['program_id']}\",\"{$row['task_id']}\",\"{$row['father_taskid']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
        //$start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['program_id']}\",\"{$row['program_name']}\")'><i class=\"fa fa-fw fa-check\"></i>" . Yii::t('common', 'start') . "</a>&nbsp;";
        $del_link = "<a href='javascript:void(0)' onclick='itemDel(\"{$row['father_taskid']}\",\"{$row['task_id']}\",\"{$row['task_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'delete1') . "</a>&nbsp;";
        $team_link = "<a href='javascript:void(0)' onclick='itemSet(\"{$row['program_id']}\",\"{$row['task_id']}\")'><i class=\"fa fa-fw fa-user\"></i>" . Yii::t('task', 'task_user_set') . "</a>&nbsp;";
        $attach_link = "<a href='javascript:void(0)' onclick='itemAttach(\"{$row['program_id']}\",\"{$row['task_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>" . Yii::t('task', 'attach_ment') . "</a>&nbsp;";
         
        $link = '';
        $date= date('Y-m-d',time());
        $now_time = strtotime($date);
        if ($row['status'] == Task::STATUS_NORMAL) {
            if($row['father_taskid']){
                $link = $editsubtask_link.$del_link.$team_link.$attach_link;
            }else{
                $link = $edit_link.$del_link.$addsubtask_link;
            }
        }
        
        $t->begin_row("onclick", "getDetail(this,'{$row['task_id']}');");
        //$t->echo_td($num); //序号
//        if(!$row['father_taskid']){
//            $t->echo_td(substr($row['task_id'],0,-2).'-'.substr($row['task_id'],-2));//任务编号
//        }else{
//            $t->echo_td(substr($row['task_id'],0,-4).'-'.substr($row['task_id'],-4,-2).'-'.substr($row['task_id'],-2));
//        }
        //$t->echo_td($row['program_id']); //项目编号
        $t->echo_td($row['task_id']); //任务编号
        $t->echo_td($row['task_name']); //任务名称
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        $t->echo_td(substr(Utils::DateToEn($row['record_time']),0,11)); //记录日期
        //$t->echo_td($row['plan_amount']);//计划量
        $t->echo_td($row['plan_amount'].$row['amount_unit']);//计划量（单位）
        $t->echo_td(substr(Utils::DateToEn($row['plan_start_time']),0,11)); //计划开始时间
        $t->echo_td(substr(Utils::DateToEn($row['plan_end_time']),0,11));//计划结束时间
        $t->echo_td($row['plan_work_hour']);//计划工时
        $t->echo_td($rate); //计划工作量
        //$t->echo_td($task_user_list[$row['task_id']]['user_id']);//参与人员
        
        

        $t->echo_td($link); //操作
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
            <?php 
            if ($rows) {
                //var_dump($rows);
               echo '<a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExport();">导出项目进度表</strong></a>';
            }
             ?>       
        </div>
        
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

