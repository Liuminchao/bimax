<div class="row" >
    <div class="col-10">
        <div class="dataTables_length">
            <form class="form-inline" name="_query_form" id="_query_form" role="form">
                <div class="form-group " style="padding-bottom:5px;">
                    <input type="text" id="operator_id" class="form-control input-sm"  name="q[operator_id]" placeholder="Username" >
                </div>
                <div class="form-group padding-lr5" style="padding-bottom:5px;margin-left: 5px;">
                    <input type="text" id="name" class="form-control input-sm"  name="q[name]" placeholder="Name" >
                </div>
                <a class="tool-a-search" href="javascript:itemQuery();" style="margin-left: 5px;"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search'); ?></a>
            </form>
        </div>
    </div>
    <div class="col-2" style="text-align: right">
        <?php if (Yii::app()->user->getState('operator_role') == '00'){ ?>
            <button class="btn btn-primary btn-sm" onclick="add('<?php echo $id ?>','<?php echo $name ?>')"><?php echo Yii::t('proj_project', 'add'); ?></button>
        <?php  } ?>
    </div>
</div>
<script type="application/javascript">

</script>