<?php 
//echo "$program_id";
?>
<div class="row" style="margin-left: -20px;">
    <div class="col-xs-9">
        <div class="dataTables_length">
            <form name="_query_form" id="_query_form" role="form">
                
                <input type="hidden" name="q[program_id]" value="<?php echo $program_id;    ?>">
                
                <div class="col-xs-2 padding-lr5" style="width:200px">
                    <input type="text" class="form-control input-sm" name="q[task_id]" placeholder="<?php echo Yii::t('task', 'task_id'); ?>">
                </div>

                <a class="tool-a-search" href="javascript:itemQuery();"><i class="fa fa-fw fa-search"></i> <?php echo Yii::t('common', 'search');?></a>
            </form>
        </div>
    </div>
    <div class="col-xs-3">
        <div class="dataTables_filter" >
            <label>
           
                <button class="btn btn-primary btn-sm" onclick="itemAdd('<?php echo $program_id; ?>')"><?php echo Yii::t('task', 'Add Task');   ?></button>
                <a class="btn btn-primary btn-sm" href="./?r=proj/project/list&ptype=SC"><?php echo Yii::t('common', 'button_back');  ?></a>
                <!--<button class="btn btn-primary btn-sm" onclick="back()"><?php echo Yii::t('common', 'button_back');  ?></button>-->
            </label>
        </div>
    </div>
</div>

<script type="text/javascript">
    //返回
    var back = function () {
        //alert('back');
        window.location = "./?r=proj/project/list&ptype=SC";
        //window.history.back();
    }

    
</script>