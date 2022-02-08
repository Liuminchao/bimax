<?php
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form2',
    'focus' => array($model, 'old_pwd'),
    'autoValidation' => false,
    "action" => "javascript:formSubmit2()",
        ));
//var_dump($name);
//exit;
?>
<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <input type="hidden" name="Operator[operator_id]" value="<?php echo $id; ?>">
    <div class="row">
        <div class="col-md-6">
    <div class="form-group">
        <label for="name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_contractor', 'Contractor_name'); ?></label>
        <div style="padding-top: 6px">
        <?php
            echo $name;
        ?>
        </div>
    </div>
    </div>
        </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="operator_type"
                       class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_contractor', 'reset_type');?></label>
                    <div style="padding-top: 6px">
                    <!--<label><input id="Operator_type_0" type="radio" name="Operator" value="01" />平台登陆密码 </label>
                    <label><input id="Operator_type_1" type="radio" name="Operator" value="02" />FACE登陆密码 </label>-->
                    <?php echo $form->activeRadioButtonList($model, 'operator_type',array('01'=>'平台登录密码','02'=>'FACE登录密码'),array('separator'=>'&nbsp;'), array('id' => 'operator_type','name'=>'type', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => '请选择重置类型')); ?>
                        </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="ajaxReset();"><?php echo Yii::t('common', 'reset'); ?></button>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $("#form1").validation(function (obj, params) {
            if (obj.id == 'pw2' && $("#pw2").val() != $("#pw1").val()) {
                params.err = '<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>';
                params.msg = "<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>";
            }
        });
    });
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
     //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['info/list']; ?>";
        // window.location = "index.php?r=comp/info/list";
    }
    //提交表单
    var ajaxReset = function () {
        var params = $('#form2').serialize();
        $.ajax({
            url: "index.php?r=comp/info/reset&" + params,
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                
            },
            success: function (data) {
                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>