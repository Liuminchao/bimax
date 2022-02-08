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
//        $program_id = Yii::app()->session['program_id'];
        $program_id = Yii::app()->user->getState('program_id');
        $pro_model = Program::model()->findByPk($program_id);
        $root_proid = $pro_model->root_proid;
        $program_name = $pro_model->program_name;
        $operator_id = Yii::app()->user->id;
        $contractor_id = Yii::app()->user->contractor_id;
        $operator_model = Operator::model()->findByPk($operator_id);
        $operator_role = $operator_model->operator_role;
        //系统管理
        $sub_menu = array();
        if (Yii::app()->user->checkAccess("101"))
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Operator'), "url" => "?r=sys/operator/list", "match" => array('sys\/operator\/list', 'sys\/operator\/new', 'sys\/operator\/edit'));
        if (Yii::app()->user->checkAccess("101"))
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu Optlog'), "url" => "?r=sys/optlog/list", "match" => array('sys\/optlog\/list'));

        if (count($sub_menu))
            $menus['sys'] = array("title" => Yii::t('dboard', 'Menu System'), 'ico' => 'fa-gear', "child" => $sub_menu);


        //承包商管理
        $sub_menu = array();

        if (Yii::app()->user->checkAccess("102"))
            $sub_menu[] = array("title" => Yii::t('dboard', 'Menu CompInfo'), "url" => "?r=comp/info/list", "match" => array('comp\/info\/new', 'comp\/info\/list', 'comp\/info\/edit', 'comp\/info\/logout'));

        if (count($sub_menu))
            $menus['comp'] = array("title" => Yii::t('dboard', 'Menu Comp'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //空间统计
        $sub_menu = array();

        if (Yii::app()->user->checkAccess("102"))
            $sub_menu[] = array("title" => Yii::t('comp_statistics', 'spatial_statistics'), "url" => "?r=statistics/spatial/list", "match" => array('statistics\/spatial\/list'));

        if (count($sub_menu))
            $menus['spatial'] = array("title" => Yii::t('comp_statistics', 'spatial_statistics'), 'ico' => 'fa-gear', "child" => $sub_menu);

        //业务统计
        $sub_menu = array();

        if (Yii::app()->user->checkAccess("102")) {
            $sub_menu[] = array("title" => Yii::t('dboard', 'Business Statistics'), "url" => "?r=statistics/spatial/businesslist", "match" => array('statistics\/spatial\/businesslist'));
            $sub_menu[] = array("title" => Yii::t('dboard', 'Business Statistics Graph'), "url" => "?r=statistics/spatial/businessgraph", "match" => array('statistics\/spatial\/businessgraph'));
        }
        if (count($sub_menu))
            $menus['business'] = array("title" => Yii::t('dboard', 'Business Statistics'), 'ico' => 'fa-gear', "child" => $sub_menu);
        //平台文档
        $sub_menu = array();

        if (Yii::app()->user->checkAccess("101"))
            $sub_menu[] = array("title" => Yii::t('comp_document', 'contentHeader_platform'), "url" => "?r=document/platform/list", "match" => array('document\/platform\/list'));

        if (count($sub_menu))
            $menus['paltform_document'] = array("title" => Yii::t('comp_document', 'smallHeader List Platform'), 'ico' => 'fa-gear', "child" => $sub_menu);
        //查询项目开通的产品
        $app_module = ProgramApp::myModuleList($root_proid);
        $record_time = date('Y-m-d H:i:s');
        if(count($app_module)>0){
            if($app_module[0]['status'] != '0'){
                $modules = '1';
            }else if($app_module[0]['end_date'] != '' and $app_module[0]['end_date']<$record_time){
                $modules = '1';
            }else{
                $modules = $app_module[0]['modules'];
            }
            $is_lite = $app_module[0]['is_lite'];
        }else{
            $modules = '1';
        }

        //Project Information
        if (Yii::app()->user->checkAccess("118")){
            $sub_menu = array();
            if($root_proid == $program_id){
                $sub_menu[] = array("title" => 'Contractors', "url" => "?r=proj/project/sublist&ptype=".Yii::app()->session['project_type']."&father_proid=".$program_id, "match" => array('proj\/project\/sublist','proj\/project\/subnew','proj\/assignuser\/subauthoritylist','proj\/project\/substafflist'), 'ico' => 'fa-sitemap');
            }
            $sub_menu[] = array("title" => 'Manpower & Staffing', "url" => "?r=proj/assignuser/authoritylist&ptype=".Yii::app()->session['project_type']."&id=".$program_id."&name=".$program_name, "match" => array('proj\/assignuser\/authoritylist','proj\/project\/subedit','proj\/staff\/tabs',
                'proj\/staff\/pertabs','proj\/staff\/instabs','proj\/assignuser\/userapply','comp\/staff\/attachlist'),'ico' => 'fa-user');
//        $sub_menu[] = array("title" => 'Models', "url" => "?r=task/model/demo&program_id=".$program_id, "match" => array('task\/model\/demo'));
            if (count($sub_menu))
                $menus['basic'] = array("title" => 'Project Information', 'ico' => 'fa-info', "child" => $sub_menu);
        }


        //DFMA
        if (Yii::app()->user->checkAccess("119")){
            if($modules == '0' || strpos($modules,'DFMA') !== false){
                $sub_menu = array();
                $sub_menu_2 = array();
                $sub_menu[] = array("title" => 'Statistics', "url" => "?r=qa/statistic/show&program_id=".$program_id, "match" => array('qa\/statistic\/show','task\/blockchart\/list'), "ico"=>"fa-signal");
//            $sub_menu[] = array("title" => 'Models', "url" => "?r=task/model/demo&program_id=".$program_id, "match" => array('task\/model\/demo','task\/model\/view'), "ico"=>"fa-cubes");
                $sub_menu[] = array("title" => 'Components Status', "url" => "?r=task/model/list&program_id=".$program_id, "match" => array('task\/model\/list'), "ico"=>"fa-cube");
                $sub_menu[] = array("title" => 'Components', "url" => "?r=task/model/pbulist&program_id=".$program_id, "match" => array('task\/model\/pbulist','task\/model\/view'), "ico"=>"fa-cube");
                $sub_menu[] = array("title" => 'Carcass', "url" => "?r=task/task/recordclist&program_id=".$program_id, "match" => array('task\/task\/recordclist'), "ico"=>"fa-tasks");
                $sub_menu[] = array("title" => 'Fitting Out', "url" => "?r=task/task/recordblist&program_id=".$program_id, "match" => array('task\/task\/recordblist'), "ico"=>"fa-tasks");
                $sub_menu[] = array("title" => 'On-Site', "url" => "?r=task/task/recordalist&program_id=".$program_id, "match" => array('task\/task\/recordalist'), "ico"=>"fa-tasks");
                $sub_menu[] = array("title" => 'Issues', "url" => "?r=qa/qadefect/dfmalist&program_id=".$program_id."&source=DFMA", "match" => array('qa\/qadefect\/dfmalist'),  'ico' => 'fa-angle-double-right');
//                if($root_proid == '2925' || $root_proid == '369'){
                //Lite版本去掉blockchart部分
                if($is_lite == '0') {
                    $sub_menu_2 = array();
                    $sub_menu_2[] = array("title" => 'Block Chart', "url" => "?r=task/pbu/list&program_id=" . $program_id, "match" => array('task\/pbu\/list','task\/pbu\/create'), "ico" => "fa-list-ul");
//            $sub_menu_2[] = array("title" => 'Allocation', "url" => "?r=task/pbu/pbulist&program_id=".$program_id, "match" => array('task\/pbu\/pbulist'), "ico"=>"fa-list-ul");
                    $sub_menu[] = array("title" => 'PBU/PPVC/Precast', "url" => "#", "child" => $sub_menu_2, "ico" => "fa-cog");
                }
                    //Lite版本去掉Schedule部分
                    if($is_lite == '0'){
                        $sub_menu_2 = array();
                        $sub_menu_2[] = array("title" => 'Key Activities Cycle', "url" => "?r=task/schedule/keyactivities&program_id=".$program_id, "match" => array('task\/schedule\/keyactivities'));
                        $sub_menu_2[] = array("title" => 'Sub Activities Cycle', "url" => "?r=task/schedule/subactivities&program_id=".$program_id, "match" => array('task\/schedule\/subactivities'));
                        $sub_menu_2[] = array("title" => 'Set Master Schedule', "url" => "?r=task/schedule/master&program_id=".$program_id, "match" => array('task\/schedule\/master'));
                        $sub_menu_2[] = array("title" => 'View Master Schedule', "url" => "?r=task/schedule/masterlist&program_id=".$program_id, "match" => array('task\/schedule\/masterlist'));
                        $sub_menu_2[] = array("title" => 'Person In Charge', "url" => "?r=task/schedule/chargelist&program_id=".$program_id, "match" => array('task\/schedule\/chargelist'));
                        $sub_menu_2[] = array("title" => 'Statistics', "url" => "?r=task/pbu/statisticslist&program_id=".$program_id, "match" => array('task\/pbu\/statisticslist'));
                        $sub_menu[] = array("title" => 'Schedule', "url" => "#","child" => $sub_menu_2, "ico"=>"fa-cog");
                    }
//                }
                if (count($sub_menu))
                    $menus['bimax'] = array("title" => 'DfMA', 'ico' => 'fa-building', "child" => $sub_menu);
            }
        }


        //Model
        if (Yii::app()->user->checkAccess("120")){
            //Lite版本去掉Model部分
            if($is_lite == '0'){
                $sub_menu = array();
                $sub_menu[] = array("title" => 'Models', "url" => "?r=task/model/demo&program_id=".$program_id, "match" => array('task\/model\/demo'), "ico"=>"fa-cubes");

                if (count($sub_menu))
                    $menus['model'] = array("title" => 'Models', 'ico' => 'fa-cubes', "child" => $sub_menu);
            }
        }

        //QA/QC-Inspection
        if (Yii::app()->user->checkAccess("121")){
            if($modules == '0' || strpos($modules,'INSPECTION') !== false){
                $sub_menu = array();
                $sub_menu_2 = array();
                $sub_menu[] = array("title" => 'AR', "url" => "?r=qa/qainspection/arlist&program_id=".$program_id, "match" => array('qa\/qainspection\/arlist','qa\/qadefect\/checklist'),  'ico' => 'fa-angle-double-right');
                $sub_menu[] = array("title" => 'C&S', "url" => "?r=qa/qainspection/cslist&program_id=".$program_id, "match" => array('qa\/qainspection\/cslist','qa\/qadefect\/checklist'),  'ico' => 'fa-angle-double-right');
                $sub_menu[] = array("title" => 'M&E', "url" => "?r=qa/qainspection/melist&program_id=".$program_id, "match" => array('qa\/qainspection\/melist','qa\/qadefect\/checklist'),  'ico' => 'fa-angle-double-right');
                $sub_menu[] = array("title" => 'General', "url" => "?r=qa/qainspection/generallist&program_id=".$program_id, "match" => array('qa\/qainspection\/generallist'),  'ico' => 'fa-angle-double-right');
                $sub_menu[] = array("title" => 'Issues', "url" => "?r=qa/qadefect/inspectionlist&program_id=".$program_id."&source=inspection", "match" => array('qa\/qadefect\/inspectionlist'),  'ico' => 'fa-angle-double-right');
                if (count($sub_menu))
                    $menus['qa'] = array("title" => 'QA/QC-Inspection', 'ico' => 'fa-search-minus', "child" => $sub_menu);
            }
        }

        //QA/QC-Defect
        if (Yii::app()->user->checkAccess("122")){
            if($modules == '0' || strpos($modules,'DEFECT') !== false){
                $sub_menu = array();
                $sub_menu_2 = array();
                $sub_menu[] = array("title" => 'Completion', "url" => "?r=qa/qadefect2/completionlist&program_id=".$program_id, "match" => array('qa\/qadefect2\/completionlist'),  'ico' => 'fa-angle-double-right');
                $sub_menu[] = array("title" => 'Handover', "url" => "?r=qa/qadefect2/handoverlist&program_id=".$program_id, "match" => array('qa\/qadefect2\/handoverlist'),  'ico' => 'fa-angle-double-right');
                $sub_menu[] = array("title" => 'DLP', "url" => "?r=qa/qadefect2/dlplist&program_id=".$program_id, "match" => array('qa\/qadefect2\/dlplist'),  'ico' => 'fa-angle-double-right');
                $sub_menu_2[] = array("title" => 'Selection', "url" => "?r=qa/defect/typelist&program_id=".$program_id."&curpage=0", "match" => array('qa\/defect\/typelist'));
                $sub_menu_2[] = array("title" => 'Location', "url" => "?r=proj/location/locationlist&program_id=".$program_id, "match" => array('proj\/location\/locationlist','proj\/location\/uploadlist'));
                $sub_menu[] = array("title" => 'Settings', "url" => "#","child" => $sub_menu_2, "ico"=>"fa-cog");

                if (count($sub_menu))
                    $menus['defect'] = array("title" => 'QA/QC-Defect', 'ico' => 'fa-eye', "child" => $sub_menu);
            }
        }


        //RFA
        $sub_menu = array();
        if (Yii::app()->user->checkAccess("123")){
            $sub_menu[] = array("title" => 'RFA', "url" => "?r=rf/rf/list&program_id=".$program_id."&type_id=2", "match" => array('rf\/rf\/list','rf\/rf\/addrfichat','rf\/rf\/changeview','rf\/rf\/forward','rf\/rf\/changeforward','rf\/rf\/info','rf\/rf\/editchat'),"ico"=>"fa-list-alt");
            $sub_menu[] = array("title" => 'RFI', "url" => "?r=rf/rf/list&program_id=".$program_id."&type_id=1", "match" => array('rf\/rf\/list','rf\/rf\/addrfichat','rf\/rf\/changeview','rf\/rf\/forward','rf\/rf\/changeforward','rf\/rf\/info','rf\/rf\/editchat'),"ico"=>"fa-edit");
            $sub_menu[] = array("title" => 'Dashboard', "url" => "?r=rf/rf/dashboard&program_id=".$program_id, "match" => array('rf\/rf\/dashboard'),"ico"=>"fa-signal");
        }
        //Transmittal
        if (Yii::app()->user->checkAccess("124")){
            $sub_menu[] = array("title" => 'Transmittal', "url" => "?r=transmittal/trans/list&project_id=".$program_id, "match" => array('transmittal\/trans\/list','transmittal\/trans\/new','transmittal\/trans\/info'),"ico"=>"fa-retweet");
        }
        //Robox
        if (Yii::app()->user->checkAccess("125")){
            $sub_menu_2 = array();
            $tag_user = 'user';
            $tag_admin = 'admin';
            if($operator_role == '00'){
                $sub_menu_2[] = array("title" => 'Admin Robox', "onclick" => "dms_admin($program_id)", "match" => array('rf\/dms\/login'),"ico"=>"fa-book");
            }else{
                $user = Staff::userByPhone($operator_id);
                $user_id = $user[0]['user_id'];
                $operator_info = ProgramUser::SelfInpro($contractor_id,$user_id,$root_proid);
                $sub_menu_2[] = array("title" => 'Robox', "onclick" => "dms_user($program_id)", "match" => array('rf\/dms\/login'),"ico"=>"fa-book");
                if($operator_info[0]['robox_role'] == '1'){
                    $sub_menu_2[] = array("title" => 'Admin Robox', "onclick" => "dms_admin($program_id)", "match" => array('rf\/dms\/login'),"ico"=>"fa-book");
                }
            }
            $sub_menu[] = array("title" => 'Robox', "url" => "#","child" => $sub_menu_2, "ico"=>"fa-cog");
        }

        $sub_menu[] = array("title" => 'Settings', "url" => "?r=rf/group/list&program_id=".$program_id, "match" => array('rf\/group\/list','rf\/group\/new'),"ico"=>"fa-cog");

        if (count($sub_menu))
            $menus['rf'] = array("title" => 'Document Management', 'ico' => 'fa-book', "child" => $sub_menu);


        //Setting
        if (Yii::app()->user->checkAccess("126")){
            $sub_menu = array();
            if($root_proid == $program_id){
//                $sub_menu[] = array("title" => 'Location', "url" => "?r=proj/project/setmcregion&ptype=".Yii::app()->session['project_type']."&program_id=".$program_id, "match" => array('proj\/project\/setmcregion'), "ico"=>"fa-building");
                $sub_menu[] = array("title" => 'Setup Location', "url" => "?r=task/pbu/create&project_id=".$program_id, "match" => array('task\/pbu\/create'), "ico"=>"fa-building");
            }
            $sub_menu[] = array("title" => 'Tracking Template', "url" => "?r=task/template/list&program_id=".$program_id, "match" => array('task\/template\/list','task\/template\/stagelist','task\/template\/newstage','task\/template\/editstage','task\/task\/list','task\/task\/new','task\/task\/edit'),"ico"=>"fa-tachometer-alt");
            $sub_menu[] = array("title" => 'QR Code Template', "url" => "?r=task/model/newqr&program_id=".$program_id, "match" => array('task\/model\/newqr'),"ico"=>"fa-qrcode");
            $sub_menu[] = array("title" => 'Checklist', "url" => "?r=qa/import/list&program_id=".$program_id, "match" => array('qa\/import\/list','qa\/import\/view'), "ico"=>"fa-list-ul");

            if (count($sub_menu))
                $menus['setting'] = array("title" => 'Settings', 'ico' => 'fa-cogs', "child" => $sub_menu);
        }


        //Setting
//        $sub_menu = array();
//        $sub_menu[] = array("title" => 'Block Chart', "url" => "?r=task/pbu/list&program_id=".$program_id, "match" => array('task\/pbu\/list'), "ico"=>"fa-list-ul");
//        $sub_menu[] = array("title" => 'PBU Allocation', "url" => "?r=task/pbu/pbulist&program_id=".$program_id, "match" => array('task\/pbu\/pbulist'), "ico"=>"fa-list-ul");
//        $sub_menu_2 = array();
//        $sub_menu_2[] = array("title" => 'Key Activities Cycle', "url" => "?r=task/schedule/keyactivities&program_id=".$program_id, "match" => array('task\/schedule\/keyactivities'));
//        $sub_menu_2[] = array("title" => 'Sub Activities Cycle', "url" => "?r=task/schedule/subactivities&program_id=".$program_id, "match" => array('task\/schedule\/subactivities'));
//        $sub_menu_2[] = array("title" => 'Master Schedule', "url" => "?r=task/schedule/master&program_id=".$program_id, "match" => array('task\/schedule\/master'));
//        $sub_menu[] = array("title" => 'Schedule', "url" => "#","child" => $sub_menu_2, "ico"=>"fa-cog");
//
//        if (count($sub_menu))
//            $menus['pbu'] = array("title" => 'PBU/PPVC/Precast', 'ico' => 'fa-cogs', "child" => $sub_menu);

        return $menus;

    }

    public function run() {
        $name = Yii::app()->user->id;

        $home_url = 'index.php';
        $home = Yii::t('dboard', 'Home');
        echo <<<EOF
                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <!--<div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block">Alexander Pierce</a>
                        </div>
                    </div>
                    -->
                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
                                 with font-awesome or any other icon font library -->
                            

                    <!-- /.sidebar-menu -->
                
                <!-- /.sidebar -->
EOF;
        echo self::showMenu();
        echo "</ul> </nav></div>";
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
                    $html_str .=" <li class='nav-item menu-is-opening menu-open'>";
                } else {
                    $html_str .= "<li class='nav-item'>";
                }
                $html_str .= "<a href='#' class='nav-link nav-link_1'>";
                $html_str .= "<i class='fa " . $menu['ico'] . " nav-icon'></i>";
//                $html_str .= "<i class='far fa-circle nav-icon'></i>";
//                $html_str .= "<img style='padding-bottom: 0px;' src='" . $menu['ico'] . "' />";
                $html_str .= "<p>";
                $html_str .= $menu['title'];
                $html_str .= "</p>";
                $html_str .= "<i class='fas fa-angle-left right'></i>";
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
                //var_dump($sub_menu['ico']);
//                exit;
                $li_class = "";
                $sub_ico = "far nav-icon " . $sub_menu['ico'] . "";
                $sub2_show = self::showSub2Menu($r, $sub2_menus);
                $current_sub_menu = $sub2_show["current_menu"];
                $sub2_str = $sub2_show["html_str"];
                //$sub_ico = "far fa-circle nav-icon";

                $sub_match = $sub_menu['match'] != '' && self::menuMatch($sub_menu['match'], $r);
                if ($current_sub_menu == true || $sub_match) {
                    $current_menu = true;
                    $li_class .= " active_new";
                }

                $sub_str .= " <li class='nav-item'>";
                if(array_key_exists('onclick',$sub_menu)){
                    $sub_onclick = $sub_menu["onclick"];
                    $sub_str .= "<a onclick='{$sub_onclick}' class='nav-link nav-link_1 {$li_class}' style='margin-left: 10px;'>";
                }else{
                    $sub_str .= "<a href='{$sub_url}' class='nav-link nav-link_1 {$li_class}' style='margin-left: 10px;'>";
                }
                $sub_str .= " <i class='fa {$sub_ico}'></i>";
                $sub_str .= "<p>".$sub_menu["title"] . "</p>";
                if (count($sub2_menus) > 0) {
                    $sub_str .= "<i class='fas fa-angle-left right'></i>";
                }
                $sub_str .= "</a>";
                $sub_str .= $sub2_str;
                $sub_str .= "</li>";
            }

            if ($current_menu == true)
                $html_str = "<ul class='nav nav-treeview' style='display:block;'>";
            else
                $html_str = "<ul class='nav nav-treeview' >";

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
                $li_class = "";
                $sub_url = $sub_menu["url"];
                if ($sub_menu['match'] != '' && self::menuMatch($sub_menu['match'], $r)) {
                    $current_menu = true;
                    $sub_str .="<li class='nav-item'>";
                    $li_class = " active_new";
                } else {
                    $sub_str .= "<li class='nav-item'>";
                }
                if(array_key_exists('onclick',$sub_menu)){
                    $sub_onclick = $sub_menu["onclick"];
                    $sub_str .= "<a onclick='{$sub_onclick}' class='nav-link nav-link_1 {$li_class}' style='margin-left: 20px;'>";
                }else{
                    $sub_str .="<a href='{$sub_url}' class='nav-link nav-link_1 {$li_class}' style='margin-left: 20px;'>";
                }
                $sub_str .= "<i class='far fa-dot-circle nav-icon'></i>";
                $sub_str .= "<p>".$sub_menu["title"] . "</p></a></li>";
            }

            if ($current_menu == true)
                $html_str = "<ul class='nav nav-treeview' style='display:block;'>";
            else
                $html_str = "<ul class='nav nav-treeview' >";

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
