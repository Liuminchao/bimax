<?php

/*
 * 供dashboard调取统计图表
 */

    $app_id = $_REQUEST['app_id'];
    $program_id = $_REQUEST['program_id'];
    $type = array(
        'PTW' => 'https://shell.cmstech.sg/test/ctmgr/index.php?r=license/licensepdf/projectchart',
        'TBM' => 'https://shell.cmstech.sg/test/ctmgr/index.php?r=tbm/meeting/companychart',
        'CHECKLIST' => 'https://shell.cmstech.sg/test/ctmgr/index.php?r=routine/routineinspection/projectchart',
        'WSH' => 'https://shell.cmstech.sg/test/ctmgr/index.php?r=wsh/wshinspection/projectchart',
        'RA' => 'https://shell.cmstech.sg/test/ctmgr/index.php?r=ra/raswp/projectchart',
        'TRAIN' => 'https://shell.cmstech.sg/test/ctmgr/index.php?r=train/training/projectchart',
        'MEET' => 'https://shell.cmstech.sg/test/ctmgr/index.php?r=meet/meeting/projectchart',
        'ACCIDENT' => 'https://shell.cmstech.sg/test/ctmgr/index.php?r=accidents/accident/companychart',
//        'QA' => 'RoutineCheck',
    );
    $url = $type[$app_id];
    $url = $url.'&program_id='.$program_id.'&platform=dashboard';
    var_dump($url);
    exit;
