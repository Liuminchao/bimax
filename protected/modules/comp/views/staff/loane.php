<div class="box box-info">

<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          <script type='text/javascript'>
          /*{$this->gridId}.refresh();*/
          </script>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'contractor_sn'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
echo $form->activeHiddenField($model, 'user_id');
echo $form->HiddenField('type', '0');
?>
<div class="box-body">

    <div class="row">
        <div class="form-group">
            <label for="user_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_staff', 'Secondee'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <span class="help-block"><?php echo $model->user_name;   ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="original_company" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_staff', 'Seconder'); ?></label>
            <div class="col-sm-5 padding-lr5">
                <span class="help-block"><?php echo $comp_model->contractor_name;   ?></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="program_content" class="col-sm-3 control-label padding-lr5"><?php echo  Yii::t('comp_staff', 'Host No')?></label>
            <div class="input-group col-sm-9">
                <div class="col-sm-5 padding-lr5">
                <?php
                    echo $form->activeTextField($model, 'contractor_sn', array('id' => 'contractor_sn', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_contractor', 'Error company_sn is null')));
                ?>
                </div>
                <div class="col-sm-4 padding-lr5">
                    <span id="msg_subcomp" class="help-block" style="display:none"></span>
                </div>
                <input type="hidden" name="Staff[contractor_id]" id="contractor_id">
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('comp_staff', 'Button Apply'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
    }
    
    jQuery(document).ready(function () {
        $('#sbtn').attr('disabled',true);
        
        $('#contractor_sn').focus(function(){
            $('#sbtn').attr('disabled',true); 
        });
        $('#contractor_sn').blur(function(){
            //alert($(this).val());
            $.ajax({
                type: "POST",
                url: "index.php?r=comp/info/querysn",
                data: {compsn:$("#contractor_sn").val()},
                dataType: "json",
                success: function(data){
                    if (data.status != 0){
                        $('#msg_subcomp').html(data.msg).show();
                        $('#contractor_id').val('');
                        $('#sbtn').attr('disabled',true);
                        return;
                    }
                    if (data.id == $('#original_conid').val()) {
                        $('#msg_subcomp').html('<?php echo Yii::t('comp_staff', 'Error loane original comp'); ?>').show();
                        $('#contractor_id').val('');
                        $('#sbtn').attr('disabled',true);
                        return;
                    }
                    
                    $('#msg_subcomp').html(data.name).show();
                    $('#contractor_id').val(data.id);
                    $('#sbtn').attr('disabled',false); 

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert(XMLHttpRequest.status);
                    //alert(XMLHttpRequest.readyState);
                    //alert(textStatus);
                },
            });
        });
    });

</script>

</div>