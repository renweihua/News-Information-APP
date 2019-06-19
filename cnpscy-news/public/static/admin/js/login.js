function login(_this){
    if ($(_this).find('input[name=start_function]').val() == 0) return false;
    $(_this).find('input[name=start_function]').val(0);
    
    axios.post( $(_this).attr('action'), $(_this).serialize()).then(function(data){
        switch (parseInt(data.status)) {
            case 0:
                layerMsg(data.msg, 5, layerJumpTime);
                break;
            case 1:
                layerMsg(data.msg, 1, layerJumpTime, 3, $(_this).attr('return-url'));
                break;
            default:
                layerMsg(data.msg, 5, layerJumpTime);
                break;
        }
    	$(_this).find('input[name=start_function]').val(1);
    }).catch(function(res){
    	$(_this).find('input[name=start_function]').val(1);
    });
}