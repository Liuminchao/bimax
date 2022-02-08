/**
 * Created by minchao on 2017-01-22.
 */
//压缩图像转base64
function dealImage(file)
{
    //array = new array();
    //var filename = file.value;
    //var mime = filename.toLowerCase().substr(filename.lastIndexOf("."));
    //alert(mime);
    var arr = new Array();
    arr["face_img"]="uploadurl";
    arr["home_id_photo"]="uploadurl_per";
    arr["ppt_photo"]="uploadurl_pass";
    arr["bca_photo"]="uploadurl_bca";
    arr["csoc_photo"]="uploadurl_csoc";
    arr["aptitude_photo"]="uploadurl";
    arr["device_img"]="device_url";
    arr["chemical_img"]="chemical_url";
    arr["certificate_photo"]="uploadurl";
    arr["remark"]="logo_url";
    document.getElementById(arr[file.id]).value=file.value;
    var URL = window.URL || window.webkitURL;
    var blob = URL.createObjectURL(file.files[0]);
    var img = new Image();
    img.src = blob;
    img.onload = function() {
        var that = this;
        //生成比例
        var w = that.width, h = that.height, scale = w / h;
        if(file.id == 'face_img'){
            //alert(300);
            new_w = 300;
            new_h = new_w / scale;
        }else{
            //alert(600);
            new_w = 600;
            new_h = new_w / scale;
        }


        //生成canvas
        var canvas = document.createElement('canvas');
        var ctx = canvas.getContext('2d');
        $(canvas).attr({
            width : new_w,
            height : new_h
        });
        ctx.drawImage(that, 0, 0, new_w, new_h);
        // 图像质量
        quality = 0.9;
        // quality值越小，所绘制出的图像越模糊
        var base64 = canvas.toDataURL('image/jpeg', quality);
        // 生成结果
        var result = {
            base64 : base64,
            clearBase64 : base64.substr(base64.indexOf(',') + 1)
        };
        $("#filebase64").val(result.base64);
        //alert(document.getElementsByTagName('img').length);
        //document.getElementsByTagName('img')[0].setAttribute('src',result.base64);
        document.getElementById('photo').setAttribute('src',result.base64);
        file.src = result.base64;
    };
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

function btnsubmit(){
    sumitImageFile($('#filebase64').val());
}
function array(){
    var arr = new Array();
    arr["face_img"]="uploadurl";
    arr["home_id_photo"]="uploadurl_per";
    arr["ppt_photo"]="uploadurl_pass";
    arr["bca_photo"]="uploadurl_bca";
    arr["csoc_photo"]="uploadurl_csoc";
    return arr;
}