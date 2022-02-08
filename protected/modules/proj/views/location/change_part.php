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
        <div class="card card-info " id="part_info">
            <div class="row" style="margin-top: 10px;">
                <div class="col-8" style="text-align: center">
                </div>
                <div class="col-2" style="text-align: center" >
                </div>
                <div class="col-2" style="text-align: center" >
                    <input type="hidden" id="block" name="block" value="<?php echo $block; ?>">
                    <input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id; ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-4" style="text-align: center">
                    Level
                </div>
                <div class="col-4" style="text-align: center">
                    Typical Level
                </div>
                <div class="col-4" style="text-align: center">
                    Non-typical Level
                </div>
            </div>
            <?php
                $level_list = ProgramLocation::locationLevel($project_id,$block);
                foreach ($level_list as $index => $value){
                    $level = $value['secondary_region'];
                    $type = $value['type'];
                    echo "<div class='row' style='margin-top: 5px;margin-bottom: 5px;'>";
                    echo "<div class='col-4' style='text-align: center'>$level</div>";
                    if($type == '0'){
                        echo "<div class='col-4' style='text-align: center'><input type='radio' name='level[$level]' value='1'></div>";
                        echo "<div class='col-4' style='text-align: center'><input type='radio' name='level[$level]' value='0' checked></div>";
                    }else if($type == '1'){
                        echo "<div class='col-4' style='text-align: center'><input type='radio' name='level[$level]' value='1' checked></div>";
                        echo "<div class='col-4' style='text-align: center'><input type='radio' name='level[$level]' value='0'></div>";
                    }
                    echo "</div>";
                }
            ?>
            <div class="row " style="margin-top: 10px;margin-bottom: 5px;">
                <div class="col-12" style="text-align: center">
                    <button type="button" id="sbtn" class="btn btn-primary" onclick="save_part()">Save</button>
                    <button type="button" class="btn btn-default" style="margin-left: 10px;" onclick="back();">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    var save_part = function () {
        url = "index.php?r=proj/location/savepart";

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
                window.location = "index.php?r=proj/location/locationlist&program_id=<?php echo $project_id; ?>&block=<?php echo $block; ?>";
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    var back = function(){
        window.location = "index.php?r=task/pbu/list&program_id=<?php echo $project_id; ?>&block=<?php echo $block; ?>";
    }
</script>
