<style type="text/css">
    .format1{
        list-style:none; padding:0px; margin:0px; width:200px; float: left;
    }
    .format2{ width:50%; display:inline-block; float: left; padding-left: 0}
</style>
<?php
$t->echo_grid_header();
$no_submit_num = Yii::t('proj_project_device', 'no_submit_num');
if (is_array($rows)|| is_array($arry)) {
        $i = 0;
        $tag = '';
        foreach($arry as $k => $value){
            if($value['check_status']==ProgramDevice::ENTRANCE_APPLY){
                $i++;
//                $arr[$i] = $value['device_id'];
                $arr[$i] = $value['device_id'];
            }
        }
        foreach($rows as $n => $val){
            if($val['check_status']==ProgramDevice::ENTRANCE_SUCCESS){
                $tag.= $val['device_id'].'|';
            }

        }

    if($i>0){
         $button = Yii::t('common', 'button_post');
        //var_dump($arr);
         $json = implode("|",$arr);
         //var_dump($json);
         echo "<div class='alert alert-success alert-dismissable'>
            <i class='fa fa-check'></i>
            <b>{$no_submit_num}:&nbsp;&nbsp;{$i}</b>&nbsp;&nbsp;&nbsp;
            <button class='btn btn-primary btn-sm' type='button' style='margin-left: 10px' onclick='itemEntrance(\"{$json}\",\"{$program_id}\");'>{$button}</button>
          </div>"
;
    }else{
         echo "<div class='alert alert-success alert-dismissable'>
            <i class='fa fa-check'></i>
            <b>{$no_submit_num}:&nbsp;&nbsp;{$i}</b>
          </div>";
    }
    $j = 1;
    $status_list = ProgramDevice::statusText(); //状态text
    $status_css = ProgramDevice::statusCss(); //状态css
    $device_list = Device::deviceAllList();//所有设备
    $device_type = Device::typeAllList();//设备类型编号
    $type_list = DeviceType::deviceList();//设备类型
    $primary_list = Device::primaryAllList();//设备主键关联
    $operator_id = Yii::app()->user->id;
    $authority_list = OperatorProject::authorityList($operator_id);
    //判断项目权限
    $value = $authority_list[$program_id];

    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    foreach ($rows as $i => $row) {
        //$t->begin_row("onclick", "getDetail(this,'{$row['device_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
//        $link = "<a href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_device', 'device_pdf') . "</a>"; //导出设备PDF
//        if ($row['check_status'] == ProgramUser::ENTRANCE_SUCCESS ) { //状态是入场审批成功
//            $link .= "&nbsp;<a href='javascript:void(0)' onclick='itemLeave(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-random\"></i>".Yii::t('proj_project_user', 'leave')."</a>&nbsp;&nbsp;";    //出场
//        }else if($row['check_status'] == ProgramUser::ENTRANCE_APPLY || $row['check_status'] == ProgramUser::ENTRANCE_PENDING){
//            $link .= "&nbsp;<a href='javascript:void(0)' onclick='itemDelete(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('proj_project_user', 'delete')."</a>";    //删除
//        }else {
//            $link .= '';
//        }
//        $link .= "<br>&nbsp;<a style='float: left;margin-left: 5px' href='javascript:void(0)' onclick='itemPhoto(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a>";    //资质照片
//        $str = "<input id='device_' value='1' name='device' style='position: absolute; opacity: 0;'  onclick='itemTest(\"{$row['device_id']}\")'  type='checkbox'>";
        if($row['status']=='00'){
            if ($row['check_status'] == ProgramUser::ENTRANCE_SUCCESS ) { //状态是入场审批成功
                if($value == '1'){
                    $link = "<ul class='format1'><li class='format2'><a style='float: left;margin-left: 5px' href='javascript:void(0)' onclick='itemLeave(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-random\"></i>".Yii::t('proj_project_user', 'leave')."</a></li><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemInvisible(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-eye\"></i>" . Yii::t('proj_project_device', 'invisible') . "</a></li></ul>";    //出场
                }else if($value == '0'){
                    $link = "";
                }
                $link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left: 5px' href='javascript:void(0)' onclick='itemPhoto(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a></li><li class='format2'><a href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_device', 'device_pdf') . "</a></li></ul>";    //资质照片
            }else if($row['check_status'] == ProgramUser::LEAVE_PENDING || $row['check_status'] == ProgramUser::LEAVE_FAIL){
                if($value == '1'){
                    $link = "<ul class='format1'><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemInvisible(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-eye\"></i>" . Yii::t('proj_project_device', 'invisible') . "</a></li><li class='format2'><a style='float: left;margin-left: 5px' href='javascript:void(0)' onclick='itemPhoto(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a></li></ul>";    //可见按钮
                    $link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left: 5px ' href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_device', 'device_pdf') . "</a></li></ul>"; //导出设备PDF
                }else if($value == '0'){
                    $link = "<ul class='format1'><li class='format2'><a style='float: left;margin-left: 5px' href='javascript:void(0)' onclick='itemPhoto(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a></li><li class='format2'><a style='float: left;margin-left: 5px ' href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_device', 'device_pdf') . "</a></li></ul>";    //可见按钮
                }
            } else if($row['check_status'] == ProgramUser::ENTRANCE_APPLY || $row['check_status'] == ProgramUser::ENTRANCE_PENDING){
                if($value == '1'){
                    $link = "<ul class='format1'><li class='format2'><a style='float: left;margin-left: 5px ' href='javascript:void(0)' onclick='itemDelete(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('proj_project_user', 'delete')."</a></li><li class='format2'><a style='float: left;margin-left: 5px' href='javascript:void(0)' onclick='itemPhoto(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a></li></ul>";    //删除
                    $link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left: 5px ' href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_device', 'device_pdf') . "</a></li><li class='format2'><a style='float: left;margin-left: 5px ' href='javascript:void(0)' onclick='itemInvisible(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-eye\"></i>" . Yii::t('proj_project_device', 'invisible') . "</a></li></ul>"; //导出设备PDF
                }else if($value == '0'){
                    $link = "<ul class='format1'><li class='format2'><a style='float: left;margin-left: 5px ' href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_device', 'device_pdf') . "</a></li><li class='format2'><a style='float: left;margin-left: 5px' href='javascript:void(0)' onclick='itemPhoto(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a></li></ul>";    //删除
                }
            }else {
                $link = "<ul class='format1'><li class='format2'><a style='float: left;margin-left: 5px' href='javascript:void(0)' onclick='itemPhoto(\"{$row['device_id']}\",\"{$row['primary_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a></li></ul>";    //资质照片
            }
        }else if($row['status'] =='99'){
            $link = "<ul class='format1'><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemVisible(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-eye\"></i>".Yii::t('proj_project_device', 'visible')."</a></li></ul>";    //可见按钮
//            $link = "<li class='format2'><a href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_device', 'device_pdf') . "</a></li></ul>"; //导出设备PDF
        }
       $type_no = $device_type[$primary_list[$row['device_id']]];
       $t->echo_td($type_list[$type_no]);
       $t->echo_td($primary_list[$row['device_id']]);
       $t->echo_td($device_list[$primary_list[$row['device_id']]]);
       if($row['status']=='00'){
           $status = '<span class="label ' . $status_css[$row['check_status']] . '">' . $status_list[$row['check_status']] . '</span>';
       }else if($row['status']=='99'){
           $status = '<span class="label ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
       }

       $t->echo_td($status,'center'); //状态
       //$t->echo_td($row['record_time']);
      // $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)));
       $t->echo_td($link); //操作
       $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>

<div class="row">
    <div class="col-xs-6">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total');?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt');?>
            <?php if($value == '1'){ ?>
                <button type="button" value="1" class="btn btn-primary btn-sm" style="margin-left: 10px" onclick="related(<?php echo $program_id; ?>);"><?php echo Yii::t('device', 'related_device'); ?></button>
            <?php } ?>&nbsp;&nbsp;&nbsp;
            <button type="button" class="btn btn-primary btn-sm" style="margin-left: 10px" onclick="back(<?php echo $program_id; ?>);"><?php echo Yii::t('proj_project_device', 'back_program'); ?></button>
            <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='compress(\"{$program_id}\",\"{$tag}\",\"{$curpage}\");'>".Yii::t('proj_project_user', 'compress')."</button>" ?>
            <?php echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExport(\"{$program_id}\")'>".Yii::t('proj_project_user', 'export')."</button>" ?>
<!--            --><?php //echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='batchlive(\"{$program_id}\",\"{$tag}\")'>".Yii::t('proj_project_device', 'batch_live')."</button>" ?>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">
    //返回
    var back = function (id) {
        window.location = "index.php?r=proj/project/list&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id="+id;
    }
    //批量压缩
    var batchlive = function (id,tag) {
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0){
                if($(value).prop('checked')){
                    var device_id = tbodyObj.rows[key].cells[2].innerHTML;
                    tag += device_id + '|';
                }
            }
        })
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
//        alert(tag);
        alert('<?php echo Yii::t('proj_project_device', 'confirm_batch_live'); ?>');
        $.ajax({
            data: {id: id,tag:tag},
            url: "index.php?r=proj/assignuser/batchlevedevice",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                if (data.refresh == true) {
                    alert("<?php echo Yii::t('common', 'success_remove'); ?>");
                    removecloud();//去遮罩
                    <?php echo $this->gridId; ?>.refresh();
                } else {
                    //alert("<?php echo Yii::t('common', 'error_remove'); ?>");
                    alert(data.msg);
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //批量压缩
    var compress = function (id,tag,curpage) {
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0){
                if($(value).prop('checked')){
                    var device_id = tbodyObj.rows[key].cells[2].innerHTML;
                    tag += device_id + '|';
                }
            }
        })
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
//        alert(tag);
        alert('<?php echo Yii::t('proj_project_user', 'confirm_compress'); ?>');
        $.ajax({
            data: {id: id,tag:tag,curpage:curpage},
            url: "index.php?r=proj/assignuser/devicebatch",
            type: "POST",
            dataType: "json",
            beforeSend: function () {
                addcloud(); //为页面添加遮罩
            },
            success: function (data) {
                var form = $("<form>");   //定义一个form表单
                form.attr('style', 'display:none');   //在form表单中添加查询参数
                form.attr('target', '');
                form.attr('method', 'post');
                form.attr('action', "index.php?r=proj/assignuser/compress");

                var input1 = $('<input>');
                input1.attr('type', 'hidden');
                input1.attr('name', 'filename');
                input1.attr('value', data.filename);
                $('body').append(form);  //将表单放置在web中
                form.append(input1);   //将查询参数控件提交到表单上
                removecloud();//去遮罩
                form.submit();
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('系统错误');
                $('#msgbox').show();
            }
        });
    }
    //导出excel
    var itemExport = function(id){
        var objs = document.getElementById("_query_form").elements;
        var i = 0;
        var cnt = objs.length;
        var obj;
        var url = '';

        for (i = 0; i < cnt; i++) {
            obj = objs.item(i);
            url += '&' + obj.name + '=' + obj.value;
        }
        $.ajax({
            data:$('#_query_form').serialize(),
            url: "index.php?r=proj/assignuser/deviceinfo",
            type: "POST",
            dataType: "json",
            success: function (data) {
                if(data.count > 0){
                    window.location = "index.php?r=proj/assignuser/deviceexport"+url;
                }else{
                    alert('<?php echo Yii::t('proj_project_device','error_project_device_null'); ?>');
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('System Error');
                $('#msgbox').show();
            }
        });

    }
    //设备关联
    var related = function(id){
        window.location = "index.php?r=proj/assignuser/deviceapply&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }
    jQuery(document).ready(function () {

        function initTableCheckbox() {
            var $thr = $('table thead tr');
            var $checkAllTh = $('<th><input type="checkbox" id="checkAll" name="checkAll" /></th>');
            /*将全选/反选复选框添加到表头最前，即增加一列*/
            $thr.prepend($checkAllTh);
            /*“全选/反选”复选框*/
            var $checkAll = $thr.find('input');
            $checkAll.click(function(event){
                /*将所有行的选中状态设成全选框的选中状态*/
                $tbr.find('input').prop('checked',$(this).prop('checked'));
                /*并调整所有选中行的CSS样式*/
                if ($(this).prop('checked')) {
                    $tbr.find('input').parent().parent().addClass('warning');
                } else{
                    $tbr.find('input').parent().parent().removeClass('warning');
                }
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击全选框所在单元格时也触发全选框的点击操作*/
            $thr.click(function(){
                $(this).find('input').click();
            });
            var $tbr = $('table tbody tr');
            var $checkItemTd = $('<td><input type="checkbox" name="checkItem" /></td>');
            /*每一行都在最前面插入一个选中复选框的单元格*/
            $tbr.prepend($checkItemTd);
            /*点击每一行的选中复选框时*/
            $tbr.find('input').click(function(event){
                /*调整选中行的CSS样式*/
                $(this).parent().parent().toggleClass('warning');
                /*如果已经被选中行的行数等于表格的数据行数，将全选框设为选中状态，否则设为未选中状态*/
                $checkAll.prop('checked',$tbr.find('input:checked').length == $tbr.length ? true : false);
                /*阻止向上冒泡，以防再次触发点击操作*/
                event.stopPropagation();
            });
            /*点击每一行时也触发该行的选中操作*/
            $tbr.click(function(){
                $(this).find('input').click();
            });
        }
        initTableCheckbox();
        
        $('.td_userphone').each(function(){
            v = $(this).text();
            v1 = see(v);
            $(this).text(v1);
        });
        $('.td_workno').each(function(){
            b = $(this).text();
            b1 = play(b);
            $(this).text(b1);
        });
    });
</script>

