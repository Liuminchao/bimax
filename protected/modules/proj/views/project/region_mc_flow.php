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
                //                    echo "<label style='float: left' for='A'>A:</label><a style='margin-left: 6px' onclick='AddA()'>".Yii::t('proj_project', 'add')."</a>";
                if(!empty($regionlist[A])){
                    echo "<input name='ProgramRegion[A][tag]' value=".$regionlist[A]['block']."><a style='margin-left: 6px' onclick='AddA()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[A] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px' name='ProgramRegion[A][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[A][tag]' value='A:'><a style='margin-left: 6px' onclick='AddA()'>".Yii::t('proj_project', 'add')."</a>";
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
                //                echo "<label style='float: left' for='B'>B:</label><a style='margin-left: 6px' onclick='AddB()'>".Yii::t('proj_project', 'add')."</a>";
                if(!empty($regionlist[B])){
                    echo "<input name='ProgramRegion[B][tag]' value=".$regionlist[B]['block']."><a style='margin-left: 6px' onclick='AddB()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[B] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px' name='ProgramRegion[B][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[B][tag]' value='B:'><a style='margin-left: 6px' onclick='AddB()'>".Yii::t('proj_project', 'add')."</a>";
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
                //                echo "<label style='float: left' for='C'>C:</label><a style='margin-left: 6px' onclick='AddC()'>".Yii::t('proj_project', 'add')."</a>";
                if(!empty($regionlist[C])){
                    echo "<input name='ProgramRegion[C][tag]' value=".$regionlist[C]['block']."><a style='margin-left: 6px' onclick='AddC()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[C] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[C][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[C][tag]' value='C:'><a style='margin-left: 6px' onclick='AddC()'>".Yii::t('proj_project', 'add')."</a>";
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
                //                echo "<label style='float: left' for='D'>D:</label><a style='margin-left: 6px' onclick='AddD()'>".Yii::t('proj_project', 'add')."</a>";
                if(!empty($regionlist[D])){
                    echo "<input name='ProgramRegion[D][tag]' value=".$regionlist[D]['block']."><a style='margin-left: 6px' onclick='AddD()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[D] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[D][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[D][tag]' value='D:'><a style='margin-left: 6px' onclick='AddD()'>".Yii::t('proj_project', 'add')."</a>";
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
                //                echo "<label style='float: left' for='E'>E:</label><a style='margin-left: 6px' onclick='AddE()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[E])){
                    echo "<input name='ProgramRegion[E][tag]' value=".$regionlist[E]['block']."><a style='margin-left: 6px' onclick='AddE()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[E] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[E][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[E][tag]' value='E:'><a style='margin-left: 6px' onclick='AddE()'>".Yii::t('proj_project', 'add')."</a>";
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
                //                echo "<label style='float: left' for='F'>F:</label><a style='margin-left: 6px' onclick='AddF()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[F])){
                    echo "<input name='ProgramRegion[F][tag]' value=".$regionlist[F]['block']."><a style='margin-left: 6px' onclick='AddF()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[F] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[F][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[F][tag]' value='F:'><a style='margin-left: 6px' onclick='AddF()'>".Yii::t('proj_project', 'add')."</a>";
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
                //                echo "<label style='float: left' for='G'>G:</label><a style='margin-left: 6px' onclick='AddG()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[G])){
                    echo "<input name='ProgramRegion[G][tag]' value=".$regionlist[G]['block']."><a style='margin-left: 6px' onclick='AddG()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[G] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[G][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[G][tag]' value='G:'><a style='margin-left: 6px' onclick='AddG()'>".Yii::t('proj_project', 'add')."</a>";
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
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[H])){
                    echo "<input name='ProgramRegion[H][tag]' value=".$regionlist[H]['block']."><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[H] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[H][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[H][tag]' value='H:'><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[H][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_I">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[I])){
                    echo "<input name='ProgramRegion[I][tag]' value=".$regionlist[I]['block']."><a style='margin-left: 6px' onclick='AddI()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[I] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[I][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[I][tag]' value='I:'><a style='margin-left: 6px' onclick='AddI()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[I][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_J">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[J])){
                    echo "<input name='ProgramRegion[J][tag]' value=".$regionlist[J]['block']."><a style='margin-left: 6px' onclick='AddJ()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[J] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[J][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[J][tag]' value='J:'><a style='margin-left: 6px' onclick='AddJ()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[J][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_K">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[K])){
                    echo "<input name='ProgramRegion[K][tag]' value=".$regionlist[K]['block']."><a style='margin-left: 6px' onclick='AddK()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[K] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[K][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[K][tag]' value='K:'><a style='margin-left: 6px' onclick='AddK()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[K][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_L">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[L])){
                    echo "<input name='ProgramRegion[L][tag]' value=".$regionlist[L]['block']."><a style='margin-left: 6px' onclick='AddL()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[L] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[L][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[L][tag]' value='L:'><a style='margin-left: 6px' onclick='AddL()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[L][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_M">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[M])){
                    echo "<input name='ProgramRegion[M][tag]' value=".$regionlist[M]['block']."><a style='margin-left: 6px' onclick='AddM()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[M] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[M][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[M][tag]' value='M:'><a style='margin-left: 6px' onclick='AddM()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[M][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_N">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[N])){
                    echo "<input name='ProgramRegion[N][tag]' value=".$regionlist[N]['block']."><a style='margin-left: 6px' onclick='AddN()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[N] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[N][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[N][tag]' value='N:'><a style='margin-left: 6px' onclick='AddN()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[N][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>


    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_O">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[O])){
                    echo "<input name='ProgramRegion[O][tag]' value=".$regionlist[O]['block']."><a style='margin-left: 6px' onclick='AddO()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[O] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[O][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[O][tag]' value='O:'><a style='margin-left: 6px' onclick='AddO()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[O][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_P">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[P])){
                    echo "<input name='ProgramRegion[P][tag]' value=".$regionlist[P]['block']."><a style='margin-left: 6px' onclick='AddP()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[P] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[P][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[P][tag]' value='P:'><a style='margin-left: 6px' onclick='AddP()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[P][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_Q">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[Q])){
                    echo "<input name='ProgramRegion[Q][tag]' value=".$regionlist[Q]['block']."><a style='margin-left: 6px' onclick='AddQ()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[Q] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[Q][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[Q][tag]' value='Q:'><a style='margin-left: 6px' onclick='AddQ()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[Q][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_R">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[R])){
                    echo "<input name='ProgramRegion[R][tag]' value=".$regionlist[R]['block']."><a style='margin-left: 6px' onclick='AddR()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[R] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[R][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[R][tag]' value='R:'><a style='margin-left: 6px' onclick='AddR()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[R][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_S">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[S])){
                    echo "<input name='ProgramRegion[S][tag]' value=".$regionlist[S]['block']."><a style='margin-left: 6px' onclick='AddS()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[S] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[S][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[S][tag]' value='S:'><a style='margin-left: 6px' onclick='AddS()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[S][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_T">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[T])){
                    echo "<input name='ProgramRegion[T][tag]' value=".$regionlist[T]['block']."><a style='margin-left: 6px' onclick='AddT()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[T] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[T][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[T][tag]' value='T:'><a style='margin-left: 6px' onclick='AddT()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[T][]' value='' type='text'><a href='#' class='remove'>×</a>";
                }

                ?>
            </div>
        </div>
    </div>

    <div calss="row">
        <div class="col-sm-12">
            <div id="div_region_U">
                <?php
                echo "<br/>";
                //                echo "<label style='float: left' for='H'>H:</label><a style='margin-left: 6px' onclick='AddH()'>".Yii::t('proj_project', 'add')."</a>";

                if(!empty($regionlist[U])){
                    echo "<input name='ProgramRegion[U][tag]' value=".$regionlist[U]['block']."><a style='margin-left: 6px' onclick='AddU()'>".Yii::t('proj_project', 'add')."</a>";
                    foreach($regionlist[U] as $cnt => $region){
                        if(is_numeric($cnt)) {
                            echo "<input  style='margin-left: 13px;margin-bottom: 10px'  name='ProgramRegion[U][]'  value='{$region}'  type='text'><a href='#' class='remove'>×</a>";
                        }
                    }
                }else{
                    echo "<input name='ProgramRegion[U][tag]' value='U:'><a style='margin-left: 6px' onclick='AddU()'>".Yii::t('proj_project', 'add')."</a>";
                    echo"<input  style='margin-left: 13px;margin-bottom: 10px'   name='ProgramRegion[U][]' value='' type='text'><a href='#' class='remove'>×</a>";
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
    var AddH = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[H][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_H").append(html);
    }
    //添加I区二级区域
    var AddI = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[I][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_I").append(html);
    }
    //添加J区二级区域
    var AddJ = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[J][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_J").append(html);
    }
    //添加K区二级区域
    var AddK = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[K][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_K").append(html);
    }
    //添加L区二级区域
    var AddL = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[L][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_L").append(html);
    }
    //添加M区二级区域
    var AddM = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[M][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_M").append(html);
    }
    //添加N区二级区域
    var AddN = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[N][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_N").append(html);
    }
    //添加O区二级区域
    var AddO = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[O][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_O").append(html);
    }
    //添加P区二级区域
    var AddP = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[P][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_P").append(html);
    }
    //添加Q区二级区域
    var AddQ = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[Q][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_Q").append(html);
    }
    //添加R区二级区域
    var AddR = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[R][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_R").append(html);
    }
    //添加S区二级区域
    var AddS = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[S][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_S").append(html);
    }
    //添加T区二级区域
    var AddT = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[T][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_T").append(html);
    }
    //添加U区二级区域
    var AddU = function () {
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px'  name='ProgramRegion[U][]'  value=''  type='text'><a href='#' class='remove'>×</a>";
        $("#div_region_U").append(html);
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
