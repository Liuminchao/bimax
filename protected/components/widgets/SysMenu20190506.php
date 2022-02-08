<?php

/*
 * @author Su DunKuai <sudk@trunkbow.com>
 * @version $Id: SysMenu.php 2597 $
 * @package application.components.widgets
 * @since 1.1.1
 */

class SysMenu extends CWidget {

    public function init() {
        $items = Auth::getItemsList();
//        var_dump($items);
//        exit;
        if (isset(Yii::app()->user->auths) && count(Yii::app()->user->auths) > 0):
            foreach (Yii::app()->user->auths as $authid):
                $bizRules = $items[$authid]['bizRules'];
                $params = $items[$authid]['data'];
                if (array_key_exists($authid, $items) == true and
                        Yii::app()->authManager->isAssigned($authid, Yii::app()->user->id) == false):
                    Yii::app()->authManager->assign($authid, Yii::app()->user->id, $bizRules, $params);
                endif;
            endforeach;
        endif;
    }

    //运营支撑系统菜单
    public function sysMenu() {

        $menus = array();

        //系统管理
        $sub_menu = array();
        if (Yii::app()->user->checkAccess("operator"))
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Operator'), "url" => "?r=sys/operator/list", "match" => array('sys\/operator\/list', 'sys\/operator\/new', 'sys\/operator\/edit'));
        if (Yii::app()->user->checkAccess("optlog"))
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Optlog'), "url" => "?r=sys/optlog/list", "match" => array('sys\/optlog\/list'));

        if (count($sub_menu))
            $menus['sys'] = array("title" => Yii::t('dboard', 'Menu System'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //承包商管理
        $sub_menu = array();

        if (Yii::app()->user->checkAccess("compinfo"))
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu CompInfo'), "url" => "?r=comp/info/list", "match" => array('comp\/info\/new', 'comp\/info\/list', 'comp\/info\/edit', 'comp\/info\/logout'));

        if (count($sub_menu))
            $menus['comp'] = array("title" => Yii::t('dboard', 'Menu Comp'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //空间统计
        $sub_menu = array();

        if (Yii::app()->user->checkAccess("compinfo"))
            $sub_menu[] = array("title" => Yii::t('comp_statistics', 'spatial_statistics'), "url" => "?r=statistics/spatial/list", "match" => array('statistics\/spatial\/list'));

        if (count($sub_menu))
            $menus['spatial'] = array("title" => Yii::t('comp_statistics', 'spatial_statistics'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //业务统计
        $sub_menu = array();

        if (Yii::app()->user->checkAccess("compinfo")) {
            $sub_menu[] = array("title" => Yii::t('dboard', 'Business Statistics'), "url" => "?r=statistics/spatial/businesslist", "match" => array('statistics\/spatial\/businesslist'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Business Statistics Graph'), "url" => "?r=statistics/spatial/businessgraph", "match" => array('statistics\/spatial\/businessgraph'));
        }
        if (count($sub_menu))
            $menus['business'] = array("title" => Yii::t('dboard', 'Business Statistics'), 'ico' => 'fa-gear', "child" => $sub_menu);
        //平台文档
        $sub_menu = array();

        if (Yii::app()->user->checkAccess("platform_document"))
            $sub_menu[] = array("title" => Yii::t('comp_document', 'contentHeader_platform'), "url" => "?r=document/platform/list", "match" => array('document\/platform\/list'));

        if (count($sub_menu))
            $menus['paltform_document'] = array("title" => Yii::t('comp_document', 'smallHeader List Platform'), 'ico' => 'fa-gear', "child" => $sub_menu);


        //许可证审批
        $sub_menu = array();

//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=PTW", "match" => array('sys\/workflow\/list&app=PTW', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("ad_license_pdf")) {
            $sub_menu[] = array("title" => Yii::t('license_condition', 'bigMenu'), "url" => "?r=license/type/list", "match" => array('license\/type\/list'));
        }
        if (count($sub_menu))
            $menus['ad_audit'] = array("title" => Yii::t('dboard', 'Menu Lice'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //例行检查
        $sub_menu = array();

//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=PTW", "match" => array('sys\/workflow\/list&app=PTW', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("ad_routine_inspection")) {
            $sub_menu[] = array("title" => Yii::t('license_condition', 'bigMenu'), "url" => "?r=routine/type/list", "match" => array('routine\/type\/list'));
        }

        if (count($sub_menu))
            $menus['ad_routine'] = array("title" => Yii::t('comp_routine', 'bigMenu'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //承包商统计信息
//        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("admin_info"))
//            $sub_menu[] = array("title" => Yii::t('comp_statistics', 'smallHeader List Day'), "url" => "?r=statistics/conmodule/list", "match" => array('statistics\/conmodule\/list'));
//
//        if (count($sub_menu))
//            $menus['admin_info'] = array("title" => Yii::t('comp_statistics', 'contentHeader_day'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //员工管理
        $sub_menu = array();

//        if (Yii::app()->user->checkAccess("comp_worker"))
////            var_dump(111111111);
            $user_menu2[] = array("title" => Yii::t('comp_staff', 'smallHeader List'), "url" => "?r=comp/staff/list", "match" => array('comp\/staff\/list'));
            $user_menu2[] = array("title" => Yii::t('comp_staff', 'smallHeader New'), "url" => "?r=comp/staff/tabs&mode=insert&title=1", "match" => array('comp\/staff\/tabs'));
//            $user_menu2[] = array("title" => Yii::t('comp_staff', 'smallHeader Edit'), "url" => "?r=comp/staff/list", "match" => array('comp\/staff\/list'));
//            $worker_menu2[] = array("title" => Yii::t('comp_staff', 'smallHeader Worker List'), "url" => "?r=comp/staff/list", "match" => array('comp\/staff\/list'));
//            $worker_menu2[] = array("title" => Yii::t('comp_staff', 'smallHeader Worker New'), "url" => "?r=comp/staff/tabs&mode=insert&title=1", "match" => array('comp\/staff\/tabs'));
//            $worker_menu2[] = array("title" => Yii::t('comp_staff', 'smallHeader Worker Edit'), "url" => "?r=comp/staff/list", "match" => array('comp\/staff\/list'));
//        if (Yii::app()->user->checkAccess("comp_staff"))
//            $sub_menu[] = array("title" => Yii::t('dboard','Menu Comp Staff'), "url" => "?r=comp/staff/list", "match" => array('comp\/staff\/new','comp\/staff\/list', 'comp\/staff\/edit', 'comp\/staff\/tabs',
//                'comp\/staff\/pertabs','comp\/staff\/passtabs','comp\/staff\/bcatabs','comp\/staff\/csoctabs','comp\/staff\/instabs','comp\/staff\/logout','comp\/staff\/attachlist'), "child" => $sub_menu2);
        if (Yii::app()->user->checkAccess("comp_staff"))
            $sub_menu[] = array("title" => Yii::t('dboard','Menu Comp Staff'), "url" => "?r=comp/staff/list", "match" => array('comp\/staff\/new','comp\/staff\/list', 'comp\/staff\/edit', 'comp\/staff\/tabs',
                'comp\/staff\/pertabs','comp\/staff\/passtabs','comp\/staff\/bcatabs','comp\/staff\/csoctabs','comp\/staff\/instabs','comp\/staff\/logout','comp\/staff\/attachlist'), "child" => $user_menu2);
//        if (Yii::app()->user->checkAccess("comp_worker"))
//            $sub_menu[] = array("title" => Yii::t('dboard','Menu Comp Worker'), "url" => "?r=comp/staff/list", "match" => array('comp\/staff\/new','comp\/staff\/list', 'comp\/staff\/edit', 'comp\/staff\/tabs',
//                'comp\/staff\/pertabs','comp\/staff\/passtabs','comp\/staff\/bcatabs','comp\/staff\/csoctabs','comp\/staff\/instabs','comp\/staff\/logout','comp\/staff\/attachlist'), "child" => $worker_menu2);
        if (Yii::app()->user->checkAccess("comp_role"))
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Comp Role'), "url" => "?r=comp/role/list", "match" => array('comp\/role\/list'));
        if (count($sub_menu))
            $menus['staff'] = array("title" => Yii::t('dboard', 'Menu Staff'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //设备模块
        $sub_menu = array();
        if (Yii::app()->user->checkAccess("device_manage")){
            $device_menu2[] = array("title" => Yii::t('device', 'smallHeader List'), "url" => "?r=device/equipment/list", "match" => array('device\/equipment\/list'));
            $device_menu2[] = array("title" => Yii::t('device', 'smallHeader New'), "url" => "?r=device/equipment/new", "match" => array('device\/equipment\/new'));
            // $device_menu2[] = array("title" => Yii::t('device', 'External ConSASS audit summary'), "url" => "?r=device/equipment/consasslist", "match" => array('device\/equipment\/consasslist'));
            $sub_menu[] = array("title" => Yii::t('device', 'bigMenu'), "url" => "?r=device/equipment/list", "match" => array('device\/equipment\/list','device\/equipment\/grid','device\/equipment\/logout','device\/equipment\/attachlist'),"child"=>$device_menu2);
//            $register_menu2[] = array("title" => Yii::t('device', 'Scaffold register'), "url" => "?r=device/register/scaffoldlist", "match" => array('device\/register\/scaffoldlist'));
//            $register_menu2[] = array("title" => Yii::t('device', 'Ladder register'), "url" => "?r=device/register/ladderlist", "match" => array('device\/register\/ladderlist'));
//            $register_menu2[] = array("title" => Yii::t('device', 'Crane data logger report summary'), "url" => "?r=device/register/cranelist", "match" => array('device\/register\/cranelist'));
//            $sub_menu[] = array("title" => Yii::t('device', 'Register'), "url" => "?r=device/register/scaffoldlist", "match" => array('device\/register\/scaffoldlist'),"child"=>$register_menu2);
        }

        if (count($sub_menu))
            $menus['device'] = array("title" => Yii::t('device', 'contentHeader'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //化学物品模块
//        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("chemical_manage")){
//            $chemical_menu2[] = array("title" => Yii::t('chemical', 'smallHeader List'), "url" => "?r=chemical/chemicalmanage/list", "match" => array('chemical\/chemicalmanage\/list'));
//            $chemical_menu2[] = array("title" => Yii::t('chemical', 'smallHeader New'), "url" => "?r=chemical/chemicalmanage/new", "match" => array('chemical\/chemicalmanage\/new'));
////            $device_menu2[] = array("title" => Yii::t('device', 'smallHeader Edit'), "url" => "?r=device/equipment/list", "match" => array('device\/equipment\/list'));
//            $sub_menu[] = array("title" => Yii::t('chemical', 'bigMenu'), "url" => "?r=chemical/chemicalmanage/list", "match" => array('chemical\/chemicalmanage\/list','chemical\/chemicalmanage\/grid','chemical\/chemicalmanage\/logout','chemical\/chemicalmanage\/attachlist'),"child"=>$chemical_menu2);
//        }
//
//        if (count($sub_menu))
//            $menus['chemical'] = array("title" => Yii::t('chemical', 'contentHeader'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //项目管理	
        $sub_menu = array();
        if (Yii::app()->user->checkAccess("project")){
            //$sub_menu[] = array("title" => Yii::t('dboard', 'Menu Project'), "url" => "?r=proj/project/list", "match" => array('proj\/project\/list', 'proj\/project\/new', 'proj\/project\/edit', 'proj\/project\/struct','proj\/assignuser\/list', 'proj\/assignuser\/new', 'proj\/assignuser\/edit'));
            $mc_menu2[] = array("title" => Yii::t('proj_project', 'smallHeader List'), "url" => "?r=proj/project/list&ptype=MC", "match" => array('proj\/project\/list'));
            $mc_menu2[] = array("title" => Yii::t('proj_project', 'smallHeader New'), "url" => "?r=proj/project/new&ptype=MC", "match" => array('proj\/project\/new'));
//            $mc_menu2[] = array("title" => Yii::t('proj_project', 'smallHeader Edit'), "url" => "?r=proj/project/list&ptype=MC", "match" => array('proj\/project\/list'));
//            $mc_menu2[] = array("title" => '压缩测试', "url" => "?r=proj/project/test&ptype=MC", "match" => array('proj\/project\/test'));
            $sub_menu[] = array(
                "title" => Yii::t('dboard', 'Menu Project MC'),
                "url" => "?r=proj/project/list&ptype=MC",
                "match" => array(
                    'proj\/project\/list&ptype=MC', 'proj\/project\/new&ptype=MC',
                    'proj\/project\/edit&ptype=MC','proj\/project\/sublist&ptype=MC',
                    'proj\/project\/subnew&ptype=MC', 'proj\/project\/subedit&ptype=MC',
                    'proj\/project\/struct&ptype=MC', 'proj\/assignuser\/list&ptype=MC',
                    'proj\/assignuser\/new&ptype=MC', 'proj\/assignuser\/edit&ptype=MC',
                    'proj\/assignuser\/authoritylist&ptype=MC', 'proj\/assignuser\/devicelist&ptype=MC',
                    'proj\/assignuser\/deviceapply&ptype=MC', 'proj\/assignuser\/userapply&ptype=MC'
                ),"child"=>$mc_menu2
            );
            $sc_menu2[] = array("title" => Yii::t('proj_project', 'smallHeader List'), "url" => "?r=proj/project/list&ptype=SC", "match" => array('proj\/project\/list'));
//            $sc_menu2[] = array("title" => Yii::t('proj_project', 'smallHeader New'), "url" => "?r=proj/project/list&ptype=SC", "match" => array('proj\/project\/list'));
//            $sc_menu2[] = array("title" => Yii::t('proj_project', 'smallHeader Edit'), "url" => "?r=proj/project/list&ptype=SC", "match" => array('proj\/project\/list'));
            $sub_menu[] = array(
                "title" => Yii::t('dboard', 'Menu Project SC'),
                "url" => "?r=proj/project/list&ptype=SC",
                "match" => array(
                    'proj\/project\/list&ptype=SC', 'proj\/project\/new&ptype=SC',
                    'proj\/project\/edit&ptype=SC','proj\/project\/sublist&ptype=SC',
                    'proj\/project\/subnew&ptype=SC', 'proj\/project\/subedit&ptype=SC',
                    'proj\/project\/setregion&ptype=SC', 'proj\/project\/selectregion&ptype=SC',
                    'proj\/project\/struct&ptype=MC', 'proj\/assignuser\/list&ptype=SC',
                    'proj\/assignuser\/new&ptype=SC', 'proj\/assignuser\/edit&ptype=SC',
                    'proj\/assignuser\/authoritylist&ptype=SC', 'proj\/assignuser\/devicelist&ptype=SC',
                    'proj\/assignuser\/deviceapply&ptype=SC', 'proj\/assignuser\/userapply&ptype=SC',
                ),"child"=>$sc_menu2
            );

        }


        if (count($sub_menu))
            $menus['proj'] = array("title" => Yii::t('dboard', 'Menu Project'), 'ico' => 'fa-gear', "child" => $sub_menu);

//        //绩效管理
//        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("workhour_query")){
//            $sub_menu[] = array("title" => Yii::t('pay_payroll', 'contentHeader'), "url" => "?r=payroll/workhour/list", "match" => array('payroll\/workhour\/list','payroll\/workhour\/grid','payroll\/workhour\/export'));
//        }
////        if (Yii::app()->user->checkAccess("workhour_set")){
////             $sub_menu[] = array("title" => Yii::t('pay_payroll', 'contentHeader_set'), "url" => "?r=payroll/workhour/set", "match" => array('payroll\/workhour\/set','payroll\/workhour\/query'));
////        }
//        if (Yii::app()->user->checkAccess("wage_set")){
//             $sub_menu[] = array("title" => Yii::t('pay_payroll', 'contentHeader_wage'), "url" => "?r=payroll/wage/list", "match" => array('payroll\/wage\/set','payroll\/wage\/query'));
//        }
//        if (Yii::app()->user->checkAccess("allowance_set")){
//             $sub_menu[] = array("title" => Yii::t('pay_payroll', 'contentHeader_allowance'), "url" => "?r=payroll/allowance/list", "match" => array('payroll\/allowance\/new','payroll\/allowance\/edit'));
//        }
//        if (Yii::app()->user->checkAccess("salary_query")){
//             $sub_menu[] = array("title" => Yii::t('pay_payroll', 'contentHeader_salary_query'), "url" => "?r=payroll/salaryquery/list", "match" => array('payroll\/salaryquery\/grid','payroll\/salaryquery\/list'));
//        }
////        if (Yii::app()->user->checkAccess("salary_set")){
////             $sub_menu[] = array("title" => Yii::t('pay_payroll', 'contentHeader_salary_calculate'), "url" => "?r=payroll/salary/list", "match" => array('payroll\/salary\/grid','payroll\/salary\/list'));
////        }
//         if (count($sub_menu))
//            $menus['payroll'] = array("title" => Yii::t('pay_payroll', 'bigMenu'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //企业级文档管理
        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=TBM", "match" => array('sys\/workflow\/list&app=TBM', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("company_document"))
            $sub_menu[] = array("title" => Yii::t('comp_document', 'contentHeader_company'), "url" => "?r=document/company/list", "match" => array('document\/company\/list'));

        if (count($sub_menu))
            $menus['company_document'] = array("title" => Yii::t('comp_document', 'smallHeader List Company'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //工具箱会议
        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=TBM", "match" => array('sys\/workflow\/list&app=TBM', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("meeting_down")) {
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Meetingdown'), "url" => "?r=tbm/meeting/list", "match" => array('tbm\/meeting\/list'));
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Meetingdown Plan'), "url" => "?r=tbm/meeting/planlist", "match" => array('tbm\/meeting\/planlist'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=tbm/meeting/companychart", "match" => array('tbm\/meeting\/companychart'));
        }
        if (count($sub_menu))
            $menus['meeting'] = array("title" => Yii::t('dboard', 'Menu Meeting'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //许可证审批
        $sub_menu = array();

//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=PTW", "match" => array('sys\/workflow\/list&app=PTW', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("license_pdf")) {
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Licedown'), "url" => "?r=license/licensepdf/list", "match" => array('license\/licensepdf\/list'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Project Statistical Charts'), "url" => "?r=license/licensepdf/projectchart", "match" => array('license\/licensepdf\/projectchart'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=license/licensepdf/companychart", "match" => array('license\/licensepdf\/companychart'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Test Charts'), "url" => "?r=license/licensepdf/testchart", "match" => array('license\/licensepdf\/testchart'));
            $sub_menu[] = array("title" => Yii::t('license_condition', 'bigMenu'), "url" => "?r=license/type/list", "match" => array('license\/type\/list'));
        }
        if (count($sub_menu))
            $menus['audit'] = array("title" => Yii::t('dboard', 'Menu Lice'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //例行检查
        $sub_menu = array();

//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=PTW", "match" => array('sys\/workflow\/list&app=PTW', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("routine_inspection")) {
            $sub_menu[] = array("title" => Yii::t('comp_routine', 'dboard'), "url" => "?r=routine/routineinspection/list", "match" => array('routine\/rouineinspection\/list'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Project Statistical Charts'), "url" => "?r=routine/routineinspection/projectchart", "match" => array('routine\/rouineinspection\/projectchart'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=routine/routineinspection/companychart", "match" => array('routine\/rouineinspection\/companychart'));
            $sub_menu[] = array("title" => Yii::t('license_condition', 'bigMenu'), "url" => "?r=routine/type/list", "match" => array('routine\/type\/list'));
        }

        if (count($sub_menu))
            $menus['routine'] = array("title" => Yii::t('comp_routine', 'bigMenu'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //安全检查
        $sub_menu = array();

//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=PTW", "match" => array('sys\/workflow\/list&app=PTW', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("wsh_inspection")) {
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Wshdown'), "url" => "?r=wsh/wshinspection/list", "match" => array('wsh\/wshinspection\/list'));
            $sub_menu[] =array("title" => Yii::t('dboard', 'NCR Type Statistics'), "url" => "?r=wsh/wshinspection/projectchart", "match" => array('wsh\/wshinspection\/projectchart'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Violation Ranking'), "url" => "?r=wsh/wshinspection/rankinglist", "match" => array('wsh\/wshinspection\/rankinglist'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=wsh/wshinspection/companychart", "match" => array('wsh\/wshinspection\/companychart'));
        }
        if (count($sub_menu))
            $menus['wsh'] = array("title" => Yii::t('dboard', 'Menu Wsh'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //风险评估
        $sub_menu = array();

//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=PTW", "match" => array('sys\/workflow\/list&app=PTW', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("ra_sample")) {
            $sub_menu[] = array("title" => Yii::t('comp_ra', 'ra_list'), "url" => "?r=ra/raswp/list", "match" => array('ra\/raswp\/list'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Project Statistical Charts'), "url" => "?r=ra/raswp/projectchart", "match" => array('ra\/raswp\/projectchart'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=ra/raswp/companychart", "match" => array('ra\/raswp\/companychart'));
//            $sub_menu[] = array("title" => 'Method Statement with Risk Assessment', "url" => "?r=ra/raswp/method", "match" => array('ra\/raswp\/method'));
//            $sub_menu[] = array("title" => '邮件', "url" => "?r=ra/phpmail/mail", "match" => array('ra\/phpmail\/mail'));
        }

        if (count($sub_menu))
            $menus['ra'] = array("title" => Yii::t('comp_ra','dboard'), 'ico' => 'fa-gear', "child" => $sub_menu);


        //QAQC安全检查
//        $sub_menu = array();
//////        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//////            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=PTW", "match" => array('sys\/workflow\/list&app=PTW', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
//        if (Yii::app()->user->checkAccess("quality_inspection")) {
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Wshdown'), "url" => "?r=quality/qualityinspection/list", "match" => array('quality\/qualityinspection\/list'));
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Project Statistical Charts'), "url" => "?r=quality/qualityinspection/projectchart", "match" => array('quality\/qualityinspection\/projectchart'));
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=quality/qualityinspection/companychart", "match" => array('quality\/qualityinspection\/companychart'));
//        }
//
//        if (count($sub_menu))
//            $menus['quality'] = array("title" => Yii::t('dboard', 'Menu Qaqc'), 'ico' => 'fa-gear', "child" => $sub_menu);


        //资源入场出场审批
        /*$sub_menu = array();
        if(Yii::app()->user->checkAccess("worker_workflow")){
            $sub_menu[] = array("title" => Yii::t('sys_workerflow', 'Menu Stuff'),  "url" => "?r=sys/workerflow/list&app=WORKER_FLOW", "match" => array('sys\/workerflow\/list&app=', 'sys\/workerflow\/new', 'sys\/workerflow\/edit', 'sys\/workerflow\/set'));
        }
        if (count($sub_menu))
            $menus['resource_workflow'] = array("title" => Yii::t('sys_workerflow', 'contentHeader'), 'ico' => 'fa-gear', "child" => $sub_menu);*/


        //培训
        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=TBM", "match" => array('sys\/workflow\/list&app=TBM', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("train")) {
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Training'), "url" => "?r=train/training/list", "match" => array('train\/training\/list'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Project Statistical Charts'), "url" => "?r=train/training/projectchart", "match" => array('train\/training\/projectchart'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=train/training/companychart", "match" => array('train\/training\/companychart'));
//            $sub_menu[] = array("title" => 'Safety Training Matrix', "url" => "?r=train/report/view", "match" => array('train\/report\/view'));
        }

        if (count($sub_menu))
            $menus['train'] = array("title" => Yii::t('dboard', 'Menu Train'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //会议
        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=TBM", "match" => array('sys\/workflow\/list&app=TBM', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("train")) {
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Training'), "url" => "?r=meet/meeting/list", "match" => array('meet\/meeting\/list'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Project Statistical Charts'), "url" => "?r=meet/meeting/projectchart", "match" => array('meet\/meeting\/projectchart'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=meet/meeting/companychart", "match" => array('meet\/meeting\/companychart'));
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Safety Promotion Programme'), "url" => "?r=meet/meeting/promotelist", "match" => array('meet\/meeting\/promotelist'));
        }
        if (count($sub_menu))
            $menus['meet'] = array("title" => Yii::t('dboard', 'Menu meeting'), 'ico' => 'fa-gear', "child" => $sub_menu);


        //意外
        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=TBM", "match" => array('sys\/workflow\/list&app=TBM', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("accident")) {
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Accident down'), "url" => "?r=accidents/accident/list", "match" => array('accidents\/accident\/list'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=accidents/accident/companychart", "match" => array('accidents\/accident\/companychart'));
        }
        if (count($sub_menu))
            $menus['accident'] = array("title" => Yii::t('dboard', 'Menu Accident'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //质量检查
        $sub_menu = array();

//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=PTW", "match" => array('sys\/workflow\/list&app=PTW', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("qa_inspection")) {
            $sub_menu[] = array("title" => Yii::t('comp_routine', 'dboard'), "url" => "?r=qa/qainspection/list", "match" => array('qa\/qainspection\/list'));
            $sub_menu[] = array("title" => 'Multiple Upload', "url" => "?r=qa/import/view", "match" => array('qa\/import\/view'));
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Project Statistical Charts'), "url" => "?r=qa/qainspection/projectchart", "match" => array('routine\/qainspection\/projectchart'));
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Company Statistical Charts'), "url" => "?r=qa/qainspection/companychart", "match" => array('routine\/qainspection\/companychart'));
        }

        if (count($sub_menu))
            $menus['qa'] = array("title" => Yii::t('comp_qa', 'bigMenu'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //月度报告模块
//        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("report")){
//            $report_menu2[] = array("title" => Yii::t('report', 'smallHeader List'), "url" => "?r=report/report/list", "match" => array('report\/report\/list'));
//            $report_menu2[] = array("title" => Yii::t('report', 'smallHeader New'), "url" => "?r=report/report/new", "match" => array('report\/report\/new'));
////            $device_menu2[] = array("title" => Yii::t('device', 'smallHeader Edit'), "url" => "?r=device/equipment/list", "match" => array('device\/equipment\/list'));
//            $sub_menu[] = array("title" => Yii::t('report', 'bigMenu'), "url" => "?r=report/report/list", "match" => array('report\/report\/list','report\/report\/grid','report\/report\/logout'),"child"=>$report_menu2);
//        }
//
//        if (count($sub_menu))
//            $menus['report'] = array("title" => Yii::t('report', 'contentHeader'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //企业统计信息
        $sub_menu = array();
//        if (Yii::app()->user->checkAccess("sys/workflow/list"))
//            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Workflow'), "url" => "?r=sys/workflow/list&app=TBM", "match" => array('sys\/workflow\/list&app=TBM', 'sys\/workflow\/new', 'sys\/workflow\/edit', 'sys\/workflow\/set'));
        if (Yii::app()->user->checkAccess("company_info")) {
//            $sub_menu[] = array("title" => Yii::t('comp_statistics', 'smallHeader List Day'), "url" => "?r=statistics/module/list", "match" => array('statistics\/module\/list'));
//            $sub_menu[] = array("title" => Yii::t('comp_statistics', 'smallHeader List Month'), "url" => "?r=statistics/module/monthlist", "match" => array('statistics\/module\/monthlist'));
            $sub_menu[] = array("title" => Yii::t('comp_statistics', 'day_statistics'), "url" => "?r=statistics/module/daylist", "match" => array('statistics\/module\/daylist'));
            $sub_menu[] = array("title" => Yii::t('comp_statistics', 'month_statistics'), "url" => "?r=statistics/module/monlist", "match" => array('statistics\/module\/monlist'));
//            $sub_menu[] = array("title" => Yii::t('comp_statistics', 'file_statistics'), "url" => "?r=statistics/module/dateapplist", "match" => array('statistics\/module\/dateapplist'));
        }

        if (count($sub_menu))
            $menus['company_info'] = array("title" => Yii::t('dboard', 'Menu Statistics'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //考勤应用
        $sub_menu = array();
        if (Yii::app()->user->checkAccess("attend_all")){
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend report'), "url" => "?r=attend/report", "match" => array('attend\/report'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend record'), "url" => "?r=attend/record", "match" => array('attend\/record'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend failure record'),"url" => "?r=sys/swipe/record", "match" => array('sys\/swipe\/record'));
            $sub_menu[] = array(
                "title" => Yii::t('dboard', 'Menu Project Report'),
                "url" => "?r=proj/report/attendlist",
                "match" => array(
                    'proj\/report\/attendlist',
                )
            );
             //$sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend device'), "url" => "?r=attend/device", "match" => array('attend\/device'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend policyManage'), "url" => "?r=attend/policyManage", "match" => array('attend\/policyManage'));
            //$sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend schedule'), "url" => "?r=attend/schedule", "match" => array('attend\/schedule'));
            //$sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend manual'), "url" => "?r=attend/manual", "match" => array('attend\/manual'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend dayManage'), "url" => "?r=attend/dayManage", "match" => array('attend\/dayManage'));
        }

        if (count($sub_menu))
            $menus['attend'] = array("title" => Yii::t('dboard', 'Menu Attend'), 'ico' => 'fa-gear', "child" => $sub_menu);


        //考勤应用
        $sub_menu = array();
        if (Yii::app()->user->checkAccess("attend_record")){
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Attend record'), "url" => "?r=sys/attend/record", "match" => array('sys\/attend\/record'));
        }

        if (count($sub_menu))
            $menus['sys_attend'] = array("title" => Yii::t('dboard', 'Menu Attend'), 'ico' => 'fa-gear', "child" => $sub_menu);


        return $menus;
    
    }

    public function run() {
        $name = Yii::app()->user->id;

        $home_url = 'index.php';
        $home = Yii::t('dboard', 'Home');
        echo <<<EOF
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <!--<div class="user-panel">
                        <div class="pull-left image">
                            <img src="img/avatar3.png" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>你好, {//$name}</p>
    
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>-->
                    <!-- search form -->
                    <!--<form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Search..."/>
                            <span class="input-group-btn">
                                <button type='submit' name='seach' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>-->
                    <!-- /.search form -->
					
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="active">
                            <a href="{$home_url}">
                                <i class="fa fa-dashboard"></i> <span>{$home}</span>
                            </a>
                        </li>
EOF;
        echo self::showMenu();
        echo "</ul> </section>";
    }

    public function showMenu() {
        $menus = self::sysMenu();
        //$r = $_REQUEST["r"];var_dump($_SERVER["QUERY_STRING"]);
        $r = $_SERVER["QUERY_STRING"];
        $html_str = "";
        if (count($menus) > 0) {
            foreach ($menus as $id => $menu) {
                $sub_show = self::showSubMenu($r, $menu["child"]);
                $current_menu = $sub_show["current_menu"];
                $sub_str = $sub_show["html_str"];
                if ($current_menu == true) {
                    $html_str .=" <li class='treeview active'>";
                } else {
                    $html_str .= "<li class='treeview'>";
                }
                $html_str .= "<a href='#'>";
                $html_str .= "<i class='fa " . $menu['ico'] . "'></i>";
                $html_str .= "<span>{$menu['title']}</span>";
                $html_str .= "<i class='fa fa-angle-left pull-right'></i>";
                $html_str .= "</a>";

                $html_str .= $sub_str;

                $html_str .= "</li>";
            }
        }
        return $html_str;
    }

    //渲染二级菜单
    private function showSubMenu($r, $children = array()) {
        $sub_str = "";
        $current_menu = false;
        $html_str = "";
        if (count($children) > 0) {
            foreach ($children as $k => $sub_menu) {
                $sub_url = $sub_menu["url"];
                $sub2_menus = $sub_menu["child"];
//                var_dump($sub2_menus);
//                exit;
                $li_class = "";
                $sub_ico = " fa-square-o ";
                $sub2_show = self::showSub2Menu($r, $sub2_menus);
                $current_sub_menu = $sub2_show["current_menu"];
                $sub2_str = $sub2_show["html_str"];
                if (count($sub2_menus) > 0) {
                    $li_class .= " treeview ";
                    if ($current_sub_menu == true)
                        $sub_ico = " fa-minus-square-o ";
                    else
                        $sub_ico = " fa-plus-square-o ";
                }

                $sub_match = $sub_menu['match'] != '' && self::menuMatch($sub_menu['match'], $r);
                if ($current_sub_menu == true || $sub_match) {
                    $current_menu = true;
                    $li_class .= " active ";
                }

                $sub_str .= " <li class='{$li_class}'>";
                $sub_str .= "<a href='{$sub_url}'>";
                $sub_str .= " <i class='fa {$sub_ico}'></i>";
                $sub_str .= $sub_menu["title"] . "</a>";
                $sub_str .= $sub2_str;
                $sub_str .= "</li>";
            }

            if ($current_menu == true)
                $html_str = "<ul class='treeview-menu' style='display:block;'>";
            else
                $html_str = "<ul class='treeview-menu' >";

            $html_str .= $sub_str . "</ul>";
        }

        return array("html_str" => $html_str, "current_menu" => $current_menu);
    }

    //渲染三级菜单
    private static function showSub2Menu($r, $children = array()) {
        $sub_str = "";
        $current_menu = false;
        $html_str = "";
        if (count($children) > 0) {
            foreach ($children as $k => $sub_menu) {

                $sub_url = $sub_menu["url"];
                if ($sub_menu['match'] != '' && self::menuMatch($sub_menu['match'], $r)) {
                    $current_menu = true;
                    $sub_str .="<li class='active'>";
                } else {
                    $sub_str .= "<li>";
                }

                $sub_str .="<a href='{$sub_url}'>";
                $sub_str .= "<i class='fa fa-angle-double-right'></i>";
                $sub_str .= $sub_menu["title"] . "</a></li>";
            }

            if ($current_menu == true)
                $html_str = "<ul class='treeview-menu' style='display:block;'>";
            else
                $html_str = "<ul class='treeview-menu' >";

            $html_str .= $sub_str . "</ul>";
        }

        return array("html_str" => $html_str, "current_menu" => $current_menu);
    }

    public static function menuMatch($match, $r) {
        if (!$match) {
            return false;
        }
        if (is_array($match)) {
            foreach ($match as $v) {
                if (preg_match('/\b' . $v . '\b/', $r)) {
                    return true;
                }
            }
        } else {
            return preg_match('/\b' . $match . '\b/', $r);
        }
    }

}
?>



