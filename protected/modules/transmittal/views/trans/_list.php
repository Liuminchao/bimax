<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $status_list = TransmittalRecord::statusText(); //状态text
    $status_css = TransmittalRecord::statusCss(); //状态css
    $company_list = Contractor::compAllList();//承包商公司列表


    foreach ($rows as $i => $row) {
//        $program_model = Program::model()->findByPk($row['program_id']);
//        if($program_model->params){
//            $params = json_decode($program_model->params,true);
//        }else{
//            $params['ptw_mode'] = 'A';
//        }
        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $t->echo_td($row['project_nos'],'center');
        $num = ($curpage - 1) * $this->pageSize + $j++;

//        $t->echo_td($row['check_id'],'center');

        $t->echo_td($row['subject'],'center'); //title

//        $t->echo_td(Utils::DateToEn($row['apply_time']),'center',$attr);
        $t->echo_td(Utils::DateToEn($row['apply_time']),'center');

        if($row['rvo'] == '1'){
            $rvo = 'Yes';
        }else if($row['rvo'] == '2'){
            $rvo = 'No';
        }else{
            $rvo = '';
        }
        $t->echo_td($rvo,'center');

        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';

        $t->echo_td($status,'center'); //状态

        $preview_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemPreview(\"{$row['check_id']}\",\"{$program_id}\")' title=\"Details\"><i class=\"fa fa-fw fa-list-alt\"></i></a>&nbsp;";
        $download_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownload(\"{$row['check_id']}\")' title=\" " . Yii::t('license_licensepdf', 'download') . "\"><i class=\"fa fa-fw fa-download\"></i></a>";

        $link = "";
        if($row['status'] == '0'){ //进行中
            $link .=  $preview_link;
        }else if($row['status'] == '1'){ //关闭
            $link .=  $preview_link.$download_link;
        }

        $t->echo_td($link,'center'); //操作
        $status = $row['status'];
        $t->end_row();
    }

}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-5">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-7">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>

