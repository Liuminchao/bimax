<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
        .btn1{
            -webkit-transform-style: preserve-3d;
            -moz-transform-style: preserve-3d;
            -ms-transform-style: preserve-3d;
            -o-transform-style: preserve-3d;
            transform-style: preserve-3d;
            -webkit-transition: -webkit-transform .2s;
            -moz-transition: -moz-transform .2s;
            -ms-transition: -ms-transform .2s;
            -o-transition: -o-transform .2s;
            transition: transform .2s;
            width:60px;
            height: 30px;
            border: 0px;
            display: block;
            float: left;
            margin-left: 6px;
        }
        .btn1:hover{
            -webkit-transform:scale(1.05);
            transform:scale(1.05);
        }
        .btn1:ACTIVE{
            -webkit-transform:scale(0.9);
            transform:scale(0.9);
        }
    </style>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/lanrenzhijia.js"></script>
    </head>

    <div id='msgbox' class='alert alert-dismissable ' style="display:none;">
        <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
        <strong id='msginfo'></strong><span id='divMain'></span>
    </div>

    <form id="form1">
        <div style="width: 55%;height:100%;float: left">
            <!--    前一个页面传过来的截图-->
            <input type="hidden" id="pic"  value="<?php echo $pic; ?>">
            <input type="hidden" id="file" name="AttachNot[file]" value="<?php echo $file; ?>">
            <input type="hidden" id="check_id"  name="AttachNot[check_id]" value="<?php echo $check_id; ?>">
            <input type="hidden" id="attach_id"  name="AttachNot[attach_id]" value="<?php echo $attach_id; ?>">
            <!--    批注后的截图 -->
            <input type="hidden" id="new_pic"  name="AttachNot[pic]">

            <canvas id="canvas" width="800" height="730" style="border:1px solid #d3d3d3;position:absolute;"></canvas>
            <canvas id="canvas2" width="800" height="730" style="border:1px solid #d3d3d3;position:absolute;"></canvas>
        </div>
        <div style="width: 45%;height:100%;float: left">
            <div class="row" style="margin-top: 30px">
                <div class="form-group">
                    <span class="btn1" onClick="change_attr(0,-1,-1)" style="background-image: url(https://shell.cmstech.sg/test/idd/htmlapi/img/pencil.png)"></span>
                    <span class="btn1" onClick="change_attr(1,-1,-1)" style="background-image: url(https://shell.cmstech.sg/test/idd/htmlapi/img/straight.png)"></span>
                    <span class="btn1" onClick="change_attr(2,-1,-1)" style="background-image: url(https://shell.cmstech.sg/test/idd/htmlapi/img/star_straight.png)"></span>
                    <span class="btn1" onClick="change_attr(3,-1,-1)" style="background-image: url(https://shell.cmstech.sg/test/idd/htmlapi/img/circle.png)"></span>
                    <span class="btn1" onClick="change_attr(4,-1,-1)" style="background-image: url(https://shell.cmstech.sg/test/idd/htmlapi/img/rect.png)"></span>
                    <span class="btn1" onClick="change_attr(5,-1,-1)" style="background-image: url(https://shell.cmstech.sg/test/idd/htmlapi/img/eraser.png)"></span>
                    <span class="btn1" onClick="reset()" style="background-image: url(https://shell.cmstech.sg/test/idd/htmlapi/img/reset.png)"></span>
<!--                    <span class="btn1" onClick="fill_canvas('#ffffff',0,0,canvas_size.x,canvas_size.y)" style="background-image: url(https://shell.cmstech.sg/test/idd/htmlapi/img/clear.png)"></span>-->
                </div>
            </div>
            <div class="row" style="margin-top: 30px">
                <div class="form-group">
                    <span id="size_span" style="border: 1px solid #999;width:15px;height: 15px;margin-top:7px;margin-left: 50px;display: block;float: left;margin-left: 20px">1</span>
                    <div id="size_bar" style="width: 100px;height: 5px;background-color:#999; float: left;margin: 12px;position: relative;">
                        <span id="size_thumb" class="btn1" onClick="" style="background-color:#666;;width: 15px; border-top-left-radius:8px; border-top-right-radius:8px; border-bottom-left-radius:8px;
                            border-bottom-right-radius:8px;height: 15px;margin:0px; margin-top:-5px;position: absolute;left: 0px;"></span>
                    </div>
                    <span id="color_span" style="border: 1px solid #999;background-color:#00aeef;width:15px;height: 15px;margin-top:7px;display: block;float: left;margin-left: 10px"></span>
                    <canvas id="canvas_color" width="198" height="15" style="border:1px solid #999;margin-top:7px;margin-left:10px;float:left;"></canvas>
                </div>
            </div>
            <div class="row" style="margin-top: 30px">
                <div class="form-group">
                    <label for="type_name_en" class="col-sm-3 control-label padding-lr5">page:</label>
                    <div class="col-sm-3 padding-lr5">
                        <input type="text" class="form-control" name="AttachNot[pagenumber]"  value="<?php echo $pagenumber; ?>" readonly>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 30px">
                <div class="form-group">
                    <label for="type_name_en" class="col-sm-3 control-label padding-lr5">Remark:</label>
                    <div class="col-sm-6 padding-lr5">
                        <textarea rows="20" name="AttachNot[remark]" cols="50"></textarea>
                    </div>
                </div>
            </div>
    </form>

    <div class="row" style="margin-top: 30px;">
        <button type="button" id="sbtn" style="margin-left: 30px" class="btn btn-primary btn-lg" onclick="dealImage();">Save</button>
        <button type="button" class="btn btn-default btn-lg" style="margin-left: 10px" onclick="back();">Back</button>
    </div>

    <script>
        $(function() {

        });

        function reset() {
            open_img($('#pic').val());
        }

        function create(){
            var img=document.createElement("img");
            var src = $('#pic').val();
//        var src = sessionStorage.getItem('pic');
            img.src = src;
            var c=document.getElementById("myCanvas");
            var ctx=c.getContext("2d");
//        var img=document.getElementById("tulip");
            ctx.drawImage(img,0,0,800,730);
        }

        function change_attr(tp,sz,clr){
            if(tp!=-1)
                type=tp;
            if(clr!=-1){
                context.strokeStyle=clr;
            }
            if(sz!=-1){
                context.lineWidth = sz;
            }
        }

        //取消
        function back () {
            var check_id = $('#check_id').val();
            var attach_id = $('#attach_id').val();
            var file = $('#file').val();
            window.location = "index.php?r=rf/rf/preview&file="+file+"&check_id="+check_id+"&attach_id="+attach_id+"&tag=0";
        }

        //保存
        function save() {
            $.ajax({
                data:$('#form1').serialize(),
                url: "index.php?r=rf/rf/saveattachnote",
                type: "POST",
                dataType: "json",
                beforeSend: function () {

                },
                success: function (data) {
                    if(data.status == 1) {
                        $('#msgbox').addClass('alert-success fa-ban');
                        $('#msginfo').html(data.msg);
                        $('#msgbox').show();
                        back();
                    }else{
                        $('#msgbox').addClass('alert-danger fa-ban');
                        $('#msginfo').html(data.msg);
                        $('#msgbox').show();
                    }
                },
                error: function () {
                    $('#msgbox').addClass('alert-danger fa-ban');
                    $('#msginfo').html('系统超时');
                    $('#msgbox').show();
                }
            });
        }

        function dealImage()
        {
            //生成canvas
            var canvas = document.getElementById("canvas");
            // 图像质量
            quality = 0.9;
            // quality值越小，所绘制出的图像越模糊
            var base64 = canvas.toDataURL();
            // 生成结果
            var result = {
                base64 : base64,
                clearBase64 : base64.substr(base64.indexOf(',') + 1)
            };
            var form = document.forms[0];

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数

            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("file1", convertBase64UrlToBlob(base64), 'sign'+'.jpg');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
            $.ajax({
                url: 'https://shell.cmstech.sg/appupload',
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // 告诉jQuery不要去处理发送的数据
                contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
                success: function (data) {
                    $.each(data, function (name, value) {
                        if (name == 'data') {
                            var file_src = value.file1;
                            $('#new_pic').val(file_src);
                            save();
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
        }

        /**
         * 将以base64的图片url数据转换为Blob
         * @param urlData
         *            用url方式表示的base64图片数据
         */
        function convertBase64UrlToBlob(urlData){

            var bytes=window.atob(urlData.split(',')[1]);        //去掉url的头，并转换为byte

            //处理异常,将ascii码小于0的转换为大于0
            var ab = new ArrayBuffer(bytes.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < bytes.length; i++) {
                ia[i] = bytes.charCodeAt(i);
            }

            return new Blob( [ab] , {type : 'image/png'});
        }
    </script>


