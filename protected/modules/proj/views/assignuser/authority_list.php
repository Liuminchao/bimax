
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
    
    echo "<div class='row' style='padding-top:5px'>
            <div class='col-12'>
            <div class='alert alert-success alert-dismissable'>
            <i class='fa fa-check'></i>";
    if($i>0){
        //var_dump($arr);
        $button = Yii::t('common', 'button_post');
         $json = implode("|",$arr);
         //var_dump($json);
        echo "<b>{$no_submit}:{$i}</b>&nbsp;&nbsp;&nbsp;
            <button class='btn btn-primary btn-sm' type='button' style='margin-left: 10px' onclick='itemEntrance(\"{$json}\",\"{$program_id}\");'>{$button}</button>";
    }else{
         echo "<b>  {$no_submit} :{$i} $people</b>";
    }
    echo "</div>
          </div>
          </div>";
          
    $j = 1;
    $status_list = ProgramUser::statusText(); //状态text
    $status_css = ProgramUser::statusCss(); //状态css
    $robox_list = ProgramUser::roboxRole();
    $authority_list = ProgramUser::AllRoleList();//角色列表
    $roleList = Role::roleList();//岗位列表
    $company_list = Contractor::compAllList();
    $operator_id = Yii::app()->user->id;
    $value_list = OperatorProject::authorityList($operator_id);
    //判断项目权限
    $value = $value_list[$program_id];

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
//        $t->echo_td('<input type="checkbox"  name="checkItem" id="'.$row['user_id'].'" onclick="test(this);">');
        $t->echo_td($row['user_id'],'center',$attr);//员工编号
        $user_model = Staff::model()->findByPk($row['user_id']);
        $user_name = $user_model->user_name;
        $t->echo_td($user_name); //姓名
        //$t->echo_td($authority_list['ra_role'][$row['ra_role']]); //风险评估角色
        $program_role = explode('|',$row['program_role']);
//        var_dump($program_role[0]);
//        exit;
        if($row['check_status'] == ProgramUser::ENTRANCE_APPLY){
//            $t->echo_td('<a href="#" class="editable editable-click ra_role" id="ra_role" data-value=""  data-type="select" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Risk Assessment Role/风险评估角色">'.$authority_list['ra_role'][$row['ra_role']].'</a>','center');
//            $t->echo_td('<a href="#" class="editable editable-click ptw_role" id="ptw_role" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Permit to work Role/许可证角色" >'.$authority_list['ptw_role'][$row['ptw_role']].'</a>','center');
//            $t->echo_td('<a href="#" class="editable editable-click wsh_mbr_flag" id="wsh_mbr_flag" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="WSH Committee Member/安全委员会委员" >'.$authority_list['wsh_mbr_flag'][$row['wsh_mbr_flag']].'</a>','center');
//            $t->echo_td('<a href="#" class="editable editable-click meeting_flag" id="meeting_flag" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Conducting of toolbox meeting/举行会议人" >'.$authority_list['meeting_flag'][$row['meeting_flag']].'</a>','center');
//            $t->echo_td('<a href="#" class="editable editable-click training_flag" id="training_flag" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Conducting of internal training/举行培训人" >'.$authority_list['training_flag'][$row['training_flag']].'</a>','center');
            //$t->echo_td('<a href="#" class="editable editable-click program_role" id="program_role" data-type="select" data-pk="1" data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'"data-title="Program Role/项目角色" >'.$authority_list['program_role'][$row['program_role']].'</a>');
//            $t->echo_td($roleList[$program_role[0]]);
            $t->echo_td($company_list[$row['contractor_id']]);
            $t->echo_td($roleList[$program_role[0]],'center');
            $t->echo_td($robox_list[$row['robox_role']],'center');
//            $t->echo_td('<a href="#" class="editable editable-click second_role" data-type="select"  id="second_role"  data-pk="1"  data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'" data-title="Select Second Role" >'.$roleList[$program_role[1]].'</a>','center');
//            $t->echo_td('<a href="#" class="editable editable-click third_role" data-type="select"  id="third_role"  data-pk="1"  data-url="index.php?r=proj/assignuser/setauthority&user_id='.$row['user_id'].'&program_id='.$program_id.'" data-title="Select Third Role" >'.$roleList[$program_role[2]].'</a>','center');
        }else{
//            $t->echo_td($authority_list['ra_role'][$row['ra_role']],'center');
//            $t->echo_td($authority_list['ptw_role'][$row['ptw_role']],'center');
//            $t->echo_td($authority_list['wsh_mbr_flag'][$row['wsh_mbr_flag']],'center');
//            $t->echo_td($authority_list['meeting_flag'][$row['meeting_flag']],'center');
//            $t->echo_td($authority_list['training_flag'][$row['training_flag']],'center');
            //$t->echo_td($authority_list['program_role'][$row['program_role']]);
            $t->echo_td($company_list[$row['contractor_id']]);
            $t->echo_td($roleList[$program_role[0]],'center');
            $t->echo_td($robox_list[$row['robox_role']],'center');
//            $t->echo_td($roleList[$program_role[1]],'center');
//            $t->echo_td($roleList[$program_role[2]],'center');
        }
        $status = '<span class="badge ' . $status_css[$row['check_status']] . '">' . $status_list[$row['check_status']] . '</span>';
        $t->echo_td($status,'center'); //状态

        $robox_hide_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemHideRobox(\"{$row['id']}\")' title='Robox Role'><i class=\"fa fa-user \"></i></a>&nbsp;";
        $robox_show_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemShowRobox(\"{$row['id']}\")' title='Robox Role'><i class=\"fa fa-user \"></i></a>&nbsp;";

        $link = "<a class='a_logo' href='javascript:void(0)' onclick='itemPhoto(\"{$row['user_id']}\")' title=\" " .Yii::t('comp_staff', 'Qualification Certificate')."\"><i class=\"fa fa-fw fa-paperclip\"></i></a>&nbsp;
        <a class='a_logo' href='javascript:void(0)' onclick='itemDownload(\"{$row['program_id']}\",\"{$row['user_id']}\")' title=\" " . Yii::t('proj_project_user', 'user_pdf') . "\"><i class=\"fa fa-fw fa-download\"></i></a>&nbsp;
        <a class='a_logo' href='javascript:void(0)' onclick='itemEdit(\"{$row['user_id']}\",\"{$program_id}\")' title=\"Edit\"><i class=\"fa fa-fw fa-edit\"></i></a>&nbsp;"; //导出人员PDF
        
        
        if ($row['check_status'] == ProgramUser::ENTRANCE_SUCCESS ) { //状态是入场审批成功
            $link .= "<a class='a_logo' href='javascript:void(0)' onclick='itemLeave(\"{$row['program_id']}\",\"{$row['user_id']}\",\"{$user_list[$row['user_id']]}\")' title=\" ".Yii::t('proj_project_user', 'leave')."\"><i class=\"fa fa-fw fa-times\"></i></a>";    //出场
        }
        else if($row['check_status'] == ProgramUser::LEAVE_PENDING || $row['check_status'] == ProgramUser::LEAVE_FAIL){
//            $link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemEye(\"{$program_id}\",\"{$row['user_id']}\",\"{$user_list[$row['user_id']]}\")'><i class=\"fa fa-fw fa-eye\"></i>可见</a></li></ul>";    //可见按钮
        } else if($row['check_status'] == ProgramUser::ENTRANCE_APPLY || $row['check_status'] == ProgramUser::ENTRANCE_PENDING || $row['check_status'] == ProgramUser::ENTRANCE_FAIL){
            if($value == '1'){
                $link .= "<ul class='format1'><li class='format2'><a style='float: left;margin-left:5px' href='javascript:void(0)' onclick='itemDelete(\"{$row['program_id']}\",\"{$row['user_id']}\",\"{$user_list[$row['user_id']]}\")'><i class=\"fa fa-fw fa-times\"></i>".Yii::t('proj_project_user', 'delete')."</a></li></ul>";    //删除
            }
        }else {
            $link .= '';
        }
        if($row['robox_role'] == '0'){
            $link.='&nbsp'.$robox_show_link;
        }else if($row['robox_role'] == '1'){
            $link.='&nbsp'.$robox_hide_link;
        }

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
    <div class="col-12">
        <div  class="padding-lr5">
            <?php  if($value == '1'){  ?>
                <button type="button" class="btn btn-primary btn-sm" onclick="related(<?php echo $program_id; ?>);"><?php echo Yii::t('proj_project_user', 'related_people'); ?></button>
            <?php } ?>
            <!--<button type="button" class="btn btn-primary btn-sm" onclick="back(<?php echo $program_id ?>);"><?php echo Yii::t('proj_project_user', 'back_program'); ?></button> -->
            <button type='button' class='btn btn-primary btn-sm' onclick='compress("{$program_id}","{$tag}","{$curpage}");'><?php echo Yii::t('proj_project_user', 'compress'); ?></button>
            <button type='button' class='btn btn-primary btn-sm' onclick='itemExport("{$program_id}")'><?php echo Yii::t('proj_project_user', 'export'); ?></button>
<!--            --><?php //echo "<button type='button' class='btn btn-primary btn-sm' style='margin-left: 10px' onclick='batchleave(\"{$program_id}\",\"{$tag}\")'>".Yii::t('proj_project_user', 'batch_live')."</button>" ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-3">
        <div class="dataTables_info" id="example2_info" >
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-9">
        <div class="dataTables_paginate paging_bootstrap"  >
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

<!--<script type="text/javascript" src="js/bootstrap.min.js"></script>-->
<!--<script type="text/javascript" src="js/bootstrap.js"></script>-->
<!--<script type="text/javascript" src="js/bootstrap-editable.js"></script>-->
<!--<script type="text/javascript" src="js/select2.js"></script>-->
<script src="js/loading.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
    //返回
    var back = function (id) {
        window.location = "index.php?r=proj/project/list&ptype=<?php echo Yii::app()->session['project_type'];?>&program_id="+id;
    }

    //checkbox
    function test(o) {
//        alert(123);
    }
    $('.select_all').click(function(){
//        alert(456);
    });
    //批量出场
    var batchleave = function (id,tag) {
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
        alert('<?php echo Yii::t('proj_project_user', 'confirm_batch_live'); ?>');
        $.ajax({
            data: {id: id,tag:tag},
            url: "index.php?r=proj/assignuser/batchleaveuser",
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
            if(key != 0) {
                if ($(value).prop('checked')) {
                    var user_id = tbodyObj.rows[key].cells[1].innerText;
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
        var modal=new TBModal();
        modal.title="Company List";
        modal.url="./index.php?r=proj/assignuser/sublist&program_id="+id;
        modal.modal();
    }
    jQuery(document).ready(function () {

        function initTableCheckbox() {
            var $thr = $('#example2 thead tr');
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
            var $tbr = $('#example2 tbody tr');
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

            //$.fn.editable.defaults.mode = 'popup';
        //$.fn.editable.defaults.mode = 'inline';
        var n = 4;
        function showTime(flag) {
            if (flag == false)
                return;
            n--;
            $('.popover fade top in editable-container editable-popup').html(n + ' <?php echo Yii::t('common', 'tip_close'); ?>');
            if (n == 0)
                $("#modal-close").click();
            else
                setTimeout('showTime()', 1000);
        }
        var program_id = $("#program_id").val();
    });

</script>

