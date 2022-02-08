<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
    //查询
    var itemQuery = function (id) {
        var user_id = $("#user_id").val();
        window.location = "index.php?r=device/equipment/selfgrid&user_id="+user_id+"&program_id="+id;
    }
</script>
<div>
    <input type="hidden" id="device_id" value="<?php echo $device_id; ?>">
    <input type="hidden" id="primary_id" value="<?php echo $primary_id; ?>">
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('statistics_toolBox', array('device_id'=>$device_id,'primary_id'=>$primary_id)); ?>
                </div>
            </div>
        </div>
    </div>
</div>