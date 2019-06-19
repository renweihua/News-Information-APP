<?php
/**
 * 公共语言包
 * @version  0.0.1
 * @datetime 2016-12-01T21:51:08+0800
 */
return array(
    // 系统版本列表
    'common_system_version_list' => array(
        '1.1.0' => array('value' => '1.1.0', 'name' => 'v1.1.0'),
        '1.2.0' => array('value' => '1.2.0', 'name' => 'v1.2.0'),
        '1.3.0' => array('value' => '1.3.0', 'name' => 'v1.3.0'),
    ),

    // 性别
    'common_gender_list' => array(
        0 => array('id' => 0, 'name' => '保密', 'checked' => true),
        1 => array('id' => 1, 'name' => '男'),
        2 => array('id' => 2, 'name' => '女'),
    ),

    // 实名认证
    'common_auth_list' => [
        0 => ['id' => 0, 'name' => '待认证', 'checked' => true],
        1 => ['id' => 1, 'name' => '已认证'],
        2 => ['id' => 2, 'name' => '认证失败'],
    ],

    // 是否显示
    'common_is_show_list' => array(
        0 => array('id' => 0, 'name' => '不显示'),
        1 => array('id' => 1, 'name' => '显示', 'checked' => true),
    ),

    // 是否已读
    'common_is_read_list' => array(
        0 => array('id' => 0, 'name' => '未读', 'checked' => true),
        1 => array('id' => 1, 'name' => '已读'),
    ),

    //审核状态类型
    'common_check_list' => [
        0 => ['id' => 0, 'name' => '待审核'],
        1 => ['id' => 1, 'name' => '通过'],
        2 => ['id' => 2, 'name' => '拒绝'],
    ],

    // 正则
    // 用户名
    'common_regex_username' => '^[A-Za-z0-9_]{5,18}$',

    // 用户名
    'common_regex_pwd' => '^.{6,18}$',

    // 手机号码
    'common_regex_mobile' => '^1((3|4|5|6|7|8){1}\d{1})\d{8}$',

    // 座机号码
    'common_regex_tel' => '^\d{3,4}-?\d{8}$',

    // 电子邮箱
    'common_regex_email' => '^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$',

    // 身份证号码
    'common_regex_id_card' => '^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$',

    // 价格格式
    'common_regex_price' => '^([0-9]{1}\d{0,6})(\.\d{1,2})?$',

    // ip
    'common_regex_ip' => '^(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$',

    // url
    'common_regex_url' => '^http[s]?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$',

    // 控制器名称
    'common_regex_control' => '^[A-Za-z]{1}[A-Za-z0-9_]{0,29}$',

    // 方法名称
    'common_regex_action' => '^[A-Za-z]{1}[A-Za-z0-9_]{0,29}$',

    // 顺序
    'common_regex_sort' => '^[0-9]{1,3}$',

    // 日期
    'common_regex_date' => '^\d{4}-\d{2}-\d{2}$',

    // 分数
    'common_regex_score' => '^[0-9]{1,3}$',

    // 分页
    'common_regex_page_number' => '^[1-9]{1}[0-9]{0,2}$',

    // 时段格式 10:00-10:45
    'common_regex_interval' => '^\d{2}\:\d{2}\-\d{2}\:\d{2}$',

    // 颜色
    'common_regex_color' => '^(#([a-fA-F0-9]{6}|[a-fA-F0-9]{3}))?$',

    // id逗号隔开
    'common_regex_id_comma_split' => '^\d(\d|,?)*\d$',

    // url伪静态后缀
    'common_regex_url_html_suffix' => '^[a-z]{0,8}$',

    // 图片比例值
    'common_regex_image_proportion' => '^([1-9]{1}[0-9]?|[1-9]{1}[0-9]?\.{1}[0-9]{1,2}|100|0)?$',

    // 问题反馈的评分
    'common_feedback_socre_list' => array(
        '1' => array('value' => '1', 'name' => '非常不满意'),
        '2' => array('value' => '2', 'name' => '不满意'),
        '3' => array('value' => '3', 'name' => '一般'),
        '4' => array('value' => '4', 'name' => '满意'),
        '5' => array('value' => '5', 'name' => '非常满意'),
    ),


    /**
     * 自定义的语言
     */

    '_UN_LOGIN_STAUT_' => -99,//尚未登录的状态标识
    '_SYNCHRONOUS_SUCCESS_' => '同步成功！',

    //是否审核的筛选
    'cnpscy_is_check_list' => [
        -1 => '请选择审核状态',
        0 => '禁用',
        1 => '正常',
    ],

    //是否公开的筛选
    'cnpscy_is_public_list' => [
        -1 => '请选择公开状态',
        0 => '私密',
        1 => '公开',
    ],

    //管理员列表的筛选
    'cnpscy_admin_check_list' => [
        -1 => '请选择审核状态',
        0 => '审核',
        1 => '正常',
        2 => '禁用',
    ],

    'cnpscy_home_show_list' => [
        -1 => '请选择首页展示',
        0 => '否',
        1 => '是',
    ],


    'common_article_type' => [ //文章类型
        0 => '动态',
        1 => '文章',
    ],

    'common_coupon_type_list' => [
        -1 => '请选择优惠券类型',
        0 => '固定金额',
        1 => '比例',
    ],
);
?>