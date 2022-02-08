<div class="row" >
    <div class="col-12">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form_2" id="_query_form_2" role="form">
                <div class="row" >
                    <input type="hidden" id="project_id" name="q[project_id]"  value="<?php echo $program_id; ?>">
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 150px;">
                        <select class="form-control input-sm"  id="template_id" name="q[template_id]" style="width: 100%;" >
                            <?php
                                $template_list = TaskTemplate::templateByProgram($program_id);
                                if(count($template_list)>0){
                                    foreach ($template_list as $x => $y) {
                                        echo "<option value='{$x}'>{$y}</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 100px;">
                        <input type="text" class="form-control input-sm" name="q[block]" placeholder="Block" style="width: 100%;"   value="<?php echo array_key_exists('block',$args)?$args['block']:""; ?>">
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 100px;">
                        <input type="text" class="form-control input-sm" name="q[level]" placeholder="Level" style="width: 100%;"   value="<?php echo array_key_exists('level',$args)?$args['level']:""; ?>">
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 100px;">
                        <input type="text" class="form-control input-sm" name="q[part]" placeholder="Part" style="width: 100%;"   value="<?php echo array_key_exists('part',$args)?$args['part']:""; ?>">
                    </div>
                    <a class="tool-a-search" style="" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery_1();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {

    })
</script>