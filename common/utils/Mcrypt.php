<?php
/**
 * Created by PhpStorm.
 * User: SCF
 * Date: 2017/5/31
 * Time: 16:14
 */
namespace common\utils;

//php aes加密类
class Mcrypt {

    public $iv = '0987654321fedcba';
    public $key = 'abcdef1234567890';
    public $bit = 128;
    private $cipher;
    private $mode = 'cbc';

    public function __construct() {

        switch($this->bit) {
            case 192:$this->cipher = MCRYPT_RIJNDAEL_192; break;
            case 256:$this->cipher = MCRYPT_RIJNDAEL_256; break;
            default: $this->cipher = MCRYPT_RIJNDAEL_128;
        }

        switch($this->mode) {
            case 'ecb':$this->mode = MCRYPT_MODE_ECB; break;
            case 'cfb':$this->mode = MCRYPT_MODE_CFB; break;
            case 'ofb':$this->mode = MCRYPT_MODE_OFB; break;
            case 'nofb':$this->mode = MCRYPT_MODE_NOFB; break;
            default: $this->mode = MCRYPT_MODE_CBC;
        }
    }

    public function encrypt($data) {
        $data = base64_encode(@mcrypt_encrypt( $this->cipher, $this->key, $data, $this->mode, $this->iv));
        return $data;
    }

    public function decrypt($data) {
        $data = @mcrypt_decrypt( $this->cipher, $this->key, base64_decode($data), $this->mode, $this->iv);
        $data = @rtrim(rtrim($data), "..");
        return $data;
    }

}
