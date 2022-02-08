<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $status_list = RfList::statusRfaText(); //状态text
    $status_css = RfList::statusCss(); //状态css
    $company_list = Contractor::compAllList();//承包商公司列表
    $detail_statustext = CheckApplyDetail::statusText();
    $app_id = 'RFI';
    foreach ($rows as $i => $row) {
//        $program_model = Program::model()->findByPk($row['program_id']);
//        if($program_model->params){
//            $params = json_decode($program_model->params,true);
//        }else{
//            $params['ptw_mode'] = 'A';
//        }
        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $t->echo_td($num,'center'); //Apply
        if($row['status'] == '-1' || $row['status'] == '0'){
            $t->echo_td("<img  class='no_selected' src='img/email_close.png' >",'center');
        }else{
            $t->echo_td("<img  class='no_selected' src='img/email_open.png' >",'center');
        }
        $t->echo_td($row['check_no'],'center');//Company Name
        $t->echo_td($row['subject'],'center'); //title

        $t->echo_td(Utils::DateToEn($row['record_time']),'center');
        $t->echo_td(Utils::DateToEn($row['valid_time']),'center');

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';


        $t->echo_td($status,'center'); //状态

        $chat_link = "<a href='javascript:void(0)' onclick='itemEdit(\"{$row['check_id']}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>Edit</a>";
        $preview_link = "<a href='javascript:void(0)' onclick='itemPreview(\"{$row['check_id']}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>Detail</a>";
        $attachment_link = "<a href='javascript:void(0)' onclick='itemAttachment(\"{$row['check_id']}\")'><i class=\"fa fa-fw fa-bar-chart-o\"></i>Attachment</a>";
        $download_link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$row['check_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('license_licensepdf', 'download') . "</a>";
        $link = "";
        if ($row['status'] == '-1') {    //未结束
            $link .= "<table><tr><td style='white-space: nowrap' align='left'>$chat_link</td></tr></table>";
        }else if($row['status'] == '1' || $row['status'] == '2' ||  $row['status'] == '3' ||  $row['status'] == '4' ||  $row['status'] == '6'){
            $link .=  "<table><tr><td style='white-space: nowrap' align='left'>$preview_link</td><td style='white-space: nowrap' align='left'>$download_link</td></tr></table>";
        }else{
            $link .=  "<table><tr><td style='white-space: nowrap' align='left'>$preview_link</td></tr></table>";
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
    <div class="col-xs-5">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-xs-7">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

