<?php
$t->echo_grid_header();

if (count($rows)>0) {
    $j = 1;
    $header_list =StatisticChecklist::headerDate($args);

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$i}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $t->echo_td($row['stage_name']);
        foreach($header_list as $m =>$n){
            $t->echo_td($row[$n]);
        }
        $j = 0;

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
            <?php if($rows){ ?>
                <a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExport_1('<?php echo $args['program_id']; ?>','<?php echo $args['template_id']; ?>');">Export Report</strong></a>
            <?php } ?>
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

