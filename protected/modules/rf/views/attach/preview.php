<style>
    .pdfobject-container { height: 100%;}
    .pdfobject { border: 1px solid #666; }
</style>
<!--<div class="col-xs-12">-->
<!--    <div class="dataTables_filter" >-->
<!--        <label>-->
<!--            <button class="btn btn-primary btn-sm" onclick="back()">--><?php //echo Yii::t('electronic_contract', 'back'); ?><!--</button>-->
<!--        </label>-->
<!--    </div>-->
<!--</div>-->
<!--<div id="example1"></div>-->
<iframe id="pdfBox" frameborder="1" width="1116" height="758"></iframe>
<!--<script src="js/AdminLTE/app.js"></script>-->
<!--<script src="js/pdf/pdfobject.js"></script>-->
<!-- jQuery 2.1.1 -->
<script src="js/jquery-2.1.1.min.js"></script>
<script>
    //返回
//    var back = function () {
//        window.location = "index.php?r=document/platform/list";
//    }
//    PDFObject.embed("<?php //echo $doc_path;  ?>//", "#example1");
    $(document).ready(function(){

        var iframe = $('#pdfBox');

        iframe.attr('src',"htmlapi/viewer.html?file=<?php echo $doc_path;  ?>");
        iframe.attr('width',"100%");
        iframe.attr('height',"100%");
    });
</script>