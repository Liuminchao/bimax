<style type="text/css">
    .format1{
        list-style:none; padding:0px; margin:0px; width:200px; float: left;
    }
    .format2{ width:50%; display:inline-block; float: left; padding-left: 0}
    #example2 td:nth-child(2){
        display: none;
    }
</style>
<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $status_rfa_list = RfList::statusRfaText(); //状态text
    $status_rfi_list = RfList::statusRfiText(); //状态text
    $status_css = RfList::statusCss(); //状态css
    $company_list = Contractor::compAllList();//承包商公司列表
    $detail_statustext = CheckApplyDetail::statusText();
    $app_id = 'RFI';
    $rfa_type = RfList::rfaType();
    $type_list = RfList::typeList();
    $trade_list = RfGroup::tradeList();
    $rvo_list = RfList::rvoList();


    foreach ($rows as $i => $row) {
//        $program_model = Program::model()->findByPk($row['program_id']);
//        if($program_model->params){
//            $params = json_decode($program_model->params,true);
//        }else{
//            $params['ptw_mode'] = 'A';
//        }
        // $t->begin_row("onclick", "getDetail(this,'{$row['apply_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $group_user = RfDetail::queryGroupByRecord($row['check_id']);//查询记录最后一步的回复人
        $item_list = RfRecordItem::dealList($row['check_id']);
        $item_data = json_decode($item_list[0]['item_data'],true);
        $rf_model = RfList::model()->findByPk($row['check_id']);
        $step = $rf_model->current_step;
        $apply_user_id = $rf_model->apply_user_id;
        $deal = RfDetail::dealListByStep($row['check_id'],$step);

        $t->echo_td($row['check_id'],'center');
//        $t->echo_td($num,'center',$attr); //Apply
//        if($row['status'] == '-1' || $row['status'] == '0'){
//            $t->echo_td("<img  class='no_selected' src='img/email_close.png' >",'center',$attr);
//        }else{
//            $t->echo_td("<img  class='no_selected' src='img/email_open.png' >",'center',$attr);
//        }
        $t->echo_td($row['check_no'],'center');//Company Name
        if(strlen($row['subject']) >= 60){
            $subject = substr($row['subject'],0,60).'..';
        }else{
            $subject = $row['subject'];
        }
        $t->echo_td($subject,'left'); //title
        $t->echo_td($type_list[$row['discipline']],'center');
        if($type_id == '2'){
            $t->echo_td($trade_list[$item_data['trade']],'center');
        }
        $t->echo_td($rvo_list[$row['rvo']],'center');
//        $t->echo_td(Utils::DateToEn($row['apply_time']),'center',$attr);
        $t->echo_td(Utils::DateToEn($row['valid_time']),'center');

        if($type_id == '1'){
            $status_echo = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_rfi_list[$row['status']] . '</span>';
        }
        if($type_id == '2'){
            $status_echo = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_rfa_list[$row['status']] . '</span>';
        }

        if($row['status'] == '-1'){
            if($row['type'] == '1'){
                $status_echo = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_rfi_list[$row['status']] . '</span>';
            }
            if($row['type'] == '2'){
                $status_echo = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_rfa_list[$row['status']] . '</span>';
            }
        }

        $deal_list = RfDetail::stepByShortType($row['check_id'],'3');

        if($deal_list['color'] != ''){
            $attr['style'] = 'color:'.$deal_list['color'].';text-align:center;';
        }else{
            $attr = array();
        }
        $attr['tooltip'] = ' data-toggle="tooltip"  data-placement="bottom" title="'.$deal_list['long_deal_type'].'" ';

        if($type_id != '1'){
            $t->echo_td($deal_list['short_deal_type'],'center',$attr);
        }
        if($apply_user_id == $deal[0]['user_id'] && $row['status'] != '1'){
            $group_user = '---';
        }

        $t->echo_td($group_user,'center'); //回复人所属组
        $t->echo_td($row['contractor_name'],'center');
        $t->echo_td($status_echo,'center'); //状态

        $edit_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemEdit(\"{$row['check_id']}\")' title='Edit'><i class=\"fa fa-pencil-square-o\"></i></a>&nbsp;";
        $preview_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemPreview(\"{$row['check_id']}\",\"{$program_id}\")' title='Detail'><i class=\"fa fa-tasks\"></i></a>&nbsp;";
        $download_link = "<a class='a_logo' href='javascript:void(0)q' onclick='itemDownload(\"{$row['check_id']}\")' title='".Yii::t('license_licensepdf', 'download')."'><i class=\"fa fa-download\"></i></a>&nbsp;";

        $link = "";
        if ($row['status'] == '-1') {    //草稿
            $link .= $edit_link;
        }else if($row['status'] == '0'){ //进行中
            $link .=  $preview_link;
        }else if($row['status'] == '1'){ //关闭
            $link .=  $preview_link.$download_link;
        }else{ //超时
            $link .=  $preview_link;
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

<?php if($rows && $type_id == '2'){ ?>
    <div class="row">
        <div class="col-12">
            <label  class="padding-lr5">
                
    <?php if ($status <> '-1'){ ?>
            <button class="btn btn-primary btn-sm" onclick="ConfirmForward()">Forward</button>
    <?php }else{    ?>
            <button type="button" class="btn btn-primary btn-sm"  onclick="Itemdelete();">Delete</button>
    <?php } ?>
            </label>
        </div>
    </div>
<?php }?>

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
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    var ConfirmForward = function() {
        var modal = new TBModal();
//        modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = 'Confirm';
        modal.url = "index.php?r=rf/rf/bashconfirmforward";
        modal.modal();
    }
    //转发
    function forward() {
        var program_id = $('#program_id').val();
        var type_id = $('#type_id').val();
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        console.log(tbodyObj);
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    if(tbodyObj.rows[key] != 'undefined'){
                        var check_id = tbodyObj.rows[key].cells[1].innerHTML;
                        tag += check_id + ',';
                    }
                }
            }
        })
        if(tag.length == 0){
            alert('<?php echo Yii::t('comp_safety', 'error_tag_is_null'); ?>');
            return false;
        }
        tag=(tag.substring(tag.length-1)==',')?tag.substring(0,tag.length-1):tag;
        // alert(tag);
        window.location = "index.php?r=rf/rf/forward&check_id="+tag+"&program_id="+program_id+"&type="+type_id;
    }

    function Itemdelete () {
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        rowcnt= 0 ;
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var apply_id = tbodyObj.rows[key].cells[1].innerText;
                    tag += apply_id + '|';
                    rowcnt++;
                }
            }
        })
        if(tag.length == 0){
            alert('Please select record.');
            return false;
        }
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
        // alert(tag);
        $.ajax({
            data: {tag:tag},
            url: "index.php?r=rf/rf/deleterecord",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
            },
            success: function (data) {
                alert('Delete success');
                itemQuery();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统超时');
                $('#msgbox').show();
            }
        });
    }

    jQuery(document).ready(function () {

        function initTableCheckbox() {
            var $thr = $('#example2 thead tr');
            var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
            /*将全选/反选复选框添加到表头最前，即增加一列*/
            $thr.prepend($checkAllTh);
            /*“全选/反选”复选框*/
            var $checkAll = $thr.find('input');
            $checkAll.click(function (event) {
                /*将所有行的选中状态设成全选框的选中状态*/
                $tbr.find('input').prop('checked', $(this).prop('checked'));
                /*并调整所有选中行的CSS样式*/
                if ($(this).prop('checked')) {
                    $tbr.find('input').parent().parent().addClass('warning');
                } else {
                    $tbr.find('input').parent().parent().removeClass('warning');
                }
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击全选框所在单元格时也触发全选框的点击操作*/
            $thr.click(function () {
                $(this).find('input').click();
            });
            var $tbr = $('#example2 tbody tr');
            var $checkItemTd = $('<td><input type="checkbox" name="checkItem" /></td>');
            /*每一行都在最前面插入一个选中复选框的单元格*/
            $tbr.prepend($checkItemTd);
            /*点击每一行的选中复选框时*/
            $tbr.find('input').click(function (event) {
                /*调整选中行的CSS样式*/
                $(this).parent().parent().toggleClass('warning');
                /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
                $checkAll.prop('checked', $tbr.find('input:checked').length == $tbr.length ? true : false);
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击每一行时也触发该行的选中操作*/
            $tbr.click(function () {
                $(this).find('input').click();
            });
        }

        initTableCheckbox();
    });
</script>

