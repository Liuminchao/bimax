<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <?php
                $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                //                $endDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                ?>
                <div class="form-group padding-lr5" style="width: 50px;padding-left: 0px;">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_start_date" name="q[start_date]" id="q_start_date"  placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" />
                        <div class="input-group-append" data-target="#q_start_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group padding-lr5" style="width: 30px;">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_end_date" name="q[end_date]" id="q_end_date"  placeholder="<?php echo Yii::t('common', 'date_of_application'); ?>" />
                        <div class="input-group-append" data-target="#q_end_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="q[project_id]" id="project_id" value="<?php echo $project_id; ?>">
                <input type="hidden" name="q[template_id]" id="project_id" value="<?php echo $template_id; ?>">
                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i><?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-3">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $project_id; ?>','<?php echo $template_id; ?>')">Add New Stage</button>
                <button class="btn btn-default btn-sm" onclick="itemBack('<?php echo $project_id; ?>')"><?php echo Yii::t('common', 'button_back');?></button>
            </label>
        </div>
    </div>
</div>
<script type="application/javascript">
    jQuery(document).ready(function () {
        $('#q_start_date').datetimepicker({
                format: 'DD MMM yyyy'
            });
        $('#q_end_date').datetimepicker({
                format: 'DD MMM yyyy'
            });
    });
</script>