function reload() {
    window.location.href = window.location.href;
}

function redirect(rul) {
    window.location.href = rul;
}

function scroll(ele, option) {
    var obj_ele = $(ele);
    var config = {
        width: 'auto', //可滚动区域宽度
        height: 'auto', //可滚动区域高度
        size: '5px', //组件宽度
        color: '#333', //滚动条颜色
        position: 'right', //组件位置：left/right
        distance: '0px', //组件与侧边之间的距离
        start: 'top', //默认滚动位置：top/bottom
        opacity: 0.4, //滚动条透明度
        alwaysVisible: true, //是否 始终显示组件
        disableFadeOut: false, //是否 鼠标经过可滚动区域时显示组件，离开时隐藏组件
        railVisible: false, //是否 显示轨道
        railColor: '#333', //轨道颜色
        railOpacity: 0.2, //轨道透明度
        railDraggable: true, //是否 滚动条可拖动
        railClass: 'slimScrollRail', //轨道div类名
        barClass: 'slimScrollBar', //滚动条div类名
        wrapperClass: 'slimScrollDiv', //外包div类名
        allowPageScroll: false, //是否 使用滚轮到达顶端/底端时，滚动窗口
        wheelStep: 5, //滚轮滚动量
        touchScrollStep: 5, //滚动量当用户使用手势
        borderRadius: '7px', //滚动条圆角
        railBorderRadius: '7px' //轨道圆角
    };
    var real_config = $.extend(config, option);
    obj_ele.slimScroll(real_config);
}

function openUrl(url) {
    $('.content iframe').attr('src', url);
}

function showDataTable(ele, url, columns, ext) {
    var dataTable = $(ele);
    var lang = {
        "sProcessing": "载入中...",
        "sLengthMenu": "显示 _MENU_ 条结果",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "显示第 _START_ 至 _END_ 条结果，共 _TOTAL_ 条",
        "sInfoEmpty": "显示第 0 至 0 条结果，共 0 条",
        "sInfoFiltered": "(由 _MAX_ 条结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    };
    var config = {
        serverSide: false,
        language: lang,
        searching: false,
        ordering: false,
        processing: true,
        lengthChange: false,
        bDestroy: true,
        iDisplayLength: 10
    };
    $.extend(config, ext);

    if(url && columns){
        config.serverSide = true;
        config.ajax = url;
        config.columns = columns;
    }

    dataTable.dataTable(config);
    return dataTable;
}

function ajax(type, url, data, callback) {
    $.ajax({
        type: type,
        url: url,
        data: data,
        dataType: 'json',
        scriptCharset: 'utf-8',
        contentType: "application/x-www-form-urlencoded; charset=utf-8",
        success: function (data) {
            callback(data);
        }
    });
}

function showEmpty(data) {
    if(data){
        return data;
    }else{
        return '';
    }
}

function render(tpl_ele, data, show_ele) {
    var source   = $(tpl_ele).html();
    var template = Handlebars.compile(source);
    var html    = template(data);
    $(show_ele).html(html)
}

//图片预览
function preview(showId, changeId) {
    var image = document.getElementById(showId);
    var imageChange = document.getElementById(changeId);
    function readUrl(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                image.setAttribute('src', e.target.result)
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    imageChange.onchange = function () {
        readUrl(this);
    }
}