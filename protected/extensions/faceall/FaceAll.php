<?php

class FaceAll {

    private $api_key;
    private $api_secret;
    private $version;
    private $host = "http://api.faceall.cn:80/";
    private $url;

    function __construct($api_key, $api_secret, $version) {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->version = $version;
        $this->url = $this->host . $version . '/';
    }

    public function sendHttpGet($method, $data) {

        $ch = curl_init();

        $http_url = $this->url . $method . '?api_key=' . $this->api_key . '&api_secret=' . $this->api_secret;

        foreach ($data as $key => $value) {
            $http_url .= '&' . $key . '=' . $value;
        }
        curl_setopt($ch, CURLOPT_URL, $http_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $output = curl_exec($ch);

        if ($output === FALSE) {
            echo "cURL Error: " . curl_error($ch);
            return null;
        }
        
        curl_close($ch);

        return $output;
    }

    public function sendHttpPost($method, $data) {

        $ch = curl_init();

        $post_data = array(
            'api_key' => $this->api_key,
            'api_secret' => $this->api_secret,
        );

        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }

        $http_url = $this->url . $method;
        curl_setopt($ch, CURLOPT_URL, $http_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);

        if ($output === FALSE) {
            echo "cURL Error: " . curl_error($ch);
            return null;
        }
        // 4. 释放curl句柄
        curl_close($ch);


        return $output;
    }

    public function sendHttpMultipart($method, $data) {

        $ch = curl_init();

        $post_data = array(
            'api_key' => $this->api_key,
            'api_secret' => $this->api_secret,
        );


        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
//        var_dump($post_data);
//        exit;
        $http_url = $this->url . $method;
        curl_setopt($ch, CURLOPT_URL, $http_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $output = curl_exec($ch);

        if ($output === FALSE) {
            echo "cURL Error: " . curl_error($ch);
            return null;
        }
        
        curl_close($ch);

        return $output;
    }

}
