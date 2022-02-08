<link href="css/select2.css" rel="stylesheet" type="text/css" />
<link href="css/select2.min.css" rel="stylesheet" type="text/css" />
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5">
                    <input type="text" class="form-control input-sm" name="q[doc_name]" placeholder="<?php echo Yii::t('comp_document', 'document_name'); ?>">
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <select id="select_tags" style="width:110px;" name="q[label_id]">
                    </select>
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemUpload()"><?php echo Yii::t('comp_document', 'upload'); ?></button>
            </label>
        </div>
    </div>
</div>
<script type="text/javascript">
    //动态加载select框
    $(document).ready(function(){
//        $("#select_tags").click(function(){
//            var slv = $("#select_tags").val();
            var html = "";
            $("#select_tags").empty();
            $.ajax({
                    type:"get",
                    dataType:"json",
                    contentType:"application/json;charset=utf-8",
                    url:'index.php?r=document/company/source',
                    success:function(result){
                        html+="<option value=''>-<?php echo Yii::t('comp_document','label') ?>-</option>";
                        $.each(result,function(index,value){
                            html+="<option value='"+value.id+"'>"+value.name+"</option>";
                            $("#select_tags").html(html);
//                            $("#ca").val(slv);
                        })
                    },
                    error : function(XMLHttpRequest, textStatus, errorThrown) {
                        alert(errorThrown);
                    },
                    async:false             //false表示同步
                }

            );
//        });
    });
</script>