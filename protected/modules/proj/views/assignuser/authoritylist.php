<script src="js/loading.js"></script>
<script type="text/javascript" src="js/zDrag.js"></script>
<script type="text/javascript" src="js/zDialog.js"></script>
<script type="text/javascript">
    $(function(){
        itemQuery();
    });
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
    //添加
    var itemAdd = function (program_id) {
        var modal=new TBModal();
        modal.title="Add Person";
        modal.url="./index.php?r=proj/assignuser/addperson&program_id="+program_id;
        // modal.url = "index.php?r=rf/rf/bashconfirmforward";
        modal.modal();
//        window.location = "index.php?r=comp/staff/new";
//         var title = '1';
        // window.location = "index.php?r=proj/staff/tabs&mode=insert&title="+title+"&program_id="+program_id;
    }
    //修改
    var itemEdit = function (id,program_id) {
        var title = '2';
        window.location = "index.php?r=proj/staff/tabs&user_id=" + id+"&program_id="+program_id+"&mode=edit&title="+title;
    }
    //查询行业资质
    var itemPhoto = function (id) {
        window.open("index.php?r=comp/staff/attachlist&user_id="+id);
    }
    //下载人员入场表
    var itemDownload = function(program_id,user_id){
        window.location = "index.php?r=proj/assignuser/downloadstaff&program_id="+program_id+"&user_id="+user_id;
    }
    //EPSS设置
    var itemEpss = function (program_id,user_id) {
        window.location = "index.php?r=proj/assignuser/epss&program_id="+program_id+"&user_id="+user_id;
    }
    //删除人员
    var itemDelete = function (program_id,user_id,user_name) {

        if (!confirm('<?php echo Yii::t('common', 'confirm_delete_1'); ?>' + user_name + '<?php echo Yii::t('common', 'confirm_delete_2'); ?>')) {
            return;
        }
       // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {program_id: program_id,user_id:user_id, confirm: 1},
            url: "index.php?r=proj/assignuser/deleteuser",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_delete'); ?>");
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_delete'); ?>");
                    alert(data.msg);
                }
            }
        });
    }
    var itemWorkforceSync = function (program_id) {
        var diag = new Dialog();
        diag.Width = 930;
        diag.Height = 980;
        diag.Title = "Manpower Sync";
        diag.URL = "syncworkforce&program_id="+program_id;
        diag.show();
    }
    //设置出场人员
    var itemLeave = function (program_id,user_id,user_name) {

        if (!confirm('<?php echo Yii::t('common', 'confirm_leave_1'); ?>' +user_name+ '<?php echo Yii::t('common', 'confirm_leave_2'); ?>')) {
            return;
        }
       // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {program_id: program_id,user_id:user_id, confirm: 1},
            url: "index.php?r=proj/assignuser/leaveuser",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_remove'); ?>");
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_remove'); ?>");
                    alert(data.msg);
                }
            }
        });
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
    var per_cnt = 5;
    //提交入场申请
    var itemEntrance = function (list,program_id) {
    //alert(list);
       // alert("index.php?r=comp/usersubcomp/logout&confirm=1&id="+id);
        $.ajax({
            data: {list: list,program_id: program_id,confirm: 1},
            url: "index.php?r=proj/assignuser/entranceuser",
            dataType: "json",
            type: "POST",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_apply'); ?>");
                    removecloud();//去遮罩
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_apply'); ?>");
                    alert(data.msg);
                }
//                var start_cnt = data.start_cnt;
//                var cnt = data.cnt;
//                itemEntranceFace(list,program_id,start_cnt,cnt);
            }
        });
    }
    //入场申请更新人脸
    var itemEntranceFace = function (list,program_id,start_cnt,cnt) {
        $.ajax({
            data: {list: list,program_id: program_id,start_cnt:start_cnt,cnt:cnt},
            url: "index.php?r=proj/assignuser/entranceuserface",
            dataType: "json",
            type: "POST",
            success: function (data) {
                var startcnt = start_cnt+per_cnt;
                if (cnt > startcnt) {
                    itemEntranceFace(list,program_id,startcnt,cnt);
                }else{
                    removecloud();//去遮罩
                    if (data.refresh == true) {
                        alert("<?php echo Yii::t('common', 'success_apply'); ?>");
                        <?php echo $this->gridId; ?>.refresh();
                    } else {
                        //alert("<?php echo Yii::t('common', 'error_apply'); ?>");
                        alert(data.msg);
                    }
                }
            }
        });
    }
    //Dshboard控制是否显示
    var itemHideRobox = function (id, name) {
        if (!confirm("Confirm to cancel Robox Admin Role?")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=proj/assignuser/hiderobox",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("Set Success");
                    itemQuery();
                } else {
                    alert("Set Error");
                }
            }
        });
    }
    //Dshboard控制是否显示
    var itemShowRobox = function (id, name) {
        if (!confirm("Confirm to set Robox Admin Role?")) {
            return;
        }
        $.ajax({
            data: {id: id, confirm: 1},
            url: "index.php?r=proj/assignuser/showrobox",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("Set Success");
                    itemQuery();
                } else {
                    alert("Set Error");
                }
            }
        });
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
                    <?php $this->renderPartial('authority_toolBox',array('program_id' => $program_id,'args'=>$args)); ?>
                    <div id="datagrid"><?php $this->actionAuthorityGrid($program_id); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
