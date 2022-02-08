<?php

if (!function_exists('curl_file_create')) {
    function curl_file_create($filename, $mimetype = '', $postname = '') {
        return "@$filename;filename="
            . ($postname ?: basename($filename))
            . ($mimetype ? ";type=$mimetype" : '');
    }
}

require("FaceAll.php");

class FaMethod {

    private $DETECTION_DETECT = "detection/detect";
    private $DETECTION_LANDMARK = "detection/landmark";
    private $DETECTION_LANDMARK68 = "detection/landmark68";
    private $DETECTION_FEATURE = "detection/feature";
    private $DETECTION_ATTRIBUTES = "detection/attributes";
    private $FACE_GET_INFO = "face/get_info";
    private $IMAGE_GET_INFO = "image/get_info";
    private $IMAGE_GET_FILE = "image/get_file";
    private $FACESET_CREATE = "faceset/create";
    private $FACESET_DELETE = "faceset/delete";
    private $FACESET_ADD_FACES = "faceset/add_faces";
    private $FACESET_REMOVE_FACES = "faceset/remove_faces";
    private $FACESET_GET_INFO = "faceset/get_info";
    private $FACESET_SET_INFO = "faceset/set_info";
    private $FACESET_TRAIN = "faceset/train";
    private $FACESET_GET_LIST = "faceset/get_list";
    private $RECOGNITION_CLUSTER = "recognition/cluster";
    private $RECOGNITION_COMPARE_FACE = "recognition/compare_face";
    private $RECOGNITION_COMPARE_FACE_FACESET = "recognition/compare_face_faceset";
    private $RECOGNITION_CELEBRITY = "recognition/celebrity";
    private $RECOGNITION_COMPARE_FEATURE = "recognition/compare_feature";
    private $OBJECT_ROCOGNIZE = "object/recognize";
    private $api_key;
    private $api_secret;
    private $version;
    private $api = null;

    function __construct() {
        $this->api = new FaceAll(Yii::app()->params['faceall']['api_key'], Yii::app()->params['faceall']['api_secret'], Yii::app()->params['faceall']['version']);
    }
   
    /*     * ****************************************detection************************************************* */

    public function detection_detect($img_file, $img_url) {
        if ($img_file == null) {
            if ($img_url == null)
                return null;
            else {
                $data['img_url'] = $img_url;
                return $this->api->sendHttpGet($this->DETECTION_DETECT, $data);
            }
        }
        $data["img_file"] = curl_file_create($img_file, 'image/jpeg', 'tmp_img');
        return $this->api->sendHttpMultipart($this->DETECTION_DETECT, $data);
    }

    public function detection_landmark($face_id) {
        $data["face_id"] = $face_id;
        return $this->api->sendHttpGet($this->DETECTION_LANDMARK, $data);
    }

    public function detection_landmark68($face_id) {
        $data["face_id"] = $face_id;
        return $this->api->sendHttpGet($this->DETECTION_LANDMARK68, $data);
    }

    public function detection_feature($face_id, $return_feature) {
        $data["face_id"] = $face_id;
        $data["return_feature"] = $return_feature;
        return $this->api->sendHttpGet($this->DETECTION_FEATURE, $data);
    }

    public function detection_attributes($face_id) {
        $data["face_id"] = $face_id;
        return $this->api->sendHttpGet($this->DETECTION_ATTRIBUTES, $data);
    }

    /*     * ****************************************faceset************************************************* */

    public function faceset_create($faceset_name) {
        $data["faceset_name"] = $faceset_name;
        return $this->api->sendHttpGet($this->FACESET_CREATE, $data);
    }

    public function faceset_getinfo($faceset_id) {
        $data["faceset_id"] = $faceset_id;
        return $this->api->sendHttpGet($this->FACESET_GET_INFO,$data);
    }

    public function faceset_delete($faceset_id) {
        $data["faceset_id"] = $faceset_id;
        return $this->api->sendHttpGet($this->FACESET_DELETE, $data);
    }

    public function faceset_add_faces($faceset_id, $face_id) {
        $data["faceset_id"] = $faceset_id;
        $data["face_id"] = $face_id;
        return $this->api->sendHttpGet($this->FACESET_ADD_FACES, $data);
    }

    public function faceset_remove_faces($faceset_id, $face_id) {
        $data["faceset_id"] = $faceset_id;
        $data["face_id"] = $face_id;
        return $this->api->sendHttpGet($this->FACESET_REMOVE_FACES, $data);
    }

    public function faceset_train($faceset_id, $async) {
        $data["faceset_id"] = $faceset_id;
        $data["async"] = $async;
        return $this->api->sendHttpGet($this->FACESET_TRAIN, $data);
    }

    public function faceset_get_list() {
        return $this->api->sendHttpGet($this->FACESET_GET_LIST, null);
    }

    public function faceset_get_info($faceset_id) {
        $data["faceset_id"] = $faceset_id;
        return $this->api->sendHttpGet($this->FACESET_GET_INFO, $data);
    }

    public function faceset_set_info($faceset_id, $faceset_name) {
        $data["faceset_id"] = $faceset_id;
        $data["faceset_name"] = $faceset_name;
        return $this->api->sendHttpGet($this->FACESET_SET_INFO, $data);
    }

    /*     * ****************************************recognition************************************************* */

    public function recognition_compare_face($faceId1, $faceId2) {
        $mapdata["face_id1"] = $faceId1;
        $mapdata["face_id2"] = $faceId2;
        $r = $this->api->sendHttpGet('recognition/compare_face', $mapdata);
        return $r;
    }

    public function recognition_compare_face_faceset($face_id, $faceSet_id) {
        $mapdata['face_id'] = $face_id;
        $mapdata['faceset_id'] = $faceSet_id;
        $r = $this->api->sendHttpGet('recognition/compare_face_faceset', $mapdata);
        return $r;
    }

    public function recognition_cluster($faceSetId) {
        $mapdata['faceset_id'] = $faceSetId;
        $r = $this->api->sendHttpGet('recognition/cluster', $mapdata);
        return $r;
    }

    public function recognition_compare_feature($feature1, $feature2) {
        $mapdata['feature1'] = $feature1;
        $mapdata['feature2'] = $feature2;
        $r = $this->api->sendHttpPost('recognition/compare_feature', $mapdata);
        return $r;
    }

    public function recognition_celebrity($face_id) {
        $mapdata['face_id'] = $face_id;
        $r = $this->api->sendHttpGet('recognition/celebrity', $mapdata);
        return $r;
    }

    /*     * ****************************************object************************************************* */

    public function object_recognize($image_path) {
        $img = curl_file_create($image_path, 'image/jpeg', 'tmp_img');
        $formdata['img_file'] = curl_file_create($img, 'image/jpeg', 'tmp_img');
        $r = $this->api->sendHttpMultipart('object/recognize', $formdata);
        return $r;
    }

    public function image_getinfo($image_id) {
        $mapdata['image_id'] = $image_id;
        $r = $this->api->sendHttpGet('image/get_info', $mapdata);
        return $r;
    }

    public function image_getfile($image_id) {
        $mapdata['image_id'] = $image_id;
        $r = $this->api->sendHttpGet('image/get_file', $mapdata);
        return $r;
    }

    public function face_getinfo($face_id) {
        $mapdata['face_id'] = $face_id;
        $r = $this->api->sendHttpGet('face/get_info', $mapdata);
        return $r;
    }

}

?>