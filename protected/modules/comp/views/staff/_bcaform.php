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
    'id' => 'bca_form',
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
<div class="row">
    <p style="color:red;font-size:16px;">Document name cannot contain special characters and symbols such as . / and etc.</p>
</div>
<div class="box-body">
    <div >
        <input type="hidden" id="user_id" name="StaffInfo[user_id]" value="<?php echo "$user_id"; ?>"/>
        <input type="hidden" id="tag_id" name="Tag[tag_id]" value="bca"/>
        <input type="hidden" id="mode" name="Mode[mode]" value="<?php echo "$_mode_"; ?>"/>
        <input type="hidden" id="bca_src" name="File[bca_src]" />
        <input type="hidden" id="filebase64">
        <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
    </div>
    <!--<div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Aptitude Info'); ?></h3>
        </div>
    </div>-->
    <div id="no"  class="row">
        <div class="col-md-6"><!--  准证编号  -->
            <div class="form-group">
                <label for="bca_pass_no" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('bca_pass_no'); ?></label>
                <div  class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <?php
                    $work_no = $model->work_no;
                    ?>
                    <input type="text" class="form-control" id="Work_no" name="Work[work_no]" value="<?php echo "$work_no"; ?>"  readonly="true"/>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  准证类型  -->
            <div class="form-group">
                <label for="bca_pass_type" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('bca_pass_type'); ?></label>
                <div class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <?php echo $model->work_pass_type
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  雇主单位名称  -->
            <div class="form-group">
                <label for="bca_company" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('bca_company'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'bca_company', array('id' => 'bca_company', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_staff','Error company_name is null'))); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6" ><!--  公司编号  -->
            <div class="form-group">
                <label for="bca_company_uen" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('bca_company_uen'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'bca_company_uen', array('id' => 'bca_company_uen', 'class' =>'form-control','check-type' => '')); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  工种  -->
            <div class="form-group">
                <label for="bca_trade" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('bca_trade'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'bca_trade', array('id' => 'bca_trade', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6" >
            <div class="form-group">
                <label for="bca_apply_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('bca_apply_date'); ?></label>
                <div class="input-group col-sm-6">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'bca_apply_date', array('id' => 'bca_apply_date', 'class' =>'form-control b_date_bca','onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6" >
            <div class="form-group">
                <label for="bca_issue_date" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('comp_staff','Issue Date') ?></label>
                <div class="input-group col-sm-6">
                    <div class="input-group-addon" ><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'bca_issue_date', array('id' => 'bca_issue_date', 'class' =>'form-control b_date_bca', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})",  'check-type' => '')); ?>
                    <i class="input-group-addon" style="color:#FF9966">*</i>
                </div>
            </div>
        </div>
        <div class="col-md-6" >
            <div class="form-group">
                <label for="bca_expire_date" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('expire_date'); ?></label>
                <div class="input-group col-sm-6">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($infomodel, 'bca_expire_date', array('id' => 'bca_expire_date', 'class' =>'form-control b_date_bca', 'onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})",  'check-type' => '')); ?>
                    <i class="input-group-addon" style="color:#FF9966">*</i>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-6"><!--  护照号  -->
            <div class="form-group">
                <label for="bca_levy_rate" class="col-sm-3 control-label padding-lr5"><?php echo $infomodel->getAttributeLabel('bca_levy_rate'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($infomodel, 'bca_levy_rate', array('id' => 'bca_levy_rate', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  照片  -->
            <div class="form-group">
                <label for="bca_photo" class="col-sm-3 control-label padding-lr5" ><?php echo $infomodel->getAttributeLabel('bca_photo');?></label>
                <?php echo $form->activeFileField($infomodel, 'bca_photo', array('id' => 'bca_photo', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this)")); ?>
                <div class="input-group col-md-6 padding-lr5">
                    <input id="uploadurl_bca" class="form-control" type="text" disabled>
                        <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=bca_photo]').click();">
                                <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                    <i class="help-block" style="color:#FF9966">*</i>
                </div>
            </div>
        </div>
    </div>
    <?php if($_mode_ == 'insert'){    ?>
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
    <?php if($_mode_ == 'edit'&& $infomodel->bca_photo!=''){    ?>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="bca_photo"
                           class="col-sm-3 control-label padding-lr5 img_bca"><?php echo Yii::t('comp_staff','Face_img'); ?></label>
                    <div class="col-sm-6 padding-lr5 img_bca">
                        <?php
                        $new_img_url = $infomodel->bca_photo;
                        ?>
                        <img width="90" id="photo" src="<?php echo $new_img_url; ?>"/>


                    </div>
                </div>
            </div>
        </div>
    <?php }else if($_mode_ == 'edit'&& $infomodel->bca_photo==''){ ?>
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
            <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="btnsubmit();"><?php echo Yii::t('common', 'button_save'); ?></button>
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
        mode = $('#mode').val();
        Work_no = $('#Work_no').val();
        //alert(mode);
        var traget=document.getElementById(no);
        if(mode == 'insert'&& Work_no == ''){
            traget.style.display="none";
            alert(Work_no);
        }
        v1 = $('#Work_no').val();
        v2 = play(v1);
        $('#Work_no').val(v2);
//        alert(v2);
        $('.b_date_bca').each(function(){
            a1 = $(this).val();
            //alert(a1);
            a2 = datetocn(a1);
            //alert(a2);
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
            formData.append("file1", convertBase64UrlToBlob(base64Codes), 'bca.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同

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
                            $("#bca_src").val(value.file1);
                            $('#bca_photo').attr("disabled","disabled");
                            $('#bca_bt').attr("disabled","disabled");
                            addcloud(); //为页面添加遮罩
                            document.onreadystatechange = subSomething; //监听加载状态改变
                            $("#bca_form").submit();
                        }
                    });
                },
            });
        }else{
            $("#bca_form").submit();
        }
    }

    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['staff/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
    //照片显示方法
    $('.img_bca').mouseout (function(){
        $("#attend_bca_Img").hide();
    });

    $('.img_bca').mousemove (function(){
        set_bca_Img(this);
//        alert(11111111);
        $("#attend_bca_Img").show();
    });

    function set_bca_Img(obj){
        var src,h;
        src=document.getElementById("photo").src;
//        alert(src);
        $("#attend_bca_Photo").attr("src",src);
        h=$("#attend_bca_Img").innerHeight();
//        alert(h);
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#attend_bca_Img").css('top', ($(obj).position().top-h+600)+"px");
        $("#attend_bca_Img").css('left', ($(obj).position().left+300)+"px");
    }
</script>
<div id="attend_bca_Img" class="popDiv">
    <div class="popDiv_top">
        <div class="popDiv_body"><img id="attend_bca_Photo" src="" width="240"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    <script type="text/javascript">
        $("#attend_bca_Img").hide();
    </script>
</div>



