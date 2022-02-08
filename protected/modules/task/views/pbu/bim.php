<?php
/* @var $this RoleController */
/* @var $model Role */
/* @var $form CActiveForm */
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => true,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
));
?>
<div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
<div class="row">
    <div class="col-12">
        <div class="card card-info ">
            <div class="row" style="margin-top: 10px;margin-bottom: 20px;">
                <div class="col-12">
                    <div class="form-group">
                        <input type="hidden" id="project_id" name="project" value="<?php echo $project_id; ?>">
                        <input type="hidden" id="pbu_tag" name="pbu_tag" value="<?php echo $pbu_tag; ?>">
                        <label for="user_name" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px">Model</label>
                        <div class="col-4 padding-lr5">
                            <select class="form-control input-sm" name="model" id="model_id" >
                                <?php
                                $model_list = RevitModel::queryList($project_id);
                                if(count($model_list)>0){
                                    foreach ($model_list as $i => $j){
                                        $model_id = $j['model_id'].'_'.$j['version'];
                                        $model_name = $j['model_name'];
                                        echo "<option value='$model_id'>$model_name</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row " style="margin-top: 10px;margin-bottom: 5px;">
                <div class="col-12" style="text-align: center">
                    <button type="button" id="sbtn" class="btn btn-primary" onclick="save_bim()">Done</button>
                    <button type="button" class="btn btn-default" style="margin-left: 10px;" onclick="back();">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    var save_bim = function () {
        url = "index.php?r=task/pbu/savebim";

        $.ajax({
            data: {model_id:$("#model_id").val(),project_id:$("#project_id").val(),pbu_tag:$('#pbu_tag').val()},
            url: url,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                $('#msgbox').addClass('alert-success fa-ban');
                $('#msginfo').html('Operation successful, synchronizing.');
                $('#msgbox').show();
                //window.location = "index.php?r=task/pbu/list&program_id=<?php //echo $project_id; ?>//&block=<?php //echo $block; ?>//";
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    var back = function(){
        //window.location = "index.php?r=task/pbu/pbulist&program_id=<?php //echo $project_id; ?>//";
        window.location = "./?<?php echo Yii::app()->session['list_url']['model/pbulist']; ?>";
    }
</script>