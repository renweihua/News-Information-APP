/**
 * [detail]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:详情
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} menu_id [description]
 * @return             {[type]}          [description]
 */
function getDetail(menu_id) {
    axios.post( api_detail_url, {
        'menu_id': menu_id
    }).then(function(data){
        if (data.status == 1) {
            data = data.data;
            $('form#details input[name=menu_id]').val(data.menu_id);
            $('form#details input[name=menu_name]').val(data.menu_name);
            $('form#details input[name=menu_tpltype]').each(function(){
                if($(this).val() == data.menu_tpltype) $(this).attr('checked', true);
                else $(this).removeAttr('checked');
            });
            $('form#details input[name=menu_link]').val(data.menu_link);
            $('form#details select[name=menu_listtpl] option').each(function(){
                if($(this).val() == data.menu_listtpl) $(this).attr('selected', true);
                else $(this).removeAttr('selected');
            });
            $('form#details select[name=menu_detailtpl] option').each(function(){
                if($(this).val() == data.menu_detailtpl) $(this).attr('selected', true);
                else $(this).removeAttr('selected');
            });
            $('form#details input[name=menu_img]').val(data.menu_img);
            if(!isEmpty(data.menu_img)){
                menu_img = data.menu_img.split(',');
                if(!isEmpty(menu_img)){
                    for (var i = 0; i < menu_img.length; i++) $('#menu_img').append(webUploaderImgHtml(menu_img[i], 'input[name=menu_img]', $('#menu_img_filePicker').attr('span-class')));
                }
            }
            $('form#details input[name=menu_keywords]').val(data.menu_keywords);
            $('form#details input[name=menu_description]').val(data.menu_description);
            $('form#details input[name=menu_sort]').val(data.menu_sort);
            $('form#details input[name=is_show]').each(function(){
                if($(this).val() == data.is_show) $(this).attr('checked', true);
                else $(this).removeAttr('checked');
            });
            ue.ready(function () {
                ue.setContent(data.menu_content);
            });
            getBlogMenusSelectList(data.parent_id, $('form#details select[name=parent_id]'));
        } else layerMsg(data.msg, 5, layerJumpTime, 1);
    }).catch(function(res){
    });
}

/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} menu_id [description]
 * @return             {[type]}          [description]
 */
function delOperation(menu_id, ajax_url) {
    menu_id = (menu_id == '' || menu_id == undefined || menu_id == 'undefined' || isEmpty(menu_id)) ? 0 : menu_id;
    if (!isNumber(menu_id) && menu_id <= 0) {
        layerMsg('菜单Id必须为数字类型！', 5, 1000);
        return false;
    }
    layer.confirm('确定要将菜单“' + $('tr#id_' + menu_id).attr('name') + '”进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'menu_id': menu_id
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

function addMenus(url)
{
    hrefUrl(url + '?parent_id=' + $('form#form_data').find('select[name=parent_id]').val() || 0);
}