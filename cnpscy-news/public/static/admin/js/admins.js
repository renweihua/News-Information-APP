if (is_detail == 1) { //详情页面
    $.validator.setDefaults({
        highlight:function(e){
            $(e).closest(".form-group").removeClass("has-success").addClass("has-error")
        },
        success:function(e){
            e.closest(".form-group").removeClass("has-error").addClass("has-success")
        },
        errorElement:"span",
        errorPlacement:function(e,r){
            e.appendTo(r.is(":radio")||r.is(":checkbox")?r.parent().parent().parent():r.parent())
        },
        errorClass:"help-block m-b-none",validClass:"help-block m-b-none"
    }),
    $().ready(function(){
        var e="<i class='fa fa-times-circle'></i> ";
        $("#detail").validate({
            rules:{
                admin_name:{required:!0,minlength:2},
                // admin_email:{required:!0,admin_email:!0},
                topic:{required:"#newsletter:checked",minlength:2},
            },
            messages:{
                admin_name:{required:e+"请输入您的用户名",minlength:e+"用户名必须两个字符以上"},
                // admin_email:e+"请输入您的E-mail",
            }
        });
    });
}


/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} admin_id [description]
 * @return             {[type]}          [description]
 */
function delOperation( admin_id, ajax_url) {
    admin_id = (admin_id == '' || admin_id == undefined || admin_id == 'undefined' || isEmpty(admin_id)) ? 0 : admin_id;
    if (!isNumber(admin_id) && admin_id <= 0) {
        layerMsg('管理员Id必须为数字类型！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将管理员“' + $('tr#id_' + admin_id).attr('name') + '”进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'admin_id': admin_id
        }).then(function(data){
            if (data.status == 1){
                $('form.first_form input[name=page]').val(parseInt($('form.first_form input[name=page]').val() - 1));
                layerMsg(data.msg, 1, layerJumpTime, 5, '', getList);
            } else layerMsg(data.msg, 5, layerJumpTime);
        }).catch(function(res){
        });
    }, function () {
    });
}

function getRolesCheckboxList(select_id, unique) {
    select_id = isEmpty(select_id) ? [] : select_id;
    unique = (unique == '' || unique == undefined || unique == 'undefined' || isEmpty(unique)) ? $('form select[name=parent_id]') : unique;
    axios.post( API_ADMIN_PREFIX + '/roles/getSelectLists', {
    }).then(function(data){
        if (data.status == 1) {
            data = data.data, html = '', selected = '';
            if (!isEmpty(data)) {
                for (let i = 0; i < data.length; i++) {
                    html += ' <label>';
                    html += '     <input name="role_id[]" class="ace ace-checkbox-2" type="checkbox" value="' + data[i].role_id + '" ' + ( $.inArray(data[i].role_id, select_id) >= 0 ? 'checked' : '' ) + ' />';
                    html += '     <span class="lbl"> <strong>' + data[i].role_name + '</strong></span>';
                    html += ' </label>';
                }
            }
            $(unique).html(html);
        }
    }).catch(function(res){
    });
}

/**
 * [getRolesGroupNameList]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:管理员列表，通过角色组，获取所有用的角色列表
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} roles [description]
 * @return             {[type]}       [description]
 */
function getRolesGroupNameList(roles)
{
    let role_names = new Array();
    for (let i = 0; i < roles.length; i++) {
        role_names.push(roles[i].role_name);
    }
    return role_names;
}