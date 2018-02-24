<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2017/2/15
 * Time: 14:28
 */

namespace common\utils;

//全局系统code
class SysCode
{
    //全局性code:
    const OK = 0;       //no error
    const MODEL_NOT_FOUND = 1;

    //赛事模块
    const MATCH_START = 1000;

    //操盘模块
    const HANDICAP_START = 1500;

    //注单模块
    const BET_START = 2500;

    //会员模块
    const MEMBER_START = 3500;
    const MEMBER_TRANSFER_MONEY_ERROR = 3501;           //金额必须大于0
    const MEMBER_TRANSFER_TYPE_ERROR = 3502;            //类型错误
    const MEMBER_TRANSFER_LOG_ERROR = 3503;             //充值写 money log 失败
    const MEMBER_TRANSFER_COMMIT_ERROR = 3504;          //充值失败
    const MEMBER_TRANSFER_MONEY_TYPE = 3505;            //充值金额必须为数字
    const MEMBER_TRANSFER_USER_ERROR = 3506;            //用户不存在

    //代理模块
    const AGENT_START = 4500;

    //消息模块
    const MESSAGE_START = 5000;

    //报表模块
    const _START = 6000;

    //系统服务模块:
    const SYSSERVER_START = 7000;
    const SYSSERVER_RECKON_HANDICAP_NOT_FOUND = 7001;                           //盘口不存在
    const SYSSERVER_RECKON_HANDICAP_NOT_CONFIRMED = 7002;                       //结算错误码:盘口未在审核通过状态
    const SYSSERVER_RECKONNULL_RESULT = 7003;                                   //结算错误码:盘口赛果为空
    const SYSSERVER_RECKON_WRONG_RESULT = 7004;                                 //结算错误码:盘口赛果无效
    const SYSSERVER_RECKON_HANDICAP_RECKONED = 7005;                            //结算错误码:盘口已结算过
    const SYSSERVER_RECKON_HANDICAP_RECKON_REPEATED = 7006;                     //结算错误码:盘口重复结算
    const SYSSERVER_RECKON_HANDICAP_HASH_INVALID = 7007;                        //结算错误码:盘口hash无效
    const SYSSERVER_RECKON_ROLLBACK_STEP_ERROR = 7008;                          //结算错误码:回滚步骤错误
    const SYSSERVER_RECKON_ROLLBACK_MONEYLOG_ERR_DATA = 7009;                   //结算错误码:回滚资金流水错误数据
    const SYSSERVER_RECKON_ROLLBACK_MONEYLOG_NO_DATA = 7010;                    //结算错误码:回滚资金流水无数据
    const SYSSERVER_RECKON_ROLLBACK_MONEYLOG_ZERO_AMOUNT = 7011;                //结算错误码:回滚资金流水金额为零
    const SYSSERVER_RECKON_ROLLBACK_MONEY_NO_LOG = 7012;                        //结算错误码:回滚资金无相关流水
    const SYSSERVER_RECKON_ROLLBACK_MONEY_NO_DATA = 7013;                       //结算错误码:回滚资金无数据
    const SYSSERVER_RECKON_RECORD_NONE = 7014;                                  //结算错误码:结算记录不存在
    const SYSSERVER_RECKON_RECORD_EXIST = 7015;                                 //结算错误码:结算记录已存在
    const SYSSERVER_RECKON_PASSED_BET_EXIST_AFTER_RECKON = 7016;                //结算完成后仍存在审核通过注单
    const SYSSERVER_RECKON_TASK_EXCEPTION_THROW = 7017;                         //结算任务抛出异常
    const SYSSERVER_RECKON_UPDATERECORD_EXCEPTION_THROW = 7018;                 //更新结算记录抛出异常
    const SYSSERVER_RECKON_SUCCESS_EXCEPTION_THROW = 7019;                      //结算成功抛出异常
    const SYSSERVER_RECKON_ROLLBACK_EXCEPTION_THROW = 7020;                     //数据回滚抛出异常

    //权限管理

    //系统日志

    // 用户端，消息号统一规范
    const FRONTEND_SUCCESS = 0;                                                 // 成功
    const FRONTEND_ERROR_SQL = -8;                                              // 写入数据库报错
    const FRONTEND_ERROR_PHP = -9;                                              // PHP代码执行报错
    const FRONTEND_ERROR_JSON = -10;                                            // JSON 数据错误，字段缺失

    const FRONTEND_ERROR_SMS = -11;                                             // 请求发送短信验证码返回错误

    const FRONTEND_LOGIN_INVALID = -1;                                          // 登录已失效

    const FRONTEND_PLATFORM_AUTH_ERROR = -9001;                                 // 平台接口鉴权失败

	// 用户端，登录、注册错误号
    const FRONTEND_LOGIN_FAILED = 10100;                                        // 登录失败
    const FRONTEND_LOGIN_USER_NOT_FOUND = 10101;                                // 用户不存在
    const FRONTEND_LOGIN_USER_INVALID = 10102;                                  // 用户未激活
    const FRONTEND_LOGIN_USER_PASSWORD_ERROR = 10103;                           // 密码错误


    const FRONTEND_LOGIN_USER_EMAIL_ERROR = 10104;                              // 邮箱错误

    const FRONTEND_RESPWD_USER_ULR_ERROR = 10105;                               // 链接错误
    const FRONTEND_RESPWD_USER_EMAIL_EXPIRE = 10106;                            // 找回密码URL过期
    const FRONTEND_RESPWD_USER_EMAIL_CODE = 10107;                              // 找回密码code错误
    const FRONTEND_RESPWD_USER_INFO_ERROR = 10108;                              // 重置密码信息为空
    const FRONTEND_RESPWD_USER_PWD_DIFF = 10109;                                // 两次密码不一致
    const FRONTEND_LOGIN_USER_EMAIL_NOT_FIND = 10110;                           // 密码找回信息为空

    const FRONTEND_SIGNUP_MOBILE_ERROR = 10200;                                 // 手机号码错误
    const FRONTEND_SIGNUP_MOBILE_REGISTERED = 10201;                            // 手机号码已注册
    const FRONTEND_SIGNUP_CODE_ERROR = 10202;                                   // 验证码错误
    const FRONTEND_SIGNUP_NAME_LEN_ERROR = 10203;                               // 用户名长度错误
    const FRONTEND_SIGNUP_PASSWORD_LEN_ERROR = 10204;                           // 密码长度错误
    const FRONTEND_SIGNUP_QQ_LEN_ERROR = 10205;                                 // QQ号长度错误
    const FRONTEND_SIGNUP_AGENT_ERROR = 10206;                                  // 代理商错误
    const FRONTEND_SIGNUP_NAME_REGISTERED = 10207;                              // 用户名（昵称）已被注册

    // 重置密码
    const FRONTEND_RESETPWD_MOBILE_ERROR = 10220;                               // 手机号码错误
    const FRONTEND_RESETPWD_CODE_ERROR = 10221;                                 // 验证码错误
    const FRONTEND_RESETPWD_PASSWORD_LEN_ERROR = 10222;                         // 密码长度错误

    // 用户端，注单错误号
    const FRONTEND_BET_AMOUNT_NOT_POSITIVE_INT = 10001;                         // 注单金额必须为整数
    const FRONTEND_BET_AMOUNT_INVALID = 10002;                                  // 注单金额有误，不在限额区间内

    const FRONTEND_BET_USER_INVALID = 10003;                                    // 用户状态未激活
    const FRONTEND_BET_AGENT_INVALID = 10004;                                   // 用户所属代理已被冻结

    const FRONTEND_BET_MATCH_INVALID = 10005;                                   // 赛事无效
    const FRONTEND_BET_RACE_INVALID = 10006;                                    // 比赛无效
    const FRONTEND_BET_HANDICAP_INVALID = 10007;                                // 盘口已关闭或结束
    const FRONTEND_BET_AGENT_HANDICAP_CLOSE = 10008;                            // 代理商已关闭该盘口

    const FRONTEND_BET_HANDICAP_MONEY_MAX = 10009;                              // 已达盘口总限额
    const FRONTEND_BET_RACE_MONEY_MAX = 10010;                                  // 已超过该场比赛最高下注金额
    const FRONTEND_BET_USER_MONEY_NOT_ENOUGH = 10011;                           // 账户余额不足，请充值

    const FRONTEND_BET_FREQUENCY = 10012;                                       // 下单频率过快
    const FRONTEND_BET_ODDS_INVALID = 10013;                                    // 注单赔率已过期

    const FRONTEND_BET_DEDUCT_MONEY_FAILED = 10014;                             // 下单扣钱失败

//    const FRONTEND_BET_STRING_COUNT_ERROR = 10015;                              // 串单数不对，至少3个，最多8个


    const FRONTEND_BET_USER_HANDICAP_MONEY_MAX = 10016;                         // 已达盘口单个会员总限额

    const FRONTEND_BET_STRING_RACE_ERROR = 10017;                               // 同一比赛不允许串注

    const FRONTEND_BET_STRING_TEAM_ERROR = 10018;                               // 同一战队相关盘口不允许串注

    const FRONTEND_ACTIVITY_SIGNED_REPEAT = 10301;                              // 活动签到重复
    const FRONTEND_ACTIVITY_SIGNIN_NOT_START = 10302;                           // 活动签到时间未开始
    const FRONTEND_ACTIVITY_SIGNIN_FINISHED = 10303;                            // 活动签到时间已结束

	const FRONTEND_SWITCH_LANG_FAILED = 10500;									// 切换语言失败

}