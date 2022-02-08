<style type="text/css">
    .format1{
        list-style:none; padding:0px; margin:0px; width:200px;
    }
    .format2{ width:50%; display:inline-block;}
    .work_format{

    }
    .popover{
        top:-35px;!important
    }
</style>
<?php
$t->echo_grid_header();

    if (is_array($rows)|| is_array($arry)) {
        $i = 0;


        $title = Yii::t('proj_project', 'Project Set');
        $j = 1;
        $status_list = ProgramUser::statusText(); //状态text
        $status_css = ProgramUser::statusCss(); //状态css
        $project_list = Program::programList();//项目列表
        $flag_list = OperatorProject::flagList();
        $contractor_list = Contractor::compList();

        $attr['style'] = 'display:none';
//        var_dump($rows);
//        exit;
        foreach ($rows as $i => $row) {
            $num = ($curpage - 1) * $this->pageSize + $j++;
//        $t->echo_td('<input type="checkbox"  name="checkItem" id="'.$row['user_id'].'" onclick="test(this);">');
            $t->echo_td($row['program_id']); //项目编号
            $program_model = Program::model()->findByPk($row['program_id']);
            $contractor_id = $program_model->contractor_id;
            $t->echo_td($contractor_list[$contractor_id]); //Contractor Name
            $t->echo_td($project_list[$row['program_id']]); //Program Name
            $t->echo_td('<a href="#" class="editable editable-click pro_flag" id="pro_flag" data-type="select" data-pk="1" data-placement="top" data-url="index.php?r=comp/info/setauthority&program_id='.$row['program_id'].'&operator_id='.$operator_id.'" data-title="'.$title.'" >'.$flag_list[$row['flag']].'</a>','center');
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
            <input type="hidden" id ="program_id" value="<?php echo $program_id ?>">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-6">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="dataTables_info" id="example2_info">&nbsp;&nbsp;
            <button type="button" class="btn btn-primary btn-sm" style="margin-left: 10px" onclick="back('<?php echo $contractor_id ?>','<?php echo $company_name ?>');"><?php echo Yii::t('common', 'button_back'); ?></button>
        </div>
    </div>
</div>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="js/bootstrap-editable.js"></script>
<script type="text/javascript" src="js/select2.js"></script>
<script src="js/loading.js"></script>
<script type="text/javascript">
    //返回
    var back = function (id,name) {
        window.location = "index.php?r=comp/info/operatorlist&id="+id+"&name="+name;
    }

    //checkbox
    function test(o) {
//        alert(123);
    }



    jQuery(document).ready(function () {

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

        $('.first_role').editable({
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=proj/assignuser/getsource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: value.id, text: value.name });
                        });
                    }
                });
                return result; } ,
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });

        $('.pro_flag').editable({
            ajaxOptions: {
                dataType: 'json' //assuming json response
            },
            pk: 1,
            validate: function(newValue) {
                if($.trim(newValue) == '') {
                    return '<?php echo Yii::t('proj_project_user', 'alert_authority'); ?>';
                }
            },
            source: function () {
                var result = [];
                $.ajax({
                    url: 'index.php?r=comp/info/setprosource',
                    async: false,
                    type: "get",
                    dataType: 'json',
                    success: function (data, status) {
                        $.each(data, function (key, value) {
                            result.push({ value: key, text: value });
                        });
                    }
                });
                return result; } ,
            success: function(response, newValue) {
                //$('#ra_role').editable('option', 'source', sources[newValue]);
                //$('#ra_role').editable('setValue', null);
                //return '设置成功';
                //showTime(response.refresh);
                //return response.msg;
            },
            error: function(response, newValue) {
                if(response.status === 500) {
                    return 'Service unavailable. Please try later.';
                }else{
                    return '未知错误';
                }
            },
        });
    });

</script>

