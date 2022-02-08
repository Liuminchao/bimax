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
            <input type="hidden" id="project_id" name="project" value="<?php echo $project_id; ?>">
            <input type="hidden" id="block" name="block" value="<?php echo $block; ?>">
            <input type="hidden" id="pbu_tag" name="pbu_tag" value="<?php echo $pbu_tag; ?>">
            <div class="row">
                <div class="col-5" style="text-align: center">
                    Existing Unit No.
                </div>
                <div class="col-2">

                </div>
                <div class="col-3" style="text-align: center">
                    New Unit No.
                </div>
            </div>
            <?php
                $unit_list = ProgramBlockChart::locationUnit($project_id,$block,$pbu_tag);
                if(count($unit_list)>0){
                    foreach($unit_list as $x => $y){
                        $unit_nos = $y['unit_nos'];
                        echo "<div class='row' style='margin-top: 10px;'>";
                        echo "<div class='col-5' style='text-align: center'>$unit_nos<input type='hidden' class='form-control input-sm' name='Unit_old[]' value='$unit_nos'></div>";
                        echo "<div class='col-2'><img  src='img/right.png' ></div>";
                        echo "<div class='col-3' style='text-align: center'><input type='text' class='form-control input-sm ' style='' name='Unit[]'></div>";
                        echo "</div>";
                    }
                }
            ?>
            <div class="row " style="margin-top: 10px;margin-bottom: 5px;">
                <div class="col-12" style="text-align: center">
                    <button type="button" id="sbtn" class="btn btn-primary" onclick="save_unit()">Save</button>
                    <button type="button" class="btn btn-default" style="margin-left: 10px;" onclick="back();">Back</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    var save_unit = function () {
        url = "index.php?r=task/pbu/saveunit";

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
                window.location = "index.php?r=task/pbu/list&program_id=<?php echo $project_id; ?>&block=<?php echo $block; ?>";
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
