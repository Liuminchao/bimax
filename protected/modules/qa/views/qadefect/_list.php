<?php
$t->echo_grid_header();
if (is_array($rows)) {
    $j = 1;

    $status_list = QaDefect::statusText(); //状态text
    $status_css = QaDefect::statusCss(); //状态css
    $type_list = QaDefectType::AllType();//type集合
    foreach ($rows as $i => $row) {
        $t->echo_td($j); //检查单编号
        $j++;
        $check_model = QaDefect::model()->findByPk($row['check_id']);
        $source = $check_model->source;
        $source_id = $check_model->source_id;

        if($source_id != ''){
            if($source == 'INSPECTION'){
                $qa_model = QaCheck::model()->findByPk($source_id);
                $clt_type = $qa_model->clt_type;

                if($clt_type == '01'){
                    $discipline = 'C&S';
                }else if($clt_type == '02'){
                    $discipline = 'AR';
                }else if($clt_type == '03'){
                    $discipline = 'M&E';
                }else{
                    $discipline = 'NA';
                }
            }
            if($source == 'DFMA'){
                $task_model = TaskRecord::model()->findByPk($source_id);
                $clt_type = $task_model->clt_type;
                if($clt_type == 'A'){
                    $discipline = 'On-Site';
                }else if($clt_type == 'B'){
                    $discipline = 'Fitting Out';
                }else if($clt_type == 'C'){
                    $discipline = 'Carcass';
                }else{
                    $discipline = 'NA';
                }
            }

        }else{
            $discipline = 'NA';
        }

//        $t->echo_td($type_list[$row['type_id']]['type_name']); //type
        $t->echo_td($discipline); //type
        $t->echo_td($row['title']); //title
        $t->echo_td($row['block']); //block
        $t->echo_td($row['secondary_region']); //secondary_region
        $apply_user = Staff::model()->findByPk($row['apply_user_id']);
        $apply_user_name = $apply_user->user_name;
        $t->echo_td($apply_user_name); //apply user
        $person_to_rectify = Staff::model()->findByPk($row['person_to_rectify']);
        $person_to_rectify_name = $person_to_rectify->user_name;
        $t->echo_td($person_to_rectify_name); //负责人
        $t->echo_td(Utils::DateToEn($row['apply_time']),'center'); //发起时间
        $status = '<span class="badge ' . $status_css[$row['status']] . '">' . $status_list[$row['status']] . '</span>';
        $t->echo_td($status,'center'); //状态

        $workflow_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemWorkflow(\"{$row['check_id']}\")' title='Workflow'><i class=\"fa fa-fw fa-sort-amount-down\"></i></a>&nbsp;";
        if($row['drawing_id']){
            $attachment_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownloadAttachment(\"{$row['check_id']}\",\"1\")' title='Drawings'><i class=\"fa fa-fw fa-paperclip\"></i></a>";
        }else{
            $attachment_link = "<a class='a_logo' href='javascript:void(0)' onclick='itemDownloadAttachment(\"{$row['check_id']}\",\"0\")' title='Drawings'><i class=\"fa fa-fw fa-paperclip\"></i></a>";
        }        $link = "";
//        if($row['status'] === '1'){    //完成后
            $link .= $attachment_link.'&nbsp;'.$workflow_link;
//        }
//        else{
//            $link .=  "<table><tr><td style='white-space: nowrap'>$preview_link</td></tr></table>";
//        }

        $t->echo_td($link,'center'); //操作
        $t->end_row();
    }

}

$t->echo_grid_floor();
$pager = new CPagination($cnt);
$pager->pageSize = $this->pageSize;
$pager->itemCount = $cnt;
?>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="row">
    <div class="col-5">
        <div class="dataTables_info" id="example2_info">
            <?php echo Yii::t('common', 'page_total'); ?> <?php echo $cnt; ?> <?php echo Yii::t('common', 'page_cnt'); ?>
        </div>
    </div>
    <div class="col-7">
        <div class="dataTables_paginate paging_bootstrap">
            <?php $this->widget('AjaxLinkPager', array('bindTable' => $t, 'pages' => $pager)); ?>
        </div>
    </div>
</div>

