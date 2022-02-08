<form name="query_form" id="_query_form_3" role="form">
    <div class="row" style="display:none;">
        <input id="program_id" name="q[program_id]" type="hidden" value="<?php echo $program_id; ?>">
        <div class="form-group padding-lr5" style="width:200px">
            <select class="form-control input-sm" name="q[template_id]"  id="template_id_2" style="width: 100%;">
                <?php
                $template_list = TaskTemplate::templateByProgram($program_id);
                if(count($template_list)>0){
                    foreach ($template_list as $template_id => $template_name) {
                        echo "<option value='{$template_id}'>{$template_name}</option>";
                    }
                }
                ?>
            </select>
        </div>
        <a class="tool-a-search" href="javascript:itemQuery_3();"><i class="fa fa-fw fa-search"></i> Search</a>
    </div>
</form>