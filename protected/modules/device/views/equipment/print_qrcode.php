<?php
$model = Device::model()->findByPk($primary_id);
$type_no = $model->type_no;
$type_list = DeviceType::deviceList();
$contractor_id = $model->contractor_id;
$com_model = Contractor::model()->findByPk($contractor_id);
$PNG_TEMP_DIR = Yii::app()->params['upload_data_path'] .'/qrcode/'.$contractor_id.'/device/';
$file_name = $PNG_TEMP_DIR.$primary_id.'_new.png';
?>
<div id="detail-print">
    <h1 style="text-align: center"><?php echo $com_model->contractor_name ?></h1>
    <table align="center" frame="hsides" width="500px">
        <tr>
            <td style="white-space: nowrap;"><span style="font-size: 15px;font-weight:bold;margin-right: 5px ">NAME:</span><span><?php echo $model->device_name ?></span></td>
            <td rowspan="4" align="right"><img src="<?php echo $file_name; ?>"></td>
        </tr>
        <tr>
            <td><span style="font-size: 15px;font-weight:bold;margin-right: 5px  ">CONTRACTOR:</span><span><?php echo $com_model->contractor_name ?></span></td>
        </tr>
        <tr>
            <td><span style="font-size: 15px;font-weight:bold;margin-right: 5px  ">Registration No.:</span><span><?php echo $model->device_id ?></span></td>
        </tr>
        <tr>
            <td><span style="font-size: 15px;font-weight:bold;margin-right: 5px  ">Equipment Type:</span><span><?php echo $type_list[$type_no] ?></span></td>
        </tr>
    </table>
    <?php
    if($com_model->remark){
        $logo = '/opt/www-nginx/web'.$com_model->remark;
        ?>
        <p style="width:900px;text-align:right;" align="center"><img src="<?php echo $logo; ?>" ></p>
    <?php } ?>

</div>
<center><button type="button" id="sbtn" style="padding: 6px 12px;" onclick="print();"><?php echo Yii::t('common','button_print'); ?></button></center>
<script src="js/jquery.1.7.min.js"></script>
<script src="js/jquery.jqprint-0.3.js"></script>
<script type="text/javascript">
    var print = function(){
        $("#detail-print").jqprint({
            debug: false, //如果是true则可以显示iframe查看效果（iframe默认高和宽都很小，可以再源码中调大），默认是false
            importCSS: true, //true表示引进原来的页面的css，默认是true。（如果是true，先会找$("link[media=print]")，若没有会去找$("link")中的css文件）
            printContainer: true, //表示如果原来选择的对象必须被纳入打印（注意：设置为false可能会打破你的CSS规则）。
            operaSupport: true//表示如果插件也必须支持歌opera浏览器，在这种情况下，它提供了建立一个临时的打印选项卡。默认是true
        });
    };
    //function UserPrint()
    //{
    //    bdhtml=window.document.body.innerHtml;
    //
    //    var headstr = "<html><head></head><body>";
    //    var footstr = "</body>";
    //    var bodystr = document.all.item("detail-print").innerHTML;
    //    var oldstr = document.body.innerHTML;
    //
    //    document.body.innerHTML = headstr + bodystr + footstr;
    //    pagesetup_null();
    //    window.print();
    ////    pagesetup_default();
    //    document.body.innerHTML = oldstr;
    //    return false;
    //}
    //
    //var hkey_root,hkey_path,hkey_key
    //hkey_root="HKEY_CURRENT_USER"
    //hkey_path="\\Software\\Microsoft\\Internet Explorer\\PageSetup\\"
    ////设置网页打印的页眉页脚为空
    //function pagesetup_null(){
    //    try{
    //        var RegWsh = new ActiveXObject("WScript.Shell")
    //        hkey_key="header"
    //        RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"")
    //        hkey_key="footer"
    //        RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"")
    //    }catch(e){}
    //}
    ////设置网页打印的页眉页脚为默认值
    //function pagesetup_default(){
    //    try{
    //        var RegWsh = new ActiveXObject("WScript.Shell")
    //        hkey_key="header"
    //        RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"&w&b页码，&p/&P")
    //        hkey_key="footer"
    //        RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"&u&b&d")
    //    }catch(e){}
    //}
</script>