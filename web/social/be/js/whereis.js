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
function InitStateSelect(){
    $('input[name="state"]').select2({
        placeholder: "Search for a state",
        minimumInputLength: 2,
        allowClear: true,
        width: '58.5%',
        ajax: {
            url: "./city/state_search",
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
                $.ajax("./city/getbystateid", {
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

var controller = 'seo';
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
    InitCountrySelect();
    InitStateSelect();
    InitCitySelect();
//    $('#image').awesomeCropper({
//       width: 150,
//       height: 150,
//       debug: true
//    });
});
function getcountryidbycode(country_code){
    var cntresult="";
    $.ajax("./city/getbycountrycode", {
        dataType: 'json',
        type: 'post',
        data: 'code=' + country_code,
        success: function(data) {
            var cntresult = data.country_id; 
            return (cntresult)
            //console.log(result);
        },error: function() {
            alert("Something went wrong ....!");
        }
        
    });
    //console.log(result);
}

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
    
    
    $('.container').on('click', 'div button.check_button', function(e){ 
        var entity_id='';
        var country_code = $('input[name="country"]').select2("val");
        
        if($('input[name="state"]').select2("val")){
            var state_id = $('input[name="state"]').select2("val");
            //alert(state_id);
        }
        if($('input[name="city_id"]').select2("val")){
            var city_id = $('input[name="city_id"]').select2("val");
            // alert(city_id);
        }
        

        var entity_type = $('select[name="entity_type"]').val();

        if(entity_type == 72 && country_code == 0){
            alert('choose the country');
            return false;
        }
        if(entity_type == 73 && state_id ==0){
            alert('choose the state');
            return false;
        }
        if(entity_type == 71 && city_id == 0){ 
            alert('choose the city');
            return false;
        }

        $.ajax("./city/getbycountrycode", {
        dataType: 'json',
        type: 'post',
        data: 'code=' + country_code,
        success: function(data) {
            entity_id = data.country_id; 
            var url;
            var main_url;
            if(country_code && state_id!=0 && city_id!=0){ 
                url = "./seo/ajax_whereis_add_check?entity_type="+entity_type+"&entity_id="+city_id;
                main_url = './' + controller +"/whereis_add/1?entity_type="+entity_type+"&entity_id="+city_id;
                
            }else if(country_code && state_id!=0){ 
                url = "./seo/ajax_whereis_add_check?entity_type="+entity_type+"&entity_id="+state_id;
                main_url = './' + controller +"/whereis_add/1?entity_type="+entity_type+"&entity_id="+state_id;
                
            }else{  
                url = "./seo/ajax_whereis_add_check?entity_type="+entity_type+"&entity_id="+entity_id;
                main_url = './' + controller +"/whereis_add/1?entity_type="+entity_type+"&entity_id="+entity_id;
            }
            $.ajax({
               url: url,
               dataType: "json",
               cache:false,
               success: function (res){    
                   if(res){  
                       //console.log(res);
                       //$('input[name="title"]').val(res.title);
                       window.location.href = './' + controller +'/whereis_edit/'+res.id;    
                   }
                   else{
                       alert("This relation does not exist inside databse");
                       window.location.href = main_url;
                       //$('.check_div_title').removeAttr('style');
                       //$('.check_div_title').show(); 
                       //$('.check_div_title').css('display', 'block');
                   }
               },
               error: function() {
                   alert("Something went wrong!");
                },
               data: { entity_type: entity_type, entity_id: entity_id }
            });          


            //console.log(result);
        },error: function() {
            alert("Something went wrong ....!");
        }

        });
            

        });
    
    $('.select2-container select[name="entity_type"]' ).change(function(){
        //alert('muk');
        $('.check_div').show(); 
        $('.check_edit_div').hide();
    });
    $('input[name="country"]').click(function () {
       // alert('hiiii');
       $('.check_div').show(); 
       $('.check_edit_div').hide();
    });
    $('input[name="state"]').click(function () {
       $('.check_div').show(); 
    });
    $('input[name="city_id"]').click(function () {
       $('.check_div').show(); 
    });
        
}); 
       
       
