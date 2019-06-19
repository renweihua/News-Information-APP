/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} label_id [description]
 * @return             {[type]}          [description]
 */
function delOperation(label_id, ajax_url) {
    label_id = (label_id == '' || label_id == undefined || label_id == 'undefined' || isEmpty(label_id)) ? 0 : label_id;
    if (!isNumber(label_id) && label_id <= 0) {
        layerMsg('文章标签Id必须为数字类型！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将文章标签“' + $('tr#id_' + label_id).attr('name') + '”进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'label_id': label_id
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