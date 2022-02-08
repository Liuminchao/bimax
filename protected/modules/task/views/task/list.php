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
    var itemAdd = function (project_id,template_id,stage_id) {
        window.location = "index.php?r=task/task/new&program_id="+project_id+"&template_id="+template_id+"&stage_id="+stage_id;
    }
    //编辑
    var itemEdit = function(task_id){
        window.location = "index.php?r=task/task/edit&task_id="+task_id;
    }
    //返回
    var back = function (project_id,template_id,stage_id) {
        window.location = "index.php?r=task/template/stagelist&id="+template_id+"&project_id="+project_id;
    }
    //启用
    var itemStart = function (id, name) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_start_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_start_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=task/task/start",
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
            url: "index.php?r=task/task/stoptask",
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

</script>

<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('program_id'=>$program_id,'template_id'=>$template_id,'stage_id'=>$stage_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid($program_id,$template_id,$stage_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>