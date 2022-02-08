<div class="card card-info card-outline">
<!--    <div class="card-header">-->
<!--        <div class="row">-->
<!--            <div class="col-9">-->
<!--                <h3 class="box-title">Create Subcon</h3>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="card-body">
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
    'focus' => array($model, 'program_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
echo $form->hiddenField('father_proid', $father_proid);
echo $form->activeHiddenField($model, 'program_id', array());
?>
<!--<div class="box-body">-->
    <div class="row">
        <div class="col-12">
            <h3 class="form-header text-blue" style="padding-left: 50px;" ><?php echo Yii::t('common', 'Base Info'); ?></h3>
        </div>
<!--        <div class="col-12">
            <h2 class="form-header text-red"><?php echo Yii::t('proj_project', 'subcon_alert'); ?></h2>
        </div>-->
    </div>
<!--    <div class="row">
        <div class="form-group">
            <label for="subcon_type" class="col-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('subcon_type'); ?></label>
            <div class="col-5 padding-lr5">
                <?php
                    /*$SubconType = Subcon::subconList();
                    array_unshift($SubconType, Yii::t('proj_project', 'subcon_type'));
                    echo $form->activeDropDownList($model, 'subcon_type',$SubconType ,array('id' => 'subcon_type', 'class' => 'form-control'));*/
                ?>
            </div>
        </div>
    </div>-->
<!--    <div class="row">-->
<!--        <div class="form-group">-->
<!--            <label for="program_name" class="col-2 control-label padding-lr5">--><?php //echo $model->getAttributeLabel('program_name'); ?><!--</label>-->
<!--            <div class="col-5 padding-lr5">-->
<!--                --><?php
//                    echo $form->activeTextField($model, 'program_name', array('id' => 'program_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('proj_project', 'error_proj_name_is_null')));
//                ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="form-group group-space-between">
        <label for="program_content" class="col-2 control-label  offset-md-1 label-rignt"><?php echo $model->getAttributeLabel('program_content'); ?></label>
        <div class="input-group col-8">
            <div class="col-9 padding-lr5">
                <?php
                echo $form->activeTextArea($model, 'program_content', array('id' => 'program_content', 'class' => 'form-control','rows'=>8));
                ?>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-12">
            <h3 class="form-header text-blue" style="padding-left: 50px;"><?php echo Yii::t('proj_project', 'Assign SC'); ?></h3>
        </div>
    </div>

    <div class="form-group group-space-between">
        <label for="program_content" class="col-2 control-label  offset-md-1 label-rignt" ><?php echo $model->getAttributeLabel('subcomp_sn'); ?></label>
        <div class="input-group col-8">
            <div class="col-9 padding-lr5">
                <?php
                    //$compList = Contractor::compList();
                    //echo $form->activeDropDownList($model, 'contractor_id',$compList ,array('id' => 'contractor_id', 'class' => 'form-control', 'check-type' => 'required','required-message'=>Yii::t('proj_project', 'error_proj_name_is_null')));
                    if($_mode_ == 'insert') {
                        echo $form->activeTextField($model, 'subcomp_sn', array('id' => 'subcomp_sn', 'class' => 'form-control', 'check-type' => ''));
                    }else{
                        echo $model->subcomp_sn;
                    }
                ?>
            </div>
            <div class="col-4 padding-lr5">
                <span id="first_msg_subcomp" class="help-block" style="display:none"></span>
            </div>
            <input type="hidden" name="Program[contractor_id]" id="contractor_id" value="<?php echo $model->contractor_id ?>">
        </div>
    </div>

    <div class="form-group group-space-between">
        <label for="program_content" class="col-2 control-label  offset-md-1 label-rignt"><?php echo $model->getAttributeLabel('subcomp_name'); ?></label>
        <div class="input-group col-8">
            <div class="col-9 padding-lr5">
                <?php
                //$compList = Contractor::compList();
                //echo $form->activeDropDownList($model, 'contractor_id',$compList ,array('id' => 'contractor_id', 'class' => 'form-control', 'check-type' => 'required','required-message'=>Yii::t('proj_project', 'error_proj_name_is_null')));
                if($_mode_ == 'insert') {
                    echo $form->activeTextField($model, 'subcomp_name', array('id' => 'subcomp_name', 'class' => 'form-control', 'check-type' => ''));
                }else{
                    echo $model->subcomp_name;
                }
                ?>
            </div>
            <div class="col-4 padding-lr5">
                <span id="second_msg_subcomp" class="help-block" style="display:none"></span>
            </div>
        </div>
    </div>

    <div class="card-footer" align="center">
        <button type="submit" id="sbtn" class="btn btn-primary"><?php echo Yii::t('common', 'button_save'); ?></button>
        <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
    </div>

<!--</div>-->
<?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    //返回
    
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/sublist']; ?>";
    }
    
    jQuery(document).ready(function () {
        
        $('#subcomp_sn').focus(function(){
            $('#sbtn').attr('disabled',true); 
        });
        $('#subcomp_sn').blur(function(){
            //alert($(this).val());
            $.ajax({
                type: "POST",
                url: "index.php?r=comp/info/querysn",
                data: {compsn:$("#subcomp_sn").val()},
                dataType: "json",
                success: function(data){
                    if(data.status==0) {//alert(data.id);
                        $('#first_msg_subcomp').html(data.name).show();
                        $('#contractor_id').val(data.id);
                        $('#sbtn').attr('disabled',false); 
                    }else{
                        $('#first_msg_subcomp').html(data.msg).show();
                        $('#contractor_id').val('');
                        $('#sbtn').attr('disabled',true); 
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    //alert(XMLHttpRequest.status);
                    //alert(XMLHttpRequest.readyState);
                    //alert(textStatus);
                },
            });
        });
        $('#subcomp_name').focus(function(){
            $('#sbtn').attr('disabled',true);
        });
        $('#subcomp_name').blur(function(){
            //alert($(this).val());
            $.ajax({
                type: "POST",
                url: "index.php?r=comp/info/queryname",
                data: {comp_name:$("#subcomp_name").val()},
                dataType: "json",
                success: function(data){
                    if(data.status==0) {//alert(data.id);
                        $('#second_msg_subcomp').html(data.name).show();
                        $('#contractor_id').val(data.id);
                        $('#sbtn').attr('disabled',false);
                    }else{
                        $('#second_msg_subcomp').html(data.msg).show();
                        $('#contractor_id').val('');
                        $('#sbtn').attr('disabled',true);
                    }
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
