<input type="hidden" id="program_id" value="<?php echo $program_id ?>" >
<input type="hidden" id="version" value="<?php echo $version ?>" >
<input type="hidden" id="model_id" value="<?php echo $model_id ?>" >

<div id="detail-print">
    <table id="qr_table" align="center" frame="hsides" width="500px">
    </table>
</div>
<center><button type="button" id="sbtn" style="padding: 6px 12px;" onclick="print();"><?php echo Yii::t('common','button_print'); ?></button></center>
<script src="js/jquery.1.7.min.js"></script>
<script src="js/loading.js"></script>
<script src="js/jquery.jqprint-0.3.js"></script>
<script type="text/javascript" src="js/WIND.js"></script>
<script type="text/javascript">
    let WINDData = WIND.WINDData;
    let WINDView = WIND.WINDView;
    let ViewStateType = WIND.ViewStateType;
    let LeftMouseOperation = WIND.LeftMouseOperation;
    let TreeRuleType = WIND.TreeRuleType;
    let MeasureType = WIND.MeasureType;
    let setLanguageType = WIND.setLanguageType;
    let CallbackType = WIND.CallbackType;

    //WINDData初始化
    let config = {};
    // config.serverIp = 'https://engine.everybim.net';//译筑测试公有云，仅限测试使用
    config.serverIp = 'https://bim.cmstech.sg';
    // config.appKey = 'ZX7sOit3IEbMvQxwBOhfRIQBRvjmNHq38FP6';
    config.appKey = 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT';
    // config.appSecret = 'ef1ae3c72df0cfad9ff0fdf81b5e2a36e1aed60b521824beb0e46ad180d2760a';
    config.appSecret = '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f';
    let data = new WINDData(config);
    $(document).ready(function() {
        load();
    })
    /*
     * 读取接口获取总记录数目
     */
    var per_read_cnt = 40;

    async function load() {
        var program_id = $('#program_id').val();
        var version = $('#version').val();
        var model_id = $('#model_id').val();
//        let arr = await data.getWINDDataQuerier().getAllComponentParameterS(model_id,false,version);
//        console.log(arr);

        addcloud();
        jQuery.ajax({
            data: {program_id:program_id, version:version, model_id:model_id},
            type: 'post',
            url: './index.php?r=rf/rf/getmodeldata',
            dataType: 'json',
            success: function (data, textStatus) {
//                $('#qr_table').append("</br>Loading...");
                ajaxReadData(program_id,version,model_id, data.rowcnt, 1);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(XMLHttpRequest);
                alert(textStatus);
                alert(errorThrown);
            },
        });
        return false;
    }

    /*
     * 加载数据
     */
    var ajaxReadData = function (program_id,version,model_id, rowcnt, startrow){//alert('aa');

        jQuery.ajax({
            data: {program_id:program_id, version:version, model_id:model_id, startrow: startrow, per_read_cnt:per_read_cnt},
            type: 'post',
            url: './index.php?r=rf/rf/readmodeldata',
            dataType: 'json',
            success: function (data, textStatus) {
                for (var o in data) {
                    $('#prompt').append("</br>Row "+o+" : "+data[o].msg);
                    $('#qr_table').append("<tr><td colspan='2' align='center'><h1 style='text-align: center'>"+data[o].type+"</h1></td></tr>");
                    $('#qr_table').append("<tr><td style='white-space: nowrap;'><span style='font-size: 15px;font-weight:bold;margin-right: 5px '>Model Id:</span><span>"+data[o].model_id+"</span></td><td rowspan='3' align='right'><img src='"+data[o].filename+"'></td> </tr>");
                    $('#qr_table').append("<tr><td><span style='font-size: 15px;font-weight:bold;margin-right: 5px'>entityId:</span><span>"+data[o].entityId+"</span></td> </tr>");
                    $('#qr_table').append("<tr><td><span style='font-size: 15px;font-weight:bold;margin-right: 5px'>uuid:</span><span>"+data[o].uuid+"</span></td> </tr>");
                }
                if (rowcnt > startrow) {
                    ajaxReadData(program_id,version,model_id, rowcnt, startrow+per_read_cnt);
                }else{
                    clearCache();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },
        });
        return false;
    }

    /*
     * 加载数据
     */
    var clearCache = function(program_id,version,model_id){//alert('aa');

        jQuery.ajax({
            data: {program_id:program_id, version:version, model_id:model_id},
            type: 'post',
            url: './index.php?r=rf/rf/clearcache',
            dataType: 'json',
            success: function (data, textStatus) {
                removecloud();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                //alert(XMLHttpRequest);
                //alert(textStatus);
                //alert(errorThrown);
            },
        });
        return false;
    }

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