<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'program_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
));
echo $form->activeHiddenField($model, 'program_id', array());
?>
<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div>
        <input type="hidden" id="ptw_report" name="Program[ptw_report]">
        <input type="hidden" id="tbm_report" name="Program[tbm_report]">
        <input type="hidden" id="acci_report" name="Program[acci_report]">
        <input type="hidden" id="wsh_report" name="Program[wsh_report]">
        <input type="hidden" id="train_report" name="Program[train_report]">
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('proj_project', 'Base Info'); ?></h3>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="ptw_report" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('ptw_report'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('ptw_report',$params)){
                        $params['ptw_report'] = 'A';
                    }
                }else{
                    $params['ptw_report'] = 'A';
                }

                if($params['ptw_report'] == 'A'){ ?>
                    <input type="radio" id="ptw_report_a" name="ptw_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="ptw_report_b" name="ptw_radio">
                    B(LTA)
                    <input type="radio" id="ptw_report_c" name="ptw_radio">
                    C(Members Signature)
                <?php }else if($params['ptw_report'] == 'B'){ ?>
                    <input type="radio" id="ptw_report_a" name="ptw_radio"  />
                    A(Normal)
                    <input type="radio" id="ptw_report_b" checked="checked" name="ptw_radio">
                    B(LTA)
                    <input type="radio" id="ptw_report_c"  name="ptw_radio">
                    C(Members Signature)
                <?php }else if($params['ptw_report'] == 'C'){ ?>
                    <input type="radio" id="ptw_report_a" name="ptw_radio"  />
                    A(Normal)
                    <input type="radio" id="ptw_report_b"  name="ptw_radio">
                    B(LTA)
                    <input type="radio" id="ptw_report_c" checked="checked" name="ptw_radio">
                    C(Members Signature)
                <?php }  ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="tbm_report" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('tbm_report'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('tbm_report',$params)){
                        $params['tbm_report'] = 'A';
                    }
                }else{
                    $params['tbm_report'] = 'A';
                }

                if($params['tbm_report'] == 'A'){ ?>
                    <input type="radio" id="tbm_report_a" name="tbm_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="tbm_report_b" name="tbm_radio">
                    B(LTA)
                    <input type="radio" id="tbm_report_c" name="tbm_radio">
                    C(Members Signature)
                <?php }else if($params['tbm_report'] == 'B'){ ?>
                    <input type="radio" id="tbm_report_a" name="tbm_radio"  />
                    A(Normal)
                    <input type="radio" id="tbm_report_b" checked="checked" name="tbm_radio">
                    B(LTA)
                    <input type="radio" id="tbm_report_c" name="tbm_radio">
                    C(Members Signature)
                <?php }else{ ?>
                    <input type="radio" id="tbm_report_a" name="tbm_radio"  />
                    A(Normal)
                    <input type="radio" id="tbm_report_b"  name="tbm_radio">
                    B(LTA)
                    <input type="radio" id="tbm_report_c" checked="checked" name="tbm_radio">
                    C(Members Signature)
                <?php } ?>
            </div>
        </div>
    </div>
<!--    <div class="row">-->
<!--        <div class="form-group">-->
<!--            <label for="tbm_report" class="col-sm-2 control-label padding-lr5">--><?php //echo $model->getAttributeLabel('routine_report'); ?><!--</label>-->
<!--            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">-->
<!--                --><?php
//                if($model->params != '0'){
//                    $params = json_decode($model->params,true);
//                    if(!array_key_exists('routine_report',$params)){
//                        $params['routine_report'] = 'A';
//                    }
//                }else{
//                    $params['routine_report'] = 'A';
//                }
//
//                if($params['routine_report'] == 'A'){ ?>
<!--                    <input type="radio" id="routine_report_a" name="tbm_radio" checked="checked"  />-->
<!--                    A(Normal)-->
<!--                    <input type="radio" id="routine_report_b" name="tbm_radio">-->
<!--                    B(Members Signature)-->
<!--                --><?php //}else if($params['routine_report'] == 'B'){ ?>
<!--                    <input type="radio" id="routine_report_a" name="tbm_radio"  />-->
<!--                    A(Normal)-->
<!--                    <input type="radio" id="routine_report_b" checked="checked" name="tbm_radio">-->
<!--                    B(Members Signature)-->
<!--                --><?php //} ?>
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <div class="row">
        <div class="form-group">
            <label for="acci_report" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('acci_report'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('acci_report',$params)){
                        $params['acci_report'] = 'A';
                    }
                }else{
                    $params['acci_report'] = 'A';
                }

                if($params['acci_report'] == 'A'){ ?>
                    <input type="radio" id="acci_report_a" name="acci_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="acci_report_b" name="acci_radio">
                    B(LTA)
                <?php }else{ ?>
                    <input type="radio" id="acci_report_a" name="acci_radio"  />
                    A(Normal)
                    <input type="radio" id="acci_report_b" checked="checked" name="acci_radio">
                    B(LTA)
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="acci_report" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('wsh_report'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('wsh_report',$params)){
                        $params['wsh_report'] = 'A';
                    }
                }else{
                    $params['wsh_report'] = 'A';
                }
                if($params['wsh_report'] == 'A'){ ?>
                    <input type="radio" id="wsh_report_a" name="wsh_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="wsh_report_b" name="wsh_radio">
                    B(LTA)
                <?php }else{ ?>
                    <input type="radio" id="wsh_report_a" name="wsh_radio"  />
                    A(Normal)
                    <input type="radio" id="wsh_report_b" checked="checked" name="wsh_radio">
                    B(LTA)
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="acci_report" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('train_report'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('train_report',$params)){
                        $params['train_report'] = 'A';
                    }
                }else{
                    $params['train_report'] = 'A';
                }

                if($params['train_report'] == 'A'){ ?>
                    <input type="radio" id="train_report_a" name="train_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="train_report_b" name="train_radio">
                    B(LTA)
                <?php }else{ ?>
                    <input type="radio" id="train_report_a" name="train_radio"  />
                    A(Normal)
                    <input type="radio" id="train_report_b" checked="checked" name="train_radio">
                    B(LTA)
                <?php } ?>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-12">
            <button type="button" id="sbtn" onclick="btnsubmit()" class="btn btn-primary btn-lg"  ><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">

    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }

    var btnsubmit = function (){

        var A = 'A';
        var B = 'B';
        var C = 'C';
        if($("#ptw_report_a").prop("checked")){
            $("#ptw_report").val(A);
        }
        if($("#ptw_report_b").prop("checked")){
            $("#ptw_report").val(B);
        }
        if($("#ptw_report_c").prop("checked")){
            $("#ptw_report").val(C);
        }
        if($("#tbm_report_a").prop("checked")){
            $("#tbm_report").val(A);
        }
        if($("#tbm_report_b").prop("checked")){
            $("#tbm_report").val(B);
        }
        if($("#tbm_report_c").prop("checked")){
            $("#tbm_report").val(C);
        }
        if($("#acci_report_a").prop("checked")){
            $("#acci_report").val(A);
        }
        if($("#acci_report_b").prop("checked")){
            $("#acci_report").val(B);
        }
        if($("#wsh_report_a").prop("checked")){
            $("#wsh_report").val(A);
        }
        if($("#wsh_report_b").prop("checked")){
            $("#wsh_report").val(B);
        }
        if($("#routine_report_a").prop("checked")){
            $("#routine_report").val(A);
        }
        if($("#routine_report_b").prop("checked")){
            $("#routine_report").val(B);
        }
        if($("#train_report_a").prop("checked")){
            $("#train_report").val(A);
        }
        if($("#train_report_b").prop("checked")){
            $("#train_report").val(B);
        }

        url = "index.php?r=proj/project/updatereport";

        $.ajax({
            data:$('#form1').serialize(),
            url: url,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();

            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>