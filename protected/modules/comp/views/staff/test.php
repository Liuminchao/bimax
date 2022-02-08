<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>图片压缩</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="js/lrz.mobile.min.js"></script>
    <script type="text/javascript">
        function compress(_this){
            lrz(_this.files[0],{width:300,quality:0.8},function(rst){
                var show_img = new Image();
                show_img.src =  rst.base64;
                $("#img_show").html(show_img);
                $("#filebase64").val(rst.base64);
                _this.src = show_img.src;
            });
        }
    </script>
</head>
<body>

<input id="orifile" type="file" onchange="compress(this)">

<input type="hidden" id="filebase64">

<input type="button" name="submit" value="Upload" onclick="btnsubmit();">
<!--<form enctype="multipart/form-data" action="/upload" method="post">-->
<!---->
<!--</form>-->
<div id="img_show"></div>
</body>
</html>


<script>

    function btnsubmit(){
        sumitImageFile($('#filebase64').val());
    }
    /**
     * @param base64Codes
     *            图片的base64编码
     */
    function sumitImageFile(base64Codes){
        var form=document.forms[0];

        var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数

        //convertBase64UrlToBlob函数是将base64编码转换为Blob
        formData.append("file1",convertBase64UrlToBlob(base64Codes), '123.png');  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同

        //formData.append("file2", $("#orifile")[0].files[0]);
        console.log(form);
        //ajax 提交form
        $.ajax({
            url : "http://47.88.139.53/upload",
            type : "POST",
            data : formData,
            dataType:"json",
            processData : false,         // 告诉jQuery不要去处理发送的数据
            contentType : false,        // 告诉jQuery不要去设置Content-Type请求头

            success:function(data){
                alert(data.data);
                $.each(data,function(name,value) {
                    if(name == 'data'){
                        alert(value.file1);
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