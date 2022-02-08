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
    'id' => 'form1',
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
    <p style="font-size:16px;margin-left: 13px"><?php echo Yii::t('common','device_form_prompt') ?></p>
</div>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_staff', 'Base Info'); ?></h3>
        </div>
    </div>
   <div >
       <input type="hidden" id="device_src" name="File[device_src]" />
       <input type="hidden" id="filebase64">
       <input type="hidden" id="permit_img" name="Device[permit_img]">
       <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
    </div>
    <div class="row">
        <div class="col-md-6"><!--  设备编码  -->
            <div class="form-group">
                <label for="device_id" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('device_id'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'device_id', array('id' => 'device_id', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('device','Error Equipment_id is null'))); ?>
                </div>
            </div>
        </div>
        <div class="col-md-6"><!--  设备名称  -->
            <div class="form-group">
                <label for="device_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('device_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'device_name', array('id' => 'device_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('device','Error Equipment_name is null'))); ?>
                </div>
            </div>
        </div>
    </div>

<!--    <div class="row">-->
<!--        <div class="col-md-6"><!--  设备编码  -->
<!--            <div class="form-group">-->
<!--                <label for="device_id" class="col-sm-3 control-label padding-lr5">--><?php //echo $model->getAttributeLabel('safe_working_load'); ?><!--</label>-->
<!--                <div class="col-sm-6 padding-lr5">-->
<!--                    --><?php //echo $form->activeTextField($model, 'safe_working_load', array('id' => 'safe_working_load', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('device','Error Equipment_id is null'))); ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="col-md-6"><!--  设备名称  -->
<!--            <div class="form-group">-->
<!--                <label for="device_name" class="col-sm-3 control-label padding-lr5">--><?php //echo $model->getAttributeLabel('test_load'); ?><!--</label>-->
<!--                <div class="col-sm-6 padding-lr5">-->
<!--                    --><?php //echo $form->activeTextField($model, 'test_load', array('id' => 'test_load', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('device','Error Equipment_name is null'))); ?>
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

    <div class="row">
        <div class="col-md-6"><!--  设备类型  -->
            <div class="form-group">
                <label for="type_no" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('type_no'); ?></label>
                <div class="col-sm-6 padding-lr5">
                        <?php
                            $devicelist = DeviceType::deviceList();
//                            array_unshift($devicelist, Yii::t('device', 'device_type'));
                            echo $form->activeDropDownList($model, 'type_no',$devicelist ,array('id' => 'type_no', 'class' => 'form-control','check-type' => ''));
                        ?>

                </div>
                <i class="help-block" style="color:#FF9966">*</i>
            </div>
        </div>
        <div class="col-md-6"><!--  设备内容  -->
            <div class="form-group">
                <label for="device_content" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('device_content'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextArea($model, 'device_content', array('id' => 'device_id', 'class' => 'form-control', 'check-type' => '')); ?>
                </div>
            </div>
        </div>
     </div>
    <div class="row">
        <div class="col-md-6"><!--  照片  -->
        <div class="form-group">
                 <label for="device_img" class="col-sm-3 control-label padding-lr5" ><?php echo $model->getAttributeLabel('device_img');?></label>
                 <?php 
                 echo $form->activeFileField($model, 'device_img', array('id' => 'device_img', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this)"));
//                 echo '<div class="col-sm-6 padding-lr5"><input id="device_img" class="form-control" type="file"  name="S[device_img]" style="display:none"  onchange="a(this)" ></div>';
                 ?>
                    <div class="input-group col-md-6 padding-lr5">
                        <input id="device_url" class="form-control" type="text" disabled>
                        <span class="input-group-btn">
                            <a class="btn btn-warning" onclick="$('input[id=device_img]').click();">
                            <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                            </a>
                        </span>
                        <i class="help-block" style="color:#FF9966">*</i>
                    </div>
                    <p style="font-size:16px; margin-left: 80px"><?php echo Yii::t('common','staff_photo_prompt') ?></p>
            </div>
        </div>

        <?php if($_mode_ == 'edit'){
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] .'/qrcode/'.$contractor_id.'/device/';
            $primary_id = $model->primary_id;
            $file_name = $PNG_TEMP_DIR.$primary_id.'_new.png';
            ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="face_img"
                           class="col-sm-3 control-label padding-lr5 img_class"><?php echo Yii::t('comp_staff','qr_code'); ?></label>
                    <div class="col-sm-3 padding-lr5 ">
                        <a href='index.php?r=device/equipment/previewprint&primary_id=<?php echo $primary_id ?>' target='_blank'><img width="90" id="qrphoto" src="<?php echo $file_name; ?>"></a>
                        <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                    </div>
                </div>
            </div>
        <?php }else{ ?>
            <div class="col-md-6" >
                <div class="form-group"><!--  下次维修日期  -->
                    <label for="certificate_enddate" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('next_service_date'); ?></label>
                    <div class="input-group col-sm-6">
                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                        <?php echo $form->activeTextField($model, 'permit_startdate', array('id' => 'permit_startdate', 'class' =>'form-control b_date_permit', 'onclick' => "WdatePicker({lang:'en',minDate:'new Date()',dateFmt:'dd MMM yyyy'})",'check-type' => '')); ?>
                    </div>
                    <div style="padding-left: 30px;">
                        <input type="radio" id="monthly" name="radio" />
                        <?php echo Yii::t('device', 'monthly') ?>
                        <input type="radio" id="quarter" name="radio">
                        <?php echo Yii::t('device', 'quarter') ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>

    <?php if($_mode_ == 'insert'){    ?>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="face_img"
                           class="col-sm-3 control-label padding-lr5 img_class"><?php echo $model->getAttributeLabel('device_img');?></label>
                    <div class="col-sm-3 padding-lr5 img_class">

                        <img width="90" id="photo" src=""/>
                        <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                    </div>
                </div>
            </div>

        </div>
    <?php } ?>
</div>
<?php if($_mode_ == 'edit'){    ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="face_img"
                       class="col-sm-3 control-label padding-lr5 device_img_class"><?php echo $model->getAttributeLabel('device_img');?></label>
                <div class="col-sm-3 padding-lr5 device_img_class">
                    <?php
                    $new_img_url = $model->device_img;
                    ?>
                    <img width="90" id="photo" src="<?php echo $new_img_url; ?>"/>
                    <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                </div>
            </div>
        </div>

        <div class="col-md-6" >
            <div class="form-group"><!--  下次维修日期  -->
                <label for="certificate_enddate" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('next_service_date'); ?></label>
                <div class="input-group col-sm-6 ">
                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                    <?php echo $form->activeTextField($model, 'permit_startdate', array('id' => 'permit_startdate', 'class' =>'form-control b_date_permit', 'onclick' => "WdatePicker({lang:'en',minDate:'new Date()',dateFmt:'dd MMM yyyy'})",'check-type' => '')); ?>
                </div>
                <div style="padding-left: 30px;">
                    <?php if($model->permit_img == ''){ ?>
                        <input type="radio" id="monthly" name="radio" />
                        <?php echo Yii::t('device', 'monthly') ?>
                        <input type="radio" id="quarter" name="radio">
                        <?php echo Yii::t('device', 'quarter') ?>
                    <?php }else if($model->permit_img == '1'){ ?>
                        <input type="radio" id="monthly" name="radio" checked="checked"  />
                        <?php echo Yii::t('device', 'monthly') ?>
                        <input type="radio" id="quarter" name="radio">
                        <?php echo Yii::t('device', 'quarter') ?>
                    <?php }else{  ?>
                        <input type="radio" id="monthly" name="radio"   />
                        <?php echo Yii::t('device', 'monthly') ?>
                        <input type="radio" id="quarter" name="radio" checked="checked" />
                        <?php echo Yii::t('device', 'quarter') ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
    <div class="row"> </div>

</div>    
     
<!--<?php //$this->renderpartial('_infoform', array( 'infomodel' => $infomodel, '_mode_' => 'insert')); ?>--> 
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
        $('.b_date_permit').each(function(){
            a1 = $(this).val();
            a2 = datetocn(a1);
            if(a2!=' undefined'){
                $(this).val(a2);
            }
        });e
        var curDate = new Date();
        var nextDate = new Date(curDate.getTime() + 24*60*60*1000); //后一天
        $('#permit_date').bind("click",function(){
            WdatePicker({
                readOnly:true,
                dateFmt:'dd MMM yyyy',
                lang:'en',
                minDate:nextDate,
            });
        });

    });
  function a(obj){
    $('#device_url').val(obj.value);
  }
  function b(obj){
    $('#permit_url').val(obj.value);
  }
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['device/list']; ?>";
        //window.location = "index.php?r=comp/usersubcomp/list";
    }
    /**
     * @param base64Codes
     *            图片的base64编码
     */
    function sumitImageFile(base64Codes){
        if($("#monthly").prop("checked")){
            $("#permit_img").val(1);
        }
        if($("#quarter").prop("checked")){
            $("#permit_img").val(3);
        }
        if(base64Codes) {
            var form = document.forms[0];

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数

            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob(base64Codes), 'device.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同

            //formData.append("file2", $("#orifile")[0].files[0]);
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
                            $("#device_src").val(value.file1);
                            $('#device_img').attr("disabled","disabled");
                            $('#sbtn').attr("disabled","disabled");
                            addcloud(); //为页面添加遮罩
                            document.onreadystatechange = subSomething; //监听加载状态改变
                            $("#form1").submit();
                        }
                    });
                },

            });
        }else{
            $("#form1").submit();
        }
    }
    //设备照片显示方法   
    $('.device_img_class').mouseout (function(){
        $("#device_attendImg").hide();
    });
    
    $('.device_img_class').mousemove (function(){

        img_url = $(this).attr("src");
//        alert(img_url);
        set_DeviceImg(img_url,this);
        $("#device_attendImg").show();
     });

     
    function set_DeviceImg(img_url,obj){
        var src,h;
        src=document.getElementById("photo").src;
//        alert(src);
        $("#device_attendPhoto").attr("src",src);
        h=$("#device_attendImg").innerHeight();
//        alert($(obj).position().top);
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#device_attendImg").css('top', ($(obj).position().top-h+550)+"px");
        $("#device_attendImg").css('left', ($(obj).position().left+320)+"px");
    }

</script>
<div id="device_attendImg" class="popDiv">
    <div class="popDiv_top">
            <div class="popDiv_body"><img id="device_attendPhoto" src="" width="340"/></div>
    </div>
    <div class="popDiv_bottom"></div>
    <script type="text/javascript">
        $("#device_attendImg").hide();
    </script>
</div>
    




