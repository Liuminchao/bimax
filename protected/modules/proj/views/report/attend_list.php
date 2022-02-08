
<?php
$t->echo_grid_header();
//var_dump($rows);
if (is_array($rows)) {
    $j = 0;
    $sum =0;
    
    
    foreach ($rows as $i => $row) {
        if($row['hours'] != 0){
            $t->echo_td(++$j); //序号
            $t->echo_td($program_list[$row['node_id']]); //Program Name
            $t->echo_td($role_list[$row['node_name']]); //Program Name
            $t->echo_td($row['hours']); //Contractor
            $t->end_row();
        }
        
            $sum += $row['hours'];
            
    }    
         
         if($sum!=0){
             
             $t->echo_td(Yii::t('proj_report', 'attend_hour_total'),'right',array('colspan'=>'3'));
             $t->echo_td($sum);
         }
}

$t->echo_grid_floor();
/*
$pager = new CPagination(count($rows));
$pager->pageSize = $this->pageSize;
$pager->itemCount = count($rows);*/
?>

<div class="row">
    <div class="col-xs-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $j; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
                   
        </div>
    </div>
    <!--<div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>-->
</div>

