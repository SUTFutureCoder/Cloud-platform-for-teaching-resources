<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 15-12-5
 * Time: 下午3:40
 */
//常量、全局变量存储库
class CoreConst{
    //平台常量
    const PLATFORM_TEST     = -1;
    const PLATFORM_UNKNOWN  = 0;
    const PLATFORM_ADMIN    = 1;
    const PLATFORM_PC       = 2;
    const PLATFORM_MOBILE   = 3;

    public static $platform = array(
        self::PLATFORM_TEST,
        self::PLATFORM_ADMIN,
        self::PLATFORM_PC,
        self::PLATFORM_MOBILE,
    );

    //教学资源云平台核心参数
    //UUID
    const LOG_UUID          = 'uuid:log';
    const COURSE_UUID       = 'uuid:course';
    const COURSE_GROUP_UUID = 'uuid:course_group';
    public static $uuid = array(
        self::LOG_UUID,
        self::COURSE_UUID,
        self::COURSE_GROUP_UUID,
    );

    //模块列表，用于打LOG等
    const MODULE_KERNEL  = 'kernel';
    const MODULE_ACCOUNT = 'account';
    const MODULE_ALUMNI  = 'alumni';
    const MODULE_DATABASE  = 'database';
    const MODULE_WEBSOCKET = 'websocket';
    const MODULE_SAL     = 'SAL';
    const MODULE_EMAIL   = 'EMAIL';
    const MODULE_BOS     = 'BOS';

    public static $moduleList = array(
        self::MODULE_ACCOUNT,
        self::MODULE_ALUMNI,
        self::MODULE_KERNEL,
        self::MODULE_DATABASE,
        self::MODULE_WEBSOCKET,
        self::MODULE_SAL,
        self::MODULE_EMAIL,
        self::MODULE_BOS,
    );

    //log是否打开
    const MNEMOSYNE_LOG = 1;

    //全局静态变量
    public static $userId = 0;
    public static $userPlatform = '';

    //TOKEN
    const TOKEN_EXPIRE  = 86400;
    const TOKEN_COOKIES = 'm_token';
    const TOKEN_SIGNATURE_COOKIES = 'm_sign';
}