<script type="text/javascript">
    $(function(){
        // itemQuery();
    });
    //查询
    var itemQuery_1 = function () {
        var objs = document.getElementById("_query_form_4").elements;
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
    //批量导出
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
        window.location = "index.php?r=qa/statistic/dateexport" + url;
    }

</script>
<div class="row">
    <div class="col-xs-12">
        <div class="card card-info card-outline">
            <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                <div class="card-header">
                    <?php $this->renderPartial('date_toolBox',array('program_id'=>$program_id)); ?>
                </div>
                <div class="card-body">
                    <div id="datagrid_1" style="overflow-x: scroll;"><?php $this->actionDateGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>