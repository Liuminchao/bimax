<?php
    $status_list = RfAttachment::statusText(); //状态text
    $status_css = RfAttachment::statusCss(); //状态css
?>
<input type="hidden" id="program_id" value="<?php echo $program_id; ?>">
<input type="hidden" id="contractor_id" value="<?php echo $contractor_id; ?>">
<table class="table" id="rf_attachment">
    <tbody>
    <?php if(count($detail_list) < 0){ ?>
        <tr>
            <td colspan="4">No Data.</td>
        </tr>
    <?php }else{?>
        <?php foreach($detail_list as $i => $j){ ?>
            <tr>
                <td>
                    <?php if($j['tag'] == '0'){ ?>
                        <input type="checkbox" name="checkItem" >
                    <?php }else{ ?>
                        <input type="checkbox" name="checkItem" checked disabled>
                    <?php } ?>
                </td>
                <th scope="row"><?php echo $i; ?></th>
                <td style="display: none"><?php echo $j['id']; ?></td>
                <?php
                    if($j['tag'] == '0') {
                        $tag = '0';
                    }else{
                        $tag = '1';
                    }
                ?>
                <td><span class="label <?php echo $status_css[$tag] ?>" ><?php echo $status_list[$tag] ?></span></td>
                <td><?php echo $j['doc_name']; ?></td>
                <td>
                    <select class="form-control input-sm" name="q[label]" id="type<?php echo $i; ?>" >
                        <!--                        <option value="">----><?php //echo Yii::t('proj_report', 'report_program').'('.Yii::t('common', 'is_requried').')'; ?><!----</option>-->
                        <?php
                            if($j['tag'] != '0'){
                                $params = json_decode($j['tag'], true);
                                if($params['label_id'] == '19'){
                        ?>
                                    <option value='19'>Others</option>
                        <?php }else if($params['label_id'] == '23'){ ?>
                                    <option value='23'>Approved Structural Drawings</option>
                        <?php }else if($params['label_id'] == '24'){ ?>
                                    <option value='24'>Approved Architectural Drawings</option>
                        <?php }else if($params['label_id'] == '25'){ ?>
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
                <td ><button type='button' onclick='preview("<?php echo $j['doc_path'] ?>")'>Preview</button></td>
                <td ><button type='button'  onclick='edit_component("<?php echo $j['id'] ?>")'>Component</button> </td>
            </tr>
        <?php } ?>
    <?php } ?>
    </tbody>
</table>
<?php if(count($detail_list) > 0){ ?>
    <div class="row">
        <div class="form-group">
            <div class="col-sm-offset-4 col-sm-10">
                <button type="button" id="sbtn" class="btn btn-primary btn-lg" onclick="btnsubmit();">Publish</button>
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
        var tag = '';
        var label = '';
        $("table :checkbox").each(function(key,value){
            if ($(value).prop('checked')) {
                var id = tbodyObj.rows[key].cells[2].innerHTML;
                var myType = document.getElementById("type"+key);//获取select对象
                var index = myType.selectedIndex; //获取选项中的索引，selectIndex表示的是当前所选中的index
                var label_id = myType.options[index].value;//获取选项中options的value值
                tag += id + '|';
                label+= label_id + '|';
            }
        })
        tag = tag.substr(0,tag.length-1);
        label = label.substr(0,label.length-1);
        var program_id = $('#program_id').val();
        var contractor_id = $('#contractor_id').val();
        $.ajax({
            data: {tag: tag,label: label,program_id: program_id,contractor_id :contractor_id},
            url: "index.php?r=rf/rf/syncattachment",
            dataType: "json",
            type: "POST",
            success: function (data) {

                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_set'); ?>");
                    window.location = "index.php?r=rf/rf/list&program_id="+program_id;
                } else {
                    alert("<?php echo Yii::t('common', 'error_set'); ?>");
                }
            }
        });
    }
    //预览
    function preview (path) {
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/preview&file="+path,"_blank");
        }else{
            window.open('https://shell.cmstech.sg'+path,"_blank");
        }
    }
//    jQuery(document).ready(function () {
//
//        function initTableCheckbox() {
//            var $thr = $('#rf_attachment thead tr');
//            var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
//            /*将全选/反选复选框添加到表头最前，即增加一列*/
//            $thr.prepend($checkAllTh);
//            /*“全选/反选”复选框*/
//            var $checkAll = $thr.find('input');
//            $checkAll.click(function (event) {
//                /*将所有行的选中状态设成全选框的选中状态*/
//                $tbr.find('input').prop('checked', $(this).prop('checked'));
//                /*并调整所有选中行的CSS样式*/
//                if ($(this).prop('checked')) {
//                    $tbr.find('input').parent().parent().addClass('warning');
//                } else {
//                    $tbr.find('input').parent().parent().removeClass('warning');
//                }
//                /*阻止向上冒泡，以防再次触发点击操作*/
//                event.stopPropagation();
//            });
//            /*点击全选框所在单元格时也触发全选框的点击操作*/
//            $thr.click(function () {
//                $(this).find('input').click();
//            });
//            var $tbr = $('#rf_attachment tbody tr');
//            var $checkItemTd = $('<td><input type="checkbox" name="checkItem" /></td>');
//            /*每一行都在最前面插入一个选中复选框的单元格*/
//            $tbr.prepend($checkItemTd);
//            /*点击每一行的选中复选框时*/
//            $tbr.find('input').click(function (event) {
//                /*调整选中行的CSS样式*/
//                $(this).parent().parent().toggleClass('warning');
//                /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
//                $checkAll.prop('checked', $tbr.find('input:checked').length == $tbr.length ? true : false);
//                /*阻止向上冒泡，以防再次触发点击操作*/
//                event.stopPropagation();
//            });
//            /*点击每一行时也触发该行的选中操作*/
//            $tbr.click(function () {
//                $(this).find('input').click();
//            });
//        }
//
//        initTableCheckbox();
//    });
</script>