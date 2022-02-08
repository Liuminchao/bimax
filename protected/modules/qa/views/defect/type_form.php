<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($model, 'name'),
    'autoValidation' => true,
    "action" => "javascript:formSubmit1()",
));
$type_1 = $model->type_1;
$type_2 = $model->type_2;
$type_3 = $model->type_3;

?>
<div class="container">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div>
        <input type="hidden" name="defect[id]" value="<?php echo $id ?>">
        <input type="hidden" name="defect[project_id]" value="<?php echo $project_id ?>">
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-3 control-label  label-rignt padding-lr5">Component Group</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control input-sm tool-a-search" name="defect[type_1]"
                   value="<?php echo $type_1 ?>"    placeholder="Type 1"/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-3 control-label  label-rignt padding-lr5">Component</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control input-sm tool-a-search" name="defect[type_2]"
                   value="<?php echo $type_2 ?>"    placeholder="Type 2"/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;">
        <label for="name" class="col-3 control-label  label-rignt padding-lr5">Defect Description</label>
        <div class="input-group col-7 padding-lr5">
            <input type="text" class="form-control input-sm tool-a-search" name="defect[type_3]"
                   value="<?php echo $type_3 ?>"    placeholder="Type 3"/>
        </div>
    </div>

    <div class="form-group" style="margin-top: 10px;text-align: center">
        <div class="col-12">
            <button type="submit" id="sbtn" class="btn btn-primary"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'back'); ?></button>
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

    var back = function (id) {
        // window.location = "index.php?r=qa/defect/typelist&project_id=" + id;
        window.location = "index.php?r=qa/defect/typelist&program_id="+id+"&curpage="+<?php echo $_SESSION['defect_type_page'] ?>;
    }

    //提交表单
    var formSubmit1 = function () {
        var params = $('#form1').serialize();
        $.ajax({
            url: "index.php?r=qa/defect/settype&" + params,
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
     //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['qa/defect/typelist']; ?>";
    }
</script>