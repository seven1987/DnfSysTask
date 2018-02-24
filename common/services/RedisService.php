<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/2/28
 * Time: 19:52
 */

namespace common\services;

use Yii;
use common\utils\RedisKeys;
use common\models\Bet;


class RedisService
{
    const PACKAGE_SIZE = 10000;
    private $redis = null;
    public $config = null;
    private $ispconnect = false;
    private $lastPingTime = 0;
    private $pingDuration = 5;

    /**
     * reids构造函数
     * @param null $config
     * @param bool $ispconnect : 是否为pconnect方式(不主动关闭)
     */
    public function __construct($config = null, $ispconnect = false)
    {
        $this->config = $config;
        $this->ispconnect = $ispconnect;
    }

    /**
     * 获取 redis 实例。支持反复重连和心跳
     * @return null|\Redis
     * @throws
     */
    public function getRedis($db = 2)
    {
        try {
            if (!is_null($this->redis)) {
                //每隔一个时间段ping,ping不通则断开，置空,重新获取
                if ($this->lastPingTime == 0)
                    $this->lastPingTime = time(true);
                $now = time(true);
                if ($now - $this->lastPingTime > $this->pingDuration) {
                    $pong = $this->redis->ping();
                    //重置, todo:发送通知，提醒ping不通
                    if ($pong != "+PONG") {
                        $this->redis->close();
                        $this->redis = null;
                    }
                    $this->lastPingTime = $now;
                }
            }
            if (is_null($this->redis)) {
                $redis = new \Redis();
                $res = null;
                //尝试连接20次,每2秒一次,连不上则抛异常:
                for ($i = 0; $i < 20; $i++) {
                    if ($this->ispconnect)
                        $res = $redis->pconnect($this->config["hostname"], $this->config["port"]);
                    else
                        $res = $redis->connect($this->config["hostname"], $this->config["port"]);
                    if ($res) {
                        break;
                    } else {
                        sleep(2);
//                        echo "reconnect redis:";
                        continue;
                    }
                }
                if (!$res) {
                    throw new \Exception("Redis connect failed:config:" . json_encode($this->config));
                } else {
//                    $redis->auth($this->config['password']);
                    //密码验证
                    $this->redis = $redis;
                    if (isset($this->config['password']) && !empty($this->config['password'])) {
                        $this->redis->auth($this->config['password']);
                    }
                }
            }

            $this->redis->select($db);
            return $this->redis;
        } catch (\RedisException $e) {
            echo $e->getMessage() . ",exception:" . $e->getTraceAsString();
        }
    }


    public function getRedisMain($db = 0)
    {
        try {
            if (!is_null($this->redis)) {
                //每隔一个时间段ping,ping不通则断开，置空,重新获取
                if ($this->lastPingTime == 0)
                    $this->lastPingTime = time(true);
                $now = time(true);
                if ($now - $this->lastPingTime > $this->pingDuration) {
                    $pong = $this->redis->ping();
                    //重置, todo:发送通知，提醒ping不通
                    if ($pong != "+PONG") {
                        $this->redis->close();
                        $this->redis = null;
                    }
                    $this->lastPingTime = $now;
                }
            }
            if (is_null($this->redis)) {
                $redis = new \Redis();
                $res = null;
                //尝试连接20次,每2秒一次,连不上则抛异常:
                for ($i = 0; $i < 20; $i++) {
                    if ($this->ispconnect)
                        $res = $redis->pconnect($this->config["hostname"], $this->config["port"]);
                    else
                        $res = $redis->connect($this->config["hostname"], $this->config["port"]);
                    if ($res) {
                        break;
                    } else {
                        sleep(2);
//                        echo "reconnect redis:";
                        continue;
                    }
                }
                if (!$res) {
                    throw new \Exception("Redis connect failed:config:" . json_encode($this->config));
                } else {
//                    $redis->auth($this->config['password']);
                    //密码验证
                    $this->redis = $redis;
                    if (isset($this->config['password']) && !empty($this->config['password'])) {
                        $this->redis->auth($this->config['password']);
                    }
                }
            }

            if ($db != 0) {
                $this->redis->select($db);
            }
            return $this->redis;
        } catch (\RedisException $e) {
            echo $e->getMessage() . ",exception:" . $e->getTraceAsString();
        }
    }

    /**
     * 析构：断开redis
     */
    public function __destruct()
    {
        try {
            if ($this->redis) {
                $this->redis->close();
            }
        } catch (\RedisException $e) {
            echo $e->getMessage() . ",exception:" . $e->getTraceAsString();
        }
    }

    /**
     * 添加新的注单
     * @param $bet
     */
    public function addNewBet($bet)
    {
        $redis = $this->getRedis();
        //$redis->hSet(RedisKeys::KEY_BET_NEW, $bet["bet_id"], json_encode($bet));
        $redis->rPush(RedisKeys::KEY_BET_NEW, json_encode($bet));
    }

    /**
     * 添加新的注单
     * @param $bet
     */
    public function addNewBets($newBets)
    {
        //$redis = $this->getRedis();
        foreach ($newBets as $bet) {
            $this->addNewBet($bet);
        }
    }

    /**
     * 获取在 redis new 里的注单信息
     * @return array
     */
    public function getNewBets($start = 0, $stop = -1)
    {
        $redis = $this->getRedis();
        $betStrs = $redis->lRange(RedisKeys::KEY_BET_NEW, $start, $stop);
        $bets = array();
        if (empty($betStrs)) {
            return $bets;
        }
        foreach ($betStrs as $betId => $betStr) {
            $bet = json_decode($betStr, true);
            $bets[$bet['bet_id']] = $bet;
        }
        return $bets;
    }

    /**
     * 删掉在 redis new 里的注单信息
     * @param $newBetIds
     */
    public function removeNewBets($newBetIds)
    {
        $redis = $this->getRedis();

//        $start = microtime(true);
//        $pipe = $redis->multi(\Redis::PIPELINE);
        foreach ($newBetIds as $bet) {
            $redis->lRem(RedisKeys::KEY_BET_NEW, json_encode($bet), 1);
        }
//        $pipe->exec();
    }

    /**
     * 清空 redis new 里的注单
     */
    public function clearNewBets()
    {
        $redis = $this->getRedis();
        $redis->del(RedisKeys::KEY_BET_NEW);
    }

    /**
     * 添加待审核的注单信息
     * @param $betMsg
     */
    public function pushCheckBets($betMsg)
    {
        $redis = $this->getRedis();
        $redis->rPush(RedisKeys::KEY_BET_CHECK, json_encode($betMsg));
    }

    /**
     * 管理端审核注单，审核后插入
     * @param $bet : 整个注单数据放入;
     * @return int
     */
    public function addConfirmBet($bet)
    {
        $redis = $this->getRedis();
        return $redis->hSet(RedisKeys::KEY_BET_CONFIRM, $bet["bet_id"], json_encode($bet));
    }

    /**
     * 获取审核后注单
     * @return array
     */
    public function getConfirmBets()
    {
        $redis = $this->getRedis();
        $betStrs = $redis->hGetAll(RedisKeys::KEY_BET_CONFIRM);
        $bets = array();
        foreach ($betStrs as $betId => $betStr) {
            $bets[$betId] = json_decode($betStr, true);
        }
        return $bets;
    }

    /**
     * 移除审核后redis注册
     * @param $betIds
     * @throws \Exception
     */
    public function removeConfirmBets(&$betIds)
    {
        $redis = $this->getRedis();

//        $start = microtime(true);
//        $pipe = $redis->multi(\Redis::PIPELINE);
        foreach ($betIds as $betId) {
            $redis->hDel(RedisKeys::KEY_BET_CONFIRM, $betId);
        }
//        $pipe->exec();
    }

    /**
     * 添加注单审核通过的单注注单（等待结算）
     * @param $bets
     */
    public function addPassBets(&$bets)
    {
        return;  // 当前版本单注结算服务不通过redis。
        $total = count($bets);
        if ($total == 0) {
            return;
        }

        $redis = $this->getRedis();
        // $pipe = $redis->multi(\Redis::PIPELINE);
        foreach ($bets as $bet) {
            $han_id = $bet['han_id'];
            $counter = $redis->incr(RedisKeys::betPassCounter($han_id));
            $index = floor($counter / RedisService::PACKAGE_SIZE);
            $key = RedisKeys::betPass($han_id, $index);
            // $pipe->hSet($key, $bet["bet_id"], json_encode($bet));
            $redis->hSet($key, $bet["bet_id"], json_encode($bet));
        }
        // return $pipe->exec();
    }

    /**
     * 更新盘口自动赔率
     * @param $han_id
     * @param $hanOddses
     */
    public function updateHandicapAutoOdds($han_id, $hanOddses)
    {
        $redis = $this->getRedis();
//        $pipe = $redis->multi(\Redis::PIPELINE);
        foreach ($hanOddses as $part_id => $partOdds) {
            $redis->hSet(RedisKeys::KEY_ODDS_AUTO_NEW . $han_id, $part_id, $partOdds);
        }
//        $pipe->exec();
    }

    /**
     * 获取盘口自动赔率
     * @param int $han_id
     * @param int $part_id
     * @return array|string
     */
    public function getHandicapAutoOdds($han_id = 0, $part_id = 0)
    {
        if (empty($han_id)) {
            return [];
        }
        $redis = $this->getRedis();
        if (empty($part_id)) {
            return $redis->hGetAll(RedisKeys::KEY_ODDS_AUTO_NEW . $han_id);
        }
        return $redis->hGet(RedisKeys::KEY_ODDS_AUTO_NEW . $han_id, $part_id);
    }

    /**
     * 获取注单审核通过的注单包列表
     * @param $han_id
     * @return array
     */
    public function getPassBetskeys($han_id)
    {
        $redis = $this->getRedis();
        // $counter = $redis->get(RedisKeys::betPassCounter($han_id));
        // $maxIdx = floor($counter / RedisService::PACKAGE_SIZE);
        // $keys = array();
        // for ($i=0; $i <= $maxIdx; $i++) {
        //     $keys[] = RedisKeys::betPass($han_id, $i);
        // }
        $keys = $redis->keys(RedisKeys::betPassPattern($han_id));
        return $keys;
    }

    /**
     * 删除指定盘口的审核通过的所有注单。
     * @param $han_id
     */
    public function removePassBets($han_id)
    {
        $redis = $this->getRedis();
        $keys = $this->getPassBetskeys($han_id);
        foreach ($keys as $key) {
            $redis->del($key);
        }
        $counterKey = RedisKeys::betPassCounter($han_id);
        $redis->del($counterKey);
    }

    /**
     * 删除指定盘口 package 的审核通过的所有注单。
     * @param $han_id
     */
    public function removePassBetsByPackage($key)
    {
        $redis = $this->getRedis();
        $redis->del($key);
    }

    /**
     * 添加注单审核通过的串注注单（等待结算）
     * @param array &$stringBets
     * @return bool
     */
    public function addPassStringBets(&$stringBets)
    {
        $total = count($stringBets);
        if ($total == 0) {
            return null;
        }
        $redis = $this->getRedis();
//        $pipe = $redis->multi(\Redis::PIPELINE);
        foreach ($stringBets as $bet) {
            $redis->hSet(RedisKeys::KEY_BET_PASS_STRING, $bet["bet_id"], json_encode($bet));
        }
//        $pipe->exec();
    }

    /**
     * 移除设置为无效、系统判定无效的注单。
     * @param $bet
     * @return bool | null
     */
    public function removePassBet($bet)
    {
        if (empty($bet)) {
            return null;
        }
        $sts = $bet['status'];
        if (is_null($sts) ||
            (Bet::STATUS_INVALID != $sts && Bet::STATUS_SYS_INVALID != $sts)
        ) {
            return null;
        }
        if ($bet["bettype"] != Bet::BET_TYPE_SINGLE) {
            return null;
        }

        $redis = $this->getRedis();
        $han_id = $bet['han_id'];
        $redis->decr(RedisKeys::betPassCounter($han_id));
        $keys = $this->getPassBetskeys($han_id);
        $betID = $bet["bet_id"];
        foreach ($keys as $key) {
            if ($redis->hDel($key, $betID)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 获取注单审核通过的串注注单
     * @return array
     */
    public function getPassStringBets($key = RedisKeys::KEY_BET_PASS_STRING)
    {
        $redis = $this->getRedis();
        $betStrs = $redis->hGetAll($key);
        $bets = array();
        foreach ($betStrs as $betId => $betStr) {
            $bets[$betId] = json_decode($betStr, true);
        }
        return $bets;
    }

    /**
     * 更新审核通过的串注注单
     * @param &$bets
     */
    public function updatePassStringBets(&$bets, $key = RedisKeys::KEY_BET_PASS_STRING)
    {
        $redis = $this->getRedis();
//        $pipe = $redis->multi(\Redis::PIPELINE);
        foreach ($bets as $bet) {
            $redis->hSet($key, $bet["bet_id"], json_encode($bet));
        }
//        $pipe->exec();
    }

    /**
     * 删除审核通过的串注注单
     * @param $betIds
     */
    public function removePassStringBets(&$betIds, $key = RedisKeys::KEY_BET_PASS_STRING)
    {
        $redis = $this->getRedis();
//        $pipe = $redis->multi(\Redis::PIPELINE);
        foreach ($betIds as $betId) {
            $redis->hDel($key, $betId);
        }
//        $pipe->exec();
    }

    /**
     * push盘口自动赔率开关
     * @param $han_id
     * @param $isauto
     * @throws \Exception
     */
    public function setHandicapAutoOdds($han_id, $isauto)
    {
        $redis = $this->getRedis();
        $redis->hSet(RedisKeys::KEY_HANDICAP_AUTO_ODDS, $han_id, $isauto);
    }

    /**
     * 获取盘口自动赔率最新选项
     * @return array
     * @throws \Exception
     */
    public function getHandicapsAutoOdds($han_id = 0)
    {
        $redis = $this->getRedis();
        $han_id = (int)$han_id;
        if (empty($han_id)) {
            return $redis->hGetAll(RedisKeys::KEY_HANDICAP_AUTO_ODDS);
        }
        return $redis->hGet(RedisKeys::KEY_HANDICAP_AUTO_ODDS, $han_id);
    }

    /**
     * 移除当前自动赔率通知
     * @throws \Exception
     */
    public function removeHandicapsAutoOdds()
    {
        $redis = $this->getRedis();
        $redis->del(RedisKeys::KEY_HANDICAP_AUTO_ODDS);
    }

    /**
     * 盘口自动赔率状态变更入队
     * @param array $data
     * @return bool|int
     */
    public function setHandicapAutoOddsAction($data = [])
    {
        if (empty($data)) {
            return false;
        }
        $redis = $this->getRedis();
        return $redis->RPUSH(RedisKeys::KEY_HANDICAP_AUTO_ODDS_ACTION_LIST, json_encode($data));
    }

    /**
     * 盘口自动赔率状态变更出队
     * @return string
     */
    public function getHandicapAutoOddsAction()
    {
        $redis = $this->getRedis();
        $data = $redis->LPOP(RedisKeys::KEY_HANDICAP_AUTO_ODDS_ACTION_LIST);
        return $data ? json_decode($data, true) : [];
    }

    /**
     * 盘口自动赔率修改修正值入队
     * @param array $data
     * @return bool|int
     */
    public function setHandicapAutoUpdateAction($data = [])
    {
        if (empty($data)) {
            return false;
        }
        $redis = $this->getRedis();
        return $redis->RPUSH(RedisKeys::KEY_HANDICAP_AUTO_UPDATE_ACTION_LIST, json_encode($data));
    }

    /**
     * 盘口自动赔率修改修正值出队
     * @return string
     */
    public function getHandicapAutoUpdateAction()
    {
        $redis = $this->getRedis();
        $data = $redis->LPOP(RedisKeys::KEY_HANDICAP_AUTO_UPDATE_ACTION_LIST);
        return $data ? json_decode($data, true) : [];
    }

    /**
     * 添加盘口赔率变化
     * @param $msg
     * @return int
     */
    public function pushOddsChange($msg)
    {
        $redis = $this->getRedis();
        $redis->hSet(RedisKeys::KEY_ODDS_CHANGE_FRONT, $msg['han_id'] . "_" . $msg['part_id'], json_encode($msg));
        $redis->hSet(RedisKeys::KEY_ODDS_CHANGE_BACK, $msg['han_id'] . "_" . $msg['part_id'], json_encode($msg));
    }

    //添加管理端盘口自动赔率变化
    public function pushAutoOddsChange($msg)
    {
        $redis = $this->getRedis();
        $redis->hSet(RedisKeys::KEY_AUTO_ODDS_CHANGE_BACK, $msg['han_id'] . "_" . $msg['part_id'], json_encode($msg));
    }

    /**
     * 盘口注单变化，通知管理端变化
     * @param $betChangeMsg
     */
    public function pushHanBetChange($betChangeMsg)
    {
        $redis = $this->getRedis();
        $redis->rPush(RedisKeys::KEY_HAN_BET_CHANGE, json_encode($betChangeMsg));
    }

    /**
     *
     * @return int
     */
    public function countBetChange()
    {
        $redis = $this->getRedis();
        return $redis->lLen(RedisKeys::KEY_HAN_BET_CHANGE);
    }

    /**
     * msgserver,sysserver 获取管理端数据
     * @return string
     */
    public function receiveBackendMsgs()
    {
        $redis = $this->getRedis();
        $messages = "";
        //待审核注单
        // $msg = $redis->lPop(RedisKeys::KEY_BET_CHECK);
        // if ($msg)
        //     $messages .= $msg;
        //盘口注单盈亏变更:
        $msg = $redis->lPop(RedisKeys::KEY_HAN_BET_CHANGE);
        if ($msg) {
            if (strlen($messages) > 0)
                $messages .= ",";
            $messages .= $msg;
        }
        //赔率变化
        $msg = $redis->hVals(RedisKeys::KEY_ODDS_CHANGE_BACK);
        $redis->del(RedisKeys::KEY_ODDS_CHANGE_BACK);
        if ($msg) {
            if (strlen($messages) > 0)
                $messages .= ",";
            $messages .= implode(',', $msg);
        }

        //自动赔率变化
//        $msg = $redis->hVals(RedisKeys::KEY_AUTO_ODDS_CHANGE_BACK);
//        $redis->del(RedisKeys::KEY_AUTO_ODDS_CHANGE_BACK);
//        if ($msg) {
//            if (strlen($messages) > 0)
//                $messages .= ",";
//            $messages .= implode(',', $msg);
//        }

        return $messages;
    }

    /**
     * 获取用户端数据
     */
    public function receiveFrontendMsgs()
    {
        $redis = $this->getRedis();
        $messages = "";
        $msg = $redis->hVals(RedisKeys::KEY_ODDS_CHANGE_FRONT);
        $redis->del(RedisKeys::KEY_ODDS_CHANGE_FRONT);
        if ($msg) {
            $messages .= implode(',', $msg);
        }
        return $messages;
    }

    /**
     * 获取数据表当前自增ID
     * @param $tableName
     * @return bool|string
     */
    public function getRedisCurrID($tableName)
    {
        $redis = $this->getRedis();
        $key = RedisKeys::KEY_TABLE_ID_INC . $tableName;
        return $redis->get($key);
    }

    /**
     * 设置表的自增ID
     * @param $tableName
     * @param $newId
     * @return bool
     */
    public function getRedisSetID($tableName, $newId)
    {
        $redis = $this->getRedis();
        $key = RedisKeys::KEY_TABLE_ID_INC . $tableName;
        $currId = $redis->get($key);
        if ((int)$currId >= (int)$newId) {
            return false;
        }
        return $redis->set($key, $newId);
    }

    /**
     * @param $tableName
     * @param int $count
     * @return bool|int|string
     */
    public function getRedisNextID($tableName, $count = 1)
    {
        $redis = $this->getRedis();
        $key = RedisKeys::KEY_TABLE_ID_INC . $tableName;
        $currId = $redis->get($key);
        if (!$currId) {
            $redis->set($key, IDService::loadTable($tableName) - 1);
        }
        if (!$currId = $redis->incrBy($key, $count)) {
            throwException(new \Exception("getNextID occur error."));
        }
        return $currId;
    }

    /**
     * 获取注单锁，判断是否重复秒注
     *
     * @param $key
     */
    public function getNewBetLock($key)
    {
        $redis = $this->getRedis();
        $redis->get($key);
    }

    /**
     * 添加注单锁
     *
     * @param $key
     */
    public function addNewBetLock($key)
    {
        $redis = $this->getRedis();
        $redis->set($key, '1');
        $redis->expire($key, 1);
    }

    /**
     * 设置盘口自动赔率修正值
     * @param $han_id
     * @param $partId
     * @param $odds
     * @return int
     */
    public function setHandicapAutoOddsFixMap($han_id, $partId, $odds)
    {
        $redis = $this->getRedis();
        return $redis->hSet(RedisKeys::KEY_HANDICAP_ODDS_FIX_MAP . $han_id, $partId, $odds);
    }

    /**
     * 获取盘口自动赔率修正值
     * @param $han_id
     * @param $partId
     * @return string
     */
    public function getHandicapAutoOddsFixMap($han_id, $partId = 0)
    {
        $redis = $this->getRedis();
        if ($partId) {
            return $redis->hGet(RedisKeys::KEY_HANDICAP_ODDS_FIX_MAP . $han_id, $partId);
        }
        return $redis->hGetAll(RedisKeys::KEY_HANDICAP_ODDS_FIX_MAP . $han_id);
    }

    /**
     * 删除盘口自动赔率修正值
     * @param $han_id
     * @return int
     */
    public function removeHandicapAutoOddsFixMap($han_id)
    {
        $redis = $this->getRedis();
        return $redis->del(RedisKeys::KEY_HANDICAP_ODDS_FIX_MAP . $han_id);
    }


    /**
     * 设置盘口自动赔率盘口注单数据
     * @param $han_id
     * @param $partId
     * @param $odds
     * @return int
     */
    public function setHandicapAutoOddsBetMap($han_id, $partId, $odds)
    {
        $redis = $this->getRedis();
        return $redis->hSet(RedisKeys::KEY_HANDICAP_ODDS_BET_MAP . $han_id, $partId, $odds);
    }

    /**
     * 获取盘口自动赔率盘口注单数据
     * @param $han_id
     * @param $partId
     * @return string
     */
    public function getHandicapAutoOddsBetMap($han_id, $partId = 0)
    {
        $redis = $this->getRedis();
        if ($partId) {
            return $redis->hGet(RedisKeys::KEY_HANDICAP_ODDS_BET_MAP . $han_id, $partId);
        }
        return $redis->hGetAll(RedisKeys::KEY_HANDICAP_ODDS_BET_MAP . $han_id);
    }

    /**
     * 删除盘口自动赔率盘口注单数据
     * @param $han_id
     * @return int
     */
    public function removeHandicapAutoOddsBetMap($han_id)
    {
        $redis = $this->getRedis();
        return $redis->del(RedisKeys::KEY_HANDICAP_ODDS_BET_MAP . $han_id);
    }

    /**
     * 设置盘口返回率信息
     * @param $han_id
     * @param $returnrate
     * @return int
     */
    public function setHandicapReturnRateMap($han_id, $returnrate)
    {
        $redis = $this->getRedis();
        return $redis->hSet(RedisKeys::KEY_HANDICAP_RETURNRATE_MAP, $han_id, $returnrate);
    }

    /**
     * 获取盘口返回率信息
     * @param $han_id
     * @return array|string
     */
    public function getHandicapReturnRateMap($han_id)
    {
        $redis = $this->getRedis();
        if ($han_id) {
            return $redis->hGet(RedisKeys::KEY_HANDICAP_RETURNRATE_MAP, $han_id);
        }
        return $redis->hGetAll(RedisKeys::KEY_HANDICAP_RETURNRATE_MAP);
    }

    /**
     * 删除盘口返回率信息
     * @param $han_id
     * @return int
     */
    public function removeHandicapReturnRateMap($han_id)
    {
        $redis = $this->getRedis();
        return $redis->del(RedisKeys::KEY_HANDICAP_RETURNRATE_MAP . $han_id);
    }

    /**
     * 设置盘口赔率信息
     * @param $han_id
     * @param $returnrate
     * @return int
     */
    public function setHandicapOddsListMap($han_id, $part_id, $data)
    {
        $redis = $this->getRedis();
        return $redis->hSet(RedisKeys::KEY_HANDICAP_ODDS_LIST_MAP . $han_id, $part_id, json_encode($data));
    }

    /**
     * 获取盘口赔率信息
     * @param $han_id
     * @return array|string
     */
    public function getHandicapOddsListMap($han_id, $part_id = 0)
    {
        $redis = $this->getRedis();
        if ($part_id) {
            $data = $redis->hGet(RedisKeys::KEY_HANDICAP_ODDS_LIST_MAP . $han_id, $part_id);
            return $data ? json_decode($data, true) : [];
        }
        $list = $redis->hGetAll(RedisKeys::KEY_HANDICAP_ODDS_LIST_MAP . $han_id);
        if ($list) {
            foreach ($list as $key => $val) {
                $list[$key] = json_decode($val, true);
            }
        }
        return $list;
    }

    /**
     * 删除盘口赔率信息
     * @param $han_id
     * @return int
     */
    public function removeHandicapOddsListMap($han_id)
    {
        $redis = $this->getRedis();
        return $redis->del(RedisKeys::KEY_HANDICAP_ODDS_LIST_MAP . $han_id);
    }

    /**
     * 添加串注注单限额变化
     * @param $msg
     * @return int
     */
    public function pushBetStringLimitChange($msg)
    {
        $redis = $this->getRedis();
        $redis->set(RedisKeys::KEY_BET_STRING_LIMIT_CHANGE_FRONT, json_encode($msg));
    }

    /**
     * 获取审核后注单
     * @return array
     */
    public function getBetStringLimit()
    {
        $redis = $this->getRedis();
        $betStringLimit = $redis->get(RedisKeys::KEY_BET_STRING_LIMIT_CHANGE_FRONT);
        return $betStringLimit;
    }

    /**
     * 设置多语言翻译信息
     * @param $han_id
     * @param $returnrate
     * @return int
     */
    public function setTranslateLang($key, $data)
    {
        $redis = $this->getRedis();
        return $redis->hSet(RedisKeys::KEY_LANG_TRANSLATE, $key, json_encode($data));
    }

    /**
     * 获取多语言翻译信息
     * @param $han_id
     * @return array|string
     */
    public function getTranslateLang($key = '')
    {
        $redis = $this->getRedis();
        if ($key) {
            $data = $redis->hGet(RedisKeys::KEY_LANG_TRANSLATE, $key);
            return $data ? json_decode($data, true) : [];
        }
        $list = $redis->hGetAll(RedisKeys::KEY_LANG_TRANSLATE);
        if ($list) {
            foreach ($list as $k => $val) {
                $list[$k] = json_decode($val, true);
            }
        }
        return $list;
    }

    /**
     * 频率控制验证（通用）
     * @param string $method 频率控制方法， 可拼接一段方法名称， 保证唯一性；  非必填
     * @param int $pexpire 频率控制时长，毫秒； 默认100毫秒
     * @return bool
     */
    public function frequencyCheck($method, $pexpire = 100)
    {
        if (empty($method)) {
            return true;
        }

        $redis = $this->getRedis();

        //1 方法频率控制
        //key验证规则： 同一方法集（必须）, 同一分钟 进行验证
        $key = RedisKeys::KEY_API_FREQUENCY . $method . date("YmdHis");
        if ($redis->get($key))//频率过快， 返回失败
        {
            return false;
        }

        //设置key值以及过期时间
        return $redis->psetex($key, $pexpire, 1);
    }
}
