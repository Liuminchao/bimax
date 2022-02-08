<?php 
$t->echo_grid_header();
    $no_submit = Yii::t('proj_project_user', 'no_submit');
    $people = Yii::t('proj_project_user','people');
    if (is_array($rows)|| is_array($arry)) {
        $i = 0;
        $tag = '';
        foreach($arry as $k => $value){
            if($value['check_status']==ProgramUser::ENTRANCE_APPLY){
                $i++;
                $arr[$i] = $value['user_id'];
            }
        }
        foreach($rows as $n => $val){
            if($val['check_status']==ProgramUser::ENTRANCE_SUCCESS){
                $tag.= $val['user_id'].'|';
            }
        }
//        var_dump($tag);
    $j = 1;
    $status_list = ProgramUser::statusText(); //状态text
    $status_css = ProgramUser::statusCss(); //状态css
//    $user_list = Staff::userAllList();//人员列表
    $authority_list = ProgramUser::AllRoleList();//角色列表
    $roleList = Role::roleList();//岗位列表
//    $user_info = Staff::userInfo();//员工信息
    if (Yii::app()->language == 'zh_CN') {
//        var_dump(11111);
        $roleList['null'] = '否';
    }else{
//        var_dump(22222);
        $roleList['null'] = 'No';
    }
        $attr['style'] = 'display:none';
//        var_dump($rows);
//        exit;
    foreach ($rows as $i => $row) {
        $num = ($curpage - 1) * $this->pageSize + $j++;
        $info_list = Staff::model()->findAllByPk($row['user_id']);
//        $t->echo_td($info_list[0]['user_id'],'center');
        $t->echo_td($info_list[0]['user_name'],'center');
        $t->echo_td($info_list[0]['user_phone'],'center');
        $t->echo_td($info_list[0]['work_no'],'center');
        $t->echo_td($info_list[0]['work_pass_type'],'center');
        $t->echo_td($info_list[0]['nation_type'],'center');
        $qrcode_path = '/opt/www-nginx/web'.$info_list[0]['qrcode'];
        $t->echo_td("<a href='index.php?r=comp/staff/previewprint&user_id={$row['user_id']}&qrcode_path={$qrcode_path}' target='_blank'><img id=\"qrphoto\" src=\"{$qrcode_path}\"></a>",'center');
        $t->echo_td(Utils::DateToEn(substr($row['record_time'],0,10)),'center');
        $status = '<span class="badge ' . $status_css[$row['check_status']] . '">' . $status_list[$row['check_status']] . '</span>';
        $t->echo_td($status,'center'); //状态

        
        $link = "<a class='a_logo' href='javascript:void(0)' onclick='itemPhoto(\"{$row['user_id']}\")' title='".Yii::t('comp_staff', 'Qualification Certificate')."'><i class=\"fa fa-fw fa-paperclip\"></i></a>&nbsp;";
        $link .= "<a class='a_logo' href='javascript:void(0)' onclick='itemDownload(\"{$program_id}\",\"{$row['user_id']}\")' title='".Yii::t('proj_project_user', 'user_pdf')."'><i class=\"fa fa-fw fa-download\"></i></a>&nbsp;";
        $link .= "<a class='a_logo' href='javascript:void(0)' onclick='itemDms(\"{$program_id}\",\"{$row['user_id']}\")' title='DMS'><i class=\"fa fa-fw fa-file\"></i></a>&nbsp;";
        

        $t->echo_td($link);
        $t->end_row();
    }
}

$t->echo_grid_floor();

$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>
<div class="row">
    <div class="col-6">
        <div class="dataTables_info" id="example2_info">
            <?php
                if($rows) {
                    echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='itemExport(\"{$program_id}\")'>" . Yii::t('proj_project_user', 'export') . "</button>";
                }
            ?>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-3">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-9">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

<script src="js/loading.js"></script>
<script type="text/javascript">
    //返回
    var back = function () {
        window.location = "./?<?php echo Yii::app()->session['list_url']['project/sublist']; ?>";
    }

    //checkbox
    function test(o) {
//        alert(123);
    }
    $('.select_all').click(function(){
//        alert(456);
    });
    //批量压缩
    var compress = function (id,tag,curpage) {
        var tbodyObj = document.getElementById('example2');
        var tag = '';
        $("table :checkbox").each(function(key,value){
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var user_id = tbodyObj.rows[key].cells[1].innerHTML;
                    tag += user_id + '|';
                }
            }
        })
        if(tag.length == 0){
            alert('<?php echo Yii::t('proj_project_user', 'error_tag_is_null'); ?>');
            return false;
        }
        tag=(tag.substring(tag.length-1)=='|')?tag.substring(0,tag.length-1):tag;
//        alert(tag);
        alert('<?php echo Yii::t('proj_project_user', 'confirm_compress'); ?>');
        $.ajax({
            data: {id: id,tag:tag,curpage:curpage},
            url: "index.php?r=proj/assignuser/userbatch",
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
            url: "index.php?r=proj/assignuser/staffinfo",
            type: "POST",
            dataType: "json",
            success: function (data) {
                if(data.count > 0){
                    window.location = "index.php?r=proj/assignuser/staffexport"+url;
                }else{
                    alert('<?php echo Yii::t('proj_project_user','error_project_user_null'); ?>');
                }
            },
            error: function () {
                $('#msgbox').addClass('alert-danger fa-ban');
                $('#msginfo').html('System Error');
                $('#msgbox').show();
            }
        });

    }
    //人员关联
    var related = function(id){
        window.location = "index.php?r=proj/assignuser/userapply&ptype=<?php echo Yii::app()->session['project_type'];?>&id=" + id;
    }
    jQuery(document).ready(function () {

    });

</script>

