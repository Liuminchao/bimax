<?php
    phpinfo();
    exit;
    $pdf = '/opt/www-nginx/web/test/bimax/demo/configuration.pdf';
    $path = '/opt/www-nginx/web/test/bimax/demo/';
    if (!extension_loaded('imagick')) {
        var_dump('111111');
    }
    if (!file_exists($pdf)) {
        var_dump('2222222');
    }
    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
    $im = new \imagick();
    $im->setResolution(120, 120); //设置分辨率 值越大分辨率越高
    $im->setCompressionQuality(100);
    $im->readImage($pdf);
    foreach ($im as $k => $v) {
        $v->setImageFormat('png');
        $fileName = $path . 'page_' . ($k + 1) . '.png';
        var_dump($v->writeImage($fileName));
        exit;
        if ($v->writeImage($fileName) == true) {
            $return[] = $fileName;
        }
    }
    var_dump($return);
    exit;
    return $return;
?>