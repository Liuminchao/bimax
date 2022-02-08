<?php

/**
 * 报告生成
 * @author LiuMinchao
 */
class DownloadPdf extends CActiveRecord {
    public static function transferDownload($params,$app_id){
        $type = array(
            'PTW' => 'ApplyBasic',
            'TBM' => 'Meeting',
            'TRAIN' => 'Train',
            'DEVICE' => 'ProgramDevice',
            'USER' => 'ProgramUser',
            'RA' => 'RaBasic',
            'CHECKLIST' => 'CheckApply',
            'WSH' => 'SafetyCheck',
            'ROUTINE' => 'RoutineCheck',
            'ACCI' => 'AccidentBasic',
            'QAQC' => 'QualityCheck',
            'QA' => 'QaCheck',
            'RF' => 'RfList',
            'TRANS' => 'TransmittalRecord',
        );
        $obj = $type[$app_id];
        $filepath = $obj::downloadPDF($params,$app_id);
        return $filepath;
    }
}