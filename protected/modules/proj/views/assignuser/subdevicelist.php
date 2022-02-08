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
    //查询设备证书
    var itemPhoto = function (id,primary_id) {
        window.location = "index.php?r=device/equipment/attachlist&device_id="+id+"&primary_id="+primary_id+"&type="+'sc';
    }
    //下载设备入场表
    var itemDownload = function(program_id,primary_id){
        window.location = "index.php?r=proj/assignuser/downloadequipment&program_id="+program_id+"&primary_id="+primary_id;
    }
</script>

<div class="row">
    <div class="col-xs-12">

        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('subdevice_toolBox',array('program_id' => $program_id)); ?>
                    <br>
                    <div id="datagrid"><?php $this->actionSubDeviceGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
