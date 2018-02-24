<?php
/**
 * Created by PhpStorm.
 * User: colen
 * Date: 2017/1/18
 * Time: 13:51
 */

namespace common\services;

class BaseMcrypt {

    public static function base_md5($key,$data){
        // mhash is deprecated from php5.3. by jiayi
        // return bin2hex(mhash(MHASH_SHA1,$data,$key));
        return bin2hex(hash_hmac('sha1', $data, $key, true));
    }
}