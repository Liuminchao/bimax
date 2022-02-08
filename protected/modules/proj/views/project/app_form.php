<?php

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
    'autoValidation' => false,
));
?>
<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div >
        <input type="hidden" id="program_id" name="ProgramApp[program_id]" value="<?php echo "$program_id"; ?>" />
    </div>

    <div class="row">
        <div class="form-group">
            <label for="role_name" class="col-sm-3 control-label padding-lr5"></label>

            <div class="col-sm-9 padding-lr5">


                <?php
                foreach((array)$app_list as $app_id => $app_name):  ?>
                    <label class="checkbox-inline checkbox_option label_<?php echo $app_id;?>" style="margin-left:10px;">
                        <?php if(empty($select_list)){ ?>
                            <input id="select"   type="checkbox" name="ProgramApp[sc_list][]" value="<?php echo $app_id; ?>"  >&nbsp;<?php echo $app_name; ?>
                        <?php }else{ ?>
                            <?php if(array_key_exists($app_id, $select_list)){ ?>
                                <input id="select"  checked="checked"  type="checkbox" name="ProgramApp[sc_list][]" value="<?php echo $app_id; ?>" >&nbsp;<?php echo $app_name;?>
                            <?php }else{ ?>
                                <input id="select"   type="checkbox" name="ProgramApp[sc_list][]" value="<?php echo $app_id; ?>"  >&nbsp;<?php echo $app_name; ?>
                            <?php } ?>
                        <?php } ?>

                    </label>

                <?php endforeach    ?>

            </div>

        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="formSubmit()" ><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back('<?php echo $company_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">

    //返回
    var back = function (company_id) {
        window.location = "index.php?r=proj/project/list&ptype=MC";
    }
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
    //提交表单
    var formSubmit = function () {
        var params = $('#form1').serialize();
        $.ajax({
            url: "index.php?r=proj/project/editapp&" + params,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass(data.class);
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