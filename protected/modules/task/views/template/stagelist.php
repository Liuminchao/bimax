<script type="text/javascript">
    $(function(){
        itemQuery();
    });
    //查询
    var itemQuery = function () {
        var objs = document.getElementById("_query_form").elements;
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
    //添加
    var itemAdd = function (project_id,template_id) {
        window.location = "index.php?r=task/template/newstage&project_id="+project_id+"&template_id="+template_id;
    }
    //任务详情
    var itemDetail = function (template_id,stage_id,project_id) {
        window.location = "index.php?r=task/task/list&template_id="+template_id+"&stage_id="+stage_id+"&project_id="+project_id;
    }
    //编辑
    var itemEdit = function (stage_id) {
        window.location = "index.php?r=task/template/editstage&stage_id="+stage_id;
    }
    //返回
    var itemBack = function (project_id) {
        window.location = "index.php?r=task/template/list&program_id="+project_id;
    }
    //启用
    var itemStart = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_start_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_start_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=task/stage/start",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_start'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_start'); ?>");
                }
            }
        });
    }
    //停用
    var itemStop = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_stop_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_stop_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=task/stage/stop",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_stop'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_stop'); ?>");
                }
            }
        });
    }
    //Dshboard控制是否显示
    var itemHideDashboard = function (id, name) {
        if (!confirm("Confirm to hide on Dashboard?")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=task/stage/hidedashboard",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("Hide Success");
                    itemQuery();
                } else {
                    alert("Hide Error");
                }
            }
        });
    }
    //Dshboard控制是否显示
    var itemShowDashboard = function (id, name) {
        if (!confirm("Confirm to show on Dashboard?")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=task/stage/showdashboard",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("Show Success");
                    itemQuery();
                } else {
                    alert("Show Error");
                }
            }
        });
    }
</script>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('stage_toolBox',array('template_id'=>$template_id,'project_id'=>$project_id)); ?>
                    <div id="datagrid"><?php $this->actionStageGrid($template_id,$project_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>