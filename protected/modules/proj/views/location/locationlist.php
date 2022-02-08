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
        modal.title = "<?php echo Yii::t('proj_project', 'Edit Location'); ?>";
        modal.url = "index.php?r=proj/location/editlocation&id="+id+"&project_id="+project_id;
        modal.modal();
    }

    //批量导入
    var itemImport = function (id) {
        var modal=new TBModal();
        modal.title="<?php echo Yii::t('comp_staff','Batch import');?>";
        modal.url="./index.php?r=proj/location/view&id="+id;
        modal.modal();
    }

    //删除
    var itemDel = function (id) {
        if (!confirm("<?php echo Yii::t('common', 'proceed_to_delete'); ?>" )) {
            return;
        }
        $.ajax({
            data: {id: id},
            url: "index.php?r=proj/location/dellocation",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_logout'); ?>");
                    location.reload();
                } else {
                    alert("<?php echo Yii::t('common', 'error_logout'); ?>");
                }
            }
        });
    }

    //同步数据
    var itemSync = function (id,block) {
        var modal=new TBModal();
        modal.title=block;
        modal.url= encodeURI("index.php?r=proj/location/changepart&project_id="+id+"&block="+block);
        modal.modal();
    }
    var itemUnit = function (project_id,block) {
        var modal = new TBModal();
        modal.title = "Change Unit No.";
        var url = encodeURI("index.php?r=proj/location/changeunit&block=" + block+"&project_id="+project_id);
        modal.url = url;
        modal.modal();
    }
    var itemAdd = function (project_id,block) {
        var modal = new TBModal();
        modal.title = "Add New Location";
        modal.url = encodeURI("index.php?r=proj/location/addlocation&block=" + block+"&project_id="+project_id);
        modal.modal();
    }
    var itemUpload = function (project_id,block) {
        window.location = "index.php?r=proj/location/uploadlist&tag=level&block="+block+"&project_id="+project_id;
    }
</script>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs">
                    <?php
                    $block_list = ProgramRegion::locationAllBlock($project_id);
                    foreach($block_list as $i => $j){
                        if($j == $block){
                            $tag = ' active ';
                        }else{
                            $tag = '';
                        }
                        ?>
                        <li class="nav-item"><a class="nav-link<?php echo $tag ?>" href="index.php?r=proj/location/locationlist&program_id=<?php echo $project_id ?>&block=<?php echo $j; ?>" ><?php echo $j ?></a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div><!-- /.card-header -->
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('location_toolBox',array('project_id'=>$project_id,'block'=>$block, 'args'=>$args)); ?>
                    <div id="datagrid"><?php $this->actionLocationGrid($project_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
