/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} banner_id [description]
 * @return             {[type]}          [description]
 */
function delOperation(banner_id, ajax_url) {
    banner_id = (banner_id == '' || banner_id == undefined || banner_id == 'undefined' || isEmpty(banner_id)) ? 0 : banner_id;
    if (!isNumber(banner_id) && banner_id <= 0) {
        layerMsg('Banner的Id必须为数字类型！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将Banner“' + $('tr#id_' + banner_id).attr('name') + '”进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'banner_id': banner_id
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