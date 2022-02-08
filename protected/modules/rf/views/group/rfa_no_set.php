<style type="text/css">
    input{
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
</style>
<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */

if (Yii::app()->user->hasFlash('success')) {
    $msg['msg'] = Yii::t('common','success_insert');
    $msg['status'] = 1;
    $msg['refresh'] = true;
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          ";
}
if (Yii::app()->user->hasFlash('error')) {
    $msg['status'] = -1;
    $msg['msg'] = Yii::t('common','error_insert');
    $msg['refresh'] = false;
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'program_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
));
echo $form->activeHiddenField($model, 'program_id', array());
//var_dump($regionlist);
?>
<input type="hidden" name="rf[program_id]" id="program_id" value="<?php echo $program_id ?>"/>
<input type="hidden" name="rf[type]" id="type" value="1"/>
<div class="row">
    <div class="col-12" style="margin-top:20px;margin-left: 10px;">
        <label style='text-align: left' class='col-4 control-label'><h3 class="text-blue">RFA Reference Number Settings</h3></label>
    </div>
</div>

<div calss="row">
    <div class="col-12" style="margin-left: 15px;">
        <div id="rfa_region_A">
            <?php
            echo "<label style='text-align: left' for='A' class='col-3 control-label'>Company Name Short Form </label>";
            if(!empty($rfa_regionlist[A])){
                echo "<a  onclick='AddRfaA()'>".Yii::t('proj_project', 'add')."</a>";
                foreach($rfa_regionlist[A] as $cnt => $region){
                    if(is_numeric($cnt)) {
                        echo "<input class='' style='margin-top: 5px;margin-left: 6px' name='rfa[A][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }
            }else{
                echo "<a  onclick='AddRfaA()'>".Yii::t('proj_project', 'add')."</a>";
                echo"<input class='' style='margin-top: 5px;margin-left: 6px' name='rfa[A][]' value='' type='text'><a href='#' class='remove'>×</a>";
            }

            ?>
        </div>
    </div>
</div>


<div calss="row">
    <div class="col-12" style="margin-left: 15px;">
        <div id="rfa_region_B">
            <?php
            echo "<br/>";
            echo "<label style='text-align: left' for='B' class='col-3 control-label'>Project Site Short Form </label>";
            if(!empty($rfa_regionlist[B])){
                echo "<a  onclick='AddRfaB()'>".Yii::t('proj_project', 'add')."</a>";
                foreach($rfa_regionlist[B] as $cnt => $region){
                    if(is_numeric($cnt)) {
                        echo "<input class='' style='margin-top: 5px;margin-left: 6px;' name='rfa[B][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }
            }else{
                echo "<a  onclick='AddRfaB()'>".Yii::t('proj_project', 'add')."</a>";
                echo"<input class='' style='margin-top: 5px;margin-left: 6px;' name='rfa[B][]' value='' type='text'><a href='#' class='remove'>×</a>";
            }

            ?>
        </div>
    </div>
</div>

<div calss="row">
    <div class="col-12" style="margin-left: 15px;">
        <div id="rfa_region_C">
            <?php
            echo "<br/>";
            echo "<label style='text-align: left' for='C' class='col-3 control-label'>Discipline Short Form </label>";
            if(!empty($rfa_regionlist[C])){
                echo "<a  onclick='AddRfaC()'>".Yii::t('proj_project', 'add')."</a>";
                foreach($rfa_regionlist[C] as $cnt => $region){
                    if(is_numeric($cnt)) {
                        echo "<input class='' style='margin-top: 5px;margin-left: 6px;'  name='rfa[C][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }
            }else{
                echo "<a  onclick='AddRfaC()'>".Yii::t('proj_project', 'add')."</a>";
                echo"<input class='' style='margin-top: 5px;margin-left: 6px;'  name='rfa[C][]' value='' type='text'><a href='#' class='remove'>×</a>";
            }

            ?>
        </div>
    </div>
</div>

<div calss="row">
    <div class="col-12" style="margin-left: 15px;">
        <div id="rfa_region_D">
            <?php
            echo "<br/>";
            echo "<label style='text-align: left' for='D' class='col-3 control-label'>Type Name Short Form </label>";
            if(!empty($rfa_regionlist[D])){
                echo "<a  onclick='AddRfaD()'>".Yii::t('proj_project', 'add')."</a>";
                foreach($rfa_regionlist[D] as $cnt => $region){
                    if(is_numeric($cnt)) {
                        echo "<input class='' style='margin-top: 5px;margin-left: 6px;'  name='rfa[D][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }
            }else{
                echo "<a  onclick='AddRfaD()'>".Yii::t('proj_project', 'add')."</a>";
                echo"<input class='' style='margin-top: 5px;margin-left: 6px;'  name='rfa[D][]' value='' type='text'><a href='#' class='remove'>×</a>";
            }

            ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12" style="margin-top:20px;margin-left: 10px;">
        
        <label style='text-align: left' class='col-4 control-label'><h3 class="text-blue">RFI Reference Number Settings</h3></label>
    </div>
</div>

<div calss="row">
    <div class="col-12" style="margin-left: 15px;">
        <div id="rfi_region_A">
            <?php
            echo "<label style='text-align: left' for='A' class='col-3 control-label'>Company Name Short Form </label>";
            if(!empty($rfi_regionlist[A])){
                echo "<a  onclick='AddRfiA()'>".Yii::t('proj_project', 'add')."</a>";
                foreach($rfi_regionlist[A] as $cnt => $region){
                    if(is_numeric($cnt)) {
                        echo "<input class='' style='margin-top: 5px;margin-left: 6px' name='rfi[A][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }
            }else{
                echo "<a  onclick='AddRfiA()'>".Yii::t('proj_project', 'add')."</a>";
                echo"<input class='' style='margin-top: 5px;margin-left: 6px' name='rfi[A][]' value='' type='text'><a href='#' class='remove'>×</a>";
            }

            ?>
        </div>
    </div>
</div>


<div calss="row">
    <div class="col-12" style="margin-left: 15px;">
        <div id="rfi_region_B">
            <?php
            echo "<br/>";
            echo "<label style='text-align: left' for='B' class='col-3 control-label'>Project Site Short Form </label>";
            if(!empty($rfi_regionlist[B])){
                echo "<a  onclick='AddRfiB()'>".Yii::t('proj_project', 'add')."</a>";
                foreach($rfi_regionlist[B] as $cnt => $region){
                    if(is_numeric($cnt)) {
                        echo "<input class='' style='margin-top: 5px;margin-left: 6px;' name='rfi[B][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }
            }else{
                echo "<a  onclick='AddRfiB()'>".Yii::t('proj_project', 'add')."</a>";
                echo"<input class='' style='margin-top: 5px;margin-left: 6px;' name='rfi[B][]' value='' type='text'><a href='#' class='remove'>×</a>";
            }

            ?>
        </div>
    </div>
</div>

<div calss="row">
    <div class="col-12" style="margin-left: 15px;">
        <div id="rfi_region_C">
            <?php
            echo "<br/>";
            echo "<label style='text-align: left' for='C' class='col-3 control-label'>Discipline Short Form </label>";
            if(!empty($rfi_regionlist[C])){
                echo "<a  onclick='AddRfiC()'>".Yii::t('proj_project', 'add')."</a>";
                foreach($rfi_regionlist[C] as $cnt => $region){
                    if(is_numeric($cnt)) {
                        echo "<input class='' style='margin-top: 5px;margin-left: 6px;'  name='rfi[C][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }
            }else{
                echo "<a  onclick='AddRfiC()'>".Yii::t('proj_project', 'add')."</a>";
                echo"<input class='' style='margin-top: 5px;margin-left: 6px;'  name='rfi[C][]' value='' type='text'><a href='#' class='remove'>×</a>";
            }

            ?>
        </div>
    </div>
</div>

<div calss="row">
    <div class="col-12" style="margin-left: 15px;">
        <div id="rfi_region_D">
            <?php
            echo "<br/>";
            echo "<label style='text-align: left' for='D' class='col-3 control-label'>Type Name Short Form </label>";
            if(!empty($rfi_regionlist[D])){
                echo "<a  onclick='AddRfiD()'>".Yii::t('proj_project', 'add')."</a>";
                foreach($rfi_regionlist[D] as $cnt => $region){
                    if(is_numeric($cnt)) {
                        echo "<input class='' style='margin-top: 5px;margin-left: 6px;'  name='rfi[D][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }
            }else{
                echo "<a  onclick='AddRfiD()'>".Yii::t('proj_project', 'add')."</a>";
                echo"<input class='' style='margin-top: 5px;margin-left: 6px;'  name='rfi[D][]' value='' type='text'><a href='#' class='remove'>×</a>";
            }

            ?>
        </div>
    </div>
</div>

<?php
$pro_model = Program::model()->findByPk($self_program_id);
$root_proid = $pro_model->root_proid;
$root_model = Program::model()->findByPk($root_proid);
$user_phone = Yii::app()->user->id;
$user = Staff::userByPhone($user_phone);
$user_model = Staff::model()->findByPk($user[0]['user_id']);
$user_contractor_id = $user_model->contractor_id;
$contractor_id = $root_model->contractor_id;
if($contractor_id == $user_contractor_id){
    ?>
    <div class="row" style="margin-top: 30px;">
        <button type="button" class="btn btn-primary" style="display:block;margin:0 auto"  onclick="save_test();"><?php echo Yii::t('common', 'button_save'); ?></button>
    </div>
    <?php
}
?>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("body").on("click",".removeclass", function(e){ //user click on remove text
            $(this).parent('div').remove(); //remove text box
        })
        $("body").on("click",".remove", function(e){ //user click on remove text
            $(this).prev().remove();//remove text box
            $(this).remove();
        })
    })
    //测试
    var save_test = function () {

        $.ajax({
            data:$('#form1').serialize(),
            url: "index.php?r=rf/group/setregion",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    alert('<?php echo Yii::t('common','success_submit'); ?>');
                }else{
                    alert(data.msg);
                }

            },
            error: function () {
//                $('#msgbox').addClass('alert-danger fa-ban');
//                $('#msginfo').html('系统错误');
//                $('#msgbox').show();
                alert('System Error!');
            }
        });
    }
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    //添加A区二级区域
    var AddRfaA = function () {
        var html = "<input class='' style='margin-left: 11px;margin-bottom: 10px'  name='rfa[A][]' value='' type='text'><a href='#' class='remove'>×</a>";
        $("#rfa_region_A").append(html);
    }
    //添加B区二级区域
    var AddRfaB = function () {
        var html = "<input class='' style='margin-left: 11px;margin-bottom: 10px'  name='rfa[B][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#rfa_region_B").append(html);
    }
    //添加C区二级区域
    var AddRfaC = function () {
        var html = "<input class='' style='margin-left: 11px;margin-bottom: 10px' name='rfa[C][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#rfa_region_C").append(html);
    }
    //添加D区二级区域
    var AddRfaD = function () {
        var html = "<input class='' style='margin-left: 11px;margin-bottom: 10px'  name='rfa[D][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#rfa_region_D").append(html);
    }

    //添加A区二级区域
    var AddRfiA = function () {
        var html = "<input class='' style='margin-left: 11px;margin-bottom: 10px'  name='rfi[A][]' value='' type='text'><a href='#' class='remove'>×</a>";
        $("#rfi_region_A").append(html);
    }
    //添加B区二级区域
    var AddRfiB = function () {
        var html = "<input class='' style='margin-left: 11px;margin-bottom: 10px'  name='rfi[B][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#rfi_region_B").append(html);
    }
    //添加C区二级区域
    var AddRfiC = function () {
        var html = "<input class='' style='margin-left: 11px;margin-bottom: 10px' name='rfi[C][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#rfi_region_C").append(html);
    }
    //添加D区二级区域
    var AddRfiD = function () {
        var html = "<input class='' style='margin-left: 11px;margin-bottom: 10px'  name='rfi[D][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#rfi_region_D").append(html);
    }

    //添加区域
    var AddRegion = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('proj_project', 'Add Region'); ?>";
        modal.url = "index.php?r=proj/project/selectregion";
        modal.modal();
    }
    //删除节点
    var DelRegion = function (cnt) {
        var block = String.fromCharCode(cnt);
        $("#div_region_" + block).remove();
    }
</script>
