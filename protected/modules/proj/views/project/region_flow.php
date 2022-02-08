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
<input type="hidden" name="Program[]" id="program_id" value="<?php echo $program_id ?>"/>

<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('proj_project', 'project_region'); ?></h3>
        </div>
    </div>
<!--    <div class="row">-->
<!--        <div class="form-group">-->
<!--            <label for="flow_region" class="col-sm-3 control-label padding-lr5"><a href="#" onclick="AddRegion()">--><?php //echo Yii::t('proj_project', 'Add Region'); ?><!--</a></label>-->
<!--        </div>-->
<!--    </div>-->
    <div calss="row">
        <div class="col-md-12">
            <div id="div_region_A">
                <?php
                    echo "<label style='float: left' for='A'>A:</label><a style='margin-left: 6px' onclick='AddA()'>添加</a>";
                    if(!empty($regionlist[A])){
                        foreach($regionlist[A] as $cnt => $region){
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px' name='ProgramRegion[A][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }else{
                        echo"<input  style='margin-left: 13px;margin-bottom: 10px' name='ProgramRegion[A][]' value='' type='text'><a href='#' class='remove'>×</a>";
                    }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-md-12">
            <div id="div_region_B">
                <?php
                echo "<br/>";
                echo "<label style='float: left' for='B'>B:</label><a style='margin-left: 6px' onclick='AddB()'>添加</a>";
                if(!empty($regionlist[B])){
                    foreach($regionlist[B] as $cnt => $region){
                        echo "<input  style='margin-left: 13px;margin-bottom: 10px' name='ProgramRegion[B][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }else{
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px' name='ProgramRegion[B][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_C">
                <?php
                echo "<br/>";
                echo "<label style='float: left' for='C'>C:</label><a style='margin-left: 6px' onclick='AddC()'>添加</a>";
                if(!empty($regionlist[C])){
                    foreach($regionlist[C] as $cnt => $region){
                        echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[C][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }else{
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[C][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_D">
                <?php
                echo "<br/>";
                echo "<label style='float: left' for='D'>D:</label><a style='margin-left: 6px' onclick='AddD()'>添加</a>";
                if(!empty($regionlist[D])){
                    foreach($regionlist[D] as $cnt => $region){
                        echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[D][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }else{
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[D][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_E">
                <?php
                echo "<br/>";
                echo "<label style='float: left' for='E'>E:</label><a style='margin-left: 6px' onclick='AddE()'>添加</a>";
                if(!empty($regionlist[E])){
                    foreach($regionlist[E] as $cnt => $region){
                        echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[E][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }else{
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[E][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_F">
                <?php
                echo "<br/>";
                echo "<label style='float: left' for='F'>F:</label><a style='margin-left: 6px' onclick='AddF()'>添加</a>";
                if(!empty($regionlist[F])){
                    foreach($regionlist[F] as $cnt => $region){
                        echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[F][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }else{
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[F][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_G">
                <?php
                echo "<br/>";
                echo "<label style='float: left' for='G'>G:</label><a style='margin-left: 6px' onclick='AddG()'>添加</a>";
                if(!empty($regionlist[G])){
                    foreach($regionlist[G] as $cnt => $region){
                        echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[G][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }else{
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[G][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_H">
                <?php
                echo "<br/>";
                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>添加</a>";
                if(!empty($regionlist[H])){
                    foreach($regionlist[H] as $cnt => $region){
                        echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[H][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                    }
                }else{
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[H][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="submit" id="sbtn" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
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
        //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    //添加A区二级区域
    var AddA = function () {
//        var n = document.getElementById(cnt_A).value();
//        n = n+1;
//        alert(n);
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[A][]' value='' type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_A").append(html);
    }
    //添加B区二级区域
    var AddB = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[B][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_B").append(html);
    }
    //添加C区二级区域
    var AddC = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px' name='ProgramRegion[C][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_C").append(html);
    }
    //添加D区二级区域
    var AddD = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[D][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_D").append(html);
    }
    //添加E区二级区域
    var AddE = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[E][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_E").append(html);
    }
    //添加F区二级区域
    var AddF = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[F][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_F").append(html);
    }
    //添加G区二级区域
    var AddG = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[G][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_G").append(html);
    }
    //添加H区二级区域
    var AddG = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[H][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_H").append(html);
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
