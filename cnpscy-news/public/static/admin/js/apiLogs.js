/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} log_id [description]
 * @return             {[type]}          [description]
 */
function delOperation(log_id, ajax_url) {
    log_id = (log_id == '' || log_id == undefined || log_id == 'undefined' || isEmpty(log_id)) ? 0 : log_id;
    if (!isNumber(log_id) && log_id <= 0) {
        layerMsg('Api日志的Id必须为数字类型！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将Api所属“' + $('tr#id_' + log_id).attr('name') + '”的日志进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'log_id': log_id
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