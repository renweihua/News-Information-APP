function getList() {
    var form = 'form.first_form',
        form_data = $(form).serialize();
    if ($(form).find('input[name=start_function]').val() == 0) return false;
    axios.post( $(form).attr('ajax-url'), form_data).then(function(data){
        laytpl(data, $('#form_list').html(), '.contentList', 'html', 'pageList', 0, {'form': 'form.first_form'}, getList, '', '', function(){
            window.onloadCallback();

           //全选的实现
            $('.check-all').on('ifChecked', function (event) {
                $('input[name="ids[]"]').iCheck('check');
            });
            $('.check-all').on('ifUnchecked', function (event) {
                $('input[name="ids[]"]').iCheck('uncheck');
            });
        });
    }).catch(function(res){
    });
}

function otherOperation(ajax_url, msg, request_data)
{
    request_data = (request_data == '' || request_data == undefined || request_data == 'undefined' || isEmpty(request_data)) ? 0 : request_data;
    if (!isNumber(request_data) && request_data <= 0) {
        layerMsg('操作表为必选项！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将“' + request_data + '”进行“' + msg + '”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, request_data).then(function(data){
            if (data.status == 1) layerMsg(data.msg, 1, layerJumpTime, 5, '', getList);
            else layerMsg(data.msg, 5, layerJumpTime);
        }).catch(function(res){
        });
    }, function () {
    });
}

function otherOperation111(table_name, ajax_url, msg)
{
    table_name = (table_name == '' || table_name == undefined || table_name == 'undefined' || isEmpty(table_name)) ? 0 : table_name;
    if (!isNumber(table_name) && table_name <= 0) {
        layerMsg('操作表为必选项！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将“' + table_name + '”进行“' + msg + '”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'tables': table_name
        }).then(function(data){
            if (data.status == 1) layerMsg(data.msg, 1, layerJumpTime, 5, '', getList);
            else layerMsg(data.msg, 5, layerJumpTime);
        }).catch(function(res){
        });
    }, function () {
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