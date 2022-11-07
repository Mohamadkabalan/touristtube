var controller = 'hotels';
function InitEntitySelect(input, entity_type){
    $('#'+input).select2({
        placeholder: "Input at least 2 characters",
        minimumInputLength: 2,
        allowClear: true,
        width: '100%',
        ajax: {
            url: "./hotels/discover_search",
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                var city_id = $('#city_id').select2("val");
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
                $.ajax("./hotels/discoverbyid", {
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
function InitStateSelect(){
    $('#state_id').select2({
        placeholder: "Search for a state",
        minimumInputLength: 2,
        allowClear: true,
        width: '100%',
        ajax: {
            url: "./city/state_search_country_id",
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                var country = $('#country_id').select2("val");
                if(country){
                    return {term: term, country_id: country};
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
    $('#city_id').select2({
        placeholder: "Search for a city",
        minimumInputLength: 2,
        allowClear: true,
        width: '100%',
        ajax: {
            url: "./city/search_country_id",
            dataType: 'json',
            type: 'post',
            data: function(term, page){
                var country = $('#country_id').select2("val");
                if(country){
                    return {term: term, country_id: country};
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
function InitCountrySelect(){
    $('#country_id').select2({
        placeholder: "Search for a country",
        minimumInputLength: 2,
        allowClear: true,
        width: '100%',
        ajax: {
            url: "./city/country_search_id",
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
                $.ajax("./city/getbycountryid", {
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
$(function(){
    InitCountrySelect();
    InitStateSelect();
    InitCitySelect();
    InitEntitySelect('hotel_id', SOCIAL_ENTITY_HOTEL);
    InitEntitySelect('poi_id', SOCIAL_ENTITY_LANDMARK);
    InitEntitySelect('airport_id', SOCIAL_ENTITY_AIRPORT);
    $('#entity_type').change(function(){
        var $this = $(this);
        switch($this.val()){
            case SOCIAL_ENTITY_HOTEL:
                break;
            case SOCIAL_ENTITY_LANDMARK:
                break;
            case SOCIAL_ENTITY_AIRPORT:
                break;
            case SOCIAL_ENTITY_CITY:
                break;
            case SOCIAL_ENTITY_COUNTRY:
                break;
            case SOCIAL_ENTITY_STATE:
                break;
            case SOCIAL_ENTITY_DOWNTOWN:
                break;
        }
    });
});