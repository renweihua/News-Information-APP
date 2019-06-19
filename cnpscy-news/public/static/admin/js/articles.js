/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} article_id [description]
 * @return             {[type]}          [description]
 */
function delOperation(article_id, ajax_url) {
    article_id = (article_id == '' || article_id == undefined || article_id == 'undefined' || isEmpty(article_id)) ? 0 : article_id;
    if (!isNumber(article_id) && article_id <= 0) {
        layerMsg('文章Id必须为数字类型！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将文章“' + $('tr#id_' + article_id).attr('name') + '”进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'article_id': article_id
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

function getArticleLabelsCheckboxList(select_id, unique) {
    select_id = isEmpty(select_id) ? [] : select_id;
    unique = (unique == '' || unique == undefined || unique == 'undefined' || isEmpty(unique)) ? '' : unique;
    if(isEmpty(unique)) return;
    axios.post( API_ADMIN_PREFIX + '/articleLabels/getSelectLists', {
    }).then(function(data){
        if (data.status == 1) {
            data = data.data, html = '', selected = '';
            if (!isEmpty(data)) {
                for (let i = 0; i < data.length; i++) {
                    html += ' <label>';
                    html += '     <input name="label_id[]" class="ace ace-checkbox-2" type="checkbox" value="' + data[i].label_id + '" ' + ( $.inArray(data[i].label_id, select_id) >= 0 ? 'checked' : '' ) + ' />';
                    html += '     <span class="lbl"> <strong>' + data[i].label_name + '</strong></span>';
                    html += ' </label>';
                }
            }
            $(unique).html(html);
        }
    }).catch(function(res){
    });
}

function getArticleLabelsGroupNameList(labels)
{
    let label_names = new Array();
    for (let i = 0; i < labels.length; i++) {
        label_names.push(labels[i].label_name);
    }
    return label_names;
}