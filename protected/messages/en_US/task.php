<?php

/**
 * 操作员管理
 * @author LiuMinchao
 */
return array(
    //--------------标题------te-----------
    'contentHeader' => 'Project Management',
    'bigMenu' => 'Project Management',
    'smallHeader List' => 'Task List',
    'smallHeader New' => 'Add Task',
    'smallHeader Subtask New' => 'Add Subtask',
    'smallHeader Edit' => 'Edit Task',
    'smallHeader Subtask Edit' => 'Edit Subtask',
    'smallHeader Set' => 'Set Staff',
    'smallHeader Export' => 'Export Project schedule',
    'smallHeader Upload' => 'Upload attachment',
    'Add Task'  =>  'Add First level task',
    'Edit Task' => 'Edit Task',
    'Delete Proj'   =>  'Delete Project',
    //--------------表单-----------------
    'month' => 'Month',
    'no_task' => 'No higher task',
    'upper_level_task' => 'Superior task',
    'add_two_task' => 'Add subtask',
    'primary_task' => 'Primary Task',
    'second_task' => 'Two sortage task',
    'task_id' => 'Task NO.',
    'program_id' => 'Program NO.',
    'task_name' => 'Task Name',
    'task_content'   =>  'Task Content',
    'contractor_id' => 'Contractor NO.',
    'add_operator' => 'Add Operator ID',
    'status' => 'Status',
    'record_time' => 'Create Date',
    'STATUS_NORMAL' => 'Normal',
    'STATUS_STOP' => 'Project Concluding',
    'task_user_set' => 'members',
    'attach_ment' => 'Attachment',
    'upload' => 'Upload',
    'attach_content'=> 'Attach Content',
    'plan_amount' => 'amount of plan',
    'plan_rate' => 'Plan to complete the progress',
    'amount_unit' => 'Company',
    'plan_work_hour' => 'Plan work hour',
    'plan_start_time' => 'Plan start time',
    'plan_end_time' => 'Plan end time',
    'act_start_time' => 'Actual start time',
    'act_end_time' => 'Actual end time',
    'act_work_hour' => 'Actual work hour',
    'task_user' => 'Participating personnel',
    'work_date' => 'work date',
    'search_contractor_id' => 'Search Contractor NO.',
    'enter_contractor_name' => 'Please enter Contractor Name.',
    'sub_contractor_name' => 'Sub-Contractor',
    'contractor_name' => 'Main-Contractor',
    'Assign SC' => 'Assigned Sub-Contractor',
    'id' => 'NO.',
    'null' => 'None',
    'confirm_stop'  =>  'Are you sure to end item？After the project is over, the project will not be opened again.',
    'contractor_type'   =>  'Project Type',
    'project_struct'    =>  'Sub-Contractor Project',
    'project_decomposition' => 'Project decomposition ',
    'project_sub_click' =>  'Click on and embark on the Sub-Contractor Project Management',
    'project_dec_click' => 'Click on and embark on the Sub-Contractor Project decomposition',
    'Assign User' => 'To specify the task team member by post',
    'export_task_report'=> 'Export task report',
    //-------------验证-----------------------
    'error_task_name_is_null' => 'Task Name can not be empty!',
    'error_task_id_is_null' => 'Task NO. can not be empty!',
    'error_work_date_is_null' => 'Date can not be empty！',
    'error_task_date_is_end' => 'Task is be overdue！',
    'error_task_date_is_start' => 'This task is not yet started',
    'error_task_user_is_null' => 'Specify at least one member',
    'error_date_early' =>'Start date must be later than the start date of primary task',
    'error_date_late' =>' End date must be earlier than the end date of the primary task',
    'delete_father_task'=>'Two level project existses,Child items will also be deleted,Confirm to Delete?',
);
