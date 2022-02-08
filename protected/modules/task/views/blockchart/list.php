<script type="text/javascript">
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
    var itemAdd = function (id) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('sys_role', 'RoleNew'); ?>";
        modal.url = "index.php?r=task/blockchart/new&project_id="+id;
        modal.modal();
    }
    var itemBack = function (id) {
        window.location = "index.php?r=qa/statistic/show&program_id="+id;
    }
    //修改
    var itemEdit = function (id) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('sys_role', 'RoleEdit'); ?>";
        modal.url = "index.php?r=task/blockchart/edit&id=" + id;
        modal.modal();
        itemQuery();
    }

    //删除
    var itemDelete = function (id) {
        var name = 'this';
        if (!confirm("<?php echo Yii::t('common', 'confirm_delete_1'); ?>" + name + "<?php echo Yii::t('common', 'confirm_delete_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=task/blockchart/delete",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    itemQuery();
                } else {
                    alert("<?php echo Yii::t('common', 'error_delete'); ?>");
                }
            }
        });
    }


</script>
<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline">

            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper " id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('project_id'=>$project_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid($project_id); ?></div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>