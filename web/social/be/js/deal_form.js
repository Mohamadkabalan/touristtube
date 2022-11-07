var controller = 'deal';

function InitCurrencySelect(){
    $('input[name="currency"]').select2({
        placeholder: "Choose currency",
        minimumInputLength: 2,
        width: "578px",
        ajax: {
            url: "./deal/currencySearch",
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
                $.ajax("./deal/currencybyid", {
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
    InitCurrencySelect();
    $('#tourFromDate').datepicker({
        clearBtn: true,
        autoclose: true
    });
    $('#tourToDate').datepicker({
        clearBtn: true,
        autoclose: true
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
