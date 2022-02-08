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
        //alert(url);
<?php echo $this->gridId; ?>.condition = url;
<?php echo $this->gridId; ?>.refresh();
    }
    //添加记录
    var itemAdd = function(program_id) {
        window.location = "index.php?r=transmittal/trans/new&project_id="+program_id;
    }
    //预览
    var itemPreview = function (check_id,program_id) {
        window.location = "index.php?r=transmittal/trans/info&check_id="+check_id+"&program_id="+program_id;
    }
   //下载
    var itemDownload = function(id) {
//        window.location = "index.php?r=rf/rf/downloadpdf&check_id="+id;
        window.open("index.php?r=transmittal/trans/downloadpdf&check_id="+id);
    }

</script>
<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline">

            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper " id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox',array('program_id'=>$program_id, 'args'=>$args)); ?>
                    <div id="datagrid"><?php $this->actionGrid($program_id); ?></div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>