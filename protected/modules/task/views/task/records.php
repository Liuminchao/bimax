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
            var val = obj.value;
            if(obj.name == 'q[pbu_name]'){
                val = encodeURIComponent(val);
            }
            url += '&' + obj.name + '=' + val;
        }
<?php echo $this->gridId; ?>.condition = url;
<?php echo $this->gridId; ?>.refresh();
    }
    //添加
    var itemAdd = function () {
        window.location = "index.php?r=task/task/new";
    }

    var itemChecklist = function (check_id) {
        window.location = "index.php?r=qa/qainspection/checklist&check_id="+check_id;
    }

    //Workflow
    var itemWorkflow = function (check_id) {
        var modal = new TBModal();
        modal.title = "Workflow";
        modal.url = "index.php?r=task/task/workflow&check_id="+check_id;
        modal.modal();
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
            url: "index.php?r=task/task/stop",
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

    //Issues
    var itemIssues= function(id) {
        var source = 'dfma';
        window.location = "index.php?r=qa/qadefect/checklist&check_id="+id+"&source="+source;
    }
</script>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('record_toolBox',array('program_id'=>$program_id,'clt_type'=>$clt_type,'args'=>$args)); ?>
                    <div id="datagrid"><?php $this->actionRecordGrid($program_id,$clt_type); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
