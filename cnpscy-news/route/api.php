<?php
$http_origin = !isset($_SERVER['HTTP_ORIGIN']) ? "*" : $_SERVER['HTTP_ORIGIN'];
$http_origin = (empty($http_origin) || $http_origin == null || $http_origin == 'null')  ? '*' : $http_origin;

$_SERVER['HTTP_ORIGIN'] = $http_origin;

header('Access-Control-Allow-Origin: ' . $http_origin);
header('Access-Control-Allow-Credentials: false');//【如果请求方存在域名请求，那么为true;否则为false】
header('Access-Control-Allow-Headers: Authorization, Origin, X-Requested-With, Content-Type, Access-Control-Allow-Headers, x-xsrf-token, Accept');
header('Access-Control-Allow-Methods: *');
 header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, PATCH');

if(strtoupper($_SERVER['REQUEST_METHOD'] ?? "") == 'OPTIONS'){  //vue 的 axios 发送 OPTIONS 请求，进行验证
    // return json([], 200);
    exit;
}

Route::group('api', function () { // 路由前缀
    Route::group(['name' => 'v1', 'prefix' => '@api/v1/'],function () { // 路由前缀
        Route::any('banners', 'Banner/index'); //首页 - 轮播图
        Route::any('article-categorys', 'ArticleCategorys/index'); //文章分类
        Route::any('articles', 'Articles/index'); //文章列表
        Route::any('articles-detail/:article_id', 'Articles/detail'); //文章详情
        Route::any('configs', 'Index/config'); //友情链接
        Route::any('friendlinks', 'Friendlink/index'); //友情链接
    });
})->allowCrossDomain();
    // ->header('Access-Control-Allow-Origin', ($_SERVER['HTTP_ORIGIN'] ?? '*'))
    // ->header('Access-Control-Allow-Credentials', 'true')
    // ->header('Access-Control-Allow-Headers', 'Authorization, Content-Type, Access-Control-Allow-Headers, x-xsrf-token')
    // ->header('Access-Control-Allow-Methods', '*')
    // ->allowCrossDomain();