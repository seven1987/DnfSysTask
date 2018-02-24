<?php

namespace common\utils;

//全局redis key 配置
class RedisKeys
{
    //系统日志key
    const KEY_SYSTEM_LOG = 'lottery.log.system.log.1';
    const REGISTER_SAVE_PROFILE = 'lottery.register.save.profile';
    const REGISTER_SAVE_WITHDRAW_PASSWORD = 'lottery.register.save.withdraw.password';
    const REGISTER_SAVE_ANSWER = 'lottery.register.save.answer';
    const REGISTER_USER_INCREASE = 'lottery.register.user.increase';
    const USER_OPT_LOG = 'lottery.user.opt.log';
}
