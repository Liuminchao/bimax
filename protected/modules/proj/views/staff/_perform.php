<style type="text/css">
    .form-group span.required {
        color: #FF0000;
        font-size: 150%;
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
    'id' => 'per_form',
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
                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=proj/staff/tabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>" ><?php echo Yii::t('comp_staff','Base Info');?></a></li>
                    <li role="presentation" class="nav-item"><a class="nav-link active" href="index.php?r=proj/staff/pertabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','Personal Info');?></a></li>
                    <li role="presentation" class="nav-item"><a class="nav-link" href="index.php?r=proj/staff/instabs&user_id=<?php echo $user_id; ?>&program_id=<?php echo $program_id ?>&mode=<?php echo $_mode_; ?>&title=<?php echo $title; ?>"><?php echo Yii::t('comp_staff','ins');?></a></li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row" style="padding-top:5px">
                    <div class="col-12">
                        <div class="alert alert-info alert-dismissable">
                            <i class="fa fa-exclamation"></i><b> Document name cannot contain special characters and symbols such as . / and etc.</b></div>
                    </div>
                </div>
                <div >
                    <input type="hidden" id="user_id" name="StaffInfo[user_id]" value="<?php echo "$user_id"; ?>"/>
                    <input type="hidden" id="tag_id" name="Tag[tag_id]" value="per"/>
                    <input type="hidden" id="home_id_src" name="File[home_id_src]" />
                    <input type="hidden" id="filebase64">
                    <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12" >
                        <div class="form-group">
                            <label for="family_name" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('family_name'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'family_name', array('id' => 'family_name', 'class' =>'form-control','check-type' => '')); ?>
                            </div>
                            <label for="first_name" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('first_name'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'first_name', array('id' => 'first_name', 'class' =>'form-control','check-type' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  性别  -->
                        <div class="form-group">
                            <label for="gender" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('gender'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php
                                $gender = Staff::gender();
                                echo $form->activeDropDownList($infomodel, 'gender',$gender ,array('id' => 'gender', 'class' => 'form-control', 'check-type' => 'required','required-message' => Yii::t('comp_staff','Error User_gender is null')));
                                ?>
                            </div>
                            <label for="birth_date" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('birth_date'); ?></label>
                            <div class="col-4 input-group date" data-target-input="nearest">
                                <input type="text" id="birth_date" class="form-control datetimepicker-input" data-target="#birth_date"  onclick="WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})" check-type="" name="StaffInfo[birth_date]" type="text">
                                <div class="input-group-append" data-target="#birth_date" data-toggle="datetimepicker">
                                    <div class="input-group-text" style="height:34px;"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12" ><!--  国籍  -->
                        <div class="form-group">
                            <label for="nationality" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('nationality'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'nationality', array('id' => 'nationality', 'class' =>'form-control','check-type' => '')); ?>
                            </div>
                            <label for="home_address" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('Race'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php
                                $race = Staff::Race();
                                echo $form->activeDropDownList($infomodel, 'race',$race ,array('id' => 'race', 'class' => 'form-control', 'check-type' => '','required-message' => ''));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12" ><!--  婚姻情况  -->
                        <div class="form-group">
                            <label for="nationality" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('Marital Status'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php
                                $marital = Staff::Marital();
                                echo $form->activeDropDownList($infomodel, 'marital',$marital ,array('id' => 'marital', 'class' => 'form-control', 'check-type' => '','required-message' => ''));
                                ?>
                            </div>
                            <label for="home_address" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('Previous Industry Experience & Designation'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'previous_designation', array('id' => 'previous_designation', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  国内第二联系人姓名  -->
                        <div class="form-group">
                            <label for="home_contact" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('home_contact'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'home_contact', array('id' => 'home_contact', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                            <label for="home_address" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('home_address'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'home_address', array('id' => 'home_address', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  联系电话  -->
                        <div class="form-group">
                            <label for="home_phone" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('home_phone'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'home_phone', array('id' => 'home_phone', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                            <label for="relationship" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('relationship'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'relationship', array('id' => 'relationship', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  新加坡联系方式  -->
                        <div class="form-group">
                            <label for="sg_phone" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('sg_phone'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'sg_phone', array('id' => 'sg_phone', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                            <label for="sg_address" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('sg_address'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'sg_address', array('id' => 'sg_address', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12"><!--  邮政编码  -->
                        <div class="form-group">
                            <label for="sg_postal_code" class="col-2 control-label padding-lr5 label-rignt"><?php echo $infomodel->getAttributeLabel('sg_postal_code'); ?></label>
                            <div class="col-4 padding-lr5">
                                <?php echo $form->activeTextField($infomodel, 'sg_postal_code', array('id' => 'sg_postal_code', 'class' => 'form-control', 'check-type' => '')); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 10px;">
                    <div class="col-12" style="text-align: center">
                        <button type="button" id="sbtn" class="btn btn-primary" onclick="btnsubmit();"><?php echo Yii::t('common', 'button_save'); ?></button>
                        <button type="button" class="btn btn-default"
                                style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
<script src="js/compress.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">

    jQuery(document).ready(function () {
        $('#birth_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('.b_date_per').each(function(){
            a1 = $(this).val();
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });

    });

    /**
     * @param base64Codes
     *            图片的base64编码
     */
    function sumitImageFile(base64Codes){
        if(base64Codes) {
            var form = document.forms[0];

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数

            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob(base64Codes), 'per.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同

            //formData.append("file2", $("#orifile")[0].files[0]);
            console.log(form);
            var upload_url = $("#upload_url").val();
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
                            $("#home_id_src").val(value.file1);
                            $('#home_id_photo').attr("disabled","disabled");
                            $('#sbtn').attr("disabled","disabled");
                            addcloud(); //为页面添加遮罩
                            document.onreadystatechange = subSomething; //监听加载状态改变
                            $("#per_form").submit();
                        }
                    });
                },
            });
        }else{
            $("#per_form").submit();
        }
    }

    //返回
    var back = function () {
        //window.location = "./?<?php //echo Yii::app()->session['list_url']['staff/list']; ?>//";
        window.location = "./?<?php echo Yii::app()->session['list_url']['assignuser/authoritylist']; ?>";
    }

    //照片显示方法
    $('.img_home').mouseout (function(){
        $("#attend_home_Img").hide();
    });

    $('.img_home').mousemove (function(){
        img_url = $(this).attr("src");
        set_per_Img(img_url,this);
        $("#attend_home_Img").show();
    });

    function set_per_Img(img_url,obj){
        var src,h;
        src=document.getElementById("photo").src;
//        alert(src);
        $("#attend_home_Photo").attr("src",src);
        h=$("#attend_home_Img").innerHeight();
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#attend_home_Img").css('top', ($(obj).position().top-h+700)+"px");
        $("#attend_home_Img").css('left', ($(obj).position().left+300)+"px");
    }
</script>
<div id="attend_home_Img" class="popDiv">
    <div class="popDiv_top">
        <div class="popDiv_body"><img id="attend_home_Photo" src="" width="240"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    <script type="text/javascript">
        $("#attend_home_Img").hide();
    </script>
</div>



