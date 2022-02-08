<script src="js/loading.js"></script>
<script type="text/javascript">
    //查询
    var itemQuery = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
<?php echo $this->gridId; ?>.condition = url;
<?php echo $this->gridId; ?>.refresh();
    }
    //查询行业资质
    var itemPhoto = function (id) {
        window.location = "index.php?r=comp/staff/attachlist&user_id="+id+"&type="+'sc';
    }
    //下载人员入场表
    var itemDownload = function(program_id,user_id){
        window.location = "index.php?r=proj/assignuser/downloadstaff&program_id="+program_id+"&user_id="+user_id;
    }
    //加入DMS
    var itemDms = function (program_id,user_id) {
        $.ajax({
            data: {user_id: user_id,program_id: program_id},
            url: "index.php?r=rf/dms/addgroup",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.code == '100'){
                    alert('Set successful');
                }else{
                    alert(data.message);
                }
            }
        })
    }
    $(document).ready(function(){
        //判断访问终端
        var browser={
            versions:function(){
                var u = navigator.userAgent, app = navigator.appVersion;
                return {
                    trident: u.indexOf('Trident') > -1, //IE内核
                    presto: u.indexOf('Presto') > -1, //opera内核
                    webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                    gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1,//火狐内核
                    mobile: !!u.match(/AppleWebKit.*Mobile.*/), //是否为移动终端
                    ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                    android: u.indexOf('Android') > -1 || u.indexOf('Adr') > -1, //android终端
                    iPhone: u.indexOf('iPhone') > -1 , //是否为iPhone或者QQHD浏览器
                    iPad: u.indexOf('iPad') > -1, //是否iPad
                    webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
                    weixin: u.indexOf('MicroMessenger') > -1, //是否微信 （2015-01-22新增）
                    qq: u.match(/\sQQ/i) == " qq" //是否QQ
                };
            }(),
            language:(navigator.browserLanguage || navigator.language).toLowerCase()
        }
        //判断是否安卓
        if(browser.versions.android){
//            alert("is android");
        }
        //判断是否iphone
        if(browser.versions.iPhone){
//            alert("is iPhone");
        }
        //判断是否ipad
        if(browser.versions.iPad){
//            alert("is iPad");
        }
        //判断是否IE内核
        if(browser.versions.trident){
//            alert("is IE");
        }
        //判断是否webKit内核
        if(browser.versions.webKit){
//            alert("is webKit");
        }
        //判断是否移动端
        if(browser.versions.mobile||browser.versions.android||browser.versions.ios){
//            alert("移动端");
            $("#scroll").css('overflow-x','scroll');
            $("#scroll").css('overflow-y','hidden');
            $("#responsive").css('width','130%');
        }
        //检测浏览器语言
        currentLang = navigator.language; //判断除IE外其他浏览器使用语言
        if(!currentLang){//判断IE浏览器使用语言
            currentLang = navigator.browserLanguage;
        }
//        alert(currentLang);
    });
</script>
<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" style="overflow-x: auto">
                <div role="grid" class="dataTables_wrapper" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('subauthority_toolBox',array('args'=>$args,'program_id' => $program_id)); ?>
                    <div id="datagrid"><?php $this->actionSubAuthorityGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
