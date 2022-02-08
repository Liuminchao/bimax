<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($model, 'name'),
    'autoValidation' => true,
    "action" => "javascript:formSubmit1()",
));
$block = $model->block;
$level = $model->level;
$unit = $model->unit;

?>
<div class="container">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div>
        <input type="hidden" name="location[id]" value="<?php echo $id ?>">
        <input type="hidden" id="project_id" name="location[project_id]" value="<?php echo $project_id ?>">
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Block</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control " id="block" name="location[block]"
                   value="<?php echo $block ?>"   placeholder="Block" readonly/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Level</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control " name="location[level]"
                   value="<?php echo $level ?>"    placeholder="Level"/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-2 control-label offset-md-1 label-rignt padding-lr5">Unit</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control " name="location[unit]"
                   value="<?php echo $unit ?>"    placeholder="Unit"/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;text-align: center">
        <div class="col-12">
            <button type="submit" id="sbtn" class="btn btn-primary"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back('<?php echo $project_id ?>','<?php echo $block ?>');"><?php echo Yii::t('common', 'back'); ?></button>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript">

    var n = 4;
    function showTime(flag) {
        if (flag == false)
            return;
        n--;
        $('#divMain').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
        if (n == 0)
            $("#modal-close").click();
        else
            setTimeout('showTime()', 1000);
    }

    var back = function (project_id,block) {
        window.location = "index.php?r=proj/location/locationlist&block="+block+"&program_id="+project_id;
    }

    //提交表单
    var formSubmit1 = function () {
        var params = $('#form1').serialize();
        $.ajax({
            url: "index.php?r=proj/location/setlocation&" + params,
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                
            },
            success: function (data) {
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
                //window.location = "index.php?ctc/handover/recordlist&apply_id=<?php //echo $apply_id ?>//";
                location.reload();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }

</script>