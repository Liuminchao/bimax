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

    $status_list = ProgramDevice::statusText(); //状态text
    $status_css = ProgramDevice::statusCss(); //状态css
    $device_list = Device::deviceAllList();//所有设备
    $device_type = Device::typeAllList();//设备类型编号
    $type_list = DeviceType::deviceList();//设备类型
    $primary_list = Device::primaryAllList();//设备主键关联
    $deviceinfo_list = Device::deviceInfo();

    $j = 1;

    $tool = true;
    //$tool = false;验证权限
    if (Yii::app()->user->checkAccess('mchtm')) {
        $tool = true;
    }
    foreach ($rows as $i => $row) {
        //$t->begin_row("onclick", "getDetail(this,'{$row['device_id']}');");
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $info_list = $deviceinfo_list[$row['device_id']];
        $link = "<table><tr><td style='white-space: nowrap' align='left'><a href='javascript:void(0)' onclick='itemPhoto(\"{$info_list[0]['device_id']}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-camera\"></i>".Yii::t('device', 'Equipment_certificate')."</a></td>";    //资质照片
        $link .= "<td><a href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['device_id']}\")'><i class=\"fa fa-fw fa-cloud-download\"></i>" . Yii::t('proj_project_device', 'device_pdf') . "</a></td></tr></table>"; //导出设备PDF
//        $str = "<input id='device_' value='1' name='device' style='position: absolute; opacity: 0;'  onclick='itemTest(\"{$row['device_id']}\")'  type='checkbox'>";
        $type_no = $device_type[$primary_list[$row['device_id']]];
        $t->echo_td($type_list[$type_no]);
        $t->echo_td($primary_list[$row['device_id']]);
        $t->echo_td($device_list[$primary_list[$row['device_id']]]);
        Device::buildQrCode($contractor_id,$row['device_id']);
        $qrcode_path = '/opt/www-nginx/web'.$info_list[0]['qrcode'];
        $primary_id = $row['device_id'];
        $t->echo_td("<a href='index.php?r=device/equipment/previewprint&primary_id=$primary_id' target='_blank'><img  id=\"qrphoto\" src=\"{$qrcode_path}\"></a>",'center');
        $t->echo_td(Utils::DateToEn(substr($row['apply_date'],0,10)),'center');
        $status = '<span class="label ' . $status_css[$row['check_status']] . '">' . $status_list[$row['check_status']] . '</span>';
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
            <button type="button" class="btn btn-primary btn-sm" style="margin-left: 10px" onclick="javascript:history.back(-1);"><?php echo Yii::t('proj_project_device', 'back_program'); ?></button>
            <?php
                if($rows) {
                    echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExport(\"{$program_id}\")'>" . Yii::t('proj_project_user', 'export') . "</button>";
                }
            ?>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<script src="js/loading.js"></script>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "index.php?r=proj/project/list&ptype=<?php echo Yii::app()->session['project_type'];?>";
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

</script>

