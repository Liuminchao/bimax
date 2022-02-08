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
$remark = $model->remark;
?>
<div class="row">
    <p style="color:red;font-size:16px;">Document name cannot contain special characters and symbols such as . / and etc.</p>
</div>
<div class="box-body">
    <div class="row">
        <div class="col-md-12">
            <h3 class="form-header text-blue"><?php echo Yii::t('comp_contractor', 'Base Info'); ?></h3>
        </div>
    </div>
    <div >
        <input type="hidden" id="tmp_src" name="Contractor[tmp_src]" />
        <input type="hidden" id="logo_src" value="<?php echo "$remark"; ?>" />
        <input type="hidden" id="filebase64">
        <input type="hidden" id="upload_url" value="<?php echo "$upload_url" ?>"/>
        <input type="hidden" id="suffix"/>
        <input type="hidden" id="max_size" name="Contractor[max_size]">
        <input type="hidden" id="pro_cnt" name="Contractor[pro_cnt]">
        <?php
        if ($_mode_ == 'edit') {
            $params = json_decode($model->params,true);
            $pro_cnt = $params['pro_cnt'];
         }else {
            $pro_cnt = 0;
        }
        ?>

        <input type="hidden" id="procnt" value="<?php echo "$pro_cnt"; ?>" />
    </div>
    <div class="row">
        <div class="form-group">
            <label for="contractor_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('contractor_name'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'contractor_name', array('id' => 'contractor_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_contractor', 'Error contractor_name is null'))); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="contractor_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('short_name'); ?>(<?php echo Yii::t('common','input_chinses'); ?>)</label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'short_name', array('id' => 'short_name', 'class' => 'form-control','onkeyup'=>'WidthCheck(this,20);', 'check-type' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="company_sn" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('company_sn'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'company_sn', array('id' => 'company_sn', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_contractor', 'Error company_sn is null'))); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="company_adr" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('company_adr'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'company_adr', array('id' => 'company_adr', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('comp_contractor', 'Error Address is null'))); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="link_tel" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('link_person'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'link_person', array('id' => 'link_person', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="link_phone" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('link_phone'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <?php echo $form->activeTextField($model, 'link_phone', array('id' => 'link_phone', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group" ><!--  照片  -->
            <div class="form-group" >
                <label for="face_img" class="col-sm-3 control-label padding-lr5" ><?php echo $model->getAttributeLabel('remark');?></label>
                    <?php echo $form->activeFileField($model, 'remark', array('id' => 'remark', 'class' => 'form-control', "check-type" => "", 'style' => 'display:none', 'onchange' => "dealImage(this)")); ?>
                        <div class="input-group col-md-6 padding-lr5">
                            <input id="uploadurl" class="form-control" type="text" disabled>
                                <span class="input-group-btn">
                                    <a class="btn btn-warning" onclick="$('input[id=remark]').click();">
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
                    <label for="face_img"
                           class="col-sm-3 control-label padding-lr5 img_class"><?php echo $model->getAttributeLabel('remark');?></label>
                    <div class="col-sm-3 padding-lr5 img_class">
                        <img width="90" id="photo" src=""/>
                        <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                    </div>
                </div>

        <?php }else{ ?>

                <div class="form-group">
                    <label for="face_img"
                           class="col-sm-3 control-label padding-lr5 img_class"><?php echo $model->getAttributeLabel('remark');?></label>
                    <div class="col-sm-3 padding-lr5 img_class">
                        <?php
                            $new_img_url = $model->remark;
                        ?>
                        <?php if($model->remark){ ?>
                            <img width="90" id="photo" src="<?php echo $new_img_url; ?>"/>
                        <?php }else{ ?>
                            <img width="90" id="photo" src=""/>
                        <?php } ?>
                        <!--                    <div id="preview" style="width: 40px;height: 30px"></div>-->
                    </div>
                </div>
        <?php } ?>
    </div>
    <?php if($_mode_ == 'insert'){ ?>
        <div class="row">
            <div class="form-group">
                <label for="login_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('file_space'); ?></label>
                <div class="col-sm-6 padding-lr5" style="padding-top: 6px">
                    <input type="radio" name="size_radio" id="size_sc" checked="checked" />3G
                    <input type="radio" name="size_radio" id="size_mc" >5G
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group" >
                <label for="type_id" class="col-sm-3 control-label padding-lr5">Contractor Type</label>
                <div class="col-sm-3 padding-lr5">
                    <select class="form-control input-sm" name="Contractor[contractor_type]" id="contractor_type" >
                        <option value="MC">MC</option>
                        <option value="SC">SC</option>
                    </select>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if($_mode_ == 'edit'){ ?>
        <div class="row">
            <div class="form-group" >
                <label for="type_id" class="col-sm-3 control-label padding-lr5">Contractor Type</label>
                <div class="col-sm-3 padding-lr5">
                    <select class="form-control input-sm" name="Contractor[contractor_type]" id="contractor_type" >
                        <?php if($model->contractor_type == 'MC'){ ?>
                            <option value="MC" selected="selected">MC</option>
                            <option value="SC">SC</option>
                        <?php }else{ ?>
                            <option value="MC">MC</option>
                            <option value="SC" selected="selected">SC</option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="form-group">
            <label for="login_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('mc_pro_cnt'); ?></label>
            <div class="col-sm-6 padding-lr5">
                <input id="spinner" name="value" onkeyup="value=value.replace(/[^\d]/g,'')">
            </div>
        </div>
    </div>

    <?php if ($_mode_ == 'insert') { ?>
        <div class="row">
            <div class="col-md-12">
                <h3 class="form-header text-blue"><?php echo Yii::t('comp_contractor', 'Login Info'); ?></h3>
            </div>
        </div>
        <div class="row">
            <div class="form-group">
                <label for="login_name" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('operator_name'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'operator_name', array('id' => 'operator_name', 'class' => 'form-control', 'check-type' => 'required', 'required-message' => Yii::t('sys_operator', 'Error operator_name is null'))); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="login_phone" class="col-sm-3 control-label padding-lr5"><?php echo $model->getAttributeLabel('operator_phone'); ?></label>
                <div class="col-sm-6 padding-lr5">
                    <?php echo $form->activeTextField($model, 'operator_phone', array('id' => 'operator_phone', 'class' => 'form-control', 'check-type' => '', 'required-message' => '')); ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="sumitImageFile();"><?php echo Yii::t('common', 'button_save'); ?></button>
                <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $this->endWidget(); ?>
<script src="js/loading.js"></script>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        $("#form1").validation(function (obj, params) {
            if (obj.id == 'pw2' && $("#pw2").val() != $("#pw1").val()) {
                params.err = '<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>';
                params.msg = "<?php echo Yii::t('sys_operator', 'Error pwd is not eq'); ?>";
            }
        });
        var procnt = $('#procnt').val();
        var spinner = $( "#spinner" ).spinner();
        spinner.spinner({min: 1});
        if(procnt == 0){
            spinner.spinner( "value", 1 );
        }else{
            spinner.spinner( "value", procnt );
        }
    });
    //简称字数限制
    function WidthCheck(str, maxLen){
        var w = 0;
        var tempCount = 0;
        //length 获取字数数，不区分汉子和英文
        for (var i=0; i<str.value.length; i++) {
            //charCodeAt()获取字符串中某一个字符的编码
            var c = str.value.charCodeAt(i);
            //单字节加1
            if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
                w++;
            } else {
                w+=2;
            }
            if (w > maxLen) {
                str.value = str.value.substr(0,i);
                break;
            }
        }
    }
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['info/list']; ?>";
        // window.location = "index.php?r=comp/info/list";
    }

    //压缩图像转base64
    function dealImage(file)
    {
        document.getElementById('uploadurl').value=file.value;
        if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG)$/.test(file.value)) {
            alert("File types must be GIF, JPEG, JPG, PNG.");
            return false;
        }

        var video_src_file = $("#remark").val();
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
            new_w = 80;
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
        var file = $("#remark").val();
        if(file != ''){
            var suffix = document.getElementById("suffix").value;
        }else{
            var suffix = 0;
        }
        if($("#szie_sc").prop("checked")){
            $("#max_size").val(3);
        }
        if($("#size_mc").prop("checked")){
            $("#max_size").val(5);
        }
        var spinner = $( "#spinner" ).spinner();
        var pro_cnt = spinner.spinner( "value" );
        $("#pro_cnt").val(pro_cnt);

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