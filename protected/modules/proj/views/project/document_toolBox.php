<link href="css/select2.css" rel="stylesheet" type="text/css" />
<link href="css/select2.min.css" rel="stylesheet" type="text/css" />
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                <div class="col-xs-2 padding-lr5">
                    <input  type="hidden" name="q[program_id]"  value="<?php echo $program_id; ?>">
                    <input type="text" class="form-control input-sm" name="q[doc_name]" placeholder="<?php echo Yii::t('comp_document', 'document_name'); ?>">
                </div>
                <div class="col-xs-2 padding-lr5" style="width: 120px;">
                    <select id="select_tags" style="width:110px;" name="q[label_id]">
                        <option value=''>-Label-</option>
                        <option value='19'>Others</option>
                        <option value='23'>Approved Structural Drawings</option>
                        <option value='24'>Approved Architectural Drawings</option>
                        <option value='25'>Approved M&E Drawings</option>
                    </select>
                </div>

                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
                <?php
                $operator_id = Yii::app()->user->id;
                $authority_list = OperatorProject::authorityList($operator_id);
                $value = $authority_list[$program_id];
                if($value == '1') {
                    ?>
                    <button class="btn btn-primary btn-sm"
                            onclick="itemUpload('<?php echo $program_id; ?>')"><?php echo Yii::t('comp_document', 'upload'); ?></button>
                    <?php
                }
                ?>
                <button class="btn btn-primary btn-sm" onclick="itemBack('<?php echo $program_id; ?>')"><?php echo Yii::t('common', 'button_back'); ?></button>
            </label>
        </div>
    </div>
</div>
<script type="text/javascript">
    //动态加载select框
//    $(document).ready(function(){
////        $("#select_tags").click(function(){
////            var slv = $("#select_tags").val();
//            var html = "";
//            $("#select_tags").empty();
//            $.ajax({
//                    type:"get",
//                    dataType:"json",
//                    contentType:"application/json;charset=utf-8",
//                    url:'index.php?r=proj/project/source',
//                    success:function(result){
//                        html+="<option value=''>-<?php //echo Yii::t('comp_document','label') ?>//-</option>";
//                        $.each(result,function(index,value){
//                            html+="<option value='"+value.id+"'>"+value.name+"</option>";
//                            $("#select_tags").html(html);
////                            $("#ca").val(slv);
//                        })
//                    },
//                    error : function(XMLHttpRequest, textStatus, errorThrown) {
//                        alert(errorThrown);
//                    },
//                    async:false             //false表示同步
//                }
//
//            );
////        });
//    });
    //返回
    var itemBack = function (id) {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>&program_id="+id;
    }
</script>