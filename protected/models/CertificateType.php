<?php

/**
 * 证书类别
 * @author LiuMinchao
 */
class CertificateType extends CActiveRecord {

    const STATUS_NORMAL = 0; //已启用
    const STATUS_DISABLE = 1; //未启用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_certificate';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
     //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('device', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('device', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }
    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //已启用
            self::STATUS_DISABLE => ' label-danger', //未启用
        );
        return $key === null ? $rs : $rs[$key];
    }
    //准证类别
    public static function  passType(){
        $type = array(
            'WP' => '36',
            'SP' => '37',
            'EP' => '38',
            'DP' => '35',
            'PR' => '39',
            'IC' => '4',
        );
        return $type;
    }

    //查询证书类别
    public static function certificateList(){

        if (Yii::app()->language == 'zh_CN') {
            $sql = "SELECT certificate_name,certificate_type FROM bac_certificate WHERE status=0 ORDER BY (case WHEN certificate_type IN (30,31,32,33,34,35,36,37,38,39,4,40) then '0' else '1' end),certificate_type desc";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['certificate_type']] = $row['certificate_name'];
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            $sql = "SELECT certificate_name_en,certificate_type FROM bac_certificate WHERE status=0 ORDER BY (case WHEN certificate_type IN (30,31,32,33,34,35,36,37,38,39,4,40) then '0' else '1' end),certificate_type desc";//var_dump($sql);
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $rs[$row['certificate_type']] = $row['certificate_name_en'];
                }
            }
        }
        return $rs;

    }

    //查询证书类别
    public static function shortList(){
         $certificate_list = array(
             '1' => 'CSCPM',
             '10'=> 'FSS',
             '11'=> 'SMSEC',
             '12'=> 'MSEC',
             '13'=> 'CSSA',
             '14'=> 'OFA',
             '15'=> 'TCO',
             '16'=> 'MCO',
             '17'=> 'PMO',
             '18'=> 'EXO',
             '19'=> 'FDT',
             '2'=> 'WSHO',
             '20'=> 'MEW (B)',
             '21'=> 'MEW (S)',
             '22'=> 'SSGIC',
             '23'=> 'WAH (SUP)',
             '24'=> 'WAH (ASS)',
             '25'=> 'WAH (MAN)',
             '26'=> 'WELDER',
             '28'=> 'R & S',
             '29'=> 'ECO',
             '3'=> 'AWSHCS',
             '30'=> 'CSOC',
             '31'=> 'SCWWSH',
             '32'=> 'BCSS',
             '41'=> 'LCO',
             '42'=> 'RMC',
             '43'=> 'WSHC',
             '44'=> 'LFS',
             '45'=> 'R',
             '46'=> 'S',
             '47'=> 'EPTOC',
             '48'=> 'SSEC',
             '49'=> 'SSSC',
             '5'=> 'MSAC',
             '50'=> 'WAH (W)',
             '51'=> 'MWAH',
             '52'=> 'CSOC (TUN)',
             '53'=> 'BCSS (TUN)',
             '54'=> 'FSW',
             '55'=> 'WSHO (C)',
             '56'=> 'MHS',
             '57'=> 'CNV',
             '58'=> 'MNV',
             '59'=> 'PWCS',
             '6'=> 'SWCS',
             '60'=> 'CSER',
             '61'=> 'LEW',
         );
        if (Yii::app()->language == 'zh_CN') {
            if (count($certificate_list) > 0) {
                foreach ($certificate_list as $key => $v) {
                    $rs[$key] = $v;
                }
            }
        } else if (Yii::app()->language == 'en_US') {
            if (count($certificate_list) > 0) {
                foreach ($certificate_list as $key => $v) {
                    $rs[$key] = $v;
                }
            }
        }
        return $rs;

    }
}
