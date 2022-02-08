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

    var back = function () {
        window.location.href = document.referrer;//返回上一页并刷新
        //window.location = "index.php?r=comp/usersubcomp/list";
    }


</script>
<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body">
                <div role="grid" class="dataTables_wrapper " id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('project_id'=>$project_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid($project_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>