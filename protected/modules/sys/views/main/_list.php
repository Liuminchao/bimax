<?php
$t->echo_grid_header();



if (is_array($rows)) {

    $status_list = Certexpiry::statusText();
    $status_css = Certexpiry::statusCss();

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }

    foreach ($rows as $i => $row) {
        $t->echo_td($row['pro_name']);
        $t->echo_td($row['con_name']);
        $pro_model =Program::model()->findByPk($row['root_proid']);
        $start_date = '';
        $end_date ='';
        $params = $pro_model->params;
        if($params != '0'){
            $params = json_decode($params,true);
            if(array_key_exists('start_date',$params)){
                $start_date = Utils::DateToEn($params['start_date']);
            }
            if(array_key_exists('end_date',$params)){
                $end_date = Utils::DateToEn($params['end_date']);
            }
        }

        $t->echo_td($start_date);
        $t->echo_td($end_date);
        $params_link = "<a href='javascript:void(0)' onclick='itemParams(\"{$row['root_proid']}\")'><i class=\"fa fa-fw fa-edit\"></i>" . Yii::t('proj_project', 'params') . "</a>";
        $link = "<table><tr><td style='white-space: nowrap' align='left'>$params_link</td></tr></table>";
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
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
        </div>
    </div>
    <div class="col-xs-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

