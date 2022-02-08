<script type="text/javascript">
    $(function(){
        // itemQuery();
    });
    //查询
    var itemQuery = function () {
        var objs = document.getElementById("_query_form_3").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
<?php echo $this->gridId; ?>.condition = url;
<?php echo $this->gridId; ?>.refresh();
    }
    //返回
    var back = function (project_id,template_id,stage_id) {
        window.location = "index.php?r=task/template/stagelist&id="+template_id+"&project_id="+project_id;
    }
    //批量导出
    var itemExport = function (project_id,template_id) {
//        var objs = document.getElementById("_query_form").elements;
//        var i = 0;
//        var cnt = objs.length;
//        var obj;
//        var url = '';
//
//        for (i = 0; i < cnt; i++) {
//            obj = objs.item(i);
//            url += '&' + obj.name + '=' + obj.value + '&q[tag]=' +tag;
//        }
        window.location = "index.php?r=qa/statistic/blockexport&program_id=" + project_id+"&template_id="+template_id;
    }

</script>
<div class="row">
    <div class="col-xs-12">
        <div class="card card-info card-outline">
            <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                <div class="card-header">
                    <?php $this->renderPartial('block_toolBox',array('program_id'=>$program_id)); ?>
                </div>
                <div class="card-body">
                    <div id="datagrid" style="overflow-x: scroll;"><?php $this->actionBlockGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>