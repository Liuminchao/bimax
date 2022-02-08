<?php
$t->echo_grid_header();
if (is_array($rows)) {

    $status_list = RevitComponent::statusText(); //状态
    $status_css = RevitComponent::statusCss();
    foreach ($rows as $i => $row) {
        $t->begin_row("onclick", "getDetail(this,'{$row['id']}');");
        if($row['stage_id']){
            $stage_model = TaskStage::model()->findByPk($row['stage_id']);
            $name = $stage_model->stage_name;
        }
        if($row['task_id'] != 0){
            $task_model = TaskList::model()->findByPk($row['task_id']);
            $name = $task_model->task_name;
        }
        $t->echo_td($name);
        $t->echo_td($row['block']);
        $t->echo_td($row['level']);
        $t->echo_td($row['part']);
        $t->echo_td(Utils::DateToEn($row['plan_date']));
        $t->echo_td(Utils::DateToEn($row['act_date']));
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
        <div class="dataTables_info" id="projlist_info">
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
<script src="js/loading.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

    });
</script>

