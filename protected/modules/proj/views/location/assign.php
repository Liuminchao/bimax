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
            <?php
            $detail_list = ProgramBlockChart::detailList($project_id,$block,$tag);
            if(count($detail_list)>0){
                $index = 0;
                foreach($detail_list as $level => $level_info){
                    $index++;
                    if($index == 1){
                        $level_array = $level_info;
                    }
                }
                $cnt = count($level_array)+1;
            }
            ?>
            <div class="row" style="margin-left: 0px;margin-right: 0px;">
                <input type="hidden" id="project_id" name="project" value="<?php echo $project_id; ?>">
                <input type="hidden" id="block" name="block" value="<?php echo $block; ?>">
                <input type="hidden" id="tag" name="pbu_tag" value="<?php echo $tag; ?>">
                <table frame="void" width="100%">
                    <?php
                        echo "<tr><td width='30%' align='center'><label>Level</label></td><td colspan='$cnt' width='70%' align='center'><label>Unit</label></td></tr>";
                        echo "<tr>";
                        echo "<td></td>";
                        echo "<td></td>";
                        foreach($level_array as $x => $y){
                            echo "<td ><label class='checkbox-inline select_all_label' id='unit_$x'  >
                                <input type='checkbox' >&nbsp;All
                              </label></td>";
                        }
                        echo "</tr>";
                        foreach($detail_list as $level => $level_array){
                            echo "<tr>";
                            echo "<td align='center'><label>$level</label></td>";
                            echo "<td align='center'><label class='checkbox-inline select_all_label' id='level_$level'  >
                                <input type='checkbox' >&nbsp;All
                              </label></td>";
                            foreach($level_array as $x => $y){
//                                $id = $y['id'];
                                $part_self = $y['type'];
                                $id = $level.'|'.$x;
                                if($part_self){
                                    if($part == $part_self){
                                        echo "<td><label class='checkbox-inline checkbox_option level_$level unit_$x' ><input name='Unit[]' type='checkbox' value='$id' checked >$x($part)</label></td>";
                                    }else{
                                        echo "<td><label class='checkbox-inline checkbox_option level_$level unit_$x' ><input name='Unit[]' type='checkbox' value='$id' >$x($part_self)</label></td>";
                                    }
                                }else{
                                    echo "<td><label class='checkbox-inline checkbox_option level_$level unit_$x' ><input name='Unit[]' type='checkbox' value='$id'>$x</label></td>";
                                }
                            }
                            echo "</tr>";
                        }
                    ?>
                </table>
            </div>
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
        url = "index.php?r=task/pbu/savepart";

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