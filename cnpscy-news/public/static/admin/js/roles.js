/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} role_id [description]
 * @return             {[type]}          [description]
 */
function delOperation(role_id, ajax_url) {
    role_id = (role_id == '' || role_id == undefined || role_id == 'undefined' || isEmpty(role_id)) ? 0 : role_id;
    if (!isNumber(role_id) && role_id <= 0) {
        layerMsg('角色Id必须为数字类型！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将角色“' + $('tr#id_' + role_id).attr('name') + '”进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'role_id': role_id
        }).then(function(data){
            if (data.status == 1){
                $('form.first_form input[name=page]').val(parseInt($('form.first_form input[name=page]').val() - 1));
                layerMsg(data.msg, 1, layerJumpTime, 5, '', getList);
            } else layerMsg(data.msg, 5, layerJumpTime);
        }).catch(function(data){
        });
    }, function () {
    });
}

function getRoleSelectMenusList(ajax_url, select_id) {
    select_id = isEmpty(select_id) ? [] : select_id;
    axios.post( ajax_url, {
    }).then(function(data){
        if (data.status == 1) {
            data = data.data, html = '', selected = '';
            if (!isEmpty(data)) {
                for (let i = 0; i < data.length; i++) {
                    html += '<tr>';
                    html += '    <td class="first_td">';
                    html += '        <label>';
                    html += '            <input name="menu_rules[]" class="ace ace-checkbox-2" type="checkbox" value="' + data[i].menu_id + '" id="' + data[i].menu_id + '" data-id="' + data[i].menu_id + '" onclick="checkedBox( this )" ' + ( $.inArray(data[i].menu_id, select_id) >= 0 ? 'checked=""' : '' ) + ' />';
                    html += '            <span class="lbl"> <strong>' + data[i].menu_name  + '</strong></span>';
                    html += '        </label>';
                    html += '    </td>';
                    html += '</tr>';
                    if(!isEmpty(data[i]._child))
                    {
                        let two_child = data[i]._child;
                        for (let j = 0; j < two_child.length; j++) {
                            html += '<tr>';
                            html += '    <td class="two_td">';
                            html += '        <label>';
                            html += '            <input name="menu_rules[]" class="ace ace-checkbox-2" type="checkbox" value="' + two_child[j].menu_id + '" id="' + two_child[j].menu_id + '" data-id="' + data[i].menu_id + '-' + two_child[j].menu_id + '" onclick="checkedBox( this )" ' + ( $.inArray(two_child[j].menu_id, select_id) >= 0 ? 'checked=""' : '' ) + ' />';
                            html += '            <span class="lbl"> ' + two_child[j].menu_name  + '</span>';
                            html += '        </label>';
                            html += '    </td>';
                            html += '</tr>';
                            if(!isEmpty(two_child[j]._child))
                            {
                                let three_child = two_child[j]._child;
                                for (let k = 0; k < three_child.length; k++) {
                                    html += '<tr>';
                                    html += '    <td class="three_td">';
                                    html += '        <label>';
                                    html += '            <input name="menu_rules[]" class="ace ace-checkbox-2" type="checkbox" value="' + three_child[k].menu_id + '" id="' + three_child[k].menu_id + '" data-id="' + data[i].menu_id + '-' + two_child[j].menu_id + '-' + three_child[k].menu_id + '" onclick="checkedBox( this )" ' + ( $.inArray(three_child[k].menu_id, select_id) >= 0 ? 'checked=""' : '' ) + ' />';
                                    html += '            <span class="lbl"> ' + three_child[k].menu_name  + '</span>';
                                    html += '        </label>';
                                    html += '    </td>';
                                    html += '</tr>';
                                    if(!isEmpty(three_child[k]._child))
                                    {
                                        let four_child = three_child[k]._child;
                                        for (let l = 0; l < four_child.length; l++) {
                                            html += '<tr>';
                                            html += '    <td class="four_td">';
                                            html += '        <label>';
                                            html += '            <input name="menu_rules[]" class="ace ace-checkbox-2" type="checkbox" value="' + four_child[l].menu_id + '"  id="' + four_child[l].menu_id + '" data-id="' + data[i].menu_id + '-' + two_child[j].menu_id + '-' + three_child[k].menu_id + '-' + four_child[l].menu_id + '" onclick="checkedBox( this )" ' + ( $.inArray(four_child[l].menu_id, select_id) >= 0 ? 'checked=""' : '' ) + ' />';
                                            html += '            <span class="lbl"> ' + four_child[l].menu_name  + '</span>';
                                            html += '        </label>';
                                            html += '    </td>';
                                            html += '</tr>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $('form#detail table tbody').append(html);
        }
    }).catch(function(data){
    });
}

/**
 * [checkedBox]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:选中效果
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} _this [description]
 * @return             {[type]}       [description]
 */
function checkedBox(_this)
{
    let dataid = $(_this).attr('data-id'),
        checked = $(_this).prop('checked'),
        menu_id = $(_this).val();
    let id_ary = dataid.split('-');

    if(checked == true)
    {
        for (var i = 0; i < id_ary.length; i++) $('form#detail table input#' + id_ary[i]).prop('checked', true);
    }else{
        $('form#detail table input').each(function(){
            let _dataid = $(this).attr('data-id');
            if(isEmpty(_dataid)) return;
            if($.inArray(menu_id, _dataid.split('-')) >= 0) $(this).prop('checked', false);
        });
    }
}

function checkAll(status)
{
    $('form#detail table input').prop('checked', status);
    if(status){
        $('form#detail table input.checkAll-true').prop('checked', status);
        $('form#detail table input.checkAll-false').prop('checked', !status);
    } else if(!status){
        $('form#detail table input.checkAll-true').prop('checked', status);
        $('form#detail table input.checkAll-false').prop('checked', !status);
    }
    
}