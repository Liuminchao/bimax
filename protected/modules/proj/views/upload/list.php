<script type="text/javascript">
    //返回
    var back1 = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    //上传
    var itemUpload = function () {
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';
        
        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }

        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('task', 'smallHeader Upload'); ?>";
        modal.url = "index.php?r=proj/upload/upload"+url;
        modal.modal();
    }
    
    //照片显示方法   
    $('.img_class').mouseout (function(){
        $("#attendImg").hide();
    });
    
    $('.img_class').mousemove (function(){
        img_url = $(this).attr("src");
        setImg(img_url,this);
        $("#attendImg").show();
     });
    
    function setImg(img_url,obj){
        var src,h;
        src=document.getElementById("photo").src;//alert(src);
        $("#attendPhoto").attr("src",src);
        h=$("#attendImg").innerHeight();
        //document.getElementById("attendImg").style.top=($(obj).position().top-h+253)+"px";
        //document.getElementById("attendImg").style.left=($(obj).position().left-350)+"px";
        $("#attendImg").css('top', ($(obj).position().top-h+450)+"px");
        $("#attendImg").css('left', ($(obj).position().left+150)+"px");
    }
    
    </script>
    <div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <div role="grid" class="dataTables_wrapper form-inline" id="<?php echo $this->gridId; ?>_wrapper">
                    <?php $this->renderPartial('_toolBox', array('task_id'=>$task_id,'program_id'=>$program_id)); ?>
                    <div id="datagrid"><?php $this->actionGrid(); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>