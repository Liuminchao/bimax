<?php
$t->echo_compound_header();

if (count($rows)>0) {
    $j = 1;

    $total_sum = 0;
    $header_list = StatisticChecklist::headerCheckList($args);
    $u = 0;
    foreach ($header_list as $index => $value){
        $group[$u]['total'] = 0;
        $u++;
    }

    $cnt_a = 0;
    $cnt_b = 0;
    $cnt_c = 0;
    foreach ($rows as $i => $row) {
//        if(stristr("Hello world!","Blk");){
//        if(strpos($row['block'],'Blk')!==false){
            $t->begin_row("onclick", "getDetail(this,'{$i}');");
            $num = ($curpage - 1) * $this->pageSize + $j++;

            $t->echo_td($row['block']);
            $t->echo_td($row['total']);
            $total_sum+=$row['total'];
            $count = count($row['stage']);
            $j = 0;
            $u = 0;
            foreach ($row['stage'] as $stage_id => $stage_cnt){
                $model = TaskStage::model()->findByPk($stage_id);
                $clt_type = $model->clt_type;
                $t->echo_td($stage_cnt,'center');
                $group[$u]['total'] += (int)$stage_cnt;
                if($clt_type == 'A'){
                    $cnt_a = (int)$stage_cnt;
                }
                if($clt_type == 'B'){
                    $cnt_b = (int)$stage_cnt;
                }
                if($clt_type == 'C'){
                    $j++;
                    if($j == 1){
                        $cnt_c = (int)$stage_cnt;
                    }
                }
                $u++;
            }
            if($cnt_a == 0 || $row['total'] == 0){
                $percent_a = 0;
            }else{
                $percent_a = $cnt_a/$row['total']*100;
                $percent_a = round($percent_a,2);
            }
            if($cnt_b == 0 || $row['total'] == 0){
                $percent_b = 0;
            }else{
                $percent_b = $cnt_b/$row['total']*100;
                $percent_b = round($percent_b,2);
            }
            if($cnt_c == 0 || $row['total'] == 0){
                $percent_c = 0;
            }else{
                $percent_c = $cnt_c/$row['total']*100;
                $percent_c = round($percent_c,2);
            }
            $t->echo_td($percent_c.'%');
            $t->echo_td($percent_b.'%');
            $t->echo_td($percent_a.'%');
            $t->end_row();
//        }
    }
    $t->echo_td('Total No.');
    $t->echo_td($total_sum);
    foreach ($group as $x => $y){
        if($y['total'] == 0 || $total_sum == 0){
            $percent_total = 0;
        }else{
            $percent_total = $y['total']/$total_sum*100;
            $percent_total = $percent_total.'%';
        }
        $t->echo_td($y['total']);
    }
    $t->echo_td('');
    $t->echo_td('');
    $t->echo_td('');
    $t->end_row();
    $t->echo_td('Percentage(%)');
    $t->echo_td('');
    foreach ($group as $x => $y){
        if($y['total'] == 0 || $total_sum == 0){
            $percent_total = 0;
        }else{
            $percent_total = $y['total']/$total_sum*100;
            $percent_total = round($percent_total,2);
        }
        $t->echo_td($percent_total.'%');
    }
    $t->echo_td('');
    $t->echo_td('');
    $t->echo_td('');
    $t->end_row();
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
                <a class="right" style="cursor: pointer;"  id="export"><strong onclick="itemExport('<?php echo $args['program_id']; ?>','<?php echo $args['template_id']; ?>');">Export Report</strong></a>
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

