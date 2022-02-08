<?php
    $status_list = RfAttachment::statusText(); //状态text
    $status_css = RfAttachment::statusCss(); //状态css
    $attach = RfAttachment::model()->findByPk($attach_id);
    $tag = $attach->status;
    $version = $attach->version;
    $doc_path = $attach->doc_path;
?>
<input type="hidden" id="program_id" value="<?php echo $attach->project_id; ?>">
<input type="hidden" id="attach_id" value="<?php echo $attach_id; ?>">
<table class="table" id="rf_attachment">
    <tbody>
    <tr>
        <td><?php echo $attach->doc_name; ?></td>
        <td>
            <select class="form-control input-sm" name="q[label]" id="type" >
                <!--                        <option value="">----><?php //echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?><!----</option>-->
                <?php
                if($tag != '0'){
                    $doc = Document::model()->findByPk($version);
                    $label_id = $doc->label_id;
                    if($label_id == '19'){
                        ?>
                        <option value='19'>Others</option>
                    <?php }else if($label_id == '23'){ ?>
                        <option value='23'>Approved Structural Drawings</option>
                    <?php }else if($label_id == '24'){ ?>
                        <option value='24'>Approved Architectural Drawings</option>
                    <?php }else if($label_id == '25'){ ?>
                        <option value='25'>Approved M&E Drawings</option>
                    <?php }}else{
                    ?>
                    <option value='19'>Others</option>
                    <option value='23'>Approved Structural Drawings</option>
                    <option value='24'>Approved Architectural Drawings</option>
                    <option value='25'>Approved M&E Drawings</option>
                <?php } ?>
            </select>
        </td>
    </tr>
    </tbody>
</table>
<?php
    if($tag == '0'){
?>
        <div class="row">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-10">
                    <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="btnsubmit();">Publish</button>
                </div>
            </div>
        </div>
<?php } ?>
    <script type="text/javascript" src="js/zDrag.js"></script>
    <script type="text/javascript" src="js/zDialog.js"></script>
    <script>
        function edit_component(id) {
            var diag = new Dialog();
            diag.Width = 800;
            diag.Height = 580;
            diag.Title = "Model Component";
            diag.URL = "componentwithattachment&id="+id;
            diag.show();
        }

        function btnsubmit() {
            var tbodyObj = document.getElementById('rf_attachment');
            var attach_id = $('#attach_id').val();
            var myType = document.getElementById("type");//获取select对象
            var index = myType.selectedIndex; //获取选项中的索引，selectIndex表示的是当前所选中的index
            var label_id = myType.options[index].value;//获取选项中options的value值
            var program_id = $('#program_id').val();
            $.ajax({
                data: {attach_id: attach_id,label: label_id,program_id: program_id},
                url: "index.php?r=rf/attach/syncattachment",
                dataType: "json",
                type: "POST",
                success: function (data) {

                    if (data.refresh == true) {
                        alert("<?php echo Yii::t('common', 'success_set'); ?>");
                        window.location = "index.php?r=rf/attach/list&program_id="+program_id;
                    } else {
                        alert("<?php echo Yii::t('common', 'error_set'); ?>");
                    }
                }
            });
        }
    </script>