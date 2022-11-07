$(document).ready(function(){		
        // Create the search box and link it to the UI element.
        var input = document.getElementById('txtName');
//        var input = $('input[name="name"]');
        var searchBox = new google.maps.places.SearchBox(input);

        // Listen for the event fired when the user selects an item from the
        // pick list. Retrieve the matching places for that item.
        google.maps.event.addListener(searchBox, 'places_changed', function() {
            var places = searchBox.getPlaces();
            place = places[0];
            console.log(place);
//            $('input[name="name"]').val(place.formatted_address);	
            $('input[name="name"]').val(place.name);
            $('input[name="latitude"]').val(place.geometry.location.lat());
            $('input[name="longitude"]').val(place.geometry.location.lng());
        });
});

function InitCountrySelect(){
    $('input[name="country_code"]').select2({
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

$(function(){
    InitCountrySelect();
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