<?php
/**
 * Created by PhpStorm.
 * User: colen
 * Date: 2017/1/22
 * Time: 14:53
 */


require_once(ROOT_PATH . '/common/config/bootstrap.php');

require_once(ROOT_PATH . '/common/services/BaseMcrypt.php');
require_once(ROOT_PATH . '/common/services/RedisService.php');

require_once(ROOT_PATH . '/common/utils/SysCode.php');
require_once(ROOT_PATH . '/common/utils/RedisKeys.php');

require_once(SYSTASK_ROOT . '/src/base/BaseHandler.php');
require_once(SYSTASK_ROOT . '/src/base/Cipher.php');
require_once(SYSTASK_ROOT . '/src/base/BaseSwooleProcess.php');
require_once(SYSTASK_ROOT . '/src/base/BaseProcessor.php');

require_once(SYSTASK_ROOT . '/src/crontab/CollectProcessor.php');
require_once(SYSTASK_ROOT . '/src/services/BaseService.php');
