<style type="text/css">
    #projlist td:nth-child(2){
        display: none;
    }
</style>
<?php
$t->echo_grid_header();
if (is_array($rows)) {

    //$type_list = Role::contractorTypeText();
    $status_list = RevitComponent::statusText(); //状态
    $status_css = RevitComponent::statusCss();
    foreach ($rows as $i => $row) {
        $attr['style'] = 'display:none';
        $t->begin_row("onclick", "getDetail(this,'{$row['id']}');");
        $t->echo_td($row['id'],'center',$attr);
        $t->echo_td($row['pbu_type']);
        $t->echo_td($row['block']);
        $t->echo_td($row['level']);
        $t->echo_td($row['unit_nos']);
        $t->echo_td($row['part']);
        $t->echo_td($row['pbu_id']);
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;

?>

<div class="row">
    <div class="col-12">
        <label  class="padding-lr5">
            <button type="button" class="btn btn-primary btn-sm" onclick="itemUpdate('<?php echo $program_id ?>')">Update</button>
        </label>
    </div>
</div>

<div class="row">
    <div class="col-3">
        <div class="dataTables_info" id="projlist_info">
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
<script src="js/loading.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

        function initTableCheckbox() {
            var $thr = $('#projlist thead tr');
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
            var $tbr = $('#projlist tbody tr');
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
    var itemEditPbutype = function (id) {
        var modal = new TBModal();
        modal.title = "Edit Pbu Type";
        modal.url = "index.php?r=task/pbu/editallocate&project_id=" + id;
        modal.modal();
        itemQuery();
    }
    //更新构件 状态置为0
    var itemUpdate = function (project_id) {
        var tbodyObj = document.getElementById('projlist');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += id + '|';
                }
            }
        })
        if(tag.length == 0){
            alert('Please select Pbu Info.');
            return false;
        }
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
//        alert(tag);
        alert('Are you sure to upgrade to the component library?');//确认升级到构件库吗
        $.ajax({
            data: {tag:tag},
            url: "index.php?r=task/pbu/updatepbu",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_update'); ?>");
                    removecloud();//去遮罩
                    window.location = "index.php?r=task/pbu/pbulist&program_id=<?php echo $program_id; ?>";
                } else {
                    //alert("<?php echo Yii::t('common', 'error_update'); ?>");
                    alert(data.msg);
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>

