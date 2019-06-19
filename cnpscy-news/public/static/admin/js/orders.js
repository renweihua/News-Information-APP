function updateDetail( _this, detail_id, ajax_url )
{
    let tr = $(_this).parents('tr'),
        order_status = $(tr).find('select[name=order_status]').val(),
        express_name = $(tr).find('select[name=express_name]').val(),
        express_num = $(tr).find('input[name=express_num]').val();

    axios.post(ajax_url, {
        'detail_id' : detail_id,
        'order_status' : order_status,
        'express_name' : express_name,
        'express_num' : express_num,
    }).then(function(data){
        layerMsg(data.msg, data.status == 1 ? 1 : 5, layerJumpTime);
    }).catch(function(res){
    }); 
}