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