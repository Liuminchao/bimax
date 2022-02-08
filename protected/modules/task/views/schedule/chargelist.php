<script type="text/javascript">
    //查询
    var itemChargeQuery = function() {
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
    var itemAdd = function(id) {
        window.location = "index.php?r=license/condition/new&type_id="+id;
    }
    //返回
    var itemBack = function() {
        window.location = "index.php?r=license/type/list";
    }
    //修改
    var itemEdit = function(id,type_id) {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('common', 'edit'); ?>";
        modal.url = "index.php?r=license/condition/edit&id="+id+"&type_id="+type_id;
        modal.modal();
    }
    //删除
    var itemDelete = function(id,type_id) {
        if (!confirm('<?php echo Yii::t('common', 'confirm_delete'); ?>')) {
            return;
        }
        $.ajax({
            data: {id: id,confirm:1},
            url: "index.php?r=license/condition/delete",
            dataType: "json",
            type: "POST",
            success: function(data) {
                
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
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header p-0 border-bottom-0">
                <div class="row" style="margin-bottom: 8px;">
                    <div class="col-9" style="text-align: right;margin-bottom: 0px;">
                        <ul class="nav nav-pills" role="tablist" id="myTab">
                            <?php
                            if($pbu_tag == '1'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=1" >PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }else if($pbu_tag == '2'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=1">PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }else if($pbu_tag == '3'){
                                ?>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=2">PPVC</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=1">PBU</a></li>
                                <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&pbu_tag=3">Precast</a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div><!-- /.card-header -->
            <div class="card-body" style="overflow-x: auto">
                <div class="row">
                    <div class="col-2 col-sm-1">
                        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                            <?php
                            $block_list = ProgramBlockChart::locationBlockbyType($program_id,$pbu_tag);
                            $total_day = 0;
                            $index = 0;
                            if(count($block_list)>0){
                                foreach($block_list as $i => $j){
                                    if($j == $block){
                                        $tag = ' active ';
                                    }else{
                                        $tag = '';
                                    }
                                    ?>
                            <a class="nav-link<?php echo $tag ?>" id="vert-tabs-<?php echo $j; ?>-tab" href="index.php?r=task/schedule/chargelist&program_id=<?php echo $program_id ?>&block=<?php echo $j; ?>&pbu_tag=<?php echo $pbu_tag; ?>" aria-controls="vert-tabs-<?php echo $j; ?>" aria-selected="false"><?php echo $j; ?></a>
                                    <?php } ?>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                    <div class="col-10 col-sm-11">
                        <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                            <?php $this->renderPartial('charge_toolBox',array('args'=>$args,'program_id'=>$program_id,'block'=>$block)); ?>
                            <div id="datagrid">
                                <form id="form1">
                                    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
                                        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
                                        <strong id='msginfo'></strong><span id='divMain'></span>
                                    </div>
                                    <input type="hidden" name="program_id" id="program_id" value="<?php echo $program_id; ?>">
                                    <input type="hidden" name="block" id="block" value="<?php echo $block; ?>">
                                    <input type="hidden" name="type" id="type" value="<?php echo $pbu_tag; ?>">
                                    <?php $this->actionChargeGrid($program_id,$block,$pbu_tag); ?>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>