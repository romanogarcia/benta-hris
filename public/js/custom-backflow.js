// CUSTOM FUNCTION FOR BACKFLOW
function put_cookie_search(cookie_array=[]){
    var cookie_array_list = [];
    $.each(cookie_array, function(key, value){
        var obj = {};
        obj[key] = value;
        cookie_array_list.push(obj);
    });
    cookie_array_list.push({'last_url' : '{{url()->current()}}'});
    alert("HELLO");
    $.cookie('backflow_cookie', JSON.stringify(cookie_array_list), { expires: 1 });  // expires after 1 day
}
function get_cookie_search(){
    if(performance.navigation.type == 2){
        if (typeof $.cookie('backflow_cookie') !== 'undefined'){
            var cookie_stringify = $.cookie('backflow_cookie');
            var cookie_array_parse = JSON.parse(cookie_stringify);
            var last_url = cookie_array_parse[cookie_array_parse.length-1]['last_url'];
            if(last_url == '{{url()->current()}}'){
                for(var key in cookie_array_parse){
                    for(var key_2 in cookie_array_parse[key]){
                        if(key_2 != 'last_url'){
                            $('#'+key_2).val(cookie_array_parse[key][key_2]);
                        }
                    }
                }
                load_datatable();
                $('#id-data_table').DataTable().draw(true);
                $.removeCookie('backflow_cookie');
            }
        }
    }
}