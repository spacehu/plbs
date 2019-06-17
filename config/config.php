<?php

/** 系统目录信息 */
define('MANAGE_LIB', './lib');
define('MANAGE_MOD', './mod');
/** 网站信息 */
define('DOMAIN_NAME', 'localweb');
define('PORT', '80');
date_default_timezone_set('Asia/Shanghai');

/** 系统常量 */
define('DEFAULT_ACTION', 'main');
define('DEFAULT_ACTION_PATH', './action');
define('MANAGE_TEMPLATE', './template');
define('ERROR_405', './index.php?a=admin&m=error&message=405');
define('ERROR_404', './index.php?a=admin&m=error&message=404');


return $config = [
    'mysql' => include_once("parem.mysql." . ENV . ".php"), // 数据库设定 
    'env' => include_once("parem.env." . ENV . ".php"), // 配置设定 
    /** 常规系统设定 */
    'page_width' => 20, //分页
    'sysDelay' => 2, //系统延迟的秒数
    'shop_name' => 'AQ', //商店名称
    'cookie_pre' => '', //定义cookie的头部信息
    'cookie_life_time' => 1000 * 60 * 60 * 24/* */, //cookie存活的时间&session
    'config' => [
        'password' => 123456,
    ],
    'language' => [
        'key' => 'zh_cn', //定义默认语言
    ],
    'template' => MANAGE_TEMPLATE, //定义模板
    'lib' => MANAGE_LIB, //定义外部插件
    'mod' => MANAGE_MOD, //定义内部插件
    'port' => PORT, //定义端口号
    'url_rewrite' => true, //路由重写是否开启
    'restful_api' => [
        'isopen' => true, //是否开启restfulApi
        'path' => [
            /* public api */
            'GET /v1/ApiSystem-saveip.htm' => 'v1-ApiSystem-saveip',
            'GET /v1/ApiEnum-getRegion.htm' => 'v1-ApiEnum-getRegion',
            'GET /v1/ApiSms-sendSms.htm' => 'v1-ApiSms-sendSms',
            'GET /v1/ApiSms-sendRegistSms.htm' => 'v1-ApiSms-sendRegistSms',
            'GET /v2/ApiEnum-getRegion.htm' => 'v2-ApiEnum-getRegion',
            /* system api */
            'GET /v2/ApiHome-getCategory.htm' => 'v2-ApiHome-getCategory',
            'GET /v2/ApiHome-getBrand.htm' => 'v2-ApiHome-getBrand',
            'GET /v2/ApiHome-getAgeRange.htm' => 'v2-ApiHome-getAgeRange',
            'GET /v2/ApiHome-getSubjectCategory.htm' => 'v2-ApiHome-getSubjectCategory',
            /* base api */
            'GET /v2/ApiHome-slideShow.htm' => 'v2-ApiHome-slideShow',
            'GET /v2/ApiHome-article.htm' => 'v2-ApiHome-article',
            'GET /v2/ApiHome-article_detail.htm' => 'v2-ApiHome-article_detail',
            'POST /v2/ApiHome-saveSingle.htm' => 'v2-ApiHome-saveSingle',
            'POST /v2/ApiHome-saveComment.htm' => 'v2-ApiHome-saveComment',
            'GET /v2/ApiHome-getHelp.htm' => 'v2-ApiHome-getHelp',
            'POST /v2/ApiHome-saveHelp.htm' => 'v2-ApiHome-saveHelp',
            /* user api */
            'GET /v2/ApiHome-getBookedList.htm' => 'v2-ApiHome-getBookedList',
            'GET /v2/ApiHome-getPoints.htm' => 'v2-ApiHome-getPoints',
            'POST /v2/ApiHome-saveUserInfo.htm' => 'v2-ApiHome-saveUserInfo',
            'POST /v2/ApiHome-savePoint.htm' => 'v2-ApiHome-savePoint',
            'POST /v1/ApiAccount-regist.htm' => 'v1-ApiAccount-regist',
            'POST /v1/ApiAccount-login.htm' => 'v1-ApiAccount-login',
            /* need token api */
            'POST /v1/ApiAccount-logout.htm' => 'v1-ApiAccount-logout',
            'GET /v1/ApiAccount-center.htm' => 'v1-ApiAccount-center',
            'POST /v1/ApiAccount-saveUserInfo.htm' => 'v1-ApiAccount-save_user_info',
            //'POST /v1/ApiAccount-uploadPhoto.htm' => 'v1-ApiAccount-upload_photo',
            //'POST /v1/ApiAccount-savePhoto.htm' => 'v1-ApiAccount-save_photo',
            'POST /v1/ApiAccount-uploadPhoto.htm' => 'v1-ApiAccount-uploadPhoto',
            'GET /v1/ApiAccount-point.htm' => 'v1-ApiAccount-point',
            /* wechat api */
            'GET /v2/ApiWeChat-getWeChatInfo.htm' => 'v2-ApiWeChat-getWeChatInfo',
            'GET /v2/ApiWeChat-getAccessToken.htm' => 'v2-ApiWeChat-getAccessToken',
            'GET /v2/ApiWeChat-getJsApiTicket.htm' => 'v2-ApiWeChat-getTicket',
            /* applets api */
            'GET /v1/ApiApplets-photo.htm' => 'v1-ApiApplets-photo',
            'POST /v1/ApiApplets-saveWeChatInfo.htm' => 'v1-ApiApplets-saveWeChatInfo',
            /** v4 for plbs */
            'GET /v4/ApiSms-sendRegistSms.htm' => 'v4-ApiSms-sendRegistSms', //发验证码
            'GET /v4/ApiSms-sendSms.htm' => 'v4-ApiSms-sendSms', //发验证码
            'POST /v4/ApiAuth-checkPhone.htm' => 'v4-ApiAuth-checkPhone', //检查手机号是否已用
            'POST /v4/ApiAuth-register.htm' => 'v4-ApiAuth-register', //注册
            'POST /v4/ApiAuth-login.htm' => 'v4-ApiAuth-login', //登录
            'POST /v4/ApiAuth-sign.htm' => 'v4-ApiAuth-sign', //登录
            'DELETE /v4/ApiAuth-logout.htm' => 'v4-ApiAuth-logout', //登出
            'PUT /v4/ApiAuth-reset.htm' => 'v4-ApiAuth-reset', //重置密码
            'GET /v4/ApiAccount-info.htm' => 'v4-ApiAccount-info', //获取用户信息&员工信息&获取企业主信息
            'PUT /v4/ApiAccount-info.htm' => 'v4-ApiAccount-updateInfo', //修改用户信息&员工信息
            'POST /v4/ApiAccount-photo.htm' => 'v4-ApiAccount-uploadPhoto', //修改用户信息&员工信息
            'POST /v4/ApiAccount-enterprise.htm' => 'v4-ApiAccount-enterprise', //用户：绑定企业 成为员工
            'DELETE /v4/ApiAccount-enterprise.htm' => 'v4-ApiAccount-unEnterprise', //用户：解绑企业
            'GET /v4/ApiAccount-personalProgresses.htm' => 'v4-ApiAccount-personalProgresses', //企业主：员工学习进度
            'GET /v4/ApiAccount-courseProgresses.htm' => 'v4-ApiAccount-courseProgresses', //企业主：课程参与度
            'GET /v4/ApiAccount-testProgresses.htm' => 'v4-ApiAccount-testProgresses', //企业主：考试合格率
            'GET /v4/ApiAccount-enterpriseCourses.htm' => 'v4-ApiAccount-enterpriseCourses', //员工：企业专用课程列表
            'GET /v4/ApiAccount-courses.htm' => 'v4-ApiAccount-courses', //用户：参与过的课程列表
            'POST /v4/ApiAccount-course.htm' => 'v4-ApiAccount-course', //用户：参与课程
            'POST /v4/ApiAccount-lesson.htm' => 'v4-ApiAccount-lesson', //用户：参与课程
            'GET /v4/ApiAccount-favorites.htm' => 'v4-ApiAccount-favorites', //用户：收藏夹
            'GET /v4/ApiAccount-resume.htm' => 'v4-ApiAccount-getResume', //用户：简历
            'PUT /v4/ApiAccount-resume.htm' => 'v4-ApiAccount-updateResume', //用户：更新简历
            'GET /v4/ApiBase-categorys.htm' => 'v4-ApiBase-categorys', //获取分类列表
            'GET /v4/ApiCourse-courses.htm' => 'v4-ApiCourse-courses', //获取课程列表
            'GET /v4/ApiCourse-course.htm' => 'v4-ApiCourse-course', //获取课程详情
            'GET /v4/ApiCourse-lessons.htm' => 'v4-ApiCourse-lessons', //获取课时列表
            'GET /v4/ApiCourse-lesson.htm' => 'v4-ApiCourse-lesson', //获取课时详情
            'GET /v4/ApiCourse-tests.htm' => 'v4-ApiCourse-tests', //获取随机试题
            'POST /v4/ApiAccount-test.htm' => 'v4-ApiAccount-testing', //提交测试
            'GET /v4/ApiArticle-supports.htm' => 'v4-ApiArticle-supports', //文章列表
            'GET /v4/ApiArticle-support.htm' => 'v4-ApiArticle-support', //文章详细
            'POST /v4/ApiAccount-favorite.htm' => 'v4-ApiAccount-favorite', //收藏文章
            'POST /v4/ApiAccount-resume.htm' => 'v4-ApiAccount-sendResume', //投递简历
            
            'GET /v4/ApiExamination-examinations.htm' => 'v4-ApiExamination-examinations', //获取考卷列表
            'GET /v4/ApiExamination-examination.htm' => 'v4-ApiExamination-examination', //获取考卷详情
        ]
    ],
    'debug' => false, //调试器
    'wechat' => [
        'appid' => 'wx11eb371cd85adfd4',
        'secret' => '01ef7de58bc18da629d4ec33a62744f9',
        'index_url' => 'https://api.tigerhuclub.com',
    ],
    'actionList' => [
        'index' => '首页',
        'awards' => '简介',
        'works' => '作品',
        'album' => '专辑列表',
        'album_music' => '专辑音乐',
        'notice' => '行程列表',
        'article' => '活动列表',
        'article_detail' => '活动详情',
        'video' => '视频列表',
        'video_detail' => '视频播放页',
        'photo' => '照片列表',
        'single' => '单页',
    ],
    'pointInfo' => [
        'firstPhone' => 30,
        'share' => 20,
        'personalMax' => 500,
    ],
    'token' => [
        'server_id' => [
            'management' => 1,
            'business' => 2,
            'customer' => 3,
        ]
    ]
];


