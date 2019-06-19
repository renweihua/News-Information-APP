/**
 * [unLoginOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:未登录的操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} res [description]
 * @return             {[type]}     [description]
 */
function unLoginOperation(res){
    localStorage.setItem("cnpscy_admin_token", '');
    localStorage.setItem("cnpscy_admin_info", '');
    layerMsg(res.msg, 5, layerJumpTime, 3, thisOrigin() + '/' + ADMIN_PREFIX);
}

/**
 * [isAdminLogin]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:检测是否已登录
 * @englishAnnotation:
 * @version:1.0
 * @return             {Boolean} [description]
 */
function isAdminLogin()
{
    axios.post( API_ADMIN_PREFIX + '/auth/me', {
        'token' : axios.defaults.headers.common['Authorization']
    }).then(function(res){
        switch(parseInt(res.status))
        {
            case 0:
                unLoginOperation(res);
                break;
            case 1:
                if(isEmpty(localStorage.getItem("cnpscy_admin_info"))) localStorage.setItem("cnpscy_admin_info", JSON.stringify(res.data));
                $('div.navbar-container div.navbar-header img.admin_head').attr('src', res.data.admin_head);
                $('div.navbar-container div.navbar-header span.admin_name').html(res.data.admin_name);
                break;
        }
    }).catch(function(res){
    });
}

function logout()
{
    axios.post( API_ADMIN_PREFIX + '/auth/logout', {
        'token' : axios.defaults.headers.common['Authorization']
    }).then(function(res){
        layerMsg(res.msg, 1, layerJumpTime, 3, thisOrigin() + '/' + ADMIN_PREFIX);
    }).catch(function(res){
    });
}

/**
 * [leftMenuListShow]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:菜单列表的展示
 * @englishAnnotation:
 * @version:1.0
 * @return             {[type]} [description]
 */
function leftMenuListShow() {
    axios.post( API_ADMIN_PREFIX + '/adminmenus/getAdminMenuLists', {
    }).then(function(data){
        switch(parseInt(data.status))
        {
            case 1:
                data = data.data, html = _child = _erchild = '';
                if (!isEmpty(data)) {
                    for (var i = 0; i < data.length; i++) {
                        html += '<li>';
                        if (isEmpty(data[i]['_child'])) html += '<a href="' + checkAdminUrl(data[i]['menu_url']) + '" nav="' + checkAdminUrl(data[i]['menu_url']) + '">';
                        else html += '<a href="javascript:;" nav="' + checkAdminUrl(data[i]['menu_url']) + '" class="dropdown-toggle">';
                        if (!isEmpty(data[i]['menu_icon'])) html += '        <i class="' + data[i]['menu_icon'] + '"></i>';
                        html += '        <span class="menu-text"> ' + data[i]['menu_name'] + ' </span>';
                        if (!isEmpty(data[i]['_child'])) html += '<b class="arrow icon-angle-down"></b>';
                        html += '    </a>';
                        if (!isEmpty(data[i]['_child'])) {
                            _child = data[i]['_child'];
                            if (!isEmpty(_child)) {
                                html += '    <ul class="submenu">';
                                for (let j = 0; j < _child.length; j++) {
                                    html += '        <li>';
                                    if (isEmpty(_child[j]['_child'])) html += '<a href="' + checkAdminUrl(_child[j]['menu_url']) + '" nav="' + checkAdminUrl(_child[j]['menu_url']) + '">';
                                    else html += '<a href="javascript:;" class="dropdown-toggle" nav="' + checkAdminUrl(_child[j]['menu_url']) + '">';
                                    if (!isEmpty(_child[j]['menu_icon'])) html += '<i class="' + _child[j]['menu_icon'] + '"></i>';
                                    html += '                ' + _child[j]['menu_name'];
                                    if (!isEmpty(_child[j]['_child'])) html += '<b class="arrow icon-angle-down"></b>';
                                    html += '            </a>';
                                    if (!isEmpty(_child[j]['_child'])) {
                                        _erchild = _child[j]['_child'];
                                        if (!isEmpty(_erchild)) {
                                            html += '            <ul class="submenu">';
                                            for (let k = 0; k < _erchild.length; k++) {
                                                html += '                <li>';
                                                html += '                    <a href="' + checkAdminUrl(_erchild[k]['menu_url']) + '" nav="' + checkAdminUrl(_erchild[k]['menu_url']) + '">';
                                                if (!isEmpty(_erchild[k]['menu_icon'])) html += '<i class="' + _erchild[k]['menu_icon'] + '"></i>';
                                                html += '                        ' + _erchild[k]['menu_name'];
                                                html += '                    </a>';
                                                html += '                </li>';
                                            }
                                            html += '            </ul>';
                                        }
                                    }
                                    html += '        </li>';
                                }
                                html += '    </ul>';
                            }
                        }
                        html += '</li>';
                    }
                    $('div#sidebar ul.nav-list').html(html);
                }
                highlight(window.location.href);
                break;
        }
    }).catch(function(res){
    });
}

function checkAdminUrl(url)
{
    if(url == 'javascript:;' || isEmpty(url)) return 'javascript:;';
    else return '/' + ADMIN_PREFIX + '/' + url;
    // else return ADMIN_PREFIX + url;
}


function highlight(url) {
    url = isEmpty(url) ? 'index.php' : url;
    /**
     * 左侧栏目的高亮显示
     */
    var ele = $('#sidebar .nav-list').find('a[nav="' + url + '"]');
    if(!ele.length)
    {
        $('#sidebar .nav-list li').each(function(){
            if(url.indexOf($(this).find('a').attr('nav')) >= 0) ele = $(this).find('a');
        });
    }
    ele.closest('li').addClass('active');
    ele.parent().parent().parent().addClass('active open');
    /**
     * 展示当前页面的路径
     */
    var maxCategory = ele.parent().parent().parent().find('a.dropdown-toggle'),
        maxIconClass = maxCategory.find('i').attr('class'),
        maxCategoryName = maxCategory.find('span.menu-text').html(),
        smallName = ele.html();
    if (url != 'index.php') {
        if(maxCategoryName == undefined) //三级
        {
            centerName = maxCategory.html(),
                maxCategory = maxCategory.parent().parent().parent().find('a.dropdown-toggle'),
                maxIconClass = maxCategory.find('i').attr('class'),
                maxCategoryName = maxCategory.find('span.menu-text').html();
                maxCategory.parent().parent().parent().addClass('active open');
            html = '<li>';
            html += '    <i class="' + maxIconClass + ' home-icon"></i>';
            html += '    <a href="javascript:;">' + maxCategoryName + '</a>';
            html += '</li>';
            html += '<li>';
            html += '    <a href="javascript:;">' + (centerName ? centerName.replace('<b class="arrow icon-angle-down"></b>', '') || "" : "" ) + '</a>';
            html += '</li>';
            html += '<li class="active">' + smallName + '</li>';
        }else{
            html = '<li>';
            html += '    <i class="' + maxIconClass + ' home-icon"></i>';
            html += '    <a href="javascript:;">' + maxCategoryName + '</a>';
            html += '</li>';
            html += '<li class="active">' + smallName + '</li>';
        }
    } else {
        html = '<li>';
        html += '    <a href="javascript:;">' + smallName + '</a>';
        html += '</li>';
    }
    $('div.main-content ul.breadcrumb').html(html);
}

function myMenuDropDown(parent_id, _this) {
    if ($('.parent_id_' + parent_id).hasClass('display_none')) {
        $('.parent_id_' + parent_id).removeClass('display_none');
        $(_this).removeClass('icon-angle-up').addClass('icon-angle-down');
    } else {
        $('.parent_id_' + parent_id).addClass('display_none');
        $(_this).removeClass('icon-angle-down').addClass('icon-angle-up');
    }
}

/**
 * [checkradioClass radio效果的切换]
 * @return {[type]} [description]
 */
function checkradioClass()
{
    $(document).ready(function(){
        $(".i-checks").iCheck({
            checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",
        });
    });
    $(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
}

/**
 * [checkboxClass checkbox批量设置]
 * @return {[type]} [description]
 */
function checkboxClass()
{
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
        var switchery = new Switchery(html,{color:"#1AB394"});
    });
}

/**
 * [loadingContentList iframe刷新]
 * @return {[type]} [description]
 */
function loadingContentList()
{
    var ary = window.top.document.getElementsByClassName('J_iframe');
    for (var i = 0; i < ary.length; i++) {
        if (ary[i].style.display != 'none') ary[i].contentWindow.location.reload(true);
    }
}