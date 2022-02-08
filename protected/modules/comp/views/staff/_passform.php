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
    'id' => 'pass_form',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    'focus' => array(
        $model,
        'name'
    ),
    'role' => 'form', // 可省略
    'formClass' => 'form-horizontal', // 可省略 表单对齐样式
    'autoValidation' => false
));
$upload_url = Yii::app()->params['upload_url'];
?>
<div class="row">
    <p style="color:red;font-size:16px;">Document name cannot contain special characters and symbols such as . / and etc.</p>
</div>
<div class="box-body">
    <div >
        <input type="hidden" id="user_id" name="StaffInfo[user_id]" value="<?php echo "$user_id"; ?>"/>
        <input type="hidden" id="tag_id" name="Tag[tag_id]" value="pass"/>
        <input type="hidden" id="ppt_src" name="File[ppt_src]" />
        <input type="hidden" id="filebase64">
        <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
    </div>
    <!--<div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Aptitude Info'); ?></h3>
        </div>
    </div>-->

    <div class="row">
        <div class="col-md-6"><!--  护照号  -->
            <div class="form-group">
                <label for="passport_no" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('passport_no'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'passport_no', array('id' => 'passport_no', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6" >
            <div class="form-group"><!--  签发日期  -->
                <label for="ppt_issue_date" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_staff','Issue Date') ?></label>
                <div class="input-group col-sm-6 ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'ppt_issue_date', array('id' => 'bca_issue_date', 'class' =>'form-control b_date_pass', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>

    </div>
    <div class="row">

        <div class="col-md-6"><!--  护照照片  -->
            <div class="form-group">
                <label for="ppt_photo" class="col-sm-3 control-label padding-lr5" ><?php echo $infomodel->getAttributeLabel('ppt_photo');?></label>
                <?php echo $form->activeFileField($infomodel, 'ppt_photo', array('id' => 'ppt_photo', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this);")); ?>
                <div class="input-group col-md-6 padding-lr5">
                    <input id="uploadurl_pass" class="form-control" type="text" disabled>
                        <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=ppt_photo]').click();">
                                <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                </div>
            </div>
        </div>
        <div class="col-md-6" ><!--  截止日期  -->
            <div class="form-group">
                <label for="ppt_expire_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('expire_date'); ?></label>
                <div class="input-group col-sm-6 ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'ppt_expire_date', array('id' => 'ppt_expire_date', 'class' =>'form-control b_date_pass', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '' )); ?>
                </div>
            </div>
        </div>
    </div>
    <?php if($_mode_ == 'insert'){    ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="face_img"
                           class="col-sm-3 control-label padding-lr5 img_class"><?php echo Yii::t('comp_staff','Face_img'); ?></label>
                    <div class="col-sm-3 padding-lr5 img_class">

                        <img width="90" id="photo" src=""/>
                        <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
    <?php if($_mode_ == 'edit'&& $infomodel->ppt_photo!=''){    ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="ppt_photo"
                           class="col-sm-3 control-label padding-lr5 img_ppt"><?php echo Yii::t('comp_staff','Face_img'); ?></label>
                    <div class="col-sm-6 padding-lr5 img_ppt">
                        <?php
                        $new_img_url = $infomodel->ppt_photo;
                        ?>
                        <img width="90" id="photo" src="<?php echo $new_img_url; ?>"/>


                    </div>
                </div>
            </div>
        </div>
    <?php }else if($_mode_ == 'edit'&& $infomodel->ppt_photo==''){ ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="face_img"
                           class="col-sm-3 control-label padding-lr5 img_class"><?php echo $model->getAttributeLabel('face_img'); ?></label>
                    <div class="col-sm-3 padding-lr5 img_class">

                        <img width="90" id="photo" src=""/>
                        <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-10">
            <button type="button" id="sbtn"  onclick="btnsubmit();" class="btn btn-primary btn-lg"><?php echo Yii::t('common', 'button_save'); ?></button>
            <button type="button" class="btn btn-default btn-lg"
                    style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script src="js/compress.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">

    jQuery(document).ready(function () {

        $('.b_date_pass').each(function(){
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
            formData.append("file1", convertBase64UrlToBlob(base64Codes), 'pass.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同

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
                            $("#ppt_src").val(value.file1);
                            $('#ppt_photo').attr("disabled","disabled");
                            $('#sbtn').attr("disabled","disabled");
                            addcloud(); //为页面添加遮罩
                            document.onreadystatechange = subSomething; //监听加载状态改变
                            $("#pass_form").submit();
                        }
                    });
                },
            });
        }else{
            $("#pass_form").submit();
        }
    }


    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }

    //照片显示方法
    $('.img_ppt').mouseout (function(){
        $("#attend_ppt_Img").hide();
    });

    $('.img_ppt').mousemove (function(){
//        alert(111111);
        set_ppt_Img(this);
        $("#attend_ppt_Img").show();
    });

    function set_ppt_Img(obj){
//        alert(111111111);
        var src,h;
        src=document.getElementById("photo").src;
//        alert(1111111);
//        alert(src);
        $("#attend_ppt_Photo").attr("src",src);
        h=$("#attend_ppt_Img").innerHeight();
//        alert(h);
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#attend_ppt_Img").css('top', ($(obj).position().top-h+600)+"px");
        $("#attend_ppt_Img").css('left', ($(obj).position().left+300)+"px");
    }
</script>
<div id="attend_ppt_Img" class="popDiv">
    <div class="popDiv_top">
        <div class="popDiv_body"><img id="attend_ppt_Photo" src="" width="240"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    <script type="text/javascript">
        $("#attend_ppt_Img").hide();
    </script>
</div>



