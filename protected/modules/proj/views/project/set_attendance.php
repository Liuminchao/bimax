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
<div id='body' class="box-body">
    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>
    <div>
        <input type="hidden" id="start_sign" name="Program[start_sign]" />
        <input type="hidden" id="tbm_sign" name="Program[tbm_sign]">
        <input type="hidden" id="program_id" name="Program[program_id]"  value="<?php echo "$program_id"; ?>"/>
        <input type="hidden" id="type" name="Program[TYPE]" value="<?php echo "$ptype"; ?>"/>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="program_content" class="col-sm-2 control-label padding-lr5"><?php echo $model->getAttributeLabel('way_attendance'); ?></label>
            <div class="col-sm-5 padding-lr5" style="padding-top: 6px">
                <?php if($model->start_sign == '1'){ ?>
                    <input type="radio" id="face" name="radio" checked="checked" />
                    <?php echo $model->getAttributeLabel('start_face'); ?>
                    <input type="radio" id="app" name="radio">
                    <?php echo $model->getAttributeLabel('close_face'); ?>
<!--                    <input type="radio" id="attendance" name="radio">-->
<!--                    --><?php //echo $model->getAttributeLabel('start_attendance'); ?>
                <?php }else if($model->start_sign == '0'){ ?>
                    <input type="radio" id="face" name="radio" onclick="add()" />
                    <?php echo $model->getAttributeLabel('start_face'); ?>
                    <input type="radio" id="app" checked="checked" name="radio">
                    <?php echo $model->getAttributeLabel('close_face'); ?>
<!--                    <input type="radio" id="attendance" name="radio">-->
<!--                    --><?php //echo $model->getAttributeLabel('start_attendance'); ?>
                <?php }else if($model->start_sign == '2') { ?>
                    <input type="radio" id="face"  name="radio" onclick="add()" />
                    <?php echo $model->getAttributeLabel('start_face'); ?>
                    <input type="radio" id="app" name="radio">
                    <?php echo $model->getAttributeLabel('close_face'); ?>
<!--                    <input type="radio" id="attendance" checked="checked" name="radio">-->
<!--                    --><?php //echo $model->getAttributeLabel('start_attendance'); ?>
                <?php }else{  ?>
                    <input type="radio" name="radio" id="face" onclick="add()"/><?php echo $model->getAttributeLabel('start_face'); ?>
                    <input type="radio" name="radio" id="app" ><?php echo $model->getAttributeLabel('close_face'); ?>
<!--                    <input type="radio" name="radio" id="attendance" >--><?php //echo $model->getAttributeLabel('start_attendance'); ?>
                <?php } ?>
            </div>
        </div>
    </div>
<div class="row" id="face_content">
    <div class="form-group">
        <div class="col-sm-5 padding-lr5" style="padding-left: 160px">
            <?php
//                if($model->start_sign == '1') {
//                    $tag = Yii::t('proj_project', 'update_faceset');
//                    echo "<a href='javascript:void(0)' onclick='updatefaceset(\"{$program_id}\")'>$tag</a>";
//                }
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10 padding-lr5" style="padding-left: 160px">
            <?php
                $tbm_tag = Yii::t('proj_project', 'tbm_sign');
            if($model->start_sign == '1') {
                if ($model->faceapp_sign == '1') {
                    echo "$tbm_tag   <input type='checkbox' name='checkbox' id='tbmsign' checked='checked'>";
                } else {
                    echo "$tbm_tag   <input type='checkbox' name='checkbox' id='tbmsign'>";
                }
            }
            ?>
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
</div>
<?php $this->endWidget(); ?>
<script src="js/loading.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

    });
    //联动
    var add =function () {
        $('#face_content').empty();
        var program_id = $("#program_id").val();
        var type = $("#type").val();
//        var update_sign = "<div class='form-group'> <div class='col-sm-5 padding-lr5' style='padding-left: 160px'><a href='javascript:void(0)' onclick='updatefaceset(\""+program_id+"\",\""+type+"\")'><?php //echo Yii::t('proj_project', 'update_faceset'); ?>//</a> </div> </div>";
        var tbm_sign = "<div class='form-group'> <div class='col-sm-10 padding-lr5' style='padding-left: 160px'><?php echo Yii::t('proj_project', 'tbm_sign'); ?>&nbsp;&nbsp;&nbsp;<input type='checkbox' name='checkbox' id='tbmsign'></div> </div>";
//        $('#face_content').append(update_sign);
        $('#face_content').append(tbm_sign);
    }
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    var per_cnt = 3;
    //更新飞搜
    var updatefaceset = function (id) {
         $.ajax({
            type: "POST",
            url: "index.php?r=proj/project/updatefaceset",
            data: {program_id:id},
            dataType: "json",
            beforeSend: function () {
//                addcloud(); //为页面添加遮罩
            },
            success: function(data){
                var add_count = data.add_count;
                var del_count = data.del_count;
                var faceset_id = data.faceset_id;
                var start_cnt = data.start_cnt;
                addfaceset(id,faceset_id,add_count,del_count,start_cnt);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
    }
    function addfaceset(id,faceset_id,add_count,del_count,start_cnt){
        $.ajax({
            type: "POST",
            url: "index.php?r=proj/project/addfaceset",
            data: {program_id:id,faceset_id:faceset_id,cnt:add_count,start_cnt:start_cnt},
            dataType: "json",
            beforeSend: function () {
            },
            success: function(data){
//                if(data.status==1) {
//                    removecloud();//去遮罩
//                    alert('<?php //echo Yii::t('common', 'success_update'); ?>//');
//                }else if(data.status==-2){
//                    removecloud();//去遮罩
//                    alert('<?php //echo Yii::t('common', 'attendance_error'); ?>//');
//                } else{
//                    removecloud();//去遮罩
//                    alert('<?php //echo Yii::t('common', 'error_update'); ?>//');
//                }
                var startcnt = start_cnt+per_cnt;
                if (add_count > startcnt) {
                    addfaceset(id,faceset_id,add_count,del_count,startcnt);
                }else{
                    delfaceset(id,faceset_id,del_count,start_cnt)
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
    }
    function delfaceset(id,faceset_id,del_count,start_cnt){
        $.ajax({
            type: "POST",
            url: "index.php?r=proj/project/deletefaceset",
            data: {program_id:id,faceset_id:faceset_id,cnt:del_count,start_cnt:start_cnt},
            dataType: "json",
            beforeSend: function () {
            },
            success: function(data){
//                if(data.status==1) {
//                    removecloud();//去遮罩
//                    alert('<?php //echo Yii::t('common', 'success_update'); ?>//');
//                }else if(data.status==-2){
//                    removecloud();//去遮罩
//                    alert('<?php //echo Yii::t('common', 'attendance_error'); ?>//');
//                } else{
//                    removecloud();//去遮罩
//                    alert('<?php //echo Yii::t('common', 'error_update'); ?>//');
//                }
                var startcnt = start_cnt+per_cnt;
                if (del_count > startcnt) {
                    removecloud();//去遮罩
                    delfaceset(id,faceset_id,startcnt,del_count);
                }else{
                    removecloud();//去遮罩
                    alert('<?php echo Yii::t('common', 'success_update'); ?>');
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                //alert(XMLHttpRequest.status);
                //alert(XMLHttpRequest.readyState);
                alert(textStatus);
            },
        });
    }
    function btnsubmit(){
        if($("#face").prop("checked")){
            $("#start_sign").val(1);
        }
        if($("#app").prop("checked")){
            $("#start_sign").val(0);
        }
        if($("#attendance").prop("checked")){
            $("#start_sign").val(2);
        }
        if($("#tbmsign").prop("checked")){
            $("#tbm_sign").val(1);
        }else{
            $("#tbm_sign").val(0);
        }
//        if($("#ind_no").prop("checked")){
//            $("#independent").val(0);
//        }
//        if($("#ind_yes").prop("checked")){
//            $("#independent").val(1);
//        }
        var mode = $("#_mode_").val();

        url = "index.php?r=proj/project/updateattendance";

        $.ajax({
            data:$('#form1').serialize(),
            url: url,
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                removecloud();//去遮罩
                alert('<?php echo Yii::t('common', 'success_update'); ?>');
//                if(data.status == 1){
//                    facesubmit();
//                }else{
//                    $('#msgbox').addClass('alert-success fa-ban');
//                    $('#msginfo').html(data.msg);
//                    $('#msgbox').show();
//                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    function facesubmit() {
        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=proj/project/setfaceset",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    var program_id = data.program_id;
                    var faceset_id = data.faceset_id;
                    var start_cnt = 0;
                    var cnt = data.cnt;
                    editfaceset(program_id, faceset_id, start_cnt, cnt);
                }else if(data.status == 2){
                    var program_id = $("#program_id").val();
                    updatefaceset(program_id);
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }

    function editfaceset(program_id,faceset_id,start_cnt,cnt){

        url = "index.php?r=proj/project/editfaceset";

        $.ajax({
            data: {program_id:program_id, faceset_id: faceset_id, start_cnt:start_cnt,cnt:cnt},
            url: url,
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {

                var startcnt = start_cnt+per_cnt;
//                alert(startcnt);
                if (cnt > startcnt) {
                    editfaceset(program_id,faceset_id,startcnt,cnt);
                }else{
                    removecloud();//去遮罩
                    alert('<?php echo Yii::t('common', 'success_update'); ?>');
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
</script>
