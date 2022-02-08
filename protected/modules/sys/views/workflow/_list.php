<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $status_list = Workflow::statusText(); //状态text
    $status_css = Workflow::statusCss(); //状态css
    $useObject = Workflow::UseObjectText(); //使用对象
//    $tool = true;
//    //$tool = false;验证权限
//    if (Yii::app()->user->checkAccess('mchtm')) {
//        $tool = true;
//    }

    foreach ($rows as $i => $row) {

        $num = ($curpage - 1) * $this->pageSize + $j++;

        if (Yii::app()->user->checkAccess("sys/workflow/edit"))
            $edit_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['flow_id']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('common', 'edit') . "</a>&nbsp;";
        if (Yii::app()->user->checkAccess("sys/workflow/set"))
            $set_link = "<a href='javascript:void(0)' onclick='itemSet(\"{$row['flow_id']}\")'><i class=\"fa fa-fw fa-user\"></i>" . Yii::t('sys_workflow', 'Set Flow') . "</a>&nbsp;";
        //if (Yii::app()->user->checkAccess("sys/workflow/start"))
           // $start_link = "<a href='javascript:void(0)' onclick='itemStart(\"{$row['flow_id']}\",\"{$row['flow_name']}\")'><i class=\"fa fa-fw fa-check\"></i>" . Yii::t('common', 'start') . "</a>&nbsp;";
        //if (Yii::app()->user->checkAccess("sys/workflow/stop"))
           // $stop_link = "<a href='javascript:void(0)' onclick='itemStop(\"{$row['flow_id']}\",\"{$row['flow_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'stop') . "</a>&nbsp;";
        if (Yii::app()->user->checkAccess("sys/workflow/preview"))
            $pre_link = "<a href='javascript:void(0)' onclick='itemPreview(\"{$row['flow_id']}\",\"{$row['flow_name']}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>" . Yii::t('sys_workflow', 'Preview Flow') . "</a>&nbsp;";

//        if ($row['status'] == Workflow::STATUS_NORMAL) {
//            $link = $pre_link . $stop_link;
//        } else if ($row['status'] == Workflow::STATUS_STOP) {
//            $link = $edit_link . $set_link . $pre_link . $start_link;
//        }
        $link = $edit_link . $set_link . $pre_link;    
        $t->echo_td($num); //序号
        $t->echo_td($row['flow_name']); //Flow Name
        $t->echo_td($useObject[$row['contractor_id']]); //Contractor
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
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
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
<?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

