$('.page-tabs-content').change(function(){
    // console.log($(this).html());
});

/**
 * 列表
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:
 * @englishAnnotation:
 * @version:1.0
 * @return             {[type]} [description]
 */
function getList() {
    console.log(123154);
    var form = 'form.first_form',
        form_data = $(form).serialize();
    if ($(form).find('input[name=start_function]').val() == 0) return false;
    axios.post( $(form).attr('ajax-url'), form_data).then(function(data){
        if (parseInt(data.data) == 0) $('div#pageList').hide();
        else $('div#pageList').show();
        // laytpl(data, $('#form_list').html(), '.contentList', 'html', 'pageList', 1, {'form': 'form.first_form'}, isEmpty(callback) ? getList : {0:getList, 1:callback});
        laytpl(data, $('#form_list').html(), '.contentList', 'html', 'pageList', 1, {'form': 'form.first_form'}, getList, '', '', window.onloadCallback);
        $("table.table").footable();
    }).catch(function(res){
    });
}

/**
 * [detail]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:详情
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} admin_id [description]
 * @return             {[type]}          [description]
 */
function getDetail(unique_id, unique_name, callback) {
    if (parseInt(unique_id) == 0) {
        laytplrender([], $('#detail-template').html(), 'form#detail', 'html', callback);
        return;
    }
    axios.post( $('form').attr('get-ajax-url'),
        unique_name + "=" + unique_id
    ).then(function(data){
        laytplrender(data.data, $('#detail-template').html(), 'form#detail', 'html', callback);
    }).catch(function(res){
    });
}

/**
 * 新增 / 编辑 提交操作
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} _this [description]
 * @return             {[type]}       [description]
 */
function formSaveSubmit( _this ) {
    if ($(_this).find('div.has-error').length > 0) {
        layerMsg($(_this).find('div.has-error:eq(0) span:eq(1)').html().replace(/<.*?>/ig,""), 0);
        return;
    }
    axios.post( $(_this).attr('action'), $(_this).serialize()).then(function(data){
        switch (parseInt(data.status)) {
            case -1:
                layerMsg(data.msg, 5, layerJumpTime, 3, login_url);
            case 0:
                layerMsg(data.msg, 5, layerJumpTime);
                break;
            case 1:
                layerMsg(data.msg, 1, layerJumpTime, 3, $(_this).attr('return-url'));
                break;
            default:
                layerMsg(data.msg, 5);
                break;
        }
    }).catch(function(res){
    });
}

function changeFiledStatus(_this, url, _data)
{
    axios.post( url, _data).then(function(data){
        switch (parseInt(data.status)) {
            case -1:
                layerMsg(data.msg, 5, layerJumpTime, 3, login_url);
                $(_this).prop('checked', !$(_this).prop('checked'));
            case 0:
                layerMsg(data.msg, 5, layerJumpTime);
                $(_this).prop('checked', !$(_this).prop('checked'));
                break;
            case 1:
                layerMsg(data.msg, 1, layerJumpTime, 5, getList);
                break;
            default:
                layerMsg(data.msg, 5);
                $(_this).prop('checked', !$(_this).prop('checked'));
                break;
        }
    }).catch(function(res){
    });
}

/**
 * [getMenusSelectList]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:后台栏目的下拉菜单栏目
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} select_id    [description]
 * @param              {[type]} unique       [description]
 * @param              {String} default_html [description]
 * @return             {[type]}              [description]
 */
function getMenusSelectList(api_url, select_id, unique, default_html = '') {
    select_id = isEmpty(select_id) ? 0 : select_id;
    unique = (unique == '' || unique == undefined || unique == 'undefined' || isEmpty(unique)) ? $('form select[name=parent_id]') : unique;
    axios.post( api_url, {
    }).then(function(data){
        switch(parseInt(data.status))
        {
            case 1:
                data = data.data, html = '<option value="0">' + (default_html ? default_html : '--默认顶级--') + '</option>', selected = '';
                if (!isEmpty(data)) {
                    for (let i = 0; i < data.length; i++) {
                        if (select_id == data[i].menu_id) selected = 'selected';
                        else selected = '';
                        html += '<option value="' + data[i].menu_id + '" ' + selected + '>├' + data[i].menu_name + '</option>';
                        if(!isEmpty(data[i]._child))
                        {
                            let one_child = data[i]._child;
                            for (let j = 0; j < one_child.length; j++) {
                                if (select_id == one_child[j].menu_id) selected = 'selected';
                                else selected = '';
                                html += '<option value="' + one_child[j].menu_id + '" ' + selected + '>├───' + one_child[j].menu_name + '</option>';
                                if(!isEmpty(one_child[j]._child))
                                {
                                    let two_child = one_child[j]._child;
                                    for (let k = 0; k < two_child.length; k++) {
                                        if (select_id == two_child[k].menu_id) selected = 'selected';
                                        else selected = '';
                                        html += '<option value="' + two_child[k].menu_id + '" ' + selected + '>├──────' + two_child[k].menu_name + '</option>';
                                        if(!isEmpty(two_child[k]._child))
                                        {
                                            let three_child = two_child[k]._child;
                                            for (let l = 0; l < three_child.length; l++) {
                                                if (select_id == three_child[l].menu_id) selected = 'selected';
                                                else selected = '';
                                                html += '<option value="' + three_child[l].menu_id + '" ' + selected + '>├─────────' + three_child[l].menu_name + '</option>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                unique.html(html);
                break;
        }
    }).catch(function(res){
    });
}




/**
 * [getBlogMenusSelectList]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:前台栏目的下拉列表
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} select_id    [description]
 * @param              {[type]} unique       [description]
 * @param              {String} default_html [description]
 * @return             {[type]}              [description]
 */
function getBlogMenusSelectList(select_id, unique, default_html = '') {
    select_id = isEmpty(select_id) ? 0 : select_id;
    unique = (unique == '' || unique == undefined || unique == 'undefined' || isEmpty(unique)) ? $('form select[name=parent_id]') : unique;
    axios.post( API_ADMIN_PREFIX + '/blogmenus/getSelectLists', {
    }).then(function(data){
        switch(parseInt(data.status))
        {
            case 1:
                data = data.data, html = '<option value="0">' + (default_html ? default_html : '--默认顶级--') + '</option>', selected = '';
                if (!isEmpty(data)) {
                    for (let i = 0; i < data.length; i++) {
                        if (select_id == data[i].menu_id) selected = 'selected';
                        else selected = '';
                        html += '<option value="' + data[i].menu_id + '" ' + selected + '>├' + data[i].menu_name + '</option>';
                        if(!isEmpty(data[i]._child))
                        {
                            let one_child = data[i]._child;
                            for (let j = 0; j < one_child.length; j++) {
                                if (select_id == one_child[j].menu_id) selected = 'selected';
                                else selected = '';
                                html += '<option value="' + one_child[j].menu_id + '" ' + selected + '>├───' + one_child[j].menu_name + '</option>';
                                if(!isEmpty(one_child[j]._child))
                                {
                                    let two_child = one_child[j]._child;
                                    for (let k = 0; k < two_child.length; k++) {
                                        if (select_id == two_child[k].menu_id) selected = 'selected';
                                        else selected = '';
                                        html += '<option value="' + two_child[k].menu_id + '" ' + selected + '>├──────' + two_child[k].menu_name + '</option>';
                                        if(!isEmpty(two_child[j]._child))
                                        {
                                            let three_child = two_child[j]._child;
                                            for (let l = 0; l < three_child.length; l++) {
                                                if (select_id == three_child[l].menu_id) selected = 'selected';
                                                else selected = '';
                                                html += '<option value="' + three_child[l].menu_id + '" ' + selected + '>├─────────' + three_child[l].menu_name + '</option>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                unique.html(html);
                break;
        }
    }).catch(function(res){
    });
}


function getgoodCategorysSelectList(api_url, select_id, unique, default_html = '') {
    select_id = isEmpty(select_id) ? 0 : select_id;
    unique = (unique == '' || unique == undefined || unique == 'undefined' || isEmpty(unique)) ? $('form select[name=parent_id]') : unique;
    axios.post( api_url, {
    }).then(function(data){
        switch(parseInt(data.status))
        {
            case 1:
                data = data.data, html = default_html ? default_html : '<option value="0">--默认顶级--</option>', selected = '';
                if (!isEmpty(data)) {
                    for (let i = 0; i < data.length; i++) {
                        if (select_id == data[i].category_id) selected = 'selected';
                        else selected = '';
                        html += '<option value="' + data[i].category_id + '" ' + selected + '>├' + data[i].category_name + '</option>';
                        if(!isEmpty(data[i]._child))
                        {
                            let one_child = data[i]._child;
                            for (let j = 0; j < one_child.length; j++) {
                                if (select_id == one_child[j].category_id) selected = 'selected';
                                else selected = '';
                                html += '<option value="' + one_child[j].category_id + '" ' + selected + '>├───' + one_child[j].category_name + '</option>';
                                if(!isEmpty(one_child[j]._child))
                                {
                                    let two_child = one_child[j]._child;
                                    for (let k = 0; k < two_child.length; k++) {
                                        if (select_id == two_child[k].category_id) selected = 'selected';
                                        else selected = '';
                                        html += '<option value="' + two_child[k].category_id + '" ' + selected + '>├──────' + two_child[k].category_name + '</option>';
                                    }
                                }
                            }
                        }
                    }
                }
                unique.html(html);
                break;
        }
    }).catch(function(res){
    });
}

function getRoleSelectList(api_url, select_id, unique)
{
    select_id = isEmpty(select_id) ? 0 : select_id;
    unique = (unique == '' || unique == undefined || unique == 'undefined' || isEmpty(unique)) ? $('form select[name=role_id]') : unique;
    axios.post( api_url, {
    }).then(function(data){
        if (parseInt(data.status) == 1) {
            data = data.data, html = '<option value="0"> 请选择角色 </option>', selected = '';
            if (!isEmpty(data)) {
                for (let i = 0; i < data.length; i++) {
                    html += '<option value="' + data[i].role_id + '" ' + ( (data[i].role_id == select_id) ? 'selected' : '' ) + ' >' + data[i].role_name + '</option>';
                }
            }
            $(unique).html(html);
        }
    }).catch(function(res){
    });
}