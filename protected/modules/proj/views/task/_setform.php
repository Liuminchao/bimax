<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'focus' => array($rs, 'old_pwd'),
    'autoValidation' => false,
    "action" => "javascript:formSubmit()",
        ));

?>
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
    <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
    <strong id='msginfo'></strong><span id='divMain'></span>
</div>
    <div >
        <input type="hidden" id="program_id" name="TaskUser[program_id]" value="<?php echo "$program_id"; ?>"/>
        </div>
    <div >
        <input type="hidden" id="task_id" name="TaskUser[task_id]" value="<?php echo "$task_id"; ?>"/>
        </div>
<!--    <div >
        <input type="hidden" id="plan_start_time" name="TaskUser[plan_start_time]" value="<?php echo "$plan_start_time"; ?>"/>
        </div>
    <div >
        <input type="hidden" id="plan_end_time" name="TaskUser[plan_end_time]" value="<?php echo "$plan_end_time"; ?>"/>
        </div>-->
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue">
                <?php echo Yii::t('task', 'Assign User'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo Yii::t('proj_project_user', 'select_cnt'); ?>:   <span id="count_all"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo Yii::t('proj_project_user', 'prc_cnt'); ?>:  <span id="count_prc"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                <?php echo Yii::t('proj_project_user', 'nts_cnt'); ?>:  <span id="count_nts"></span>
            </h3>
        </div>
    </div>
<!--    <div class="row">
        <div class="form-group">
            <label for="work_date" class="col-sm-2 control-label padding-lr5"><?php //echo $model->getAttributeLabel('work_date'); ?></label>
            <div class="col-sm-3 padding-lr5">
                <?php
                   // echo $form->activeTextField($model, 'work_date', array('id' => 'work_date', 'class' =>'form-control b_date', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => ''));
                ?>
            </div>
        </div>
        </div>-->
    <?php foreach ((array)$staff_List['team'] as $team_id => $team_name): ?>
        <div class="row">
            <label for="team_name" class="col-sm-2 control-label padding-lr5"><?php echo $team_name; ?>
</label>
        </div>
        
        <?php foreach ((array)$staff_List['role'][$team_id] as $role_id => $role_name): ?>
        <div class="row">
            <div class="form-group">
                <label for="role_name" class="col-sm-3 control-label padding-lr5"><?php echo $role_name; ?></label>
                
                <div class="col-sm-9 padding-lr5">
                    
                    <label class="checkbox-inline"  style="margin-left:10px;">
                        <input type="checkbox" id="label_<?php echo $role_id;?>" class="select_all_label label_<?php echo $role_id;?>"  value="">&nbsp;<?php echo Yii::t('common', 'select_all');?>
                    </label>
                    
                <?php $cnt = array('PRC'=>0, 'NTS'=>0);
                    foreach((array)$staff_List['staff'][$role_id] as $user_id => $user_name):  ?>
                    <label class="checkbox-inline checkbox_option " style="margin-left:10px;">
                        <input  class="label_<?php echo $role_id;?>" type="checkbox" name="TaskUser[sc_list][]" value="<?php echo $user_id; ?>" <?php if(array_key_exists($user_id, $select_List)) echo 'checked';  ?> nation_type="<?php echo $staff_List['nation'][$user_id]; ?>">&nbsp;<?php echo $user_name; if($staff_List['nation'][$user_id]<>'') echo '('.$staff_List['nation'][$user_id].')';    ?>
                    </label>
                    
                <?php endforeach    ?>
                
                </div>
                
            </div>
        </div>
        
        <?php endforeach;   ?>
        
    <?php endforeach;   ?>
    
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-12">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
     var n = 4;
    function showTime(flag) {
        if (flag == false)
            return;
        n--;
        $('#divMain').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
        if (n == 0)
            $("#modal-close").click();
        else
            setTimeout('showTime()', 1000);
    }
    //var plan_start_time = document.getElementById('plan_start_time').value;
    //var plan_end_time = document.getElementById('plan_end_time').value;
    //提交表单（人员设置）
    var formSubmit = function () {
        
        //var params = $('#form1').serialize();
        //alert("index.php?r=proj/task/tnew&" + params);
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=proj/task/tset",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                //alert(data);
                $('#msgbox').addClass('alert-success');
                $('#msginfo').html(data.msg);
                $('#msgbox').show();
                showTime(data.refresh);
            },
            error: function () {
                //alert('error');
                //alert(data.msg);
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    
    jQuery(document).ready(function () {

        function select_all(obj){
            var id = obj.attr('id'); 
            //alert(id);
            if ($(obj).prop('checked')==true){//alert('true');
                $('.'+id).prop('checked', true);

            }else{//alert('false');
                $('.'+id).prop('checked', false);
            }
            option_count();
        }
        
        $('.select_all_label').click(function(){
            select_all($(this));
        });
        
        
        //人数计算
        option_count(); // 初始化人数计算

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
        //人数计算结束
 })
</script>
