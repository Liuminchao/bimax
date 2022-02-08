<!--<link href="css/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" type="text/css" />-->
<link href="css/select2/fselect.css" rel="stylesheet" type="text/css" />
<style type="text/css">
    .level_tab td:nth-child(2){
        display: none;
    }
    .level_tab th{
        text-align: center;
    }
    /*#status_tab td:nth-child(2){*/
    /*    display: none;*/
    /*}*/
    /*#status_tab td:nth-child(3){*/
    /*    display: none;*/
    /*}*/
    #status_tab th{
        text-align: center;
    }
</style>
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-12">
        <div class="dataTables_length">
            <ul class="nav nav-tabs" role="tablist" id="myTab">
                <li role="presentation" class="active"><a href="index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>" role="tab" data-toggle="tab">Records</a></li>
                <li role="presentation"><a href="index.php?r=rf/rf/list&program_id=<?php echo $program_id; ?>&status=-1" role="tab" data-toggle="tab">Draft</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row" >
    <div class="col-xs-12" >
        <div class="tab-content" >
            <div class="tab-pane" activeid="settings" >
                <iframe id="attendIframe" name="attendIframe" frameborder="0" class="iframe_r" src="<?php echo "?r=task/model/list&program_id=".$program_id;?>" style="height: 600px;width:100%; background-color:#fff;"></iframe>
            </div>
            <div class="tab-pane" id="qr_settings" style="height:650px;">
                <iframe id="attendIframe" name="attendIframe" frameborder="0" class="iframe_r" src="<?php echo "?r=task/model/newqr&program_id=".$program_id;?>" style="height:100%;width:100%; background-color:#fff;"></iframe>
            </div>
        </div>
    </div>
</div>

<!--<script type="text/javascript" src="js/bootstrap-select/bootstrap-select.min.js"></script>-->
<!--<script src="js/browser.js" type="text/javascript" ></script>-->
<!--<script src="js/browser-polyfill.js" type="text/javascript" ></script>-->
<script type="text/javaascript" src="js/select2/fselect.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">
    $(function () {
        // var t = window.devicePixelRatio   // 获取下载的缩放 125% -> 1.25    150% -> 1.5
        // document.write('您的显示器分辨率为:\n' + screen.width + '*' + screen.height + ' pixels<br/>');
        // var w1cm = document.getElementById("hutia").offsetWidth, w = screen.width/w1cm, h = screen.height/w1cm, r = Math.round(Math.sqrt(w*w + h*h) / 2.54);
        // document.write('您的显示器尺寸为:\n' + (screen.width/w1cm).toFixed(1) + '*' + (screen.height/w1cm).toFixed(1) + ' cm, '+ r +'寸<br/>');
        // alert(t);
        // document.getElementById("content-header").style.display="none";//隐藏
        // $("#weatherType").selectpicker('refresh');
        $('#myTab a').click(function (e) {
            console.log($(this).context);
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
            var tab_text = $(this).context.text;
            console.log(tab_text);
        })
    })

</script>