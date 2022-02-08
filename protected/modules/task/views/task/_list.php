<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;


    $status_list = TaskList::statusText(); //状态
    $status_css = TaskList::statusCss();
    $template_list = TaskTemplate::templateByProgram($project_id);
    $stage_list = TaskStage::stageByProgram($project_id);
    $detail_link = "<a href='javascript:void(0)' onclick='itemDetail(\"{$row['template_id']}\",\"{$row['project_id']}\")'><i class=\"fa fa-fw fa-list-alt\"></i>" . Yii::t('sys_workflow', 'detail') . "</a>&nbsp;";

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['template_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $stop_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemStop(\"{$row['task_id']}\",\"{$row['task_name']}\")' title='".Yii::t('common', 'stop')."'><i class=\"fa fa-fw fa-times\"></i></a>&nbsp;";
        $edit_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemEdit(\"{$row['task_id']}\")' title='".Yii::t('sys_workflow', 'detail')."'><i class=\"fa fa-fw fa-list-alt\"></i></a>&nbsp;";


        $link =  $edit_link.$stop_link;


        $t->echo_td($row['task_id']); //Id
        $t->echo_td($row['task_name']); //Template Name
        $t->echo_td($template_list[$row['template_id']]); //Template Name
        $t->echo_td($stage_list[$row['stage_id']]); //Stage Name
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        $t->echo_td(Utils::DateToEn($row['record_time'])); //Record Time
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
<script type="text/javascript">
    //详情
    var itemDetail = function (id) {
        window.location = "index.php?r=task/task/method&id=" + id;
    }
</script>

