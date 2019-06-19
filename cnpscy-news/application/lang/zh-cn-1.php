<?php

/**
 * 公共语言包
 * @author   Devil
 * @blog     http://gong.gg/
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
        1 => array('id' => 1, 'name' => '女'),
        2 => array('id' => 2, 'name' => '男'),
    ),

    // 是否启用
    'common_is_enable_tips' => array(
        0 => array('id' => 0, 'name' => '未启用'),
        1 => array('id' => 1, 'name' => '已启用'),
    ),
    'common_is_enable_list' => array(
        0 => array('id' => 0, 'name' => '不启用'),
        1 => array('id' => 1, 'name' => '启用', 'checked' => true),
    ),

    // 是否显示
    'common_is_show_list' => array(
        0 => array('id' => 0, 'name' => '不显示'),
        1 => array('id' => 1, 'name' => '显示', 'checked' => true),
    ),

    // 状态
    'common_state_list' => array(
        0 => array('id' => 0, 'name' => '不可用'),
        1 => array('id' => 1, 'name' => '可用', 'checked' => true),
    ),

    // 是否满屏
    'common_is_full_screen_list' => array(
        0 => array('id' => 0, 'name' => '否', 'checked' => true),
        1 => array('id' => 1, 'name' => '是'),
    ),

    // excel编码列表
    'common_excel_charset_list' => array(
        0 => array('id' => 0, 'value' => 'utf-8', 'name' => 'utf-8', 'checked' => true),
        1 => array('id' => 1, 'value' => 'gbk', 'name' => 'gbk'),
    ),

    // 支付状态
    'common_order_pay_status' => array(
        0 => array('id' => 0, 'name' => '待支付', 'checked' => true),
        1 => array('id' => 1, 'name' => '已支付'),
        2 => array('id' => 2, 'name' => '已退款'),
    ),

    // 用户端 - 订单管理
    'common_order_user_status' => array(
        0 => array('id' => 0, 'name' => '待确认', 'checked' => true),
        1 => array('id' => 1, 'name' => '待付款'),
        2 => array('id' => 2, 'name' => '待发货'),
        3 => array('id' => 3, 'name' => '待收货'),
        4 => array('id' => 4, 'name' => '已完成'),
        5 => array('id' => 5, 'name' => '已取消'),
        6 => array('id' => 6, 'name' => '已关闭'),
    ),

    // 后台管理 - 订单管理
    'common_order_admin_status' => array(
        0 => array('id' => 0, 'name' => '待确认', 'checked' => true),
        1 => array('id' => 1, 'name' => '已确认/待支付'),
        2 => array('id' => 2, 'name' => '已支付/待发货'),
        3 => array('id' => 3, 'name' => '已发货/待收货'),
        4 => array('id' => 4, 'name' => '已完成'),
        5 => array('id' => 5, 'name' => '已取消'),
        6 => array('id' => 6, 'name' => '已关闭'),
    ),

    // 优惠券类型
    'common_coupon_type' => array(
        0 => array('id' => 0, 'name' => '缤纷活动', 'checked' => true),
        1 => array('id' => 1, 'name' => '注册送'),
    ),

    // 用户优惠券状态
    'common_user_coupon_status' => array(
        0 => array('id' => 0, 'name' => '未使用', 'checked' => true),
        1 => array('id' => 1, 'name' => '已使用'),
        2 => array('id' => 2, 'name' => '已过期'),
    ),

    // 所属平台
    'common_platform_type' => array(
        'pc' => array('value' => 'pc', 'name' => 'PC网站'),
        'h5' => array('value' => 'h5', 'name' => 'H5手机网站'),
        // 'app' => array('value' => 'app', 'name' => 'APP'),
        // 'alipay' => array('value' => 'alipay', 'name' => '支付宝小程序'),
        // 'weixin' => array('value' => 'weixin', 'name' => '微信小程序'),
        // 'baidu' => array('value' => 'baidu', 'name' => '百度小程序'),
    ),

    // 小程序url跳转类型
    'common_jump_url_type' => array(
        0 => array('value' => 0, 'name' => 'WEB页面'),
        // 1 => array('value' => 1, 'name' => '内部页面(小程序或APP内部地址)'),
        // 2 => array('value' => 2, 'name' => '外部小程序(同一个主体下的小程序appid)'),
    ),

    // 扣除库存规则
    'common_deduction_inventory_rules_list' => array(
        0 => array('id' => 0, 'name' => '订单确认成功', 'checked' => true),
        1 => array('id' => 1, 'name' => '订单支付成功'),
        2 => array('id' => 2, 'name' => '订单发货'),
    ),

    // 是否已读
    'common_is_read_list' => array(
        0 => array('id' => 0, 'name' => '未读', 'checked' => true),
        1 => array('id' => 1, 'name' => '已读'),
    ),

    // 消息类型
    'common_message_type_list' => array(
        0 => array('id' => 0, 'name' => '默认', 'checked' => true),
    ),

    // 支付类型
    'common_pay_type_list' => array(
        0 => array('id' => 0, 'name' => '支付宝', 'checked' => true),
        1 => array('id' => 1, 'name' => '微信'),
    ),

    // 业务类型
    'common_business_type_list' => array(
        0 => array('id' => 0, 'name' => '默认', 'checked' => true),
        1 => array('id' => 1, 'name' => '订单'),
    ),

    // 用户投诉状态
    'common_complaint_status_list' => array(
        0 => array('id' => 0, 'name' => '未处理', 'checked' => true),
        1 => array('id' => 1, 'name' => '已处理'),
        2 => array('id' => 2, 'name' => '异常'),
    ),

    // 是否上架/下架
    'common_is_shelves_list' => array(
        0 => array('id' => 0, 'name' => '已下架'),
        1 => array('id' => 1, 'name' => '已上架', 'checked' => true),
    ),

    // 是否
    'common_is_text_list' => array(
        0 => array('id' => 0, 'name' => '否', 'checked' => true),
        1 => array('id' => 1, 'name' => '是'),
    ),

    // 是否新窗口打开
    'common_is_new_window_open_list' => array(
        0 => array('id' => 0, 'name' => '否', 'checked' => true),
        1 => array('id' => 1, 'name' => '是'),
    ),

    // 导航数据类型
    'common_nav_type_list' => array(
        'custom' => '自定义',
        'article' => '文章',
        'customview' => '自定义页面',
        'goods_category' => '商品分类',
    ),

    // 是否含头部
    'common_is_header_list' => array(
        0 => array('id' => 0, 'name' => '否'),
        1 => array('id' => 1, 'name' => '是', 'checked' => true),
    ),

    // 是否含尾部
    'common_is_footer_list' => array(
        0 => array('id' => 0, 'name' => '否'),
        1 => array('id' => 1, 'name' => '是', 'checked' => true),
    ),

    // 用户状态
    'common_user_status_list' => array(
        0 => array('id' => 0, 'name' => '正常', 'checked' => true),
        1 => array('id' => 1, 'name' => '禁止发言', 'tips' => '用户被禁止发言'),
        2 => array('id' => 2, 'name' => '禁止登录', 'tips' => '用户被禁止登录'),
    ),

    // 是否已评价
    'common_comments_status_list' => array(
        0 => array('value' => 0, 'name' => '待评价'),
        1 => array('value' => 1, 'name' => '已评价'),
    ),

    // 搜索框下热门关键字类型
    'common_search_keywords_type_list' => array(
        0 => array('value' => 0, 'name' => '关闭'),
        1 => array('value' => 1, 'name' => '自动'),
        2 => array('value' => 2, 'name' => '自定义'),
    ),

    // 发送状态
    'common_send_status_list' => array(
        0 => array('value' => 0, 'name' => '未发送'),
        1 => array('value' => 1, 'name' => '发送中'),
        2 => array('value' => 2, 'name' => '发送成功'),
        3 => array('value' => 3, 'name' => '部分成功'),
        4 => array('value' => 4, 'name' => '发送失败'),
    ),

    // 发布状态
    'common_release_status_list' => array(
        0 => array('value' => 0, 'name' => '未发布'),
        1 => array('value' => 1, 'name' => '发布中'),
        2 => array('value' => 2, 'name' => '已发布'),
        3 => array('value' => 3, 'name' => '部分成功'),
        4 => array('value' => 4, 'name' => '发布失败'),
    ),

    // 处理状态
    'common_handle_status_list' => array(
        0 => array('value' => 0, 'name' => '未处理'),
        1 => array('value' => 1, 'name' => '处理中'),
        2 => array('value' => 2, 'name' => '已处理'),
        3 => array('value' => 3, 'name' => '部分成功'),
        4 => array('value' => 4, 'name' => '处理失败'),
    ),

    // 支付宝生活号菜单事件类型
    'common_alipay_life_menu_action_type_list' => array(
        0 => array('value' => 0, 'out_value' => 'out', 'name' => '事件型菜单'),
        1 => array('value' => 1, 'out_value' => 'link', 'name' => '链接型菜单'),
        2 => array('value' => 2, 'out_value' => 'tel', 'name' => '点击拨打电话'),
        3 => array('value' => 3, 'out_value' => 'map', 'name' => '点击查看地图'),
        4 => array('value' => 4, 'out_value' => 'consumption', 'name' => '点击查看用户与生活号'),
    ),

    // 支付宝生活号菜单类型
    'common_alipay_life_menu_type_list' => array(
        0 => array('value' => 0, 'name' => '文字'),
        1 => array('value' => 1, 'name' => '文字+图标'),
    ),

    // 上下架选择
    'common_shelves_select_list' => array(
        0 => array('value' => 0, 'name' => '下架'),
        1 => array('value' => 1, 'name' => '上架', 'checked' => true),
    ),

    // app事件类型
    'common_app_event_type' => array(
        0 => array('value' => 0, 'name' => 'WEB页面'),
        // 1 => array('value' => 1, 'name' => '内部页面(小程序/APP内部地址)'),
        // 2 => array('value' => 2, 'name' => '外部小程序(同一个主体下的小程序appid)'),
        // 3 => array('value' => 3, 'name' => '跳转原生地图查看指定位置'),
        // 4 => array('value' => 4, 'name' => '拨打电话'),
    ),


    // 色彩值
    'common_color_list' => array(
        '',
        'am-badge-primary',
        'am-badge-secondary',
        'am-badge-success',
        'am-badge-warning',
        'am-badge-danger',
    ),

    // 文件上传错误码
    'common_file_upload_error_list' => array(
        1 => '文件大小超过服务器允许上传的最大值',
        2 => '文件大小超出浏览器限制，查看是否超过[站点设置->附件最大限制]',
        3 => '文件仅部分被上传',
        4 => '没有找到要上传的文件',
        5 => '没有找到服务器临时文件夹',
        6 => '没有找到服务器临时文件夹',
        7 => '文件写入失败',
        8 => '文件上传扩展没有打开',
    ),


    // 正则
    // 用户名
    'common_regex_username' => '^[A-Za-z0-9_]{5,18}$',

    // 用户名
    'common_regex_pwd' => '^.{6,18}$',

    // 手机号码
    'common_regex_mobile' => '^1((3|4|5|6|8|7|9){1}\d{1})\d{8}$',

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

    'common_a_regex_interval' => '^\d{1}\-\d{1}$',
    'common_b_regex_interval' => '^[0-9]{0,3}-[0-9]{0,3}$',


    // 颜色
    'common_regex_color' => '^(#([a-fA-F0-9]{6}|[a-fA-F0-9]{3}))?$',

    // id逗号隔开
    'common_regex_id_comma_split' => '^\d(\d|,?)*\d$',

    // url伪静态后缀
    'common_regex_url_html_suffix' => '^[a-z]{0,8}$',

    // 图片比例值
    'common_regex_image_proportion' => '^([1-9]{1}[0-9]?|[1-9]{1}[0-9]?\.{1}[0-9]{1,2}|100|0)?$',

    // 比例格式
    'common_proportional_format' => '^\d{2}\:\d{2}$',

    'common_number' => '^[0-9]$',

    'common_user_level_list' => array(
        1 => '普通会员',
        2 => '区代理',
    ),

    //审核状态类型
    'common_check_list' => [
        0 => ['id' => 0, 'name' => '待审核'],
        1 => ['id' => 1, 'name' => '通过'],
        2 => ['id' => 2, 'name' => '拒绝'],
    ],

    //审核状态类型
    'common_auth_status_list' => [
        0 => ['id' => 0, 'name' => '认证中'],
        1 => ['id' => 1, 'name' => '认证通过'],
        2 => ['id' => 2, 'name' => '认证失败'],
    ],

    // 用户积分 - 操作类型
    'common_integral_log_type_list' => array(
        0 => array('id' => 0, 'name' => '减少', 'checked' => true),
        1 => array('id' => 1, 'name' => '增加'),
    ),

    // 用户资金类型 - 操作类型
    'common_money_type_list' => array(
        0 => ['id' => 0, 'name' => '余额', 'checked' => true],
        1 => ['id' => 1, 'name' => '余额-不可提'],//余额2
        2 => ['id' => 2, 'name' => '积分'],
        // 3 => ['id' => 3, 'name' => '好通宝（会员）'],
        4 => ['id' => 4, 'name' => '好通宝（代理商）'],
        // 5 => ['id' => 5, 'name' => '好通宝（锁定）'], 去除了
        6 => ['id' => 6, 'name' => '代理奖金'],//余额3   代理商的直推与间推奖励，共享奖，物流管理奖，全国一条线奖励
        7 => ['id' => 7, 'name' => '平台抵扣分'],
        8 => ['id' => 8, 'name' => '累加券'],

        //新版本，需要把代理的其他奖励全部进行拆分，存在增减记录
        9 => ['id' => 9, 'name' => '共享奖'], //区代理的每天分红奖励
        10 => ['id' => 10, 'name' => '物流管理奖'],
        11 => ['id' => 11, 'name' => '拼团奖励'], //全国一条线 - 会员
        12 => ['id' => 12, 'name' => '出局奖励'], //寄售完成之后，余额的奖励 - 会员
        13 => ['id' => 13, 'name' => '直推代理奖'],
        14 => ['id' => 14, 'name' => '间推代理奖'],
        15 => ['id' => 15, 'name' => '市场扶持奖'],
    ),

    'common_withdrawals_type_list' => [
        1 => '余额提现',
        2 => '代理商提现',
        3 => '平台抵扣分',
    ],

    // 所属平台
    'common_banner_localtion_type' => [
        0 => '平台首页',
        1 => '商场首页',
        2 => '零售商场',
        3 => '批发商场',
        4 => '大宗商城',
        5 => '平台首页中部',
        6 => '商场首页-人气推荐（单张）',
        7 => '商场首页-限时新品（单张）',
    ],

    'common_pay_pament_type' => [
        1 => '积分',
        2 => '余额-不可提',
        3 => '余额',
        4 => '代理奖金',
        5 => '累加券',
    ],

    //品牌展示的位置
    'common_show_localtion_list' => [
        0 => '不展示',
        1 => '平台首页 - 精选活动',
        2 => '商场首页 - 精选专题',
    ],

    //问题反馈的类型选择【主要是前端遍历展示，只能用这个格式写，要不然拿不到第一个key，又不想写死】
    'common_feedback_type_list' => [
        0 => '账户问题',
        1 => '支付问题',
        2 => '其他问题',
    ],
    'common_exit_list' => [
        0 => '寄售中',
        1 => '已售出',
        2 => '待出售',
        3 => '预约寄售中',
    ],

    //订单类型
    'common_order_type_list' => [
        1 => '零售区',
        2 => '批发区',
        3 => '大件区',
    ],

    /**
     * 零售区商品价值 对于奖励配比设置
     */
    'common_retail_area_quota_list' => [
        0 => 1200,
        1 => 2400,
        2 => 4800,
    ],

    //提现到账的方式
    'common_withdrawal_type_mode_list' => [
        0 => '银行卡',
        1 => '支付宝',
        2 => '微信',
    ],


    /**
     * 抽奖活动的奖励类型
     */
    'common_luckydraw_activity_reward_category_list' => [
        0 => '无奖励',
        1 => '消费积分',
        2 => '抵扣积分',
        3 => '产品奖励',
    ],

    'common_finish_list' => [
        0 => '待处理',
        1 => '已结束',
    ],

);
?>