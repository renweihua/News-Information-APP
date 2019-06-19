/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} config_id [description]
 * @return             {[type]}          [description]
 */
function delOperation(config_id, ajax_url) {
    config_id = (config_id == '' || config_id == undefined || config_id == 'undefined' || isEmpty(config_id)) ? 0 : config_id;
    if (!isNumber(config_id) && config_id <= 0) {
        layerMsg('配置的Id必须为数字类型！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将配置“' + $('tr#id_' + config_id).attr('name') + '”进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'config_id': config_id
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

function pushRefreshConfig(api_refresh_url)
{
    layer.confirm('确定要将配置同步到配置文件吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( api_refresh_url ).then(function(data){
            if (data.status == 1) layerMsg(data.msg, 1, layerJumpTime);
            else layerMsg(data.msg, 5, layerJumpTime);
        }).catch(function(res){
        });
    }, function () {
    });
}