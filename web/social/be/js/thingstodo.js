function InitTTDCitySelect(){
    $('input[name="parent_id"]:not([type=hidden])').select2({
        placeholder: "Input at least 2 characters",
        minimumInputLength: 2,
        allowClear: true,
        width: '100%',
        ajax: {
            url: "./thingstodo/city_search",
            dataType: 'json',
            type: 'post',
            data: function(term, page){                              
                return {term: term};
            },
            results: function(data, page){
                return { results: data };
            }
        },
        initSelection: function(element, callback){
            var id = $(element).val();
            if(id !== "" && id !== 0){               
                $.ajax("./thingstodo/citybyid", {
                    dataType: 'json',
                    type: 'post',
                    data: {id: id}
                }).done(function(data) { 
                    callback(data); 
                });
            }
        },
        formatResult: function(item){
            return item.text;
        },
        formatSelection: function(item){
            return item.text;
        },
        escapeMarkup: function(m){
            return m;
        }
    });
}
function InitCountrySelect(){
    $('input[name="country"]').select2({
        placeholder: "Search for a country",
        minimumInputLength: 2,
        allowClear: true,
        width: '58.5%',
        ajax: {
            url: "./city/country_search",
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                return 'term=' + term;
            },
            results: function(data, page){
                return { results: data };
            }
        },
        initSelection: function(element, callback){
            var id = $(element).val();
            if(id !== "" && id != 0){
                $.ajax("./city/getbycountrycode", {
                    dataType: 'json',
                    type: 'post',
                    data: 'code=' + id
                }).done(function(data) { callback(data); });
            }
        },
        formatResult: function(item){
            return item.text;
        },
        formatSelection: function(item){
            return item.text;
        },
        escapeMarkup: function(m){
            return m;
        }
    });
}
function InitCitySelect(){
    $('input[name="city_id"]').select2({
        placeholder: "Search for a city",
        minimumInputLength: 2,
        allowClear: true,
        width: '58.5%',
        ajax: {
            url: "./city/search",
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                var country = $('input[name="country"]').select2("val");
                if(country){
                    return {term: term, country: country};
                }
                else{
                    return {term: term};
                }
            },
            results: function(data, page){
                return { results: data };
            }
        },
        initSelection: function(element, callback){
            var id = $(element).val();
            if(id !== "" && id != 0){
                $.ajax("./city/getbyid", {
                    dataType: 'json',
                    type: 'post',
                    data: 'id=' + id
                }).done(function(data) { callback(data); });
            }
        },
        formatResult: function(item){
            return item.text;
        },
        formatSelection: function(item){
            return item.text;
        },
        escapeMarkup: function(m){
            return m;
        }
    });
}

function InitEntitySelect(){
    $('input[name="entity_id"]').select2({
        placeholder: "Input at least 2 characters",
        minimumInputLength: 2,
        allowClear: true,
        width: '100%',
        ajax: {
            url: "./thingstodo/discover_search",
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                var entity_type = $('select[name="entity_type"]').val();
                var city_id = $('input[name="city_id"]').select2("val");
//                return 'term=' + term;
                return {term: term, type: entity_type, city_id: city_id};
            },
            results: function(data, page){
                return { results: data };
            }
        },
        initSelection: function(element, callback){
            var id = $(element).val();
            if(id !== "" && id !== 0){
                var entity_type = $('select[name="entity_type"]').val();
                $.ajax("./thingstodo/discoverbyid", {
                    dataType: 'json',
                    type: 'post',
                    data: {id: id, type: entity_type}
                }).done(function(data) { 
                    callback(data); 
                });
            }
        },
        formatResult: function(item){
            return item.text;
        },
        formatSelection: function(item){
            return item.text;
        },
        escapeMarkup: function(m){
            return m;
        }
    });
}

var controller = 'thingstodo';
$(function(){
    $('.container').on('click', '.linksContainer a', function(e){
        e.preventDefault();
        var url = './' + $(this).attr('href');
        $('#imgLoading').show();
        $.ajax({
            url: url,
            type: 'post',
            success     : function (res)
            {
                $('#imgLoading').hide();
                $("#listContainer").html(res);
            }
        });
    });
    InitTTDCitySelect();
    InitCountrySelect();
    InitEntitySelect();
    InitCitySelect();
//    $('#image').awesomeCropper({
//       width: 150,
//       height: 150,
//       debug: true
//    });
});

$(function(){
$('.container').on('click', 'div.lang button', function(e){
    var $this = $(this);
        e.preventDefault();
        e.stopPropagation();
        //var div = $(this).parent();
        var div = $(this).parent().data('id');
        div_value = (div.split("_"));
        lang = div_value[0];
        id = div_value[1];
        $('#imgSaving').show();
		var ttype = $('#ttype').val();
        $.ajax({
           url: "./thingstodo/ajax_lang_add?id="+id+"&lang="+lang+"&type="+ttype,
           type: 'post',
           dataType: 'text',
           cache:false,
           success: function (res){    
               if(res){
                   alert('sucess');
                   $('#imgSaving').hide();
                   $this.css({"background-color": "#919191"});
                   $this.attr({"disabled": "disabled"});
               }
               else{
                   alert("An error occured while trying to copy this");
               }
           },
           error: function() {
               $('#imgSaving').hide();
               alert("Something went wrong!");
            },
           data: { id: id, lang: lang }
        });
    });
});