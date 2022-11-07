var pagenumber = 1;
var priceOrder = '';
var starsOrder = '';
$(document).ready(function () {
    $('input:checkbox').removeAttr('checked');
    var pageType = $('input[name="pageType"]').val();
    pagenumber = $('#paging .page li.active').attr('data-page');
    $(document).on('click', '.prev_pg', function () {
        var curpage = $('.active a').text();
        if (curpage == 1) {
            pagenumber = 1;
        } else {
            pagenumber = Number(curpage) - Number(1);
        }
        if (pageType == 1) {
            getsearchrelated();
        } else if (pageType == 2) {
            getsearchrelatedcategory();
        } else if (pageType == 3) {
            getsearchnearby();
        } else if (pageType == 4) {
            getsearchrestaurant();
        }
    });
    $(document).on('click', '.first_pg', function () {
        var curpage = $('.active a').text();
        pagenumber = 1;
        if (pageType == 1) {
            getsearchrelated();
        } else if (pageType == 2) {
            getsearchrelatedcategory();
        } else if (pageType == 3) {
            getsearchnearby();
        } else if (pageType == 4) {
            getsearchrestaurant();
        }
    });
    $(document).on('click', '.next_pg', function (e) {
        var curpage = $('.active a').text();
        var lastpage = $('input[name="totalpage"]').val() - 1;
        if (curpage == lastpage) {
            pagenumber = lastpage;
        } else {
            pagenumber = Number(curpage) + Number(1);
        }
        if (pageType == 1) {
            getsearchrelated();
        } else if (pageType == 2) {
            getsearchrelatedcategory();
        } else if (pageType == 3) {
            getsearchnearby();
        } else if (pageType == 4) {
            getsearchrestaurant();
        }
    });
    $(document).on('click', '.last_pg', function () {
        pagenumber = $('input[name="totalpage"]').val() - 1;

        if (pageType == 1) {
            getsearchrelated();
        } else if (pageType == 2) {
            getsearchrelatedcategory();
        } else if (pageType == 3) {
            getsearchnearby();
        } else if (pageType == 4) {
            getsearchrestaurant();
        }
    });
    $(document).on('click', ".restaurantPrefrences", function () {
        if (pageType == 1) {
            getsearchrelated();
        } else if (pageType == 2) {
            getsearchrelatedcategory();
        } else if (pageType == 3) {
            getsearchnearby();
        } else if (pageType == 4) {
            getsearchrestaurant();
        }
    });
    $(document).on('click', ".pagerWrapper li[data-page] a", function (e) {
        e.preventDefault();
        $('.pagerWrapper li').removeClass('active');
        $(this).parent().addClass('active');
        pagenumber = $(this).parent().attr('data-page');
        if (pageType == 1) {
            getsearchrelated();
        } else if (pageType == 2) {
            getsearchrelatedcategory();
        } else if (pageType == 3) {
            getsearchnearby();
        } else if (pageType == 4) {
            getsearchrestaurant();
        }
    });
});


function getsearchrelated() {
    //$('.upload-overlay-loading-fix').addClass('hotelsLoader');
    $('.upload-overlay-loading-fix').show();
    var type = $('input[name="types"]').val();
    var city = $('input[name="citys"]').val();
    var dest = $('input[name="dest"]').val();
    var restaurantPrefrences = new Array();
    $('input[name="restaurantPrefrences"]:checked').each(function () {
        restaurantPrefrences.push(this.value);
    });
    $.ajax({
        url: generateLangURL('/ajax/search_Restaurant'),
        data: {type: type, city: city, restaurantPrefrences: restaurantPrefrences, page: pagenumber, dest: dest},
        type: 'post',
        success: function (res) {
            $('#search_hotel_in').html(res.restaurant);
            $('.pagerWrapper .fr').html(res.paging);
            $('.srch_total_num').html(res.total);
            $('.upload-overlay-loading-fix').hide();
            $('.upload-overlay-loading-fix').removeClass('hotelsLoader');
        }
    });
}

function getsearchrelatedcategory() {
    //$('.upload-overlay-loading-fix').addClass('hotelsLoader');
    $('.upload-overlay-loading-fix').show();
    var city = $('input[name="citys"]').val();
    var dest = $('input[name="dest"]').val();
    var restaurantPrefrences = new Array();
    $('input[name="restaurantPrefrences"]:checked').each(function () {
        restaurantPrefrences.push(this.value);
    });
    $.ajax({
        url: generateLangURL('/ajax/search_prefix_Restaurant'),
        data: {city: city, restaurantPrefrences: restaurantPrefrences, page: pagenumber, dest: dest},
        type: 'post',
        success: function (res) {
            $('#search_hotel_in').html(res.restaurant);
            $('.pagerWrapper .fr').html(res.paging);
            $('.srch_total_num').html(res.total);
            $('.upload-overlay-loading-fix').hide();
            $('.upload-overlay-loading-fix').removeClass('hotelsLoader');
        }
    });
}

function getsearchnearby() {
    //$('.upload-overlay-loading-fix').addClass('hotelsLoader');
    $('.upload-overlay-loading-fix').show();
    var city = $('input[name="citys"]').val();
    var dest = $('input[name="dest"]').val();
    var restaurantPrefrences = new Array();
    $('input[name="restaurantPrefrences"]:checked').each(function () {
        restaurantPrefrences.push(this.value);
    });
    $.ajax({
        url: generateLangURL('/ajax/search_near_by_Restaurants'),
        data: {city: city, restaurantPrefrences: restaurantPrefrences, page: pagenumber, dest: dest},
        type: 'post',
        success: function (res) {
            $('#search_hotel_in').html(res.restaurant);
            $('.pagerWrapper .fr').html(res.paging);
            $('.srch_total_num').html(res.total);
            $('.upload-overlay-loading-fix').hide();
            $('.upload-overlay-loading-fix').removeClass('hotelsLoader');
        }
    });
}

function getsearchrestaurant() {
    //$('.upload-overlay-loading-fix').addClass('hotelsLoader');
    $('.upload-overlay-loading-fix').show();
//    var city =$('input[name="citys"]').val();
    var dest = $('input[name="dest"]').val();
    var restaurantPrefrences = new Array();
    $('input[name="restaurantPrefrences"]:checked').each(function () {
        restaurantPrefrences.push(this.value);
    });
    $.ajax({
        url: generateLangURL('/ajax/search_Restaurants'),
        data: {restaurantPrefrences: restaurantPrefrences, page: pagenumber, dest: dest},
        type: 'post',
        success: function (res) {
            $('#search_hotel_in').html(res.restaurant);
            $('.pagerWrapper .fr').html(res.paging);
            $('.srch_total_num').html(res.total);
            $('.upload-overlay-loading-fix').hide();
            $('.upload-overlay-loading-fix').removeClass('hotelsLoader');
        }
    });
}