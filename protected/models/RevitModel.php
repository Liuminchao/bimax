<?php

/**
 * RevitModel
 *
 * @author liuxy
 */
class RevitModel extends CActiveRecord {

    //状态
    public static function statusText($key = null) {
        $rs = array(
            '0' => Yii::t('common', 'normal'),
            '1' => Yii::t('common', 'expiring'),
            '2' => Yii::t('common', 'expired'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            '0'=> 'label-info',
            '1' => 'label-success',
            '2' => 'label-danger',
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($project_id) {

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($project_id);
        if($pro_model->main_conid != $contractor_id) {
            $root_proid = $pro_model->root_proid;
        }else{
            $root_proid = $project_id;
        }
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        $user_id = $user[0]['user_id'];
        $data = array(
            'uid' => $user_id,
            'token' => 'lalala',
            'project_id' => $root_proid,
            'page' => '1',
            'pagesize' => '10000',
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $data = json_encode($post_data);

        $module = 'ProjRevitModelList';
        $url = "https://shell.cmstech.sg/cms_bim/dbapi?cmd=".$module."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        $r = array();
        $x = 0;
        if($rs['code'] == 0 ){
            if(count($rs['result']['result'])>0){
                foreach ($rs['result']['result'] as $k => $v){
                    $r[$x] = $v;
                    $x++;
                }
            }else{
                $r = array();
            }
        }
        if ($output === FALSE) {
            echo "cURL Error: " . curl_error($ch);
            return null;
        }
        // 4. 释放curl句柄
        curl_close($ch);

        $res['status'] = 0;
        $res['desc'] = '成功';
        $res['rows'] = $r;

        return $r;
    }

    public static function modelList($page, $pageSize, $args = array()) {
        $data = array(
            'appKey' => 'WXV779X1ORqkxbQZZOyuoFW58UyZZOmrX6UT',
            'appSecret' => '5850b40146687cc795d992e94dc04d1ba7d76ce40dd67a59a79f9066c375df2f'
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        //        $data = json_encode($post_data);
        $url = "https://bim.cmstech.sg/api/v1/token";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $data = array(
            'property' => 'false',
            'version' => $args['version'],
            'id' => $args['model_id']
        );
        $arr = array(
            'x-access-token:'.$rs['data']['token']
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        //        $data = json_encode($post_data);
        //https://bim.cmstech.sg/api/v1/models/5e099f9f3443310011286d98/components?version=1&property=undefined
        $url = "https://bim.cmstech.sg/api/v1/models/".$args['model_id']."/components?version=".$args['version']."&property=true";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arr);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        //跳过SSL验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($rs['data']);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($rs['data'],$start,$pageSize);
        }else{
            $pagedata = array();
        }

        $res['status'] = 0;
        $res['desc'] = '成功';
        $res['page_num'] = ($page + 1);
        $res['total_num'] = $count;
        $res['num_of_page'] = $pageSize;
        $res['rows'] = $pagedata;

        return $res;
    }

    //Domain
    public static function domainList() {
        $list = array(
            '0'=> '未知',
            '1'=> '其他',
            '2'=> '建筑',
            '3' => '结构',
            '4' => '给排水',
            '5'=> '暖通',
            '6' => '电气',
            '7' => '消防',
            '8'=> '幕墙',
            '9' => '钢结构',
            '10' => '预制'
        );
        return $list;
    }

    //Category
    
    public static function categoryList() {
        $list = array(
            '0'=> '未知',
            '1'=> '其他',
            '2'=> '建筑',
            '3' => '结构',
            '4' => '给排水',
            '5'=> '暖通',
            '6' => '电气',
            '7' => '消防',
            '8'=> '幕墙',
            '9' => '钢结构',
            '10' => '预制'
        );
        return $list;
    }

    //Domain
    public static function domainText($key = null) {
        $rs = array(
            '0' => '未知',
            '1' => '其他',
            '2' => '建筑',
            '3' => '结构',
            '4' => '给排水',
            '5' => '暖通',
            '6' => '电气',
            '7' => '消防',
            '8' => '幕墙',
            '9' => '钢结构',
            '9' => '预制'
        );
        return $rs;
    }

    //Category
    public static function categoryText($key = null) {
        $rs = array(
            '0' => '未知',
            '1' => '模型组',
            '2' => '部件',
            '50' => '轴网',
            '51' => '房间',
            '100' => '墙',
            '101' => '柱',
            '102' => '梁',
            '103' => '门',
            '104' => '窗',
            '105' => '屋顶',
            '106' => '檐沟',
            '107' => '封檐带',
            '108' => '楼板边缘',
            '109' => '屋檐底板',
            '110' => '栏杆扶手',
            '111' => '顶部扶栏',
            '112' => '楼板',
            '113' => '楼梯',
            '114' => '专用设备',
            '115' => '体量',
            '116' => '停车场',
            '117' => '地形',
            '118' => '建筑地坪',
            '119' => '场地',
            '120' => '坡道',
            '121' => '天花板',
            '122' => '家具',
            '123' => '家具系统',
            '124' => '常规模型',
            '125' => '橱柜',
            '126' => '幕墙竖梃',
            '127' => '幕墙嵌板',
            '128' => '幕墙系统',
            '129' => '植物',
            '130' => '环境',
            '131' => '竖井洞口',
            '132' => '道路',
            '133' => '烟囱',
            '134' => '墙饰条',
            '135' => '组成部分',
            '200' => '结构基础',
            '201' => '结构柱',
            '202' => '结构桁架',
            '203' => '结构框架',
            '204' => '结构梁系统',
            '205' => '结构连接',
            '206' => '结构钢筋',
            '207' => '钢板',
            '208' => '螺栓',
            '209' => '结构墙',
            '210' => '结构加强板',
            '211' => '结构楼板',
            '300' => '管道',
            '301' => '软管',
            '302' => '管件',
            '303' => '管路附件',
            '304' => '风管',
            '305' => '软风管',
            '306' => '风管管件',
            '307' => '风管附件',
            '308' => '风道末端',
            '309' => '电缆桥架',
            '310' => '电缆桥架配件',
            '311' => '线管',
            '312' => '线管配件',
            '313' => '导线',
            '314' => '卫浴装置',
            '315' => '喷头',
            '316' => '安全设备',
            '317' => '护理呼叫设备',
            '318' => '数据设备',
            '319' => '机械设备',
            '320' => '火警设备',
            '321' => '灯具',
            '322' => '照明设备',
            '323' => '电气设备',
            '324' => '电气装置',
            '325' => '电话设备',
            '326' => '通讯设备',
            '327' => '暖通设备',
            '328' => '给排水设备',
            '329' => '消防设备',
            '330' => '空调机组',
            '331' => '空气盒',
            '332' => '锅炉',
            '333' => '燃烧器',
            '334' => '冷水机组',
            '335' => '盘管',
            '336' => '压缩机',
            '337' => '冷凝器',
            '338' => '冷梁',
            '339' => '冷却塔',
            '340' => '风阀',
            '341' => '消声器',
            '342' => '发动机',
            '343' => '蒸发器',
            '344' => '蒸发式冷凝器',
            '345' => '通风机',
            '346' => '过滤器',
            '347' => '流量计',
            '348' => '换热器',
            '349' => '加湿器',
            '350' => '水泵',
            '351' => '散热器',
            '352' => '水箱',
            '353' => '管束',
            '354' => '单元设备',
            '355' => '水阀',
            '356' => '隔振器',
            '357' => '发电机',
            '358' => '电动机',
            '359' => '接线盒',
            '360' => '插座',
            '361' => '开关',
            '362' => '防护设备',
            '363' => '变压器',
            '364' => '管道隔热层',
            '365' => '风管隔热层',
            '366' => '预制构件',
            '367' => '预制管道',
            '368' => '预制保护层',
            '369' => '预制支架',
            '370' => '预制管网'
        );
        return $rs;
    }
}