function zhotel_markdown(str, mobile) {//simple markdown parser
    mobile = mobile || false;

    if(!str || str == "" ) return "";


    var imageHack = "";
    if(!mobile){

        //https://developer.qiniu.com/dora/manual/1270/the-advanced-treatment-of-images-imagemogr2 图片处理参考
    }
    imageHack = "?imageView2/2/w/800";

    var arr = str.split("\n");
    var html = "";
    var item = "";
    var gallery = 0;
    var gallery_str = "";
    var gallery_pre = '<div class="swiper-container markdown-gallery" style="margin:8px 0px;" ><div class="swiper-wrapper" >';
    var gallery_post = '</div>' +
        '<div class="swiper-button-next2"></div>' +
        '<div class="swiper-button-prev2"></div>' +
        '<div class="swiper-pagination"></div>' +
        '</div>';
    if(mobile){
        gallery_post = '</div>' +
            '<div class="swiper-pagination"></div>' +
            '</div>';
    }
    for (var i = 0, len = arr.length; i < len; i++) {
        item = (arr[i]);
        //console.log(item);
        if(item.indexOf('//') == 0){
            //skip
        }
        else if(item == ""){
            html = html + '<div style="height: 14px;width: 100px;"></div>';
        }
        else if(item.indexOf("[") == 0){
            var dim_start = item.indexOf('[');
            var dim_end = item.indexOf(']');
            var start = item.indexOf('(');
            var end = item.indexOf(')');
            var word = "";
            if (dim_end > dim_start + 1) {
                word = item.substring(dim_start + 1, dim_end)
            }
            if (end > start) {
                var url = item.substring(start + 1, end);
                if(word == ""){
                    word = url;
                }
                html = html + '<a class="markdown_link" href="'+url+'">'+word+'</a>';
                html = html + '<br />'

            }
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
                //html = html + '<br />'
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
                    gallery_str = gallery_str + '<img class="markdown-image" src="' + merge_url(url ,imageHack) + '"/>';
                    gallery_str = gallery_str + '</div>';
                }
                else{
                    html = html + '<img class="markdown-image" src="' + merge_url(url ,imageHack) + '"/>'
                    html = html + '<br />'
                }

            }
        }
        else if (item.indexOf('!![') == 0) {
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
                    gallery_str = gallery_str + '<img class="markdown-image-none" src="' + merge_url(url ,imageHack) + '"/>';
                    gallery_str = gallery_str + '</div>';
                }
                else{
                    html = html + '<img class="markdown-image-none" src="' + merge_url(url ,imageHack) + '"/>'
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
                    || ext == "tif"
                    || ext == "png") {
                    if(gallery == 1){
                        gallery_str = gallery_str + '<div class="swiper-slide">';
                        gallery_str = gallery_str + '<img class="markdown-image" src="' + merge_url(item ,imageHack) + '"/>';
                        gallery_str = gallery_str + '</div>';
                    }
                    else{
                        html = html + '<img class="markdown-image" src="' + merge_url(item ,imageHack) + '"/>'
                        html = html + '<br />'
                    }
                }
                else {
                    html = html + item;
                    html = html + '<br />';
                }
            }
        }
        else {
            html = html + "<span>" + item + "</span>";
            html = html + '<br />';
        }
    }
    return html;
}
function merge_url(url, hack){
    if(url.indexOf("?")  == 0){
        return url + hack;
    }
    return url;
}
function zhotel_clear_url_parameters(url){
    var urlparts= url.split('?');
    if (urlparts.length>=2) {
        return urlparts[0];
    } else {
        return url;
    }
}
function clone(obj) {
    var copy;

    // Handle the 3 simple types, and null or undefined
    if (null == obj || "object" != typeof obj) return obj;

    // Handle Date
    if (obj instanceof Date) {
        copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    // Handle Array
    if (obj instanceof Array) {
        copy = [];
        for (var i = 0, len = obj.length; i < len; i++) {
            copy[i] = clone(obj[i]);
        }
        return copy;
    }

    // Handle Object
    if (obj instanceof Object) {
        copy = {};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = clone(obj[attr]);
        }
        return copy;
    }

    throw new Error("Unable to copy obj! Its type isn't supported.");
}

function is_number(num){
    return !isNaN(parseFloat(num)) && !isNaN(num - 0);
}