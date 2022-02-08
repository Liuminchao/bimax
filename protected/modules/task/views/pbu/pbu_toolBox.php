<div class="row" >
    <div class="col-9">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="row" >
                    <input type="hidden" id="pbu_tag" name="q[pbu_tag]"  value="<?php echo $pbu_tag; ?>">
                    <input type="hidden" id="project_id" name="q[project_id]"  value="<?php echo $program_id; ?>">
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                        <input type="text" class="form-control input-sm" name="q[pbu_type]" placeholder="Pbu Type" style="width: 100%;"   value="<?php echo array_key_exists('pbu_type',$args)?$args['pbu_type']:""; ?>">
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 100px;">
                        <input type="text" class="form-control input-sm" name="q[block]" placeholder="Block" style="width: 100%;"   value="<?php echo array_key_exists('block',$args)?$args['block']:""; ?>">
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 100px;">
                        <input type="text" class="form-control input-sm" name="q[level]" placeholder="Level" style="width: 100%;"   value="<?php echo array_key_exists('level',$args)?$args['level']:""; ?>">
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 100px;">
                        <input type="text" class="form-control input-sm" name="q[unit_nos]" placeholder="Unit No" style="width: 100%;"   value="<?php echo array_key_exists('unit_nos',$args)?$args['unit_nos']:""; ?>">
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 100px;">
                        <input type="text" class="form-control input-sm" name="q[part]" placeholder="Part" style="width: 100%;"   value="<?php echo array_key_exists('part',$args)?$args['part']:""; ?>">
                    </div>
                    <div class="form-group padding-lr5" style="padding-bottom:5px;width: 200px;">
                        <input type="text" class="form-control input-sm" name="q[pbu_id]" placeholder="System Id" style="width: 100%;"   value="<?php echo array_key_exists('pbu_id',$args)?$args['pbu_id']:""; ?>">
                    </div>
                    <a class="tool-a-search" style="" href="javascript:<?php echo $this->gridId; ?>.page=0;itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
                </div>
            </form>
        </div>
    </div>
    <div class="col-3">
        <label  class="padding-lr5 float-sm-right">
            <button class="btn btn-primary btn-sm" onclick="itemAllocate('<?php echo $program_id; ?>','<?php echo $pbu_tag; ?>')">Allocate</button>
            <button class="btn btn-primary btn-sm" onclick="itemEditPbutype('<?php echo $program_id; ?>')">Edit</button>
        </label>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {

    })
</script>