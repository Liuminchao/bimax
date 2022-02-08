<script type="text/javascript">
    $(function(){
        itemQuery();
    });
    //查询
    var itemQuery = function() {
        var start_date = $("#q_start_date").val();
        var end_date = $("#q_end_date").val();
        if(start_date != '' && end_date == ''){
            alert('<?php echo Yii::t('license_licensepdf','time_period') ?>');
        }
        if(start_date == '' && end_date != ''){
            alert('<?php echo Yii::t('license_licensepdf','time_period') ?>');
        }
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
    //添加记录
    var itemAdd = function(program_id,type) {
        window.location = "index.php?r=rf/rf/addrfichat&program_id="+program_id+"&type="+type;
    }
    //编辑
    var itemEdit = function(check_id) {
        window.location = "index.php?r=rf/rf/editchat&check_id="+check_id;
    }
    //预览
    var itemPreview = function (check_id,program_id) {
        window.location = "index.php?r=rf/rf/info&check_id="+check_id+"&program_id="+program_id;
    }
   //下载
    var itemDownload = function(id) {
//        window.location = "index.php?r=rf/rf/downloadpdf&check_id="+id;
        window.open("index.php?r=rf/rf/downloadpdf&check_id="+id);
    }
    //添加附件
    var itemAttachment = function(id) {
        var modal = new TBModal();
//        modal.title = "["+app_id+"] <?php //echo Yii::t('sys_workflow', 'Approval Process'); ?>//";
        modal.title = 'Synchronous Attachment';
        modal.url = "index.php?r=rf/rf/attachment&check_id="+id;
        modal.modal();
    }
    //详情
    var itemDetail= function(id,app_id) {
        window.location = "index.php?r=license/licensepdf/detail&apply_id="+id+"&app_id="+app_id;
    }
</script>

<!--<div class="row" >-->
<!--    <div class="col-12" >-->
<!--        <div class="card card-primary card-outline card-outline-tabs">-->
<!--            <div role="grid"  id="--><?php //echo $this->gridId; ?><!--_wrapper">-->
<!--                <div class="card-header p-0 border-bottom-0">-->
<!--                    <div class="row" >-->
<!--                        <div class="col-xs-12">-->
<!--                            <div class="dataTables_length">-->
<!---->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="card-header">-->
<!--                    --><?php //$this->renderPartial('_toolBox',array('program_id'=>$program_id,'type_id'=>$type_id,'status'=>$status,'args'=>$args)); ?>
<!--                </div>-->
<!--                <div class="card-body">-->
<!--                    <div id="datagrid">--><?php //$this->actionGrid($program_id,$type_id,$status); ?><!--</div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</div>-->

<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <?php
                    if($status=='-1'){
                        ?>
                        <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>&type_id=<?php echo $type_id; ?>" >Records</a></li>
                        <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>&type_id=<?php echo $type_id; ?>&status=-1" >Draft</a></li>
                        <?php
                    }else{
                        ?>
                        <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>&type_id=<?php echo $type_id; ?>" >Records</a></li>
                        <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>&type_id=<?php echo $type_id; ?>&status=-1" >Draft</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>

            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper " id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('program_id'=>$program_id,'type_id'=>$type_id,'status'=>$status,'args'=>$args)); ?>
                    <div id="datagrid"><?php $this->actionGrid($program_id,$type_id,$status); ?></div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

