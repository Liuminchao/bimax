<style type="text/css">
    #example2 td:nth-child(2){
        display: none;
    }
</style>
<?php
$t->echo_grid_header();

if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = RevitComponent::statusText(); //状态
    $status_css = RevitComponent::statusCss();
    $type_list = RevitComponent::typeList();
    $data = array(
        'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
        'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
    );
    foreach ($data as $key => $value) {
        $post_data[$key] = $value;
    }
    foreach ($rows as $i => $row) {

        $t->begin_row("onclick", "getDetail(this,'{$row['id']}');");
//        $num = ($curpage - 1) * $this->pageSize + $j++;
        $stop_link = "<a href='javascript:void(0)' onclick='itemStop(\"{$row['id']}\",\"{$row['pbu_name']}\")'><i class=\"fa fa-fw fa-times\"></i>" . Yii::t('common', 'stop') . "</a>&nbsp;";

        if ($row['status'] == QaChecklist::STATUS_NORMAL) {
            $link = $stop_link;
        }

        $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';

        $args['pbu_id'] = $row['pbu_id'];
        $args['model_id'] = $row['model_id'];
        $args['project_id'] = $program_id;
        $args['template_id'] = $template_id;
//        $stage_name = StatisticPbuInfo::BlockData($args);
        $pro_model =Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        if($root_proid == '2419'){
            $part = '';
        }else{
            $part = $row['part'];
        }
        $t->echo_td($row['id']);
        $t->echo_td($row['block']);
        $t->echo_td($row['level']);
        $t->echo_td($part);
        $t->echo_td($row['unit_nos']);
        $t->echo_td($row['unit_type']);
        $t->echo_td($row['pbu_type']);
        $attr['tooltip'] = ' data-toggle="tooltip"  data-placement="bottom" title="'.$row['pbu_id'].'" ';
        $t->echo_td($row['pbu_name'],'',$attr);
        $t->echo_td($row['module_type']);
        $t->echo_td($row['version']);
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;

?>
<?php if($rows){ ?>
<div class="row">
    <div class="col-12">
        <div  class="padding-lr5">
            <button type='button' class='btn btn-danger btn-sm' onclick='itemDelete();'>Delete</button>
            <button type='button' class='btn btn-primary btn-sm' onclick='itemExport();'>Export</button>
            <button type='button' class='btn btn-primary btn-sm' onclick='itemReplace("<?php echo $pbu_tag; ?>");'>Replace</button>
        </div>
    </div>
</div>
<?php } ?>

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
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
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
    //查询
    var itemExport = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        window.location = "index.php?r=task/model/bachexport"+url;
    }
    //批量删除
    var itemDelete = function () {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete_1'); ?><?php echo Yii::t('common', 'confirm_delete_2'); ?>')) {
            return;
        }
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += id + '|';
                    i++;
                }
            }
        })
        if(tag != ''){
            tag = tag.substr(0,tag.length-1);
        }else{
            alert('Please select Pbu Info.');
            return false;
        }
        console.log(tag);
        $.ajax({
            data: {tag: tag, confirm: 1},
            url: "index.php?r=task/model/deletepbu",
            dataType: "json",
            type: "GET",
            success: function (data) {
                if(data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    itemQuery();
                }
                else{
                    alert("<?php echo Yii::t('common', 'error_delete'); ?>");
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
    }

    //替换
    var itemReplace = function (pbu_tag) {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += id + '|';
                    i++;
                }
            }
        })
        if(tag != ''){
            tag = tag.substr(0,tag.length-1);
        }else{
            alert('Please select Pbu Info.');
            return false;
        }

        // if(i > 1){
        //     alert('Only one component can be selected.');
        //     return false;
        // }
        console.log(tag);
        var modal = new TBModal();
//        name = name.replace(" ","&amp;nbsp;");
//        alert(name);
        modal.title = "Replace";
        modal.url = "index.php?r=task/model/replace&tag="+tag+"&pbu_tag="+pbu_tag;
        modal.modal();
    }

    //Allocate
    var itemAllocate = function (id,pbu_tag) {
        var modal = new TBModal();
        if(pbu_tag == '1'){
            modal.title = "Allocate PBU Type";
        }else if(pbu_tag == '2'){
            modal.title = "Allocate PPVC Type";
        }else if(pbu_tag == '3'){
            modal.title = "Allocate Precast Type";
        }
        modal.url = "index.php?r=task/pbu/allocate&project_id=" + id+"&pbu_tag="+pbu_tag;
        modal.modal();
        itemQuery();
    }
    var itemEditPbutype = function (id,pbu_tag) {
        var modal = new TBModal();
        if(pbu_tag == '1'){
            modal.title = "Edit PBU Type";
        }else if(pbu_tag == '2'){
            modal.title = "Edit PPVC Type";
        }else if(pbu_tag == '3'){
            modal.title = "Edit Precast Type";
        }
        modal.url = "index.php?r=task/pbu/editallocate&project_id=" + id+"&pbu_tag="+pbu_tag;
        modal.modal();
        itemQuery();
    }
</script>

