/**
 * [delOperation]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:删除操作
 * @englishAnnotation:
 * @version:1.0
 * @param              {[type]} goods_id [description]
 * @return             {[type]}          [description]
 */
function delOperation(goods_id, ajax_url) {
    goods_id = (goods_id == '' || goods_id == undefined || goods_id == 'undefined' || isEmpty(goods_id)) ? 0 : goods_id;
    if (!isNumber(goods_id) && goods_id <= 0) {
        layerMsg('商品Id必须为数字类型！', 5, layerJumpTime);
        return false;
    }
    layer.confirm('确定要将商品“' + $('tr#id_' + goods_id).attr('name') + '”进行“删除”操作吗？\b\b 删除之后将无法恢复！', {title: webTitle}, function () {
        axios.post( ajax_url, {
            'goods_id': goods_id
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

//刷新参数表！！！生成表格！
function refresh_parameter(goods_id, data) {
    refreshSpecificationsTable();
    if(!isEmpty(data)) dataFilling(JSON.parse(data));
    else{
        axios.post( $('form').attr('get-ajax-url'), {
            'goods_id': goods_id
        }).then(function(data){
            if (data.status == 1) {
                dataFilling(data.data);
            }
        }).catch(function(res){
        });
    }
}

function dataFilling(data)
{
    extend_list = data.formatAttr,
    attr_name_ary = data.goodsFormat,
    attr_name_num = attr_name_ary.length,
    first_tr_length = $('li#options table tbody tr:eq(0)').children().length;
    $('li#options table tbody tr').each(function () {
        for (var a = 0; a < extend_list.length; a++) {
            input_attr_name = $(this).find('.attr_val').val();
            if (input_attr_name != '' && input_attr_name.replace(',', '') != '') {
                check_val_name = input_attr_name.split(',');//检测属性值是否对应
                if (check_val_name != '') {
                    is_same_column = true;//是否是公用同列
                    for (var j = 0; j < extend_list[a]['goodsAttr'].length; j++) {
                        is_same_column = ($.inArray(extend_list[a]['goodsAttr'][j]['attr_name'], check_val_name) != -1) ? true : false;
                        if (is_same_column == false) break;
                    }
                    if (is_same_column == true) {
                        $(this).find('.com_id').val(extend_list[a]['com_id']);
                        $(this).find('.goods_stock').val(extend_list[a]['goods_stock']);
                        $(this).find('.com_old_price').val(extend_list[a]['market_money']);
                        $(this).find('.com_price').val(extend_list[a]['cash_money']);
                        $(this).find('.ary_weight').val(extend_list[a]['weight']);
                        $(this).find('.ary_banner_img').val(extend_list[a]['banner_img']);
                        if (!isEmpty(extend_list[a]['banner_img'])) {
                            $(this).find('img').attr('src', extend_list[a]['banner_img']);
                            $(this).find('img').css('margin-top', 5);
                            $(this).find('img').show();
                        }
                        $(this).find('.com_status option').each(function () {
                            if (extend_list[a]['com_status'] == $(this).val()) $(this).attr('selected', 'selected');
                            else $(this).removeAttr('selected');
                        });
                        break;
                    }
                }
            }
        }
    });

    /**
     * 在  规格组合的时候，有个地方有个BUG
     *
     * 更新：
     *
     * 假设：当前是规格 XL；颜色：红，绿
     *
     * 当添加新的规格属性名
     *  品牌：
     *  阿迪达斯
     * 江南皮克城
     *
     * 在“前台页面”，刷新规格的时候，
     *
     * 就会出现：
     *  XL  红  阿迪达斯 【会展示红颜色的详情数据】   [与下方的数据重复，最主要的是com_id重复]
     *          江南皮克城   【会展示红颜色的详情数据】   [与上方的数据重复，最主要的是com_id重复]
     *      绿  阿迪达斯 【会展示绿颜色的详情数据】  [与下方的数据重复，最主要的是com_id重复]
     *          江南皮克城 【会展示绿颜色的详情数据】 [与上方的数据重复，最主要的是com_id重复]
     * 会默认覆盖
     *
     *
     * 解决方法：前端，移除所有重复的第二次的com_id、以及其他 数据库对应字段的数据 全部数据
     *
     * 也就是说，只有第一条数据会替换掉，其他数据默认全部会新增
     * 
     */
    let com_ids = new Array(),
        _com_id = 0,
        _parent;
    $('#options input.com_id').each(function(i){
        _com_id = $(this).val();
        if($.inArray(_com_id, com_ids) > -1){
            _parent = $(this).parent();
            _parent.find('input.goods_stock').val('') //清除库存的值
            _parent.find('input.com_id').val('') //清除com_id的值
            _parent.find('input.com_old_price').val('') //清除市场价的值
            _parent.find('input.com_price').val('') //清除现价的值
            _parent.find('input.ary_banner_img').val(''); //清除图片的值
            _parent.find('img').remove();
        }else com_ids.push(_com_id);
    });
}

/**
 * [checkPrice]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:对于商品的组合原价与组合现价，价格与商品原、现价做对比。
 * @englishAnnotation:
 * @param              {[type]} _this [description]
 * @return             {[type]}       [description]
 */
function checkPrice(_this) {
    var cash_money = $('input[name=cash_money]').val(),
        market_money = $('input[name=market_money]').val(),
        this_price = Math.ceil($(_this).val());
    if (this_price > market_money) $(_this).val(market_money);
    else if (this_price < cash_money) $(_this).val(cash_money);
    else if (this_price >= cash_money && cash_money <= market_money) $(_this).val(this_price);
    else $(_this).val(this_price);
}

// 添加规格;
function addSpecifications() {
    var i = 0;
    var len = $(".spec_item").length;
    var html = '<div class="panel panel-default spec_item" id="spec_' + key()[0] + '">';
    html += '   <div style="padding: 15px 15px 60px 15px;">';
    html += '       <input name="spec_id[]" type="hidden" class="form-control spec_id" value="' + key()[0] + '">';
    html += '       <div class="form-group width-30-rem">';
    html += '           <label class="col-xs-12 col-sm-3 col-md-2 control-label width-10-rem">参数名：</label>';
    html += '           <div class="col-sm-9 col-xs-12" style="width: 60%;">';
    html += '               <input name="spec_title[' + key()[0] + ']" type="text" class="form-control  spec_title" value="" placeholder="(比如: 颜色)">';
    html += '           </div>';
    html += '           <div style="clear:both"></div>';
    html += '       </div>';
    html += '       <div class="form-group">';
    html += '           <label class="col-xs-12 col-sm-2 col-md-2 control-label width-10-rem">参数项：</label>';
    html += '           <div class="col-sm-10 col-xs-12">';
    html += '               <div id="spec_item_' + key()[0] + '" class="spec_item_items add_table ui-sortable">';
    html += '                   <div class="spec_item_item float-left margin-bottom-1-rem width-20-rem margin-right-2-rem">';
    html += '                       <input type="hidden" class="form-control spec_item_show" name="spec_item_show_' + key()[0] + '[]" value="1">';
    html += '                       <input type="hidden" class="form-control spec_item_id" name="spec_item_id_' + key()[0] + '[]" value="' + key()[1] + '">';
    html += '                       <div class="input-group age_txt">';
    html += '                           <input type="text" class="form-control spec_item_title error" name="spec_item_title_' + key()[0] + '[]" value="">';
    html += '                           <span class="input-group-addon" onclick="removeSpecItem(this)">删除</span>';
    html += '                       </div>';
    html += '                   </div>';
    html += '               </div>';
    html += '           </div>';
    html += '           <div style="clear:both"></div>';
    html += '       </div>';
    html += '       <div class="form-group col-xs-4 col-sm-4">';
    html += '           <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>';
    html += '           <div class="col-sm-9 col-xs-12">';
    html += '               <a href="javascript:;" id="add-specitem-' + key()[0] + '" specid="' + key()[0] + '" class="btn btn-info add-specitem" onclick="addSpecItem(this,\'' + key()[0] + '\' , \'' + key()[1] + '\')">';
    html += '                   <i class="iconfont icon-tianjia"></i> 添加参数项';
    html += '               </a>';
    html += '               <a href="javascript:void(0);" class="btn btn-danger" onclick="removeSpec(this)"><i class="iconfont icon-shanchu"></i> 删除参数</a>';
    html += '           </div>';
    html += '           <div style="clear:both"></div>';
    html += '       </div>';
    html += '   </div>';
    html += '</div>';
    $('#specifications').append(html);
    var len = $(".add-specitem").length - 1;
    $(".add-specitem:eq(" + len + ")").focus();

    window.optionchanged = true;
}

/*删除规格*/
function removeSpec(_this) {
    layer.confirm('确认要删除此参数规格吗? \b\ 删除之后无法恢复！', {title: webTitle}, function (index) {
        $(_this).parent().parent().parent().parent().remove();
        layer.close(index);
    }, function () {
    });
}

/*添加规格项*/
function addSpecItem(_this, specid, spec_item_id) {
    var clone_html = $('.spec_item_item').eq(0).clone();
    clone_html = '<div class="spec_item_item float-left margin-bottom-1-rem width-20-rem margin-right-2-rem"><input type="hidden" class="form-control spec_item_show" name="spec_item_show_' + specid + '[]" value="1"><input type="hidden" class="form-control spec_item_id" name="spec_item_id_' + specid + '[]" value="' + spec_item_id + '"><div class="input-group age_txt"><input type="text" class="form-control spec_item_title error" name="spec_item_title_' + specid + '[]" value=""><span class="input-group-addon" onclick="removeSpecItem(this)">删除</span></div></div>';

    $(_this).parent().parent().parent().parent('.panel').find('.add_table').append(clone_html);
    var len = $("#spec_" + specid + " .spec_item_title").length - 1;
    $("#spec_" + specid + " .spec_item_title:eq(" + len + ")").focus();
    window.optionchanged = true;
}

/*删除规格项*/
function removeSpecItem(obj) {
    layer.confirm('确认要删除此参数项吗? \b\ 删除之后无法恢复！', {title: webTitle}, function (index) {
        if ($(obj).parent().parent().parent().parent().children().children().length > 1) {
            $(obj).parent().parent().remove();
            layer.close(index);
        } else layerMsg('每个参数名至少保留一项参数值！', 0);
    }, function () {
    });
}

function randomString(len) {
    len = len || 32;
    var $chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
    /****默认去掉了容易混淆的字符oOLl,9gq,Vv,Uu,I1****/
    var maxPos = $chars.length;
    var pwd = '';
    for (i = 0; i < len; i++) {
        pwd += $chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}

/* 生成随机数 */
function key() {
    var data = [];
    for (var j = 0; j < 2; j++) {
        data[j] = '';
        var strPol = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';
        var max = strPol.length - 1;
        for (var i = 0; i < 32; i++) {
            data[j] += strPol[Math.ceil(Math.random() * 32)];

        }
    }
    return data;
}

/*刷新规格项目表*/
function refreshSpecificationsTable() {
    var so = 0;
    window.optionchanged = false;
    var html = '<table class="table table-bordered table-condensed"><thead><tr class="active">';
    var specs = [];
    if ($('.spec_item').length <= 0) {
        $("#options").html('');
        return;
    }
    $(".spec_item").each(function (i) {
        var _this = $(this);

        var spec = {
            id: _this.find(".spec_id").val(),
            title: _this.find(".spec_title").val()
        };

        var items = [];
        _this.find(".spec_item_item").each(function () {
            var __this = $(this);
            var item = {
                id: __this.find(".spec_item_id").val(),
                title: __this.find(".spec_item_title").val(),
                virtual: __this.find(".spec_item_virtual").val(),
                show: __this.find(".spec_item_show").get(0).checked ? "1" : "0"
            }
            items.push(item);
        });
        spec.items = items;
        specs.push(spec);
    });
    specs.sort(function (x, y) {
        if (x.items.length > y.items.length) {
            return 1;
        }
        if (x.items.length < y.items.length) {
            return -1;
        }
    });
    var len = specs.length;
    var newlen = 1;
    var h = new Array(len);
    var rowspans = new Array(len);
    for (var i = 0; i < len; i++) {
        html += "<th style='width:80px;'>" + specs[i].title + "</th>";
        var itemlen = specs[i].items.length;
        if (itemlen <= 0) {
            itemlen = 1
        }
        ;
        newlen *= itemlen;
        h[i] = new Array(newlen);
        for (var j = 0; j < newlen; j++) {
            h[i][j] = new Array();
        }
        var l = specs[i].items.length;
        rowspans[i] = 1;
        for (j = i + 1; j < len; j++) {
            rowspans[i] *= specs[j].items.length;
        }
    }

    html += '<th class="" style="width:150px;"><div class=""><div style="text-align:center;font-size:16px;">商品库存</div></th>';
    html += '<th class="" style="width:150px;"><div class=""><div style="text-align:center;font-size:16px;">组合市场价</div></th>';
    html += '<th class="" style="width:150px;"><div class=""><div style="text-align:center;font-size:16px;">组合现价</div></th>';
    html += '<th class="" style="width:150px;"><div class=""><div style="text-align:center;font-size:16px;">商品轮播图</div></th>';
    // html += '<th class="" style="width:150px;"><div class=""><div style="text-align:center;font-size:16px;">商品重量（克）</div></th>';
    html += '<th class="" style="width:150px;"><div class=""><div style="text-align:center;font-size:16px;">组合状态</div></th>';
    html += '</tr></thead>';
    for (var m = 0; m < len; m++) {
        var k = 0, kid = 0, n = 0;
        for (var j = 0; j < newlen; j++) {
            var rowspan = rowspans[m], title = $.trim(specs[m].items[kid].title) || "";
            if (title == '' || title == 'undefined' || title == undefined) continue;
            if (j % rowspan == 0) {
                h[m][j] = {
                    attr_name: specs[m].title,
                    title: specs[m].items[kid].title,
                    virtual: specs[m].items[kid].virtual,
                    html: "<td rowspan='" + rowspan + "' valign='middle'>" + specs[m].items[kid].title + "</td>\r\n",
                    id: specs[m].items[kid].id
                };
            }
            else {
                h[m][j] = {
                    attr_name: specs[m].title,
                    title: specs[m].items[kid].title,
                    virtual: specs[m].items[kid].virtual,
                    html: "",
                    id: specs[m].items[kid].id
                };
            }
            n++;
            if (n == rowspan) {
                kid++;
                if (kid > specs[m].items.length - 1) {
                    kid = 0;
                }
                n = 0;
            }
        }
    }
    var hh = "";
    for (var i = 0; i < newlen; i++) {
        hh += "<tr>";
        var ids = [];
        var titles = [];
        var attrs = [];
        var virtuals = [],
            img = [];
        for (var j = 0; j < len; j++) {
            if (h[j][i] == undefined || h[j][i] == "undefined" || h[j][i] == '') continue;
            hh += h[j][i].html;
            ids.push(h[j][i].id);
            titles.push(h[j][i].title);
            attrs.push(h[j][i].attr_name);
            virtuals.push(h[j][i].virtual);
            img.push(h[j][i].banner_img);
        }
        ids = ids.join('_');
        titles = titles.join(',');
        attrs = attrs.join(',');

        var val = {
            id: "",
            title: titles,
            attr_name: attrs,
            stock: "",
            productprice: "",
            marketprice: "",
            weight: "",
            state: "",
            goodssn: "",
            virtual: virtuals,
            img: "",
            freight_money: "",
        };
        if (val.title == '' || val.title == 'undefined' || val.title == undefined) continue;
        if ($(".option_id_" + ids).length > 0) {
            val = {
                id: $(".option_id_" + ids + ":eq(0)").val(),
                title: titles,
                attr_name: attrs,
                stock: $(".option_stock_" + ids + ":eq(" + so + ")").val(),
                weight: $(".option_weight_" + ids + ":eq(" + so + ")").val(),
                productprice: $(".option_productprice_" + ids + ":eq(" + so + ")").val(),
                marketprice: $(".option_marketprice_" + ids + ":eq(" + so + ")").val(),
                goodssn: $(".option_goodssn_" + ids + ":eq(" + so + ")").val(),
                state: $(".option_state_" + ids + ":eq(" + so + ")").val(),
                img: $(".option_img_" + ids + ":eq(" + so + ")").val(),
                freight_money: $(".option_freight_money_" + ids + ":eq(" + so + ")").val(),
                virtual: virtuals
            }
        }
        so++;
        hh += '<td>'
        hh += '<input name="goods_stock[]" type="text" class="goods_stock form-control spec_title option_stock option_stock_' + ids + '" value="' + (isEmpty(val.stock) ? '' : val.stock ) + '" onchange="int_num( this )" /></td>';
        hh += '<input name="attr_name[]" type="hidden" class="attr_name form-control option_title option_title_' + ids + '" value="' + (isEmpty(val.attr_name) ? '' : val.attr_name ) + '"/>';
        hh += '<input name="attr_val[]" type="hidden" class="attr_val form-control option_title option_title_' + ids + '" value="' + (isEmpty(val.title) ? '' : val.title ) + '"/>';
        hh += '<input name="mosaic[]" type="hidden" class="mosaic form-control option_title option_title_' + ids + '" value="' + (isEmpty(val.virtual) ? '' : val.virtual ) + '"/>';
        hh += '<input name="com_id[]" type="hidden" class="com_id form-control option_title option_title_' + ids + '" value=""/>';
        hh += '</td>';
        hh += '<td><input name="com_old_price[]" type="text" class="com_old_price form-control spec_title option_productprice option_productprice_' + ids + '" " value="' + (isEmpty(val.productprice) ? '' : val.productprice ) + '" onchange="two_decimal( this )" /></td>';
        hh += '<td class=""><input name="com_price[]" type="text" class="com_price form-control spec_title option_marketprice option_marketprice_' + ids + '" value="' + (isEmpty(val.marketprice) ? '' : val.marketprice ) + '" onchange="two_decimal( this )" /></td>';
        hh += '<td class="text-center">';
        hh += '<div id="add_file" class="btn btn-primary col-xs-12 col-sm-12" onclick="previewUploadImg(\'#upload' + i + '\', \'#view-img-' + i + '\', \'input.ary_banner_img' + i + '\')" >图片上传</div>';
        hh += '<img src="" id="view-img-' + i + '" style="display:none;width: 100px;height: 100px;    margin: 55px auto 0;">';
        hh += '<input type="file" name="" id="upload' + i + '" style="display:none;">';
        hh += '<input name="ary_banner_img[]" type="hidden" class="ary_banner_img ary_banner_img' + i + ' form-control spec_title option_stock option_img_' + ids + '" value="' + (isEmpty(val.img) ? '' : val.img ) + '" />';
        hh += '</td>';
        hh += '<td class=""><select class="select com_status col-sm-12 col-xs-12" size="1" name="com_status[]"><option value="0">上架</option><option value="1">下架</option></select></td>';
        hh += "</tr>";
    }
    html += hh;
    html += "</table>";
    $("#options").html(html);
}

function previewUploadImg_banner(file, fill, inputfill) {
    $(file).trigger('click');
    $(file).bind("change", function () {
        var thatFun = arguments.callee;
        $(this).unbind("change", thatFun);
        convertImgToBase64(getObjectURL(this.files[0]), function (base64img) {
            if ($(fill)) $(fill).attr('src', base64img);
            $(fill).css('display', 'block');
            uploadImgBase64imgBanner(base64img, inputfill);
        });
    })
}