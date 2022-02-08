<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $status_list = TaskList::statusText(); //状态
    $status_css = TaskList::statusCss();
    $pro_model =Program::model()->findByPk($project_id);
    $root_proid = $pro_model->root_proid;
    $template_list = TaskTemplate::templateByProgram($root_proid);
    $stage_list = TaskStage::stageByProgram($root_proid);
    $task_list = TaskList::taskByProgram($root_proid);
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['template_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $detail_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDetail(\"{$row['task_id']}\")' title=\" ".Yii::t('sys_workflow', 'detail')."\"><i class=\"fa fa-fw fa-list-alt\"></i></a>&nbsp;";
        $attachment_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownloadAttachment(\"{$row['link_check_id']}\")' title=\" ".Yii::t('comp_qa', 'attachment')."\"><i class=\"fa fa-fw fa-paperclip\"></i></a>&nbsp;";
        $checklist_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemChecklist(\"{$row['link_check_id']}\")' title='Checklist'><i class=\"fa fa-fw fa-bars\"></i></a>&nbsp;";
        $workflow_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemWorkflow(\"{$row['link_check_id']}\")' title='Workflow'><i class=\"fa fa-fw fa-sort-amount-down\"></i></a>&nbsp;";
        $issues_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemIssues(\"{$row['check_id']}\")' title='Issues'><i class=\"fa fa-fw fa-lightbulb\"></i></a>";
        //        if ($row['status'] == TaskTemplate::STATUS_NORMAL) {
//            $link =  "<table><tr><td style='white-space: nowrap' align='left'>$detail_link</td><td style='white-space: nowrap' align='left'>$stop_link</td></tr></table>";
//        } else if ($row['status'] == TaskTemplate::STATUS_STOP) {
//            $link = $start_link;
//        }
        // if($row['status'] == TaskList::STATUS_COMPLETED){
        $link = '';
        $issues_cnt = QaDefect::cntBySource($row['check_id']);
        if($row['link_check_id']){
            $download_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownload(\"{$row['link_check_id']}\")' title=\" ".Yii::t('license_licensepdf', 'download'). "\"><i class=\"fa fa-fw fa-download\"></i></a>&nbsp;";
            $link=$download_link;
            $link.=$workflow_link;
            $link.=$checklist_link;
            $link.= $attachment_link;
        }
        if($issues_cnt>0){
            $link.=$issues_link;
        }
        // }else{
        //     $link = '';
        // }

//        $t->echo_td($row['check_id']); //Id
        $template_model =TaskTemplate::model()->findByPk($row['template_id']);
        $template_name = $template_model->template_name;
        $stage_model =TaskStage::model()->findByPk($row['stage_id']);
        $stage_name = $stage_model->stage_name;
        $task_model =TaskList::model()->findByPk($row['task_id']);
        $task_name = $task_model->task_name;
        $t->echo_td($template_name,'center'); //Template Name
        $t->echo_td($stage_name,'center'); //Stage Name
        $t->echo_td($task_name,'center'); //Task Name
        $t->echo_td($row['name']);
//        $t->echo_td(Utils::DateToEn($row['start_date'])); //Start Date
//        $t->echo_td(Utils::DateToEn($row['end_date'])); //End Date
        $user_model =Staff::model()->findByPk($row['user_id']);
        $user_name = $user_model->user_name;
        $t->echo_td($user_name);
//        $t->echo_td($row['remarks']); //Remarks
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status,'center'); //状态
        $t->echo_td(Utils::DateToEn($row['record_time']),'center'); //Record Time
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
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    //详情
    var itemDetail = function (id) {
        window.location = "index.php?r=task/task/method&id=" + id;
    }
    //下载附件
    var itemDownloadAttachment = function(id) {
        var modal = new TBModal();
//        modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = 'Attachment';
        modal.url = "index.php?r=task/task/downloadattachment&check_id="+id;
        modal.modal();
    }
    //下载
    var itemDownload = function(check_id) {
//        window.location = "index.php?r=qa/qainspection/qaexport&check_id="+id;
        var modal = new TBModal();
        modal.title = 'QA';
        modal.url = "index.php?r=qa/qainspection/downloadpreview&check_id="+check_id;
        modal.modal();
    }
</script>

