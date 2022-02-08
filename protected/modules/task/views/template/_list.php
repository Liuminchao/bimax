<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;


    $status_list = TaskTemplate::statusText(); //状态
    $status_css = TaskTemplate::statusCss();
    $type_list = TaskTemplate::typeList();

    foreach ($rows as $i => $row) {
        $t->echo_td($j,'center'); //Id
        $t->begin_row("onclick", "getDetail(this,'{$row['template_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        
        $copy_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemCopy(\"{$row['template_id']}\")' title=\" " . Yii::t('common', 'copy') . "\"><i class=\"fa fa-fw fa-edit\"></i></a>&nbsp;";
        
        $start_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemStart(\"{$row['template_id']}\",\"{$row['template_name']}\")' title=\" " . Yii::t('common', 'start') . "\"><i class=\"fa fa-fw fa-check\"></i></a>&nbsp;";
        
        $stop_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemStop(\"{$row['template_id']}\",\"{$row['template_name']}\")' title=\" " . Yii::t('common', 'delete') . "\"><i class=\"fa fa-fw fa-times\"></i></a>&nbsp;";
        
        $detail_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDetail(\"{$row['template_id']}\",\"{$project_id}\")' title=\" " . Yii::t('sys_workflow', 'detail') . "\"><i class=\"fa fa-fw fa-list-alt\"></i></a>&nbsp;";

        if ($row['status'] == TaskTemplate::STATUS_NORMAL) {
            $link =  $detail_link.$stop_link;
        } else if ($row['status'] == TaskTemplate::STATUS_STOP) {
            $link = $start_link;
        }

//        $t->echo_td($row['template_id'],'center'); //Id
//        $t->echo_td($type_list[$row['clt_type']]);
        $t->echo_td($row['template_name'],'center'); //Template Name
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status); //状态
        $t->echo_td(Utils::DateToEn($row['record_time']),'center'); //Record Time
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
    var itemDetail = function (id,project_id) {
        window.location = "index.php?r=task/template/stagelist&id=" + id+"&project_id="+project_id;
    }
</script>

