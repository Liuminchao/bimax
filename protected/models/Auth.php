<?php

/**
 * 权限
 *
 * @author liuxy
 */
class Auth extends CFormModel {

    /**
     * 登录使用，返回该角色的权限及孩子
     * @param type $role_id
     * @return type
     */
//    public static function GetMyAuth($role_id) {
//
//        if ($role_id == '')
//            return null;
//
//        $items = self::getItemsList(); //得到所有权限对象
//
//        $result[$role_id] = $role_id;
//        $children = self::getSonRows($role_id);
//        $result = array_merge($result, $children);
//        foreach($children as $key => $value){
//            $sub_children = self::getSonRows($key);
//            $result = array_merge($result, $sub_children);
//        }
//
//        return $result;
//    }


    public static function GetMyAuth($operator_id) {

        if ($operator_id == '')
            return null;

        $items = self::getItemsList(); //得到所有权限对象

        $sql = "SELECT menu_id FROM bac_operator_menu_q where operator_id=:operator_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":operator_id", $operator_id, PDO::PARAM_STR);

        $rows = $command->queryAll();
//        var_dump($rows);

        foreach($rows as $key => $row){
            $result[$row['menu_id']] = $row['menu_id'];
            $sub_children = self::getSonRows($row['menu_id']);
            $result = array_merge($result, $sub_children);
        }

        return $result;
    }

    /**
     * 得到所有二级菜单列表
     * @param string $v 一级菜单名
     * @return array
     */
    public static function getSonRows($v) {

        $rs = array(); //二级菜单名
        $items = self::getItemsList(); //得到所有权限对象
        $row = $items[$v]; //得到一级菜单名
        //一级菜单不存在
        if ($row == null):
            return $rs;
        endif;

        //没有子项
        if (is_array($row['children']) == false or count($row['children']) == 0):
            return $rs;
        endif;

        //收集子项的信息
        foreach ($row['children'] as $sid):
            $rs[$sid] = $sid; //$items[$sid];
        endforeach;

        return $rs;
    }

    /**
     * 得到所有权限对象
     * @return <type>
     */
    public static function getItemsList() {
        $path = dirname(__FILE__) . '/../data/auth.php';
        $_items = require($path);
        return $_items;
    }

    /**
     * 得到所有角色对象
     * @return type
     */
    public static function getRoleList(){
        $path = dirname(__FILE__) . '/../data/role.php';
        $_items = require($path);
        return $_items;
    }
}
