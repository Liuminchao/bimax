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
echo $form->activeHiddenField($pro_model, 'program_id', array());
?>
<div class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue">Set Date</h3>
        </div>
    </div>

    <?php
    $start_date = '';
    $end_date ='';
    $params = $pro_model->params;
    if($params != '0'){
        $params = json_decode($params,true);
        if(array_key_exists('start_date',$params)){
            $start_date = Utils::DateToEn($params['start_date']);
        }
        if(array_key_exists('end_date',$params)){
            $end_date = Utils::DateToEn($params['end_date']);
        }
    }
    ?>

    <div class="row">
        <div class="form-group">
            <label for="ptw_mode" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('electronic_contract', 'start_date'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <input type="text" class="form-control input-sm tool-a-search" name="Program[start_date]"
                       value="<?php echo $start_date; ?>" id="start_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="日期"/>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="ptw_mode" class="col-sm-2 control-label padding-lr5"><?php echo Yii::t('electronic_contract', 'end_date'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <input type="text" class="form-control input-sm tool-a-search" name="Program[end_date]"
                       value="<?php echo $end_date; ?>" id="end_date" onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" placeholder="日期"/>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-12">
            <button type="button" id="sbtn" onclick="btnsubmit()" class="btn btn-primary btn-lg"  ><?php echo Yii::t('common', 'button_save'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">

    var btnsubmit = function (){

        url = "index.php?r=sys/main/updateparams";

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
                window.location = "index.php?r=sys/main/list";
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>