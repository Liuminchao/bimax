<?php

return array(
    '00' => array( //系统管理员
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('dboard','smanager'),
        'children' => array(
            'person', //个人信息维护
            'operator', //操作员管理
            'optlog', //系统日志
            'compinfo', //承包商管理
            'platform_document',//平台文档
            'licence_template', //许可证模板维护
            'workflow', //工作流维护
            'workflow_step',//工作流配置
            'attend_record',    //考勤记录查询
            'admin_info',//承包商统计信息
            'ad_license_pdf',//PTW安全条件
            'ad_routine_inspection',//例行检查安全条件
        ),
        'bizRules' => '',
        'data' => 0
    ),

    '01' => array( //承包商管理员
        'type' => CAuthItem::TYPE_ROLE,
        'description' => Yii::t('dboard','cmanager'),
        'children' => array(
            'person', //个人信息维护
            'comp_staff', //人员管理
            'comp_worker',
            'comp_role', //总包角色
            //     'role', //总包角色
            //     'staff',//员工管理
            'aptitude',//资质管理
            'project', //项目维护
            'workhour_query',//工时查询
            'workhour_set',//工时设置
            'wage_set',//时薪设置
            'allowance_set',//补贴设置
            'salary_set',//工资计算
            'salary_query',//工资查询
            'assign_worker', //项目指派工人
            'license_pdf', //许可证PDF查询及下载
            'ra_sample',//风险评估
            'train',//培训
            'meet',//会议
            'accident',//意外
            'wsh_inspection',//安全检查
            'quality_inspection',//QA/QC安全检查
            'routine_inspection',//例行检查
            'qa_inspection',//质量检查
            'worker_workflow',//人员出入场审核配置
            'workflow_step',//工作流配置
            'meeting_down',//会议文档下载
            'device_manage',//设备管理
            'chemical_manage',//化学物品管理
            'attend_all',   //考勤应用
            'company_document', //企业管理平台
            'company_info',//承包商统计信息
            'report',//月度报告
        ),
        'bizRules' => '',
        'data' => 0
    ),

);
