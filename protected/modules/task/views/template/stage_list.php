<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;


    $status_list = TaskTemplate::statusText(); //状态
    $status_css = TaskTemplate::statusCss();
    $template_list = TaskTemplate::templateByProgram($project_id);
    $stage_list = TaskStage::queryStage($template_id);
    $type_list = TaskTemplate::typeList();

    foreach ($rows as $i => $row) {
        $t->echo_td($j,'center');
        $t->begin_row("onclick", "getDetail(this,'{$row['stage_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $edit_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemEdit(\"{$row['stage_id']}\")' title='Edit'><i class=\"fa fa-fw fa-edit\"></i></a>&nbsp;";
        $stop_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemStop(\"{$row['stage_id']}\",\"{$row['stage_name']}\")' title='". Yii::t('common', 'stop') ."'><i class=\"fa fa-fw fa-times\"></i></a>&nbsp;";
        $detail_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDetail(\"{$template_id}\",\"{$row['stage_id']}\",\"{$project_id}\")' title='Detail'><i class=\"fa fa-fw fa-list-alt\"></i></a>&nbsp;";
        $dashboard_hide_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemHideDashboard(\"{$row['stage_id']}\",\"{$row['stage_name']}\")' title='Dashboard'><i class=\"fa fa-fw fa-tachometer-alt\"></i></a>&nbsp;";
        $dashboard_show_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemShowDashboard(\"{$row['stage_id']}\",\"{$row['stage_name']}\")' title='Dashboard'><i class=\"fa fa-fw fa-tachometer-alt\"></i></a>&nbsp;";


        if ($row['status'] == TaskTemplate::STATUS_NORMAL) {
            $link = $edit_link.$detail_link.$stop_link;
            if($row['dashboard_flag'] == '0'){
                $dashboard_tag = 'Show';
                $link.=$dashboard_hide_link;
            }
            if($row['dashboard_flag'] == '1'){
                $dashboard_tag = 'Hide';
                $link.=$dashboard_show_link;
            }
        }

//        $t->echo_td($row['stage_id'],'center'); //Id
        $t->echo_td($template_list[$row['template_id']],'center'); //Template Name
        $t->echo_td($stage_list[$row['stage_id']]); //Stage Name
        $rs['bgcolor'] = $row['stage_color'];
        $t->echo_td('','',$rs); //Stage Color
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($type_list[$row['clt_type']],'center');
//        $t->echo_td($row['order_id']);
        $t->echo_td($status,'center'); //状态
        $t->echo_td($dashboard_tag,'center');
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
<script type="text/javascript">

</script>

