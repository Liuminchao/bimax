<?php
    class Rsa {

        /**
        * 获取私钥
        * @return bool|resource
        */
        private static function getPrivateKey() {
            $abs_path = dirname(__FILE__) . '/keys/rsa_private_key.pem';
            $content = file_get_contents($abs_path);
            return openssl_pkey_get_private($content);
        }

        /**
        * 获取公钥
        * @return bool|resource
        */
        private static function getPublicKey() {
//            $abs_path = dirname(__FILE__) . '/keys/rsa_public_key.pem';
//            $content = file_get_contents($abs_path);
            $content = '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvdhE3JTIsMj+WgD0ow7v
h5gMAAI/bnrAnm7ExZ/hHzadzMLEGL/VqgJI5ef6R/JVzllH/79+zcStfEOXrRiA
If16C0DjXFbniwrJUTjmaktIdhQeudDYvC2PHzJi7koGXGp84IFgafqtmug7j/Oy
aVsjnbeKC4/FGT4xsaBg1LBUU+H6AX2RpqqZV1KF5kIn3/Rsg66qKKK9Rcppp+Dz
qO6MC/L9fpfCS1SM739UfaGktzdjrXEyTWqRU6r4NycZVI0Jr2KhIfqsH00sArjs
G8QBMNeqZzOuUpbwNPvnoZGYY9rCQEjxacpyKz2b5COx2pPLH5g0u1ChJtca2Vai
IQIDAQAB
-----END PUBLIC KEY-----';
//            return openssl_pkey_get_public($content);
            return $content;
        }

        /**
        * 私钥加密
        * @param string $data
        * @return null|string
        */
        public static function privEncrypt($data = '') {
            if (!is_string($data)) {
            return null;
            }
            return openssl_private_encrypt($data, $encrypted, self::getPrivateKey()) ? base64_encode($encrypted) : null;
        }

        /**
        * 公钥加密
        * @param string $data
        * @return null|string
        */
        public static function publicEncrypt($data = '') {
            if (!is_string($data)) {
            return null;
            }
            return openssl_public_encrypt($data, $encrypted, self::getPublicKey()) ? base64_encode($encrypted) : null;
        }

        /**
        * 私钥解密
        * @param string $encrypted
        * @return null
        */
        public static function privDecrypt($encrypted = '') {
            if (!is_string($encrypted)) {
            return null;
            }
            return (openssl_private_decrypt(base64_decode($encrypted), $decrypted, self::getPrivateKey())) ? $decrypted : null;
        }

        /**
        * 公钥解密
        * @param string $encrypted
        * @return null
        */
        public static function publicDecrypt($encrypted = '') {
            if (!is_string($encrypted)) {
            return null;
            }
            return (openssl_public_decrypt(base64_decode($encrypted), $decrypted, self::getPublicKey())) ? $decrypted : null;
        }

    }