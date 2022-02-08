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

    $status_list = RfAttachment::statusText(); //状态text
    $status_css = RfAttachment::statusCss(); //状态css
    $approve_list = RfAttachment::approveText(); //状态text
    $approve_css = RfAttachment::approveCss(); //状态css
    $tag_list = DocumentLabel::tagList();//标签
//    var_dump($tag_list);
//    exit;
    $program_list =  Program::programAllList();
    foreach ($rows as $i => $row) {
        $num = ($curpage - 1) * $this->pageSize + $j++;

        $publish_link = "<a href='javascript:void(0)' onclick='itemPublish(\"{$row['attach_id']}\",\"{$program_id}\")'><i class=\"fa fa-fw fa-briefcase\"></i>Publish</a>&nbsp;";
        $record_link = "<a href='javascript:void(0)' onclick='itemRecord(\"{$row['attach_id']}\",\"{$program_id}\")'><i class=\"fa fa-fw fa-info\"></i>RFA(s)</a>";
        if($row['status'] == '0'){
            if($row['archi_status'] == '1' || $row['me_status'] == '1' || $row['cs_status'] == '1'){
                $link = "<ul class='format1'><li class='format2'>$publish_link</li><li class='format2'>$record_link</li></ul>";
            }else if($row['archi_status'] == '1' || $row['me_status'] == '1' || $row['cs_status'] == '2'){
                $link = "<ul class='format1'><li class='format2'>$publish_link</li><li class='format2'>$record_link</li></ul>";
            }else if($row['archi_status'] == '1' || $row['me_status'] == '2' || $row['cs_status'] == '1'){
                $link = "<ul class='format1'><li class='format2'>$publish_link</li><li class='format2'>$record_link</li></ul>";
            }else if($row['archi_status'] == '1' || $row['me_status'] == '2' || $row['cs_status'] == '2'){
                $link = "<ul class='format1'><li class='format2'>$publish_link</li><li class='format2'>$record_link</li></ul>";
            }else if($row['archi_status'] == '2' || $row['me_status'] == '1' || $row['cs_status'] == '1'){
                $link = "<ul class='format1'><li class='format2'>$publish_link</li><li class='format2'>$record_link</li></ul>";
            }else if($row['archi_status'] == '2' || $row['me_status'] == '1' || $row['cs_status'] == '2'){
                $link = "<ul class='format1'><li class='format2'>$publish_link</li><li class='format2'>$record_link</li></ul>";
            }else if($row['archi_status'] == '2' || $row['me_status'] == '2' || $row['cs_status'] == '1'){
                $link = "<ul class='format1'><li class='format2'>$publish_link</li><li class='format2'>$record_link</li></ul>";
            }else if($row['archi_status'] == '2' || $row['me_status'] == '1' || $row['cs_status'] == '2'){
                $link = "<ul class='format1'><li class='format2'>$publish_link</li><li class='format2'>$record_link</li></ul>";
            }else{
                $link = "<ul class='format1'><li class='format2'>$record_link</li></ul>";
            }
        }else{
            $link = "<ul class='format1'><li class='format2'>$record_link</li></ul>";
        }

        $t->echo_td($row['attach_id']);
        if($row['doc_type'] == 'pdf'){
//            $t->echo_td("<a href='javascript:void(0)' onclick='itemPreview(\"{$row['doc_path']}\",\"{$row['doc_id']}\")'>" . $row['doc_name'] . "</a>");
            $t->echo_td("<a href='index.php?r=rf/rf/previewdoc&doc_path={$row['doc_path']}' target='_blank'>" . $row['doc_name'].'.'.$row['doc_type'] . "</a>",'center');
        }else{
            $t->echo_td("<a href='javascript:void(0)' onclick='window.open(\"{$row['doc_path']}\",\"_blank\")'>" . $row['doc_name'].'.'.$row['doc_type'] . "</a>",'center');
        }
        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $archi_status = '<span class="label ' . $approve_css[$row['archi_status']] . '">' . $approve_list[$row['archi_status']] . '</span>';
        $me_status = '<span class="label ' . $approve_css[$row['me_status']] . '">' . $approve_list[$row['me_status']] . '</span>';
        $cs_status = '<span class="label ' . $approve_css[$row['cs_status']] . '">' . $approve_list[$row['cs_status']] . '</span>';
        $t->echo_td($row['subject'],'center');
        $t->echo_td($archi_status,'center');
        $t->echo_td($me_status,'center');
        $t->echo_td($cs_status,'center');
        $t->echo_td($status,'center');
        $t->echo_td($row['record_time'],'center');
//        $t->echo_td($row['file_tag']);
        $t->echo_td($link,'center'); //操作
        $t->end_row();
    }
}

$t->echo_grid_floor();

//$pager = new CPagination($cnt);
//$pager->pageSize = $this->pageSize;
//$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
<!--    <div class="col-xs-9">-->
<!--        <div class="dataTables_paginate paging_bootstrap">-->
<!--            --><?php //$this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
<!--        </div>-->
<!--    </div>-->
</div>
<!--<script src="js/bootstrap.min.js"></script>-->
<!--<script type="text/javascript" src="js/select2_new.js"></script>-->
<!--<script type="text/javascript" src="js/bootstrap-editable.js"></script>-->
<script type="text/javascript">
//    $('#tags').editable({
//        inputclass: 'input-large',
//        select2: {
////            tags: [{id: 'PTW', text: 'PTW'},{id: 'TBM', text: 'TBM'},{id: 'RA/SWP', text: 'RA/SWP'}],
//            tags:function () {
//                var result = [];
////                    var result = [{value:1,text:'PTW'},{value:1,text:'TBM'},{value:1,text:'RA'}];
//                $.ajax({
//                    url: 'index.php?r=document/platform/source',
//                    async: false,
//                    type: "get",
//                    dataType: 'json',
//                    success: function (data, status) {
//                        $.each(data, function (key, value) {
//                            result.push({ id: value.id, text: value.name });
//                        });
//                    }
//                });
//                return result; } ,
//            width: '200px',
////            data: [{id: 0, text: 'PTW'},{id: 1, text: 'TBM'},{id: 2, text: 'RA/SWP'}],
//            multiple: true
//        }
//    });

jQuery(document).ready(function () {

    function initTableCheckbox() {
        var $thr = $('#example2 thead tr');
        var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" />All</th>');
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
