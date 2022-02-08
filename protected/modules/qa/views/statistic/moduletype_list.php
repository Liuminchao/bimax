<?php
$t->echo_grid_header();

if (count($rows)>0) {
    $j = 1;
    $total_sum = 0;
    $cnt_a = 0;
    $cnt_b = 0;
    foreach ($rows as $i => $row) {
//        if(stristr("Hello world!","Blk");){
//        if(strpos($row['block'],'Blk')!==false){
        $t->begin_row("onclick", "getDetail(this,'{$i}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $t->echo_td('Block '.$i);
        $t->echo_td($row['total']);
        $total_sum+=$row['total'];
        $t->echo_td($row['vertical']);
        $cnt_a+=$row['vertical'];
        $t->echo_td($row['horizontal']);
        $cnt_b+=$row['horizontal'];
        $v_per = round($row['vertical']/$row['total']*100,2)."％";
        $t->echo_td($v_per);
        $h_per = round($row['horizontal']/$row['total']*100,2)."％";
        $t->echo_td($h_per);
        $t->end_row();
    }
    $t->echo_td('Total No.');
    $t->echo_td($total_sum);
    $t->echo_td($cnt_a);
    $t->echo_td($cnt_b);
    $t->echo_td('');
    $t->echo_td('');
    $t->end_row();

    $percent_total_a = round($cnt_a/$total_sum*100,2)."％";
    $percent_total_b = round($cnt_b/$total_sum*100,2)."％";

    $t->echo_td('Percentage(%)');
    $t->echo_td('');
    $t->echo_td($percent_total_a);
    $t->echo_td($percent_total_b);
    $t->echo_td('');
    $t->echo_td('');
    $t->end_row();
}
$t->echo_grid_floor();

//$pager = new CPagination($cnt);
//$pager->pageSize = $this->pageSize;
//$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-9">
        <div class="dataTables_paginate paging_bootstrap">
<!--            --><?php //$this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    //详情
    var itemDetail = function (id) {
        window.location = "index.php?r=task/task/method&id=" + id;
    }
</script>

