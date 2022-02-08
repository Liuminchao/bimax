<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $status_list = QaCheck::statusText(); //状态text
    $status_css = QaCheck::statusCss(); //状态css
    $deal_list = QaCheckDetail::dealList(); //状态text
    $deal_css = QaCheckDetail::dealCss(); //状态css
    $check_type = RoutineCheckType::checkType();//检查类型列表
    $check_kind = RoutineCheckType::checkKind();//检查种类列表
    $company_list = Contractor::compAllList();//承包商公司列表
    $staff_list = Staff::userAllList();//所有人员列表
    $form_list = QaChecklist::formList();//form集合
    $type_list = QaCheckType::checkType();//type集合
    $form_type = QaChecklist::formType();
    $args = array();
    $program_list = Program::programList($args);
    foreach ($rows as $i => $row) {
        $t->echo_td($j); //检查单编号
        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $current_step = $row['current_step'];
        $detail_list = TaskRecord::QadetailList($row['check_id']);
        $last_list = QaCheckDetail::stepRecord($row['check_id'],$current_step);
        $deal_type = $last_list[0]['deal_type'];
        if($detail_list){
            $stage_model = TaskStage::model()->findByPk($detail_list[0]['stage_id']);
            $task_model = TaskList::model()->findByPk($detail_list[0]['task_id']);
            $stage_name = $stage_model->stage_name;
            $task_name = $task_model->task_name;
            $element_name = TaskRecordModel::QaByModel($row['check_id']);
        }else{
            $stage_name = 'NA';
            $task_name = $row['title'];
            $element_name = 'NA';
        }
//        $t->echo_td($form_type[$row['clt_type']]); //trade
//        $t->echo_td($stage_name); //stage name
//        $t->echo_td($element_name); //Element Name
//        $form_model = QaFormDataReal::model()->findByPk($row['form_data_id']);
//        $form_id = $form_model->form_id;
//        $type_id = $form_model->type_id;

//        $t->echo_td($type_list[$type_id]); //检查类型
//        $t->echo_td($form_list[$form_id]); //表单类型

        $apply_user =  Staff::model()->findAllByPk($row['apply_user_id']);//申请人
        if($row['insp_no']){
            $insp_no = $row['insp_no'];
        }else{
            $insp_no = 'NA';
        }
        $pro_model = Program::model()->findByPk($row['project_id']);
        $root_proid = $pro_model->root_proid;
        if($root_proid == '2419'){
            $t->echo_td($insp_no);
        }
        $t->echo_td($task_name);
        $t->echo_td($apply_user[0]['user_name']);//发起人姓名
        $contractor =  Contractor::model()->findByPk($row['contractor_id']);
        $contractor_name = $contractor->contractor_name;
        $t->echo_td($contractor_name);
//        $t->echo_td(Utils::DateToEn($row['apply_date']));//申请时间
        $t->echo_td(Utils::DateToEn($row['apply_time']),'center'); //规定时间
//        $t->echo_td(Utils::DateToEn($row['apply_time']));
//        if($row['status'] != '0'){
//            $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
//        }
//        if($row['status'] == '0' || $row['status'] == '9'){
            $status = '<span class="badge ' . $deal_css[$deal_type] . '">' . $deal_list[$deal_type] . '</span>';
//        }
        $t->echo_td($status,'center'); //状态

        $workflow_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemWorkflow(\"{$row['check_id']}\")' title='Workflow'><i class=\"fa fa-fw fa-sort-amount-down\"></i></a>&nbsp;";
        $download_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownload(\"{$row['check_id']}\")' title=\" ".Yii::t('license_licensepdf', 'download')."\"><i class=\"fa fa-fw fa-download\"></i></a>";
        $attachment_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownloadAttachment(\"{$row['check_id']}\")' title=\" ".Yii::t('comp_qa', 'attachment')."\"><i class=\"fa fa-fw fa-paperclip\"></i></a>";
        $issues_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemIssues(\"{$row['check_id']}\")' title='Issues'><i class=\"fa fa-fw fa-lightbulb\"></i></a>";
        //        $detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-file-text-o\"></i>" . Yii::t('sys_workflow', 'detail') . "</a>";
//        $staff_link ="<a href='javascript:void(0)' onclick='itemStaff(\"{$row['apply_id']}\",\"{$app_id}\")'><i class=\"fa fa-fw fa-users\"></i>" . Yii::t('sys_workflow', 'construction personnel') . "</a>";
        $link = "";
        $issues_cnt = QaDefect::cntBySource($row['check_id']);
        if($issues_cnt >0){
            $link .= $download_link.'&nbsp;'.$attachment_link.'&nbsp;'.$workflow_link.'&nbsp'.$issues_link;
        }else{
            $link .= $download_link.'&nbsp;'.$attachment_link.'&nbsp;'.$workflow_link;
        }


        $t->echo_td($link,'center'); //操作
        $t->end_row();
    }

}

$t->echo_grid_floor();
$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="row">
    <div class="col-5">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-7">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

