<link href="css/select2.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .form-group span.required {
        color: #FF0000;
        font-size: 150%;
    }
    .open>.dropdown-menu{
        min-width:400px !important;
        height:300px;
        left:50%;
        top:50%;
        margin-left:-200px;
        margin-top:-150px;
        position:fixed;
    }
    .bootstrap-select .dropdown-menu {
        top: 250px !important;
    }
</style>
<?php
if ($msg) {
    $class = Utils::getMessageType($msg ['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          <script type='text/javascript'>
          /*{$this->gridId}.refresh();*/
          </script>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'face_form',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'focus' => array(
        $model,
        'name'
    ),
    'role' => 'form', // 可省略
    'formClass' => 'form-horizontal', // 可省略 表单对齐样式
    'autoValidation' => true
  ));
$upload_url = Yii::app()->params['upload_url'];
?>

<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline card-outline-tabs">
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" role="tablist" id="myTab">
                    <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=proj/staff/tabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>" ><?php echo Yii::t('comp_staff','Base Info');?></a></li>
                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=proj/staff/pertabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','Personal Info');?></a></li>
                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=proj/staff/instabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','ins');?></a></li>
                </ul>
            </div>

            <div class="card-body">
                <div class="row" style="padding-top:5px">
                    <div class="col-12">
                        <div class="alert alert-info alert-dismissable">
                            <i class="fa fa-exclamation"></i><b> (1) <?php echo Yii::t('common','staff_form_prompt') ?>(2) Please upload photo in .jpg format</b></div>
                    </div>
                </div>
                <div >
                    <input type="hidden" id="user_id" name="Staff[user_id]" value="<?php echo "$user_id"; ?>"/>
                    <input type="hidden" id="program_id" name="Staff[program_id]" value="<?php echo "$program_id"; ?>"/>
                    <input type="hidden" id="app" name="Staff[app]">
                    <input type="hidden" id="web" name="Staff[web]">
                    <input type="hidden" id="face_src" name="File[face_src]" />
                    <input type="hidden" id="filebase64">
                    <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  姓名  -->
                        <div class="form-group">
                            <label for="user_name" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px"><?php echo $model->getAttributeLabel('user_name'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($model, 'user_name', array('id' => 'user_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_staff','Error User_name is null'))); ?>
                            </div>
                            <label for="name_cn" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px"><?php echo $infomodel->getAttributeLabel('name_cn'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'name_cn', array('id' => 'name_cn', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  手机号  -->
                        <div class="form-group">
                            <label for="user_phone" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px"><?php echo $model->getAttributeLabel('user_phone'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($model, 'user_phone', array('id' => 'user_phone', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_staff','Error User_phone is null'))); ?>
                            </div>
                            <label for="work_pass_type" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px"><?php echo $model->getAttributeLabel('work_pass_type');?></label>
                            <div class="col-4 padding-lr5">
                                <div class="form-group">
                                    <div class="col-6 " style="padding-left: 0px;">
                                        <?php
                                        $WorkPassType = Staff::WorkPassType();
                                        echo $form->activeDropDownList($model, 'work_pass_type',$WorkPassType ,array('id' => 'work_pass_type', 'class' => 'form-control', 'check-type' => 'required'));
                                        ?>
                                    </div>
                                    <div class="col-6 padding-lr5">
                                        <?php
                                        $NationType = array();
                                        $NationType =  Staff::NationType($model->work_pass_type);
                                        echo $form->activeDropDownList($model, 'nation_type', $NationType ,array('id' => 'nation_type', 'class' => 'form-control'));
                                        ?>

                                    </div>
                                    <span id="valierr" class="help-block" style="color:#FF9966">*</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  工作准证编号  -->
                        <div class="form-group">
                            <label for="work_no" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo $model->getAttributeLabel('work_no');?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($model,'work_no', array('id' => 'work_no', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_staff','Error Work_no is null'))); ?>
                            </div>
                            <label for="role_id" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo $model->getAttributeLabel('role_id');?></label>
                            <div class="col-4 padding-lr5">
                                <div class="form-group">
                                    <div class="col-6 padding-lr5" style="padding-left: 0px;">
                                        <?php
                                        $teamlist = Role::roleTeamList();
                                        array_unshift($teamlist, '-'.Yii::t('sys_role', 'team_name').'-');
                                        //                        var_dump($teamlist);
                                        //                    var_dump($model);
                                        //                    exit;
                                        echo $form->activeDropDownList($model, 'team_id',$teamlist ,array('id' => 'team_id', 'class' => 'form-control', 'check-type' => ''));
                                        ?>
                                    </div>
                                    <div class="col-6 padding-lr5">
                                        <?php
                                        $roleList = array();
                                        if($model->team_id)
                                            $roleList = Role::roleListByTeam($model->team_id);

                                        echo $form->activeDropDownList($model, 'role_id', $roleList ,array('id' => 'role_id', 'class' => 'form-control', 'check-type' => 'required','required-message'=>Yii::t('comp_staff', 'Error role_id is null')));
                                        ?>

                                    </div>
                                    <span id="valierr" class="help-block" style="color:#FF9966">*</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  雇主单位名称 -->
                        <div class="form-group">
                            <label for="bca_company" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo $infomodel->getAttributeLabel('bca_company'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'bca_company', array('id' => 'bca_company', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_staff','Error company_name is null'))); ?>
                            </div>
                            <label for="bca_levy_rate" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo $infomodel->getAttributeLabel('bca_levy_rate'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'bca_levy_rate', array('id' => 'bca_levy_rate', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  邮箱  -->
                        <div class="form-group">
                            <label for="primary_email" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo $model->getAttributeLabel('primary_email'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($model, 'primary_email', array('id' => 'primary_email', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
                            </div>
                            <label for="working_life" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo $model->getAttributeLabel('working_life'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($model, 'working_life', array('id' => 'working_life', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12" style="margin-left: -11px"><!--  入职时间  -->
                        <div class="form-group" >
                            <label style="margin-left:10px" for="bca_service_date" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('Joined Date'); ?></label>
                            <div class="col-4 input-group date" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#bca_service_date" name="StaffInfo[service_date]" id="bca_service_date" placeholder="" value="">
                                <div class="input-group-append" data-target="#bca_service_date" data-toggle="datetimepicker">
                                    <div class="input-group-text" style="height:34px"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                            <label for="work_pass_type" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo $model->getAttributeLabel('category');?></label>
                            <div class="col-4 padding-lr5">
                                <?php
                                $category = Staff::Category();
                                echo $form->activeDropDownList($model, 'category',$category ,array('id' => 'category', 'class' => 'form-control', 'check-type' => 'required'));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-12">
                        <div class="form-group">
                            <?php if($_mode_ == 'insert'){ ?>
                                <label for="app_content" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px"><?php echo Yii::t('comp_staff', 'App Setting') ?></label>
                                <div class="col-4 padding-lr5" style="padding-top: 6px">
                                    <input type="radio" id="app_no" name="app_radio"  />
                                    <?php echo Yii::t('comp_staff', 'Do not login') ?>
                                    <input type="radio" id="app_yes" checked="checked" name="app_radio">
                                    <?php echo Yii::t('comp_staff', 'login') ?>
                                </div>
                            <?php }else if($_mode_ == 'edit'){ ?>
                                <label for="app_content" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo Yii::t('comp_staff', 'Web User Setting') ?></label>
                                <div class="col-4 padding-lr5" style="padding-top: 6px">
                                    <?php if($model->web_tag == '0'){ ?>
                                        <input type="radio" id="web_no" name="web_radio" checked="checked"  />
                                        <?php echo Yii::t('comp_staff', 'Do not login') ?>
                                        <input type="radio" id="web_yes" name="web_radio">
                                        <?php echo Yii::t('comp_staff', 'login') ?>
                                    <?php }else if($model->web_tag == '1'){ ?>
                                        <input type="radio" id="web_no" name="web_radio"  />
                                        <?php echo Yii::t('comp_staff', 'Do not login') ?>
                                        <input type="radio" id="web_yes" checked="checked" name="web_radio">
                                        <?php echo Yii::t('comp_staff', 'login') ?>
                                    <?php }  ?>
                                </div>
                            <?php  } ?>
                            <label for="work_pass_type" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo $model->getAttributeLabel('skill');?></label>
                            <div class="col-4 padding-lr5">
                                <?php
                                $skill = Staff::Skill();
                                echo $form->activeDropDownList($infomodel, 'skill',$skill ,array('id' => 'skill', 'class' => 'form-control', 'check-type' => ''));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-12">
                        <div class="form-group">
                            <?php if($_mode_ == 'insert'){ ?>
                                <label for="app_content" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;">Company Name</label>
                                <div class="col-4 padding-lr5">
                                    <select id="contractor_id" class="form-control" name="Staff[contractor_id]">
                                        <?php
                                        $contractor_list = Contractor::compList();
                                        foreach ($contractor_list as $k => $name) {
                                            echo "<option value='{$k}'>{$name}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <label for="app_content" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo Yii::t('comp_staff', 'Web User Setting') ?></label>
                                <div class="col-4 padding-lr5" style="padding-top: 6px">
                                    <input type="radio" id="web_no" name="web_radio"  />
                                    <?php echo Yii::t('comp_staff', 'Do not login') ?>
                                    <input type="radio" id="web_yes" checked="checked" name="web_radio">
                                    <?php echo Yii::t('comp_staff', 'login') ?>
                                </div>
                            <?php } ?>
                            <?php if($_mode_ == 'edit'){ ?>
                                <label for="app_content" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;"><?php echo Yii::t('comp_staff', 'App Setting') ?></label>
                                <div class="col-4 padding-lr5" style="padding-top: 6px">
                                    <?php if($model->login_passwd == ''){ ?>
                                        <input type="radio" id="app_no" name="app_radio" checked="checked"  />
                                        <?php echo Yii::t('comp_staff', 'Do not login') ?>
                                        <input type="radio" id="app_yes" name="app_radio">
                                        <?php echo Yii::t('comp_staff', 'login') ?>
                                    <?php }else if($model->login_passwd != ''){ ?>
                                        <input type="radio" id="app_no" name="app_radio"  />
                                        <?php echo Yii::t('comp_staff', 'Do not login') ?>
                                        <input type="radio" id="app_yes" checked="checked" name="app_radio">
                                        <?php echo Yii::t('comp_staff', 'login') ?>
                                    <?php }  ?>
                                </div>

                                <label for="app_content" class="col-2 control-label padding-lr5 label-rignt" style="margin-top: 6px;">Company Name</label>
                                <div class="col-4 padding-lr5" style="padding-top: 7px;">
                                    <?php
                                    $contractor_list = Contractor::compList();
                                    echo $contractor_list[$model->contractor_id];
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-12" style="margin-left: -11px"><!--  照片  -->
                        <div class="form-group" >
                            <label for="face_img" style="margin-top: 6px;margin-left: 10px;" class="col-2 control-label padding-lr5 label-rignt" ><?php echo $model->getAttributeLabel('face_img');?></label>
                            <?php echo $form->activeFileField($infomodel, 'face_img', array('id' => 'face_img', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this)")); ?>
                            <div class="input-group col-10 padding-lr5">
                                <input id="uploadurl" class="form-control" type="text" disabled>
                                <span class="input-group-btn">
                                    <a class="btn btn-warning" style="height: 34px;" onclick="$('input[id=face_img]').click();"><i class="fa fa-folder-open"></i><?php echo Yii::t('common','button_browse'); ?></a>
                                </span>
                                <i class="help-block" style="color:#FF9966">*</i>
                            </div>
                            <!--                            <p style="font-size:16px; margin-left: 80px">--><?php //echo Yii::t('common','staff_photo_prompt') ?><!--</p>-->
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top: 10px;">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="face_img" class="col-2 control-label padding-lr5 label-rignt img_class" style="margin-top: 6px;"><?php echo $model->getAttributeLabel('face_img'); ?></label>
                            <div class="col-4 padding-lr5 img_class">
                                <?php
                                $new_img_url = $infomodel->face_img;
                                ?>
                                <?php
                                if($new_img_url){
                                    ?>
                                    <img width="90" id="photo" src="<?php echo $new_img_url; ?>"/>
                                <?php }else{ ?>
                                    <img width="90" id="photo" src=""/>
                                <?php } ?>
                                <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                            </div>
                            <?php if($_mode_ == 'edit'){
                                $contractor_id = Yii::app()->user->getState('contractor_id');
                                $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] .'/qrcode/'.$contractor_id.'/user/';
                                $file_name = $PNG_TEMP_DIR.$user_id.'.png'; ?>
                                <label for="face_img" class="col-2 control-label padding-lr5 label-rignt img_class" style="margin-top: 6px;"><?php echo Yii::t('comp_staff','qr_code'); ?></label>
                                <div class="col-4 padding-lr5 ">
                                    <a href='index.php?r=comp/staff/previewprint&user_id=<?php echo $user_id ?>&qrcode_path=<?php echo $file_name ?>' target='_blank'><img width="90" id="qrphoto" src="<?php echo $file_name; ?>"></a>
                                    <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                                </div>
                            <?php  } ?>
                        </div>
                    </div>
                </div>



                <div class="row" style="margin-top: 10px;">
                    <div class="col-12" style="text-align: center">
                        <button type="button" id="sbtn" class="btn btn-primary" onclick="btnsubmit();"><?php echo Yii::t('common', 'button_save'); ?></button>
                        <button type="button" class="btn btn-default"
                                style="margin-left: 10px;" onclick="back('<?php echo $program_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


     
<!--<?php //$this->renderpartial('_infoform', array( 'infomodel' => $infomodel, '_mode_' => 'insert')); ?>--> 


<?php $this->endWidget(); ?>
<script src="js/compress.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript" src="js/select2/select2.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

        v1 = $('#work_no').val();
        v2 = play(v1);
        $('#work_no').val(v2);
        
        b1 = $('#user_phone').val();
        b2 = see(b1);
        $('#user_phone').val(b2);
        $('#contractor_id').select2();
        $('#bca_service_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        //$("#form1").validation(function (obj, params) {
        //    if (obj.id == 'pw2' && $("#pw2").val() != $("#pw1").val()) {
        //        params.err = '<?php //echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>//';
        //        params.msg = "<?php //echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>//";
        //    }
        //});

        //处理角色及角色分组
        $('#team_id').change(function(){
            //alert($(this).val());
            
            var selObj = $("#role_id");
            var selOpt = $("#role_id option");
            
            if ($(this).val() == 0) {
                selOpt.remove();
                return;
            }
            $.ajax({
                type: "POST",
                url: "index.php?r=comp/role/queryteam",
                data: {teamid:$("#team_id").val()},
                dataType: "json",
                success: function(data){ //console.log(data);

                    selOpt.remove();
                    if (!data) {
                        return;
                    }
                    for (var o in data) {//console.log(o);
                        selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            });
        });
        
        //处理work_pass_type
        $('#work_pass_type').change(function(){
            //alert($(this).val());
            
            var selObj = $("#nation_type");
            var selOpt = $("#nation_type option");
            
            var val = $(this).val() ;
//            if (val != 'WP' && val != 'SP' ) {
//                selOpt.remove();
//                return;
//            }
            if ($(this).val() == 0) {
                selOpt.remove();
                return;
            }
            $.ajax({
                type: "POST",
                url: "index.php?r=comp/staff/querynationtype",
                data: {work_pass_type:$("#work_pass_type").val()},
                dataType: "json",
                success: function(data){ //console.log(data);

                    selOpt.remove();
                    if (!data) {
                        return;
                    }
                    for (var o in data) {//console.log(o);
                        selObj.append("<option value='"+o+"'>"+data[o]+"</option>");
                    }
                },
            })
        })
        
    });

    /**
     * @param base64Codes
     *            图片的base64编码
     */
    function sumitImageFile(base64Codes){
        if($("#app_no").prop("checked")){
            $("#app").val(0);
        }
        if($("#app_yes").prop("checked")){
            $("#app").val(1);
        }
        if($("#web_no").prop("checked")){
            $("#web").val(0);
        }
        if($("#web_yes").prop("checked")){
            $("#web").val(1);
        }
        if(base64Codes) {
            var form = document.forms[0];

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数


            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob(base64Codes), 'face.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
//            formData.append("filupload_urle1", fileInputElement.files[0]);
            var upload_url = $("#upload_url").val();
            console.log(form);
            //ajax 提交form
            $.ajax({
                url: upload_url,
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // 告诉jQuery不要去处理发送的数据
                contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
                success: function (data) {
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            $("#face_src").val(value.file1);
                            $('#face_img').attr("disabled","disabled");
                            $('#sbtn').attr("disabled","disabled");
                            addcloud(); //为页面添加遮罩
                            document.onreadystatechange = subSomething; //监听加载状态改变
                            $("#face_form").submit();
                        }
                    });
                },
                /*xhr:function(){            //在jquery函数中直接使用ajax的XMLHttpRequest对象
                 var xhr = new XMLHttpRequest();

                 xhr.upload.addEventListener("progress", function(evt){
                 if (evt.lengthComputable) {
                 var percentComplete = Math.round(evt.loaded * 100 / evt.total);
                 console.log("正在提交."+percentComplete.toString() + '%');        //在控制台打印上传进度
                 }
                 }, false);

                 return xhr;
                 }*/

            });
        }else{
//            var formData = new FormData();
//            formData.append("file1",$('#face_img')[0].files[0]);
            $("#face_form").submit();
        }
    }


    //手机号码格式
    document.getElementById("user_phone").onkeyup = function(evt) {
        evt = (evt) ? evt : ((window.event) ? window.event : "");  
        var key = evt.keyCode?evt.keyCode:evt.which;
        if ( key != 8 ){
            var str=(this.value).replace(/[^\d]/g, "");
            var maxlen = 11;
            if (str.length < maxlen) {
                maxlen = str.length;
            }
            var temp = "";
            for (var i = 0; i < maxlen; i++) {
                temp = temp + str.substring(i, i + 1);
                if (i != 0 && (i + 1) % 4 == 0 ) {
                    temp = temp + " ";
                }
            }
            this.value=temp;
        }
    }
    
    //证件号格式
    document.getElementById("work_no").onkeyup = function(evt) {
        evt = (evt) ? evt : ((window.event) ? window.event : "");  
        var key = evt.keyCode?evt.keyCode:evt.which;
        if ( key != 8 ){
            //var str=(this.value).replace(/[^\d||-]/g, "");
            var str=(this.value).replace(/[^\S||-]/g, "");
            var maxlen = 9;
            if (str.length < maxlen) {
                maxlen = str.length;
            }
            var temp = "";
            for (var i = 0; i < maxlen; i++) {
                temp = temp + str.substring(i, i + 1);
                if (i==0 ||(i + 1)==5) {
                    temp = temp + " ";
                }
            }
            this.value=temp;
        }
    }    
    ////返回
    //var back = function () {
    //    window.location = "./?<?php //echo Yii::app()->session['list_url']['staff/list']; ?>//";
    //    //window.location = "index.php?r=comp/usersubcomp/list";
    //}
    //返回
    var back = function (id) {
        //window.location = "index.php?r=proj/assignuser/authoritylist&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
        window.location = "./?<?php echo Yii::app()->session['list_url']['assignuser/authoritylist']; ?>";
    }
    
    //照片显示方法   
    $('.img_class').mouseout (function(){
        $("#attendImg").hide();
    });
    
    $('.img_class').mousemove (function(){
        img_url = $(this).attr("src");
        set_Img(img_url,this);
        $("#attendImg").show();
     });
    
    function set_Img(img_url,obj){
        var src,h;
        src=document.getElementById("photo").src;
//        alert(src);
        $("#attendPhoto").attr("src",src);
        h=$("#attendImg").innerHeight();
//        alert($(obj).position().top);
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#attendImg").css('top', ($(obj).position().top-h+650)+"px");
        $("#attendImg").css('left', ($(obj).position().left+300)+"px");
    }

</script>
<div id="attendImg" class="popDiv">
    <div class="popDiv_top">
            <div class="popDiv_body"><img id="attendPhoto" src=""  width="240px"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    <script type="text/javascript">
        $("#attendImg").hide();
    </script>
</div>

    




