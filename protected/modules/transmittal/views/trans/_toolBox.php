<div class="row" >
    <div class="col-10">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="form-group " style="width: 200px;">
                    <input id="program_id" type="hidden" name="q[program_id]" value="<?php echo $program_id  ?>">
                    <input type="text" id="project_nos" class="form-control" style="width: 100%"  name="q[project_nos]" placeholder="Reference No." value="<?php echo array_key_exists('project_nos',$args)?$args['project_nos']:""; ?>">
                </div>
                <div class="form-group padding-lr5" style="width: 200px;">
                    <input type="text" id="subject" class="form-control" style="width: 100%"  name="q[subject]" placeholder="Subject" value="<?php echo array_key_exists('subject',$args)?$args['subject']:""; ?>">
                </div>
                
                <?php
                    $startDate = Utils::DateToEn(date('Y-m-d',strtotime('-1 day')));
                    $endDate   = Utils::DateToEn(date('Y-m-d'));
                ?>
                <div class="form-group padding-lr5" style="width: 40px;height:30px">
                    <?php echo Yii::t('license_licensepdf', 'from'); ?>
                </div>
                <div class="form-group padding-lr5" style="width: 170px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_start_date" name="q[start_date]" id="q_start_date"  placeholder="<?php echo Yii::t('common', 'issuance_date'); ?>"  value="<?php echo array_key_exists('start_date',$args)?$args['start_date']:$startDate; ?>"/>
                        <div class="input-group-append" data-target="#q_start_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group padding-lr5" style="width: 20px;height:30px">
                    <?php echo Yii::t('license_licensepdf', 'to'); ?>
                </div>
                <div class="form-group padding-lr5" style="width: 170px;">
                    <div class="input-group date"  data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#q_end_date" name="q[end_date]" id="q_end_date"  placeholder="<?php echo Yii::t('common', 'issuance_date'); ?>"  value="<?php echo array_key_exists('end_date',$args)?$args['end_date']:$endDate; ?>"/>
                        <div class="input-group-append" data-target="#q_end_date" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>

                <div class="form-group padding-lr5">
                    <select class="form-control input-sm" name="q[status]" id="status" style="width: 100%;">
                        <option value="">--Status--</option>
                        <option value="0" <?php if($args['status'] == '0') echo "selected"; ?>>Issued</option>
                        <option value="1" <?php if($args['status'] == '1') echo "selected"; ?>>Received</option>
                        
                    </select>
                </div>
                <a class="tool-a-search" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-2">
        <div class="dataTables_filter" >
            <label>
                <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id; ?>')">Create</button>
            </label>
        </div>
    </div>
</div>
<script type="application/javascript">
    $(function () {
        $('#q_start_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
        $('#q_end_date').datetimepicker({
            format: 'DD MMM yyyy'
        });
    })
</script>