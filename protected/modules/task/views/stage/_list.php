<?php
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }

    $model = PtwType::model()->findByPk($id);
    $contractor_id = $model->contractor_id;
    foreach ($rows as $i => $row) {

        //$t->begin_row("onclick", "getDetail(this,'{$row['id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        if ($tool) {
            $link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['condition_id']}\",\"{$id}\")'><i class=\"fa fa-fw fa-edit\"></i>".Yii::t('common','edit')."</a>";
            //if (Yii::app()->user->id == $row['operator'])
                $link .= "&nbsp;&nbsp;<a href='javascript:void(0)' onclick='itemDelete(\"{$row['condition_id']}\",\"{$id}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('common','delete')."</a>";
        } else {
            $link = '无操作';
        }
        $t->echo_td($num); //Condition
        $t->echo_td($row['condition_name']); //Condition Name
        $t->echo_td($row['condition_name_en']); //Condition Name En
        $t->echo_td($row['status']); //Status
        $t->echo_td(Utils::DateToEn($row['record_time'])); //Record Time
        if($contractor_id != 0){
            $t->echo_td($link); //操作
        }
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

