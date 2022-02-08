<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */
if (Yii::app()->user->hasFlash('success')) {
    $tag = Yii::t('common', 'success_apply');
    $status = 1;
    $class = Utils::getMessageType($status);
    $button = Yii::t('common', 'button_back');
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$tag}
              <button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='back(\"{$program_id}\");'>{$button}</button>
          </div>
          ";
}
if (Yii::app()->user->hasFlash('error')) {
    $tag = Yii::t('common', 'error_apply');
    $status = -1;
    $class = Utils::getMessageType($status);
    $button = Yii::t('common', 'button_back');
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$tag}
              <button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='back(\"{$program_id}\");'>{$button}</button>
          </div>
          ";
}

$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'program_id'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
        ));
echo $form->activeHiddenField($model, 'program_id', array());
?>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue">
                <?php echo Yii::t('device', 'Assign Device'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
<!--                --><?php //echo Yii::t('device', 'select_cnt'); ?><!--:   <span id="count_all"></span>&nbsp;&nbsp;-->
                &nbsp;&nbsp;
                <?php echo Yii::t('proj_project_user', 'already_select'); ?>:<input type="checkbox" id="device" disabled="disabled" checked="checked" name="Device[]">&nbsp;&nbsp;&nbsp;&nbsp;
            </h3>
        </div>
    </div>
    <?php foreach ((array)$device_list['type'] as $type_no => $device_type): ?>
        <div class="row">
            <label for="device_type" style="white-space: nowrap; float:left;" class="col-sm-6  padding-lr5"><?php echo $device_type; ?></label>
        </div>
        
        <div class="row">
            <div class="form-group">
                <label for="bootstrap"   class="col-sm-1 control-label padding-lr5"></label>
                <div class="col-sm-6 padding-lr5">
                    
                    <label class="checkbox-inline select_all_label" id="label_<?php echo $type_no;?>"  style="margin-left:10px;">
                        <input  type="checkbox" value="">&nbsp;<?php echo Yii::t('common', 'select_all');?>
                    </label>
                    
                <?php   foreach((array)$device_list['device'][$type_no] as $primary_id => $device_name):  ?>
                    <label class="checkbox-inline checkbox_option label_<?php echo $type_no;?>" style="margin-left:10px;">    
                    <?php if(array_key_exists($primary_id, $select_List)){ ?>
                        <input id="select" checked="checked" disabled="disabled" type="checkbox" name="Device[sc_list][]" value="<?php echo $primary_id; ?>"  > <?php echo $device_name; ?>
                    <?php }else{ ?>
                        <input id="select"   type="checkbox" name="Device[sc_list][]" value="<?php echo $primary_id; ?>"  > <?php echo $device_name; ?>
                    <?php } ?>
                    </label>
                <?php endforeach    ?>
                
                </div>
                
            </div>
        </div>
        
        
    <?php endforeach;   ?>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back(<?php echo $program_id ?>);"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script src="js/jquery-2.1.1.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/AdminLTE/app.js" ></script>
<script type="text/javascript">
    //返回
    var back = function (id) {
        window.location = "index.php?r=proj/assignuser/devicelist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
        //window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    //权限设置
    var itemSet = function (program_id) {
       window.location = "index.php?r=proj/assignuser/authoritylist&program_id=" + program_id;
    }
    
   
    jQuery(document).ready(function () {
        
        //全选功能
        $('.select_all_label').find('ins').addClass('select_all_ins');//给全选按钮的选框ins增加class
        
        function select_all(obj){
            var id = obj.attr('id'); //alert(id); 
            if ($(obj).find('div').attr('aria-checked') == 'true'){//alert('true');
                $('.'+id).find('div').attr('aria-checked', 'true');
                $('.'+id).find('div').addClass('checked');
                $('.'+id).find("input:not(:disabled)").prop('checked',true);

            }
            if ($(obj).find('div').attr('aria-checked') == 'false'){//alert('false');
                $('.'+id).find('div').attr('aria-checked', 'false');
                $('.'+id).find("div[class='icheckbox_minimal checked']").removeClass('checked');
                //$('.'+id).find('input').removeAttr('checked');
                $('.'+id).find('input:not(:disabled)').prop('checked',false);
            }
            option_count();
        }
        
        $('.select_all_label').click(function(){
            select_all($(this));
        });
        
        $('.select_all_ins').click(function(){
            obj = $(this).closest('.select_all_label');
            select_all(obj);
        });
        //全选功能结束
        
        
        //设备数量计算
        option_count(); // 初始化设备数计算
        $('.checkbox_option').find('ins').addClass('checkbox_option');//给选框ins增加计算人数的class
        
        function option_count(){
            cnt_all = $('.checkbox_option').find("input:checkbox:checked").size();
            $('#count_all').html(cnt_all);
            cnt_prc = $('.checkbox_option').find("input:checkbox:checked[nation_type='PRC']").size();
            $('#count_prc').html(cnt_prc);
            cnt_nts = $('.checkbox_option').find("input:checkbox:checked[nation_type='NTS']").size();
            $('#count_nts').html(cnt_nts);
        }
        
        $('.checkbox_option').click(function(){
            option_count();
        });
        //设备数计算结束
 })
</script>
