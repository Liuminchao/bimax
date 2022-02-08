<?php

/**
 * 文档标签管理
 * @author LiuMinchao
 */
class DocumentLabel extends CActiveRecord {

    //承包商类型
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包
    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_document_label';
    }

    /**
     * 返回所有可用的标签
     * @return type
     */
    public static function tagList() {
        $sql = "SELECT label_id,label_name,label_name_en FROM bac_document_label WHERE status=00";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['label_id']] = $row['label_name'];
                }else{
                    $rs[$row['label_id']] = $row['label_name_en'];
                }
            }
        }
        return $rs;
    }


}
