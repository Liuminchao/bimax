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
        <input type="hidden" id="ptw_mode" name="Program[ptw_mode]">
        <input type="hidden" id="tbm_mode" name="Program[tbm_mode]">
        <input type="hidden" id="acci_mode" name="Program[acci_mode]">
        <input type="hidden" id="ins_mode" name="Program[ins_mode]">
        <input type="hidden" id="train_mode" name="Program[train_mode]">
        <input type="hidden" id="location_require" name="Program[location_require]">
        <input type="hidden" id="tbm_start" name="Program[tbm_start]">
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('proj_project', 'Base Info'); ?></h3>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="ptw_mode" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('ptw_mode'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('ptw_mode',$params)){
                        $params['ptw_mode'] = 'A';
                    }
                }else{
                    $params['ptw_mode'] = 'A';
                }

                if($params['ptw_mode'] == 'A'){ ?>
                    <input type="radio" id="ptw_mode_a" name="ptw_radio" checked="checked"  />
                    A(3+3)
                    <input type="radio" id="ptw_mode_b" name="ptw_radio">
                    B(4+2)
                    <input type="radio" id="ptw_mode_c" name="ptw_radio">
                    C(LTA)
                <?php }else if($params['ptw_mode'] == 'B'){ ?>
                    <input type="radio" id="ptw_mode_a" name="ptw_radio"  />
                    A(3+3)
                    <input type="radio" id="ptw_mode_b" checked="checked" name="ptw_radio">
                    B(4+2)
                    <input type="radio" id="ptw_mode_c"  name="ptw_radio">
                    C(LTA)
                <?php }else if($params['ptw_mode'] == 'C'){ ?>
                    <input type="radio" id="ptw_mode_a" name="ptw_radio"  />
                    A(3+3)
                    <input type="radio" id="ptw_mode_b"  name="ptw_radio">
                    B(4+2)
                    <input type="radio" id="ptw_mode_c" checked="checked" name="ptw_radio">
                    C(LTA)
                <?php }  ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="tbm_mode" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('tbm_mode'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('tbm_mode',$params)){
                        $params['tbm_mode'] = 'A';
                    }
                }else{
                    $params['tbm_mode'] = 'A';
                }

                if($params['tbm_mode'] == 'A'){ ?>
                    <input type="radio" id="tbm_mode_a" name="tbm_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="tbm_mode_b" name="tbm_radio">
                    B(LTA)
                <?php }else if($params['tbm_mode'] == 'B'){ ?>
                    <input type="radio" id="tbm_mode_a" name="tbm_radio"  />
                    A(Normal)
                    <input type="radio" id="tbm_mode_b" checked="checked" name="tbm_radio">
                    B(LTA)
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="acci_mode" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('acci_mode'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('acci_mode',$params)){
                        $params['acci_mode'] = 'A';
                    }
                }else{
                    $params['acci_mode'] = 'A';
                }

                if($params['acci_mode'] == 'A'){ ?>
                    <input type="radio" id="acci_mode_a" name="acci_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="acci_mode_b" name="acci_radio">
                    B(LTA)
                <?php }else{ ?>
                    <input type="radio" id="acci_mode_a" name="acci_radio"  />
                    A(Normal)
                    <input type="radio" id="acci_mode_b" checked="checked" name="acci_radio">
                    B(LTA)
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="acci_mode" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('wsh_mode'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('ins_mode',$params)){
                        $params['ins_mode'] = 'A';
                    }
                }else{
                    $params['ins_mode'] = 'A';
                }
                if($params['ins_mode'] == 'A'){ ?>
                    <input type="radio" id="ins_mode_a" name="ins_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="ins_mode_b" name="ins_radio">
                    B(LTA)
                <?php }else{ ?>
                    <input type="radio" id="ins_mode_a" name="ins_radio"  />
                    A(Normal)
                    <input type="radio" id="ins_mode_b" checked="checked" name="ins_radio">
                    B(LTA)
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="acci_mode" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('train_mode'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    $params = json_decode($model->params,true);
                    if(!array_key_exists('train_mode',$params)){
                        $params['train_mode'] = 'A';
                    }
                }else{
                    $params['train_mode'] = 'A';
                }

                if($params['train_mode'] == 'A'){ ?>
                    <input type="radio" id="train_mode_a" name="train_radio" checked="checked"  />
                    A(Normal)
                    <input type="radio" id="train_mode_b" name="train_radio">
                    B(LTA)
                <?php }else{ ?>
                    <input type="radio" id="train_mode_a" name="train_radio"  />
                    A(Normal)
                    <input type="radio" id="train_mode_b" checked="checked" name="train_radio">
                    B(LTA)
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="ptw_mode" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('location_require'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    if(!array_key_exists('location_require',$params)){
                        $params['location_require'] = '0';
                    }
                }else{
                    $params['location_require'] = '0';
                }

                if($params['location_require'] == '1'){ ?>
                    <input type="radio" id="location_require_no" name="location_require_radio"  />
                    <?php echo $model->getAttributeLabel('location_require_no'); ?>
                    <input type="radio" id="location_require_yes" name="location_require_radio" checked="checked">
                    <?php echo $model->getAttributeLabel('location_require_yes'); ?>
                <?php }else { ?>
                    <input type="radio" id="location_require_no"  checked="checked" name="location_require_radio"  />
                    <?php echo $model->getAttributeLabel('location_require_no'); ?>
                    <input type="radio" id="location_require_yes"  name="location_require_radio">
                    <?php echo $model->getAttributeLabel('location_require_yes'); ?>
                <?php }  ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="ptw_mode" class="col-sm-2 control-label padding-lr5">TBM Link To PTW</label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php
                if($model->params != '0'){
                    if(!array_key_exists('tbm_start',$params)){
                        $params['tbm_start'] = '1';
                    }
                }else{
                    $params['tbm_start'] = '1';
                }
                if($params['tbm_start'] == '1'){ ?>
                    <input type="radio" id="tbm_start_no" name="tbm_start_radio"  />
                    No
                    <input type="radio" id="tbm_start_yes" name="tbm_start_radio" checked="checked">
                    Yes
                <?php }else { ?>
                    <input type="radio" id="tbm_start_no"  checked="checked" name="tbm_start_radio"  />
                    No
                    <input type="radio" id="tbm_start_yes"  name="tbm_start_radio">
                    Yes
                <?php }  ?>
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
        if($("#ptw_mode_a").prop("checked")){
            $("#ptw_mode").val(A);
        }
        if($("#ptw_mode_b").prop("checked")){
            $("#ptw_mode").val(B);
        }
        if($("#ptw_mode_c").prop("checked")){
            $("#ptw_mode").val(C);
        }
        if($("#ptw_mode_d").prop("checked")){
            $("#ptw_mode").val(D);
        }
        if($("#tbm_mode_a").prop("checked")){
            $("#tbm_mode").val(A);
        }
        if($("#tbm_mode_b").prop("checked")){
            $("#tbm_mode").val(B);
        }
        if($("#tbm_mode_c").prop("checked")){
            $("#tbm_mode").val(C);
        }
        if($("#acci_mode_a").prop("checked")){
            $("#acci_mode").val(A);
        }
        if($("#acci_mode_b").prop("checked")){
            $("#acci_mode").val(B);
        }
        if($("#ins_mode_a").prop("checked")){
            $("#ins_mode").val(A);
        }
        if($("#ins_mode_b").prop("checked")){
            $("#ins_mode").val(B);
        }
        if($("#train_mode_a").prop("checked")){
            $("#train_mode").val(A);
        }
        if($("#train_mode_b").prop("checked")){
            $("#train_mode").val(B);
        }
        //location_require_no
        if($("#location_require_no").prop("checked")){
            $("#location_require").val(0);
        }
        if($("#location_require_yes").prop("checked")){
            $("#location_require").val(1);
        }
        //tbm_start_no
        if($("#tbm_start_no").prop("checked")){
            $("#tbm_start").val(0);
        }
        if($("#tbm_start_yes").prop("checked")){
            $("#tbm_start").val(1);
        }

        url = "index.php?r=proj/project/updateparams";

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