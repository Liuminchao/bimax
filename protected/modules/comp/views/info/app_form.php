<link rel="stylesheet" href="css/jQueryUI/jquery-ui1.1.css">
<link rel="stylesheet" href="css/jQueryUI/style1.1.css">
<?php
if ($msg) {
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          <script type='text/javascript'>
          {$this->gridId}.refresh();
          </script>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
    'autoValidation' => false,
));
$upload_url = Yii::app()->params['upload_url'];
$app_icon = $model->app_icon;
?>
<div class="box-body">

    <div >
        <input type="hidden" id="company_id" name="CompanyApp[company_id]" value="<?php echo "$company_id"; ?>" />
        <input type="hidden" id="tmp_src" name="CompanyApp[tmp_src]" />
        <input type="hidden" id="logo_src" value="<?php echo "$app_icon"; ?>" />
        <input type="hidden" id="filebase64">
        <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
        <input type="hidden" id="suffix"/>
        <?php
//        if ($_mode_ == 'edit') {
//            $params = json_decode($model->params,true);
//            $pro_cnt = $params['pro_cnt'];
//        }else {
//            $pro_cnt = 0;
//        }
        ?>
    </div>


    <div class="row">
<!--        --><?php //if($_mode_ == 'insert'){ ?>
            <div class="row">
                <div class="form-group">
                    <label for="login_name" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('sys_app', 'app_name'); ?></label>
                    <div class="col-sm-3 padding-lr5" >
                        <?php
                            $app_list = App::appList();
                            echo $form->activeDropDownList($model, 'app_id', $app_list, array('id' => 'app_id','class' => 'form-control', 'check-type' => 'required', 'placeholder' => '', 'required-message' => ''));
                        ?>
                    </div>
                </div>
            </div>
<!--        --><?php //} ?>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="open_time" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('sys_app', 'open_time'); ?></label>
            <div class="input-group col-sm-3">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <?php echo $form->activeTextField($model, 'open_time', array('id' => 'open_time', 'class' =>'form-control b_date_ins','onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="form-group">
            <label for="close_time" class="col-sm-3 control-label padding-lr5"><?php echo Yii::t('sys_app', 'close_time'); ?></label>
            <div class="input-group col-sm-3">
                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                <?php echo $form->activeTextField($model, 'close_time', array('id' => 'close_time', 'class' =>'form-control b_date_ins','onclick' => "WdatePicker({lang:'en',dateFmt:'dd MMM yyyy'})", 'check-type' => '')); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group" ><!--  照片  -->
            <div class="form-group" >
                <label for="face_img" class="col-sm-3 control-label padding-lr5" ><?php echo Yii::t('sys_app', 'app_icon'); ?></label>
                <?php echo $form->activeFileField($model, 'app_icon', array('id' => 'app_icon', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this)")); ?>
                <div class="input-group col-md-3 padding-lr5">
                    <input id="uploadurl" class="form-control" type="text" disabled>
                    <span class="input-group-btn">
                                    <a class="btn btn-warning" onclick="$('input[id=app_icon]').click();">
                                        <i class="fa fa-folder-open"></i> <?php echo Yii::t('common','button_browse'); ?>
                                    </a>
                                </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if ($_mode_ == 'insert') { ?>
            <div class="form-group">
                <label for="app_icon"
                       class="col-sm-3 control-label padding-lr5 img_class"><?php echo Yii::t('sys_app', 'app_icon'); ?></label>
                <div class="col-sm-3 padding-lr5 img_class">
                    <img width="90" id="photo" src=""/>
                    <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                </div>
            </div>

        <?php }else{ ?>

            <div class="form-group">
                <label for="app_icon"
                       class="col-sm-3 control-label padding-lr5 img_class"><?php echo Yii::t('sys_app', 'app_icon'); ?></label>
                <div class="col-sm-3 padding-lr5 img_class">
                    <?php
                    $new_img_url = $model->app_icon;
                    ?>
                    <?php if($model->app_icon){ ?>
                        <img width="90" id="photo" src="<?php echo $new_img_url; ?>"/>
                    <?php }else{ ?>
                        <img width="90" id="photo" src=""/>
                    <?php } ?>
                    <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="sumitImageFile();"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back('<?php echo $company_id ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script src="js/loading.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {

    });

    //返回
    var back = function (company_id) {
        window.location = "index.php?r=comp/info/applist&id="+company_id;
    }

    //压缩图像转base64
    function dealImage(file)
    {
        document.getElementById('uploadurl').value=file.value;
        if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(file.value)) {
            alert("File types must be GIF, JPEG, JPG, PNG.");
            return false;
        }

        var video_src_file = $("#app_icon").val();
        var newFileName = video_src_file.split('.');
        var URL = window.URL || window.webkitURL;
        var blob = URL.createObjectURL(file.files[0]);
        var img = new Image();
//        alert(blob);
        img.src = blob;
        img.onload = function () {
            var that = this;
            //生成比例
            var w = that.width, h = that.height, scale = w / h;
            new_w = 20;
            new_h = new_w / scale;

            //生成canvas
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');
            $(canvas).attr({
                width: new_w,
                height: new_h
            });
            ctx.drawImage(that, 0, 0, new_w, new_h);
            // 图像质量
            quality = 0.8;
            // quality值越小，所绘制出的图像越模糊
            var base64 = canvas.toDataURL('image/jpeg', quality);
            // 生成结果
            var result = {
                base64: base64,
                clearBase64: base64.substr(base64.indexOf(',') + 1)
            };
            $("#filebase64").val(result.base64);
            document.getElementById('photo').setAttribute('src',result.base64);
            document.getElementById("suffix").value= '1';
//                btnsubmit(result.base64);
        }
    }

    /**
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     *            用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){
        var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte
//        alert(5);
        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }

    function sumitImageFile() {
        var file = $("#app_icon").val();
        if(file != ''){
            var suffix = document.getElementById("suffix").value;
        }else{
            var suffix = 0;
        }

        var logo_src = document.getElementById("logo_src").value;

        if (suffix == '1') {
            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob($("#filebase64").val()), 'logo.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
            var form = document.forms[0];
            var upload_url = $("#upload_url").val();
//        console.log(form);
            //ajax 提交form
            $.ajax({
                url: upload_url,
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // 告诉jQuery不要去处理发送的数据
                contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
                beforeSend: function () {
                    addcloud();
                },
                success: function (data) {
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            $("#tmp_src").val(value.file1);
//                            movePic(value.file1);
                            $("#form1").submit();
                            removecloud();
                        }
                    });
                },
            });
        } else {
//            if(logo_src){
            $("#form1").submit();
            removecloud();
//            }else{
//                alert('Please upload Logo');
//                return false;
//            }
        }
    }


    /**
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     *            用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){
        var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte
//        alert(5);
        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }

</script>