<?php
$operator_role = Operator::roleText();
$t->echo_grid_header();

if (is_array($rows)) {
    $j = 1;

    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['contractor_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $set_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemSet(\"{$row['operator_id']}\",\"{$row['contractor_id']}\",\"{$name}\")' title='".Yii::t('comp_staff', 'Binding')."'><i class=\"fa fa-fw fa-eye\"></i></a>&nbsp;";
        $delete_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDelete(\"{$row['operator_id']}\",\"{$row['contractor_id']}\",\"{$name}\")' title='".Yii::t('common', 'delete')."'><i class=\"fa fa-fw fa-times\"></i></a>";
        $pro_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemPro(\"{$row['operator_id']}\",\"{$row['contractor_id']}\",\"{$name}\")' title='".Yii::t('proj_project','Project Set')."'><i class=\"fa fa-fw fa-cog\"></i></a>";

        $t->echo_td($row['operator_id'],'center');
        $t->echo_td($row['name'],'center');
        $t->echo_td($operator_role[$row['operator_role']],'center');
//        $t->echo_td($row['phone']);
//        $t->echo_td($row['record_time']);
        //$t->echo_td(substr($row['record_time'],0,10));
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)),'center');
        if($row['operator_role'] == '01'){
            $link = $set_link.$pro_link.$delete_link;
        }else{
            $link = $set_link.$pro_link;
        }

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

