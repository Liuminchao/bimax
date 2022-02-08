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
//    //添加
//    var itemAdd = function () {
//        var modal = new TBModal();
//        modal.title = "<?php //echo Yii::t('sys_role', 'RoleNew'); ?>//";
//        modal.url = "index.php?r=comp/role/new";
//        modal.modal();
//    }
    //修改
    var itemEdit = function (id,project_id) {
        var modal = new TBModal();
        modal.title = "Defect Selection";
        modal.url = "index.php?r=qa/defect/edittype&id=" + id+"&project_id="+project_id;
        modal.modal();
        itemQuery();
    }

    //批量导入
    var itemImport = function (id) {
        var modal=new TBModal();
        modal.title="<?php echo Yii::t('comp_staff','Batch import');?>";
        modal.url="./index.php?r=qa/defect/view&id="+id;
        modal.modal();
    }

    //删除
    var itemDel = function (id,program_id) {
        if (!confirm("<?php echo Yii::t('common', 'confirm_stop_1'); ?>" + "<?php echo Yii::t('common', 'confirm_stop_2'); ?>")) {
            return;
        }
        $.ajax({
            data: {id: id},
            url: "index.php?r=qa/defect/deltype",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_logout'); ?>");
                    //window.location = "index.php?r=qa/defect/typelist&program_id="+program_id+"&curpage="+<?php //echo $_SESSION['defect_type_page'] ?>//;
                    window.location = "./?<?php echo Yii::app()->session['list_url']['qa/defect/typelist']; ?>";
                } else {
                    alert("<?php echo Yii::t('common', 'error_logout'); ?>");
                }
            }
        });
    }

</script>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <div class="row" style="margin-bottom: 8px;">
                    <div class="col-9" style="text-align: right;margin-bottom: 0px;">
                        <ul class="nav nav-pills" role="tablist" id="myTab">
                            <?php
                            if($type_id=='Completion'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=Completion" >Completion</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=Handover" >Handover</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=DLP" >DLP</a></li>
                                <?php
                            }else if($type_id=='Handover'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=Completion" >Completion</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=Handover" >Handover</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=DLP" >DLP</a></li>
                                <?php
                            }else if($type_id=='DLP'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=Completion" >Completion</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=Handover" >Handover</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=qa/defect/typelist&program_id=<?php echo $project_id; ?>&type_id=DLP" >DLP</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="col-3" style="text-align: right;margin-bottom: 0px;">
                        <button class="btn btn-primary btn-sm" onclick="itemImport('<?php echo $project_id ?>')"><?php echo Yii::t('comp_staff', 'Batch import'); ?></button>
                    </div>
                </div>            </div>
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('type_toolBox',array('project_id'=>$project_id,'type_id'=>$type_id, 'args'=>$args)); ?>
                    <div id="datagrid"><?php $this->actionTypeGrid($project_id,$type_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>