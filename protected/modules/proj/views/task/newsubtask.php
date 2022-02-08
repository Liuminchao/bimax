<div class="box box-info">
<?php
//var_dump($program_id);
$this->renderpartial('_subtaskform', array('program_id' => $program_id,'task_id' => $task_id,'_mode_' => 'insert','model' => $model, 'msg' => $r)); ?>
</div>

