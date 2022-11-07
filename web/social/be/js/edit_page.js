function InitCitySelect(){
    $('input[name="cityId"]').select2({
        placeholder: "Search for a city",
        minimumInputLength: 2,
        allowClear: true,
        ajax: {
            url: "./city/search",
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

function InitRegionSelect(){
    $('input[name="region"]').select2({
        placeholder: "Input at least 2 characters",
        minimumInputLength: 2,
        allowClear: true,
        width: '100%',
        ajax: {
            url: "./thingstodo/region_search",
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
                $.ajax("./thingstodo/regionbyid", {
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

$(function(){
    InitCitySelect();
	InitRegionSelect();
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
});
