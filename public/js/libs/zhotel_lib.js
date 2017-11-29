function zhotel_markdown(str) {//simple markdown parser
    if(!str || str == "" ) return "";
    var arr = str.split("\n");
    var html = "";
    var item = "";
    var gallery = 0;
    var gallery_str = "";
    var gallery_pre = '<div class="swiper-container" style="margin:8px 0px;" ><div class="swiper-wrapper" >';
    var gallery_post = '</div>' +
        '<div class="swiper-button-next2"></div>' +
        '<div class="swiper-button-prev2"></div>' +
        '<div class="swiper-pagination"></div>' +
        '</div>';
    for (var i = 0, len = arr.length; i < len; i++) {
        item = (arr[i]);
        //console.log(item);
        if(item.indexOf('//') == 0){
            //skip
        }
        else if(item.indexOf("{") == 0){
            //gallery start
            gallery = 1;
        }
        else if(item.indexOf("}") == 0){
            //gallery end
            gallery = 0;
            if(gallery_str){
                html = html + gallery_pre + gallery_str + gallery_post;
                html = html + '<br />'
            }
            gallery_str = '';
        }
        else if (item.indexOf('![') == 0) {
            var start = item.indexOf('(');
            var end = item.indexOf(')');
            var dim_start = item.indexOf('[');
            var dim_end = item.indexOf(']');
            var width = '';
            var height = '';
            if (dim_end > dim_start + 1) {
                var size = item.substring(dim_start + 1, dim_end).split(' ');
                if (size.length == 2) {
                    width = size[0];
                    height = size[1];
                }
                else if (size.length == 1) {
                    width = size[0]
                }
            }

            if (end > start) {
                var url = item.substring(start + 1, end);
                if(gallery == 1){
                    gallery_str = gallery_str + '<div class="swiper-slide">';
                    gallery_str = gallery_str + '<img class="markdown-image" src="' + url + '"/>';
                    gallery_str = gallery_str + '</div>';
                }
                else{
                    html = html + '<img class="markdown-image" src="' + url + '"/>'
                    html = html + '<br />'
                }

            }
        }
        else if (item.indexOf("http") == 0) {
            var ext = item.substring(item.lastIndexOf(".") + 1);
            if (!ext) {
                html = html + item;
                html = html + '<br />';
            }
            else {
                ext = ext.toLowerCase();
                if (ext == "jpg"
                    || ext == "jpeg"
                    || ext == "gif"
                    || ext == "png") {
                    if(gallery == 1){
                        gallery_str = gallery_str + '<div class="swiper-slide">';
                        gallery_str = gallery_str + '<img class="markdown-image" src="' + item + '"/>';
                        gallery_str = gallery_str + '</div>';
                    }
                    else{
                        html = html + '<img class="markdown-image" src="' + item + '"/>'
                        html = html + '<br />'
                    }
                }
                else {
                    html = html + item;
                    html = html + '<br />';
                }
            }
        }
        else if (item.indexOf("{") == 0) {

        }
        else {
            html = html + item;
            html = html + '<br />';
        }
    }
    return html;
}