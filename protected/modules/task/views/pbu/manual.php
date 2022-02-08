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
                <div class="form-group padding-lr5" style="margin-left:10px;padding-bottom:5px;width: 200px;">
                    <input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id; ?>">
                    <input type="hidden" id="pbu_tag" name="pbu_tag" value="<?php echo $pbu_tag; ?>">
                    <?php
                        if($pbu_tag == '1'){
                    ?>
                            <input type="text" class="form-control input-sm" name="pbu_type" placeholder="PBU Type" style="width: 100%;"  >
                    <?php }else if($pbu_tag == '2'){ ?>
                            <input type="text" class="form-control input-sm" name="pbu_type" placeholder="PPVC Type" style="width: 100%;"  >
                    <?php }else if($pbu_tag == '3'){ ?>
                            <input type="text" class="form-control input-sm" name="pbu_type" placeholder="Precast Type" style="width: 100%;"  >
                    <?php } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-2" style="text-align: center">
                    <label>Block</label>
                </div>
                <div class="col-2" style="text-align: center">
                    <label>Level</label>
                </div>
                <div class="col-8" style="text-align: center">
                    <label>Unit</label>
                </div>
            </div>
            <?php
            $all_list = ProgramBlockChart::detailAllList($project_id,$pbu_tag);
            if(count($all_list)>0){
                $block_bak = '';
                foreach($all_list as $block => $detail_list){
                    foreach($detail_list['level'] as $level => $level_array){
                        echo "<div class='row' style='margin-top: 10px;'>";
                        if($block_bak != $block){
                            $block_bak = $block;
                            echo "<div class='col-2' style='text-align: center'><label>$block</label></div>";
                        }else{
                            echo "<div class='col-2' style='text-align: center'><label></label></div>";
                        }
                        echo "<div class='col-2' style='text-align: center'><label>$level</label></div>";
                        echo "<div class='col-8' style='text-align: center'>";
                        $class = 'block_'.$block.'_'.'level_'.$level;
                        echo "<label class='checkbox-inline select_all_label' id='$class'  style='margin-left:10px;'>
                            <input type='checkbox' >&nbsp;All
                          </label>";
                        foreach($level_array as $x => $y){
//                            $id = $y['id'];
                            $pbu_type_self = $y['pbu_type'];
                            $id = $block.'|'.$level.'|'.$x;
                            echo "<label  class='checkbox-inline checkbox_option $class' style='margin-left:10px;'><input name='Unit[]' type='checkbox' value='$id'>$x</label>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "<hr/>";
                }
            }
            ?>

            <div class="row " style="margin-top: 10px;margin-bottom: 5px;">
                <div class="col-12" style="text-align: center">
                    <button type="button" id="sbtn" class="btn btn-primary" onclick="save_pbutype()">Save</button>
<!--                    <button type="button" class="btn btn-default" style="margin-left: 10px;" onclick="back();">Back</button>-->
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    var save_pbutype = function () {
        url = "index.php?r=task/pbu/savepbutype";

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
                window.location = "index.php?r=task/model/pbulist&program_id=<?php echo $project_id; ?>";
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    var back = function(){
        window.location = "index.php?r=task/pbu/pbulist&program_id=<?php echo $project_id; ?>";
    }

    jQuery(document).ready(function () {

        //全选功能
        $('.select_all_label').find('ins').addClass('select_all_ins');//给全选按钮的选框ins增加class
        function select_all(obj){
            var id = obj.attr('id');
            // alert(id);
            // alert($(obj).find("input:checkbox").prop('checked'));
            if($(obj).find("input:checkbox").prop('checked') == true){
                // alert("勾选");
                $('.'+id).find("input:not(:disabled)").prop('checked',true);
            }
            if($(obj).find("input:checkbox").prop('checked') == false){
                // alert('取消');
                $('.'+id).find('input:not(:disabled)').prop('checked',false);
            }
            // option_count();
        }

        $('.select_all_label').click(function(){
            select_all($(this));
        });

        $('.select_all_ins').click(function(){
            obj = $(this).closest('.select_all_label');
            select_all(obj);
        });
        //全选功能结束

    })
</script>