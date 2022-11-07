var $container;
var fixFilterAfterCurrencyChange;

$(document).ready(function () {
    $('.filters :checkbox:checked').removeAttr("checked");
    $('#amount').val("");
    $('.sortingSelect').val("");

    // filter functions
    var filterFns = {};
    // store filter for each group
    var filters = {};
    // store sortValue for each sort group
    var sortValue = [];

    // init Isotope
    $container = $('.grid').isotope({
        itemSelector: '.fly',
        filter: function () {
            var isMatched = true;

            for (var prop in filters) {
                var filter = filters[ prop ];
                // use function if it matches
                filter = filterFns[ filter ] || filter;
                // test each filter
                if (filter) {
                    isMatched = isMatched && $(this).is(filter);
                }
                // break if not matched
                if (!isMatched) {
                    break;
                }
            }
            return isMatched;
        },
        getSortData: {
            cheapestPrice: function (itemElem) {
                var price = $(itemElem).find('.flight-price').attr("data-price");
                return parseFloat(price.replace(/[\(\)]/g, ''));
            },
            highestPrice: function (itemElem) {
                var price = $(itemElem).find('.flight-price').attr("data-price");
                return parseFloat(price.replace(/[\(\)]/g, ''));
            },
            shortestDuration: function (itemElem) {
                var duration = parseInt($(itemElem).find('.leaving-duration').attr("data-flight-duration"));
                
                if ($(itemElem).find('.returning-duration').length){
                    duration = duration + $(itemElem).find('.returning-duration').attr("data-flight-duration");
                }
                
                return duration; //parseFloat(duration.replace(/[\(\)]/g, ''));
            },
            longestDuration: function (itemElem) {
               var duration = parseInt($(itemElem).find('.leaving-duration').attr("data-flight-duration"));
                
                if ($(itemElem).find('.returning-duration').length){
                    duration = duration + $(itemElem).find('.returning-duration').attr("data-flight-duration");
                }
                
                return duration; //parseFloat(duration.replace(/[\(\)]/g, ''));
            },
            minimumStops: function (itemElem) {
                var stops = "";
                if ($(itemElem).find('.returning-stops').length > 0 && $(itemElem).find('.leaving-stops').attr("data-stops") < $(itemElem).find('.returning-stops').attr("data-stops")) {
                    stops = $(itemElem).find('.returning-stops').attr("data-stops");
                } else {
                    stops = $(itemElem).find('.leaving-stops').attr("data-stops");
                }
                return parseFloat(stops.replace(/[\(\)]/g, ''));
            },
            maximumStops: function (itemElem) {
                var stops = "";
                if ($(itemElem).find('.returning-stops').length > 0 && $(itemElem).find('.leaving-stops').attr("data-stops") < $(itemElem).find('.returning-stops').attr("data-stops")) {
                    stops = $(itemElem).find('.returning-stops').attr("data-stops");
                } else {
                    stops = $(itemElem).find('.leaving-stops').attr("data-stops");
                }
                return parseFloat(stops.replace(/[\(\)]/g, ''));
            },
            earliestDeparture: function (itemElem) {
                var timestamp = $(itemElem).find('.leaving-stops').attr("data-earliestdeparture");
                return timestamp;
            },
            earliestArrival: function (itemElem) {
                var timestamp = $(itemElem).find('.leaving-stops').attr("data-earliestarrival");
                return timestamp;
            },
        },
        // sortBy: [ 'minimumStops', 'cheapestPrice', 'shortestDuration' ],
        sortAscending: {
            cheapestPrice: true,
            highestPrice: false,
            shortestDuration: true,
            longestDuration: false,
            minimumStops: true,
            maximumStops: false,
            earliestDeparture: true,
            earliestArrival:true
        }
    });
//    $(".accordionwithstyle").on("click", function(){
//        $('.contentfly').css('width','auto');
//        $container.isotope('layout');
//    });
    $("#sortPrice").change(function () {
        sortValue = $.grep(sortValue, function (a) {
            return a !== "cheapestPrice" && a !== "highestPrice";
        });

        if ($(this).val() !== "")
            sortValue.unshift($(this).val());

        $container.isotope({sortBy: sortValue});
    });

    $("#sortDuration").change(function () {
        sortValue = $.grep(sortValue, function (a) {
            return a !== "shortestDuration" && a !== "longestDuration";
        });

        if ($(this).val() !== "")
            sortValue.unshift($(this).val());

        $container.isotope({sortBy: sortValue});
    });

    $("#sortStops").change(function () {
        sortValue = $.grep(sortValue, function (a) {
            return a !== "minimumStops" && a !== "maximumStops";
        });

        if ($(this).val() !== "")
            sortValue.unshift($(this).val());

        $container.isotope({sortBy: sortValue});
    });

    $("#sortEarliestTime").change(function () {
        sortValue = $.grep(sortValue, function (a) {
            return a !== "earliestDeparture" && a !== "earliestArrival";
        });

        if ($(this).val() !== "")
            sortValue.unshift($(this).val());

        $container.isotope({sortBy: sortValue});
    });

    $('#bestCombinationFilter').click(function() {
        if (this.checked) {
            $container.isotope({sortBy: ['cheapestPrice','shortestDuration']});
        } else {
            $container.isotope({sortBy: 'original-order'});
        }
    });
    
    $container.on('arrangeComplete', function (event, filteredItems) {
        if (filteredItems.length === 0) {
            $(".no-filter-result").show("fast");
        } else {
            $(".no-filter-result").hide("fast");
        }
    });

    $('.filters').on('change', 'input', function () {

        var exclusives = [];
        var inclusives = [];

        $('#airlinesGroup input').each(function (i, elem) {
            if (elem.checked) {
                inclusives.push(elem.getAttribute('data-filter'));
            }
        });
        $('#refundableGroup input').each(function (i, elem) {
            if (elem.checked) {
                exclusives.push(elem.getAttribute('data-filter'));
            }
        });

        exclusives = exclusives.join('');

        var filterValue;
        if (inclusives.length) {
            // map inclusives with exclusives for
            filterValue = $.map(inclusives, function (value) {
                return value + exclusives;
            });
            filterValue = filterValue.join(', ');
        } else {
            filterValue = exclusives;
        }

        // set filter for group
        filters[ 'filterValue' ] = filterValue;
        // arrange, and use filter fn
        $container.isotope('arrange');
    });

    $('[id=slider-time-1]').slider({
        range: true,
        min: 0,
        max: 1440,
        step: 15,
        values: [0, 1440],
        slide: function (event, ui) {
            numberToTime(ui.values[0], ui.values[1], '#amount-time-1');

            filterFns.leavingDeparture = function () {
                var number = $(this).find('.leaving-departure-time-minutes').attr('data-time-minutes');
                return parseInt(number, 10) >= ui.values[0] && parseInt(number, 10) <= ui.values[1];
            };

            filters[ 'slider-time-1' ] = 'leavingDeparture';

            // arrange, and use filter fn
            $container.isotope('arrange');
        }
    });
    $('[id=slider-time-2]').slider({
        range: true,
        min: 0,
        max: 1440,
        step: 15,
        values: [0, 1440],
        slide: function (event, ui) {
            numberToTime(ui.values[0], ui.values[1], '#amount-time-2');

            filterFns.leavingArrival = function () {
                var number = $(this).find('.leaving-arrival-time-minutes').attr('data-time-minutes');
                return parseInt(number, 10) >= ui.values[0] && parseInt(number, 10) <= ui.values[1];
            };

            filters[ 'slider-time-2' ] = 'leavingArrival';

            // arrange, and use filter fn
            $container.isotope('arrange');
        }
    });
    var minLeavingFlightDuration = parseInt(parseInt($('#slider-time-3').attr('data-min-duration')) - 15);
    var maxLeavingFlightDuration = parseInt(parseInt($('#slider-time-3').attr('data-max-duration')) + 15);
    var leavingSteps = (maxLeavingFlightDuration - minLeavingFlightDuration > 30) ? 15 : 1;
    $('[id=slider-time-3]').slider({
        range: true,
        min: minLeavingFlightDuration,
        max: maxLeavingFlightDuration,
        step: leavingSteps,
        values: [minLeavingFlightDuration, maxLeavingFlightDuration],
        slide: function (event, ui) {
            numberToTime(ui.values[0], ui.values[1], '#amount-time-3');
            filterFns.leavingDuration = function () {
                var number = $(this).find('.leaving-duration').attr('data-flight-duration');
                return parseInt(number, 10) >= ui.values[0] && parseInt(number, 10) <= ui.values[1];
            };

            filters[ 'slider-time-3' ] = 'leavingDuration';

            // arrange, and use filter fn
            $container.isotope('arrange');
        }
    });
    $('[id=slider-time-4]').slider({
        range: true,
        min: 0,
        max: 1440,
        step: 15,
        values: [0, 1440],
        slide: function (event, ui) {
            numberToTime(ui.values[0], ui.values[1], '#amount-time-4');

            filterFns.returningDeparture = function () {
                var number = $(this).find('.returning-departure-time-minutes').attr('data-time-minutes');
                return parseInt(number, 10) >= ui.values[0] && parseInt(number, 10) <= ui.values[1];
            };

            filters[ 'slider-time-4' ] = 'returningDeparture';

            // arrange, and use filter fn
            $container.isotope('arrange');
        }
    });
    $('[id=slider-time-5]').slider({
        range: true,
        min: 0,
        max: 1440,
        step: 15,
        values: [0, 1440],
        slide: function (event, ui) {
            numberToTime(ui.values[0], ui.values[1], '#amount-time-5');

            filterFns.returningArrival = function () {
                var number = $(this).find('.returning-arrival-time-minutes').attr('data-time-minutes');
                return parseInt(number, 10) >= ui.values[0] && parseInt(number, 10) <= ui.values[1];
            };

            filters[ 'slider-time-5' ] = 'returningArrival';

            // arrange, and use filter fn
            $container.isotope('arrange');
        }
    });
    var minReturningFlightDuration = parseInt(parseInt($('#slider-time-6').attr('data-min-duration')) - 15);
    var maxReturningFlightDuration = parseInt(parseInt($('#slider-time-6').attr('data-max-duration')) + 15);
    var returningSteps = (minReturningFlightDuration - minReturningFlightDuration > 30) ? 15 : 1;
    $('[id=slider-time-6]').slider({
        range: true,
        min: minReturningFlightDuration,
        max: maxReturningFlightDuration,
        step: returningSteps,
        values: [minReturningFlightDuration, maxReturningFlightDuration],
        slide: function (event, ui) {
            numberToTime(ui.values[0], ui.values[1], '#amount-time-6');
            filterFns.returningDuration = function () {
                var number = $(this).find('.returning-duration').attr('data-flight-duration');
                return parseInt(number, 10) >= ui.values[0] && parseInt(number, 10) <= ui.values[1];
            };

            filters[ 'slider-time-6' ] = 'returningDuration';

            // arrange, and use filter fn
            $container.isotope('arrange');
        }
    });

    var minFlightPrice = parseInt(Math.floor($('#price-range').attr('data-min-price')) - 1), maxFlightPrice = parseInt(Math.ceil($('#price-range').attr('data-max-price')) + 1);
    $("#price-range").slider({
        range: true,
        min: minFlightPrice,
        max: maxFlightPrice,
        values: [minFlightPrice, maxFlightPrice],
        slide: function (event, ui) {
//            $("#amount").val("$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ]);
            $("#amount").val(ui.values[ 0 ] + " - " + ui.values[ 1 ]);

            filterFns.priceFilter = function () {
                var number = $(this).find('.flight-price').attr('data-price');
                return parseInt(number, 10) >= ui.values[0] && parseInt(number, 10) <= ui.values[1];
            };

            filters[ 'price-group' ] = 'priceFilter';

            // arrange, and use filter fn
            $container.isotope('arrange');
        }
    });

    $("#slider-range-2").slider({
        range: "min",
        min: 0,
        max: 3,
        value: 3,
        slide: function (event, ui) {
            var stops = (ui.value === 1) ? " Stop" : " Stops";
            $("#amount-1").val(ui.value + stops);

            filterFns.stopsFilter = function () {
                var number = 0;
                if ($(this).find('.leaving-stops').attr('data-stops') >= $(this).find('.returning-stops').attr('data-stops')) {
                    number = $(this).find('.leaving-stops').attr('data-stops');
                } else {
                    number = $(this).find('.returning-stops').attr('data-stops');
                }
                return parseInt(number, 10) <= ui.value;
            };

            filters[ 'stops-group' ] = 'stopsFilter';

            // arrange, and use filter fn
            $container.isotope('arrange');
        }
    });


    var $window = $(window);
    var $pane = $('#alwaysvisible');

    function checkWidth() {
        var windowsize = $window.width();
        if (windowsize < 767) {

            function close_accordion_section() {
                $('.accordion-section .accordion-section-title').removeClass('active');
                $('.accordion-section .accordion-section-content').slideUp(300).removeClass('open');
            }

            $('.accordion-section .accordion-section-title').click(function (e) {
                var $this = $(this);
                var currentAttrValue = $(this).attr('href');
                if ($this.closest('.accordion-section').find('.accordion-section-content').css('display') != 'none') {
                    close_accordion_section();
                } else {
                    close_accordion_section();

                    // Add active class to section title
                    $(this).addClass('active');
                    // Open up the hidden content panel
                    $this.closest('.accordion-section').find('.accordion-section-content').slideDown(300).addClass('open');
                }
                e.preventDefault();
            });
        }
    }

    checkWidth();

    $(window).resize(checkWidth);



    $('.bool-slider .inset .control').click(function () {
        if (!$(this).parent().parent().hasClass('disabled')) {
            if ($(this).parent().parent().hasClass('true')) {
                $(this).parent().parent().addClass('false').removeClass('true');
            } else {
                $(this).parent().parent().addClass('true').removeClass('false');
            }
        }
    }
    );

    $(".morestyle").on("click", function () {
        var target = $(this).closest('.fly');
        target.find('.morestyle').hide();
        target.find('.lessstyle').show();
        target.find('.closedborder').hide();
        target.find('.collapsablediv').show();
        $container.isotope('layout');
    });
    $(".lessstyle").on("click", function () {
        var target = $(this).closest('.fly');
        target.find('.morestyle').show();
        target.find('.lessstyle').hide();
        target.find('.closedborder').show();
        target.find('.collapsablediv').hide();
        $container.isotope('layout');
    });
    if ($(".flexibledate").attr('data-checked') === 1 || $(".flexibledate").attr('data-checked') === 1) {
        $('.flexibledate').prop('checked', true);
    }
    if ($(".oneway").attr('data-checked') === 1 || $(".oneway").attr('data-checked') === 1) {
        $('.oneway').prop('checked', true);
        $('#toContainer').hide();
    }

    $(".submit-booking").click(function () {
        $('.overlay-loading-flights').show();
    });

    fixFilterAfterCurrencyChange = function (min, max) {
        $('#amount').val("");
        $('#price-range').attr('data-min-price', min);
        $('#price-range').attr('data-max-price', max);
        $("#price-range").slider({
            range: true,
            min: min,
            max: max,
            values: [min, max]
        });
        filterFns.priceFilter = function () {
            var number = $(this).find('.flight-price').attr('data-price');
            return parseInt(number, 10) >= min && parseInt(number, 10) <= max;
        };

        filters[ 'price-group' ] = 'priceFilter';
        $container.isotope('arrange');
    };
});

$(window).bind('resize load', function () {
    if ($(this).width() > 767)
    {
        $("div").removeClass("accordion-section");
    }
    if ($(this).width() < 767)
    {
        $("div").removeClass("accordion");
        $('[id=accordion]').accordion({
            collapsible: false,
        });
        $(".accordion").accordion({active: false, collapsible: true});
    }

});
$(window).load(function () {
    setTimeout(function () {
        // $('.overlay-loading-flights').hide();
        $('.flight-booking-result-page').show();
        $container.isotope('layout');
    }, 150);
});
function numberToTime(initValue, finalValue, id) {
    var hours1 = Math.floor(initValue / 60);
    var minutes1 = initValue - (hours1 * 60);

    if (hours1 < 10)
        hours1 = '0' + hours1;

    if (minutes1 < 10)
        minutes1 = '0' + minutes1;

    if (minutes1 === 0)
        minutes1 = '00';

    var hours2 = Math.floor(finalValue / 60);
    var minutes2 = finalValue - (hours2 * 60);

    if (hours2 < 10)
        hours2 = '0' + hours2;
    if (minutes2 < 10)
        minutes2 = '0' + minutes2;

    if (minutes2 === 0)
        minutes2 = '00';

    if (id === "#amount-time-3" || id === "#amount-time-6") {
        $(id).val(Math.floor(initValue / 60) + 'h ' + minutes1 + 'm - ' + Math.floor(finalValue / 60) + 'h ' + minutes2 + "m");
    } else {
        $(id).val(hours1 + ':' + minutes1 + ' - ' + hours2 + ':' + minutes2);
    }
}