<?php

/**
 * 证书到期查询
 *
 * @author liuxy
 */
class Certexpiry extends CActiveRecord {

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
    public static function queryList($page, $pageSize, $args = array()) {
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $pro_model =Program::model()->findByPk($args['program_id']);
        if($pro_model->main_conid != $contractor_id) {
            $root_proid = $pro_model->root_proid;
        }else{
            $root_proid = $args['program_id'];
        }
        $data = array(
            'uid' => $args['user_id'],
            'token' => 'lalala',
            'program_id' => $root_proid,
            'type' => $args['type_id']
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $data = json_encode($post_data);
        $module = 'CMSC0315';
        $url = "https://127.0.0.1/dbcms/dbapi?cmd=".$module."";
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
        if($rs['errno'] == 0 ){
            foreach ($rs['result'] as $k => $v){
                foreach($v['list'] as $i => $j){
                    $r[$x]['info'] = $j;
                    $r[$x]['contractor_name'] = $v['contractor_name'];
                    $x++;
                }
            }
        }
        if ($output === FALSE) {
            echo "cURL Error: " . curl_error($ch);
            return null;
        }
        // 4. 释放curl句柄
        curl_close($ch);

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($r);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($r,$start,$pageSize);
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
}
