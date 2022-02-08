<style type="text/css">
    input{
        height: calc(2.25rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        box-shadow: inset 0 0 0 transparent;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .b_logo {color: #8c9192;}
    .c_logo {color: #3ABDC7;}
</style>
<?php
/* @var $this ProgramController */
/* @var $model Program */
/* @var $form CActiveForm */

if (Yii::app()->user->hasFlash('success')) {
    $msg['msg'] = Yii::t('common','success_insert');
    $msg['status'] = 1;
    $msg['refresh'] = true;
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          ";
}
if (Yii::app()->user->hasFlash('error')) {
    $msg['status'] = -1;
    $msg['msg'] = Yii::t('common','error_insert');
    $msg['refresh'] = false;
    $class = Utils::getMessageType($msg['status']);
    echo "<div class='alert {$class[0]} alert-dismissable'>
              <i class='fa {$class[1]}'></i>
              <button class='close' aria-hidden='true' data-dismiss='alert' type='button'>×</button>
              <b>" . Yii::t('common', 'tip') . "：</b>{$msg['msg']}
          </div>
          ";
}
$form = $this->beginWidget('SimpleForm', array(
    'id' => 'form1',
    'enableAjaxSubmit' => false,
    'ajaxUpdateId' => 'content-body',
    'focus' => array($model, 'program_name'),
    'role' => 'form', //可省略
    'formClass' => 'form-horizontal', //可省略 表单对齐样式
));
echo $form->activeHiddenField($model, 'program_id', array());
//var_dump($regionlist);
?>
<input type="hidden" name="Program[]" id="program_id" value="<?php echo $program_id ?>"/>
<input type="hidden"  id="block_cnt" value="<?php echo $block_cnt ?>"/>

<div class="row" >
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-body" id="area-body">

                <?php
                $index = 0;
                $num_list = array();
                for($num=0;$num<50;$num++){
                    $num_list[] = $num;
                }
                if(!empty($regionlist)){
                    foreach($regionlist as $block => $region_array){
                        $index++;
                        $son_index = 0;
                        echo "<div id='row_{$index}' class='row' style='margin-top: 10px;'>";
                        echo "<div class='col-12'>";
                        echo "<div id='div_region_{$index}'>";
                        echo "<a class='b_logo'  onclick='delete_row({$index});' title=\"Delete\"><i class=\"fa fa-fw fa-times\"></i></a>";
                        echo "<a class='a_logo'  onclick='copy({$index});' title=\"Copy\"><i class=\"fa fa-fw fa-copy\"></i></a>";
                        echo "<input name='block[{$index}][]' value='{$block}'><a class='a_logo' style='margin-left: 6px' onclick='Add({$index})' title='Non-typical Level'><i class='fa fa-fw fa-plus'></i></a><a class='a_logo' style='margin-left: 6px' onclick='Add_1({$index})'  title='Typical Level'><i class='fa fa-fw fa-plus'></i></a>";
                        $first = '0';
                        $end = '0';
                        $region_first = '';
                        $region_end = '';
                        $tag = '';
                        foreach($region_array as $region => $region_list){
                            //标准层
                            if($region_list['type'] == '1'){
                                if($tag == ''){
                                    $son_index++;
                                    $tag = '1';
                                    $no_son_index = $son_index;
                                    $str_start = "<input  style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='level[{$index}][{$son_index}][region_1]'  value='{$region}'  type='text'><span>TO</span>";
                                }
                                if($region_list['drawing_id'] == ''){
                                    $str_end="<input  style='margin-bottom: 10px;width: 120px;' name='level[{$index}][{$no_son_index}][region_2]'  value='{$region}'  type='text'><a href='#' class='b_logo remove_no'><i class='fa fa-fw fa-times'></i></a><a class='b_logo' style='margin-left: 6px' id='upload_{$index}_{$no_son_index}' title='Upload'  onclick='upload_file({$index},{$no_son_index})'><i class='fa fa-upload'></i></a><input id='level{$index}_{$no_son_index}_file' type='file'  onchange='attachupload(this,{$index},{$no_son_index})' style='display:none;'><input type='hidden' name='level[{$index}][{$no_son_index}][file]' id='level{$index}_{$no_son_index}_hfile' value='{$region_list['drawing_id']}'>";
                                }else{
                                    $str_end="<input  style='margin-bottom: 10px;width: 120px;' name='level[{$index}][{$no_son_index}][region_2]'  value='{$region}'  type='text'><a href='#' class='b_logo remove_no'><i class='fa fa-fw fa-times'></i></a><a class='c_logo' style='margin-left: 6px' id='upload_{$index}_{$no_son_index}' title='Upload'  onclick='upload_file({$index},{$no_son_index})'><i class='fa fa-upload'></i></a><input id='level{$index}_{$no_son_index}_file' type='file'  onchange='attachupload(this,{$index},{$no_son_index})' style='display:none;'><input type='hidden' name='level[{$index}][{$no_son_index}][file]' id='level{$index}_{$no_son_index}_hfile' value='{$region_list['drawing_id']}'>";
                                }
                            }
                            //非标准层
                            if($region_list['type'] == '0'){
                                if($tag == '1'){
                                    echo $str_start.$str_end;
                                }
                                $tag = '0';
                                $son_index++;
                                if($tag == '0'){
                                    if($region_list['drawing_id'] == ''){
                                        $str = "<input  style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='level[{$index}][{$son_index}][region]'  value='{$region}'  type='text'><a href='#' class='b_logo remove'><i class='fa fa-fw fa-times'></i></a><a class='b_logo' style='margin-left: 6px' id='upload_{$index}_{$son_index}'  title='Upload'  onclick='upload_file({$index},{$son_index})'><i class='fa fa-upload'></i></a><input id='level{$index}_{$son_index}_file' type='file'  onchange='attachupload(this,{$index},{$son_index})' style='display:none;'><input type='hidden' name='level[{$index}][{$son_index}][file]' id='level{$index}_{$son_index}_hfile' value='{$region_list['drawing_id']}'>";
                                    }else{
                                        $str = "<input  style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='level[{$index}][{$son_index}][region]'  value='{$region}'  type='text'><a href='#' class='b_logo remove'><i class='fa fa-fw fa-times'></i></a><a class='c_logo' style='margin-left: 6px' id='upload_{$index}_{$son_index}'  title='Upload'  onclick='upload_file({$index},{$son_index})'><i class='fa fa-upload'></i></a><input id='level{$index}_{$son_index}_file' type='file'  onchange='attachupload(this,{$index},{$son_index})' style='display:none;'><input type='hidden' name='level[{$index}][{$son_index}][file]' id='level{$index}_{$son_index}_hfile' value='{$region_list['drawing_id']}'>";
                                    }
                                    echo $str;
                                }
                                $tag = '';
                            }
                        }
                        if($tag == '1'){
                            echo $str_start.$str_end;
                        }
                        echo "<input type='hidden' id='num_{$index}' value='{$son_index}'></div>";
                        echo "</div>";
                        echo "</div>";
                    }
                }else{
                    $son_index = 1;
                    echo "<div id='row_{$index}' class='row' style='margin-top: 10px;'>";
                    echo "<div class='col-12'>";
                    echo "<div id='div_region_{$index}'>";
                    echo "<a class='b_logo'  onclick='delete_row({$index});' title=\"Delete\"><i class=\"fa fa-fw fa-times\"></i></a>";
                    echo "<a class='a_logo'  onclick='copy({$index});' title=\"Copy\"><i class=\"fa fa-fw fa-copy\"></i></a>";
                    echo "<input name='block[{$index}][]' value='' placeholder='Block'><a class='a_logo' style='margin-left: 6px' onclick='Add({$index})' title='Non-typical Level'><i class='fa fa-fw fa-plus'></i></a><a class='a_logo' style='margin-left: 6px' onclick='Add_1({$index})'  title='Typical Level'><i class='fa fa-fw fa-plus'></i></a>";
                    echo "<input  style='margin-left: 13px;margin-bottom: 10px;width: 120px;' name='level[{$index}][{$son_index}][region]'  value='' placeholder='Level' type='text'><a href='#' class='b_logo remove'><i class='fa fa-fw fa-times'></i></a><a class='b_logo' style='margin-left: 6px' id='upload_{$index}_{$son_index}'  title='Upload'  onclick='level{$index}_{$son_index}_file.click()'><i class='fa fa-upload'></i></a><input id='level{$index}_{$son_index}_file' type='file'  onchange='attachupload(this,{$index},{$son_index})' style='display:none;'><input type='hidden' name='level[{$index}][{$son_index}][file]' id='level{$index}_{$son_index}_hfile' value=''>";
                    echo "<input type='hidden' id='num_{$index}' value='{$son_index}'></div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
            <div class="row">
                <div class="col-12" style="text-align: left;margin-left: 10px;">
                    <button type="button" class="btn btn-primary btn-sm"  onclick="create();">New Block</button>
                </div>
            </div>
            <div class="row" style="margin-top: 10px;margin-bottom: 10px">
                <div class='col-12' style="text-align: center">
                    <button type="button" class="btn btn-primary"  onclick="save_block();">Save</button>
                    <button type="button" class="btn btn-default" style="margin-left: 10px" onclick="back();"><?php echo Yii::t('common', 'button_back'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>
<script type="text/javascript" src="js/loading_upload.js"></script>
<script type="text/javascript">
    function fileSelect() {
        document.getElementById("fileToUpload").click();
    }
    function pdf_png() {
        alert('demo');
        window.location = "index.php?r=proj/project/pdftopng";
    }
    $(document).ready(function() {
        $("body").on("click",".removeclass", function(e){ //user click on remove text
            $(this).parent('div').remove(); //remove text box
        })
        $("body").on("click",".remove", function(e){ //user click on remove text
            $(this).prev().remove();//remove text box
            $(this).next().remove();//remove text box
            $(this).next().remove();//remove text box
            $(this).next().remove();//remove text box
            $(this).remove();
        })
        $("body").on("click",".remove_no", function(e){ //user click on remove text
            $(this).prev().remove();//remove text box
            $(this).prev().remove();//remove text box
            $(this).prev().remove();//remove text box
            $(this).next().remove();//remove text box
            $(this).next().remove();//remove text box
            $(this).next().remove();//remove text box
            $(this).remove();
        })
    })
    function upload_file(index,son_index) {
        var project_id = $('#program_id').val();
        var draw_id = $('#level'+index+'_'+son_index+'_hfile').val();
        var modal = new TBModal();
        modal.title = "Select File";
        modal.url = "index.php?r=proj/project/uploadfile&project_id="+project_id+"&index="+index+"&son_index="+son_index+"&draw_id="+draw_id;
        modal.modal();
    }

    window.addEventListener('message', function (messageEvent) {
        var data = messageEvent.data;
        console.info('message from child:', data);
        var obj = eval('(' + data + ')');
        console.info(obj.file_list[0].file_id);
    }, false);

    var delete_row = function (index) {
        var my = document.getElementById("row_"+index);
        console.log(my);
        if (my != null)
            my.parentNode.removeChild(my);
    }

    var copy = function(index) {
        var d = document.getElementById("div_region_"+index);  //获取tag名称为div的html元素们

        // if(d[i].className=="q2")                                     //获取tag名称为div的html元素们中，class名称为q2的html元素

        var a = d.getElementsByTagName("input");              //获取tag名称为div的html元素们中，class名称为q2的html元素中，tag名称为a的html元素们

        for (i = 0; i < a.length; i++){                              //遍历tag名称为div的html元素们中，class名称为q2的html元素中，tag名称为a的html元素们
            // var s =a[i].innerHTML;
            console.log(a[i].name);//遍历tag名称为div的html元素们
        }
        var block_cnt = $('#block_cnt').val();
        new_index = parseInt(block_cnt)+1;
        var html = '<div id="row_'+new_index+'" class="row" style="margin-top: 10px;">\n' +
            '           <div class="col-12">\n' +
            '           <div id="div_region_'+new_index+'">\n';
        html += '<a class="b_logo"  onclick="delete_row('+new_index+');" title="Delete"><i class="fa fa-fw fa-times"></i></a>';
        html += '<a class="a_logo"  onclick="copy('+new_index+');" title="Copy"><i class="fa fa-fw fa-copy"></i></a>';
        var j =1;
        for(i=0;i<a.length;i++){
            if(a[i].name == 'block['+index+'][]'){
                html += '<input name="block['+new_index+'][]" value="'+a[i].value+'"><a class="a_logo" style="margin-left: 6px" onclick="Add('+new_index+')" title="Non-typical Level"><i class="fa fa-fw fa-plus"></i></a>';
                html += '<a class="a_logo" style="margin-left: 6px" onclick="Add_1('+new_index+')" title="Typical Level"><i class="fa fa-fw fa-plus"></i></a>';
            }

            if(a[i].name == 'level['+index+']['+j+'][region]'){
                html+= '<input style="margin-left: 13px;margin-bottom: 10px;width: 120px;" name="level['+new_index+']['+j+'][region]" value="'+a[i].value+'" type="text"><a href="#" class="b_logo remove"><i class="fa fa-fw fa-times"></i></a>';
                html+= '<a class="b_logo" style="margin-left: 6px" id="upload_'+new_index+'_'+j+'" title="Upload" onclick="upload_file('+new_index+','+j+')"><i class="fa fa-upload"></i></a><input id="level'+new_index+'_'+j+'_file" type="file" onchange="attachupload(this,'+new_index+','+j+')" style="display:none;"><input type="hidden" name="level['+new_index+']['+j+'][file]" id="level'+new_index+'_'+j+'_hfile" value="">';
                j++;
            }
            if(a[i].name == 'level['+index+']['+j+'][region_1]'){
                html+= '<input style="margin-left: 13px;margin-bottom: 10px;width: 120px;" name="level['+new_index+']['+j+'][region_1]" value="'+a[i].value+'" type="text"><span>TO</span>';
            }
            if(a[i].name == 'level['+index+']['+j+'][region_2]'){
                html+= '<input style="margin-bottom: 10px;width: 120px;" name="level['+new_index+']['+j+'][region_2]" value="'+a[i].value+'" type="text"><a href="#" class="b_logo remove_no"><i class="fa fa-fw fa-times"></i></a>';
                html+= '<a class="b_logo" style="margin-left: 6px" id="upload_'+new_index+'_'+j+'" title="Upload" onclick="upload_file('+new_index+','+j+')"><i class="fa fa-upload"></i></a><input id="level'+new_index+'_'+j+'_file" type="file" onchange="attachupload(this,'+new_index+','+j+')" style="display:none;"><input type="hidden" name="level['+new_index+']['+j+'][file]" id="level'+new_index+'_'+j+'_hfile" value="">';
                j++;
            }

        }
        html += '</div>\n';
        html +=  '</div>\n' +
            '         </div>';
        $("#area-body").append(html);
        $('#block_cnt').val(new_index);
    }

    var create = function() {
        var block_cnt = $('#block_cnt').val();
        new_index = parseInt(block_cnt)+1;
        var html = '<div id="row_'+new_index+'" class="row" style="margin-top: 10px;">\n' +
            '           <div class="col-12">\n' +
            '           <div id="div_region_'+new_index+'">\n';
        html += '<a class="b_logo"  onclick="delete_row('+new_index+');" title="Delete"><i class="fa fa-fw fa-times"></i></a>';
        html += '<a class="a_logo"  onclick="copy('+new_index+');" title="Copy"><i class="fa fa-fw fa-copy"></i></a>';
        html += '<input name="block['+new_index+'][]" value="" placeholder="Block"><a class="a_logo" style="margin-left: 6px" onclick="Add('+new_index+')" title="Non-typical Level"><i class="fa fa-fw fa-plus"></i></a>';
        html += '<a class="a_logo" style="margin-left: 6px" onclick="Add_1('+new_index+')" title="Typical Level"><i class="fa fa-fw fa-plus"></i></a>';

        html += '<input type="hidden" id="num_'+new_index+'" value="0">';
        html += '</div>\n';
        html +=  '</div>\n' +
            '         </div>';
        $("#area-body").append(html);
        $('#block_cnt').val(new_index);
    }

    var save_block = function () {
        $.ajax({
            data:$('#form1').serialize(),                 //将表单数据序列化，格式为name=value
            url: "index.php?r=proj/project/setregion",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    alert('<?php echo Yii::t('common','success_submit'); ?>');
                }else{
                    alert('<?php echo Yii::t('common','error_submit'); ?>');
                }

            },
            error: function () {
//                $('#msgbox').addClass('alert-danger fa-ban');
//                $('#msginfo').html('系统错误');
//                $('#msgbox').show();
                alert('System Error!');
            }
        });
    }

    //测试
    var save_test = function (location,b) {
        var program_id = $("#program_id").val();
        var region = [];
        var j = 0;
        $("input[name='"+location+"']").each(function(index,item){
            region[j] = $(this).val();
            j++;
        })
        var str = region.join(",");
//        alert(str);
//        return;
        var tag = $("input[name='"+b+"']").val();
//        alert(tag);
//        var lcoation = 'A';
        $.ajax({
            data:{program_id:program_id,str:str,tag:tag,location:location},
            url: "index.php?r=proj/project/setregion",
            type: "POST",
            dataType: "json",
            beforeSend: function () {

            },
            success: function (data) {
                if(data.status == 1) {
                    alert('<?php echo Yii::t('common','success_submit'); ?>');
                }else{
                    alert('<?php echo Yii::t('common','error_submit'); ?>');
                }

            },
            error: function () {
//                $('#msgbox').addClass('alert-danger fa-ban');
//                $('#msginfo').html('系统错误');
//                $('#msgbox').show();
                alert('System Error!');
            }
        });
    }
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/list']; ?>";
    }
    var Add = function (new_index) {
        var num = $('#num_'+new_index).val();
        var num = parseInt(num)+1;
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px;width: 120px;'  name='level["+new_index+"]["+num+"][region]'  value='' placeholder='Level'  type='text'><a href='#' class='b_logo remove'><i class='fa fa-fw fa-times'></i></a>";
        html+= '<a class="b_logo" style="margin-left: 6px" id="upload_'+new_index+'_'+num+'" title="Upload" onclick="upload_file('+new_index+','+num+')"><i class="fa fa-upload"></i></a><input id="level'+new_index+'_'+num+'_file" type="file" onchange="attachupload(this,'+new_index+','+num+')" style="display:none;"><input type="hidden" name="level['+new_index+']['+num+'][file]" id="level'+new_index+'_'+num+'_hfile" value="">';
        $("#div_region_"+new_index).append(html);
        $('#num_'+new_index).val(num);
    }

    var Add_1 = function (new_index) {
        var num = $('#num_'+new_index).val();
        var num = parseInt(num)+1;
        var html = "<input  style='margin-left: 11px;margin-bottom: 10px;width: 120px;'  name='level["+new_index+"]["+num+"][region_1]'  value='' placeholder='Level'  type='text'><span>TO</span><input  style='margin-bottom: 10px;width: 120px;'  name='level["+new_index+"]["+num+"][region_2]'  value='' placeholder='Level'  type='text'><a href='#' class='b_logo remove_no'><i class='fa fa-fw fa-times'></i></a>";
        html+= '<a class="b_logo" style="margin-left: 6px" id="upload_'+new_index+'_'+num+'"  title="Upload" onclick="upload_file('+new_index+','+num+')"><i class="fa fa-upload"></i></a><input id="level'+new_index+'_'+num+'_file" type="file" onchange="attachupload(this,'+new_index+','+num+')" style="display:none;"><input type="hidden" name="level['+new_index+']['+num+'][file]" id="level'+new_index+'_'+num+'_hfile" value="">';
        $("#div_region_"+new_index).append(html);
        $('#num_'+new_index).val(num);
    }

    var attachupload = function(file,index,son_inde){
        var file_list = $('#level'+index+'_file')[0].files;
        console.log(file.value);
        $.each(file_list, function (name, file) {
            if (!/\.(gif|jpg|jpeg|png|GIF|JPG|PNG|pdf|doc|docx|xls|xlsx|dwg|zip|rar)$/.test(file.name)) {
                alert("Please upload document in either .gif, .jpeg, .jpg, .png, .doc, .xls, .dwg, .zip, .rar, .xlsx, .docx or .pdf format.");
                return false;
            }
            ra_tag = file.name.lastIndexOf(".");
            ra_length = file.name.length;
            //获取后缀名
            ra_type=file.name.substring(ra_tag,ra_length);
            ra_name = file.name.substr(0,ra_tag);
            var video_src_file = file.name;
            containSpecial = new RegExp(/[\~\%\^\*\[\]\{\}\|\\\;\:\'\"\,\.\/\?]+/);
            status = containSpecial.test(ra_name);
            if(status == 'true'){
                alert('File name contains special characters, please check before uploading');
                return false;
            }
            var newFileName = video_src_file.split('.');

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数
            formData.append("file1", file);

            $.ajax({
                url: "https://shell.cmstech.sg/appupload",
                type: "POST",
                data: formData,
                dataType: "json",
                processData: false,         // 告诉jQuery不要去处理发送的数据
                contentType: false,        // 告诉jQuery不要去设置Content-Type请求头
                beforeSend: function () {
                    addcloud();
                },
                success: function (res) {
                    removecloud();//去遮罩
                    $('level'+index+'_hfile').val(res.data.file1);
                    console.log(res.data.file1);
                }
            });
        })
    }

    //添加区域
    var AddRegion = function () {
        var modal = new TBModal();
        modal.title = "<?php echo Yii::t('proj_project', 'Add Region'); ?>";
        modal.url = "index.php?r=proj/project/selectregion";
        modal.modal();
    }
    //删除节点
    var DelRegion = function (cnt) {
        var block = String.fromCharCode(cnt);
        $("#div_region_" + block).remove();
    }

    //浏览PDF
    function preview_attachment(path){
        path = encodeURIComponent(path);
        var tag = path.slice(-3);
        if(tag == 'pdf'){
            window.open("index.php?r=rf/rf/preview&doc_path="+path,"_blank");
        }else{
            window.open("index.php?r=rf/rf/previewdoc&doc_path="+path,"_blank");
        }

    }

</script>
