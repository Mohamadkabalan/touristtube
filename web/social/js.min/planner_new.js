var numberOfDay = 4;
var currencyLogo = "$";
function loadStep(number) {
    switch (number) {
        case 1:
            goTo('1');
            break;
        case 2:
            goTo('2');
            break;
        case 3:
            goTo('3');
            break;
        case 4:
            goTo('4');
            break;
        default:
            var current = $(".plSteps.active").attr('data-value');
            
			var next;
            if (current != 4) {
                next = parseInt(current) + 1;
            }
            if (next == 1 || next == 2 || next == 3 || next == 4) {
                loadStep(next);
            }
            break;
    }
}
function goTo(number) {
    $(".plSteps").removeClass('active');
    $(".plSteps[data-value='" + number +"']").addClass('active');
    $(".gray").removeClass('active');
    $(".gray[data-value='" + number +"']").addClass('active');
    $(".plRightSteps").removeClass('active');
    $(".plRightSteps[data-value='" + number +"']").addClass('active');
}

function resetDailyBudget(val) {
    budgetPerDay = Math.round(val / $(".plannerADaySel").attr("data-value"));

	if(budgetPerDay=="NaN"){
    $(".plannerDescRight,.dailyDisplay").html(" ");
	}
	else
	{
	   $(".plannerDescRight,.dailyDisplay").html(budgetPerDay + " " + currencyLogo);
	}
	}


$(function() {
    $(document).on('click', ".validate", function() {
        //loadStep(0);
		//$("#planner_form").submit();
    });

    // aCuisineOption onclick
    $(document).on('click', ".aCuisineOption", function() {
        var dVal = $(this).attr('data-value');
        var dHtml = $(this).html();
        $(this).toggleClass('active');
        if ($(this).hasClass('active')) {
            $(".cuisineRightList").prepend('<div class="aCuisineRight" data-value="' + dVal + '">'+dHtml+'</div>');
        }else{
            $(".cuisineRightList .aCuisineRight[data-value='" + dVal +"']").remove();
        }
		
    });
//   / aCuisineOption onclick
    // plannerADay onclick
    $(document).on('click', ".plannerADay", function() {
        var dThis = $(this);
        numberOfDay = dThis.attr('data-value');
        $('.plannerADay').removeClass('plannerADaySel');
        dThis.addClass('plannerADaySel');
	  	$("#Numdays").text(numberOfDay);
		$("#day_select").val(numberOfDay);
    });
	
//   / plannerADay onclick
    // aThemeOption onclick
    
	$(document).on('click', ".aThemeOption", function() {
        $(this).toggleClass('active');		
		var ThemeOption = $(this).attr('data-value');
		var dHtml = $(this).html();
		
		$(this).hasClass('active');
		 if ($(this).hasClass('active')) {
             $("#budgetDisplay").append('<a class="budgetDisplay_themes" id= "budgetDisplay_themes" data-value="' + ThemeOption + '">'+dHtml+',</a><input type="hidden" name="the_val[]" id="' + ThemeOption + '" value="' + ThemeOption + '">' );
			//var themearr = [];
			//var = themearr.push(ThemeOption);
			
			
         } else{
			 $("#budgetDisplay .budgetDisplay_themes[data-value='" + ThemeOption +"']").remove();
          }
		  var divCount = $(".budgetDisplay_themes").length;
		  $("#theme_val").val(divCount);
		  
		var elems = document.getElementById( "budgetDisplay_themes" );
		// Convert the NodeList to an Array
		
    });
	   
	   
//   / aThemeOption onclick

    var instance_name = null;
    Calendar.setup({
        cont: "calendar",
        bottomBar: false,
        selectionType: Calendar.SEL_MULTIPLE,
        onSelect   : function() {
            var date = Calendar.intToDate(this.selection.get());
            console.log(Calendar.printDate(date, "%Y-%m-%d"));
            var  day = Calendar.printDate(date, "%d");
			$("#dayVal").text(day);
			$("#dateVal").val(day);				
			var month = new Array();
           month[0] = "January";
           month[1] = "February";
           month[2] = "March";
           month[3] = "April";
           month[4] = "May";
           month[5] = "June";
           month[6] = "July";
           month[7] = "August";
           month[8] = "September";
           month[9] = "October";
     	   month[10] = "November";
		   month[11] = "December";
		     var mnth = month[date.getMonth()];
		     $("#monthVal").text(mnth);
					
    		//TO_CAL.args.min = date;
            //TO_CAL.redraw();
            //$('#fromtxt').attr('data-cal',Calendar.printDate(date, "%Y-%m-%d"));

            //addCalTo(this);
            //this.hide();
        }
    });

//    $("#budgetSlider").slider({
//        from: 0, to: 19500, step: 500, smooth: true, round: 0, dimension: "&nbsp;$", skin: "tube",
//        onstatechange: function(e) {
//            var pointers = this.o.pointers;
//            var value = pointers[0].value.origin;
//            var tovalue = pointers[1].value.origin;
//            var output = value == tovalue ? value + currencyLogo : value + currencyLogo + ' - ' + tovalue + currencyLogo;
//            $('.budgetOutput').html(output)
//            $('.budgetOutput').attr('data-value', tovalue);
//            resetDailyBudget();
//        }
//    });




$("[data-slider]")
    .each(function () {
      var input = $(this);
      $("<span>")
        .addClass("budgetOutput")
        .insertAfter($(this));
    })
   
   .bind("slider:ready slider:changed", function (event, data) {
      $(this)
        .nextAll(".budgetOutput:first")
          .html((data.value)+'$');
     resetDailyBudget(data.value);
    });
});



 
 //Country  
   $(document).ready(function(){
    $(".step2").on('click', function() {
          var country_val = $(this).attr('data-value');
		  $(".countryDesc").text(country_val); 
		  $("#c_val").val(country_val);
		loadStep(2);
	  });
	  
	  
$(".validate").click(function() {
var c_val= $("#c_val").val();
var cuisine= $(".aCuisineRight").text();
var dayVal=  $("#dateVal").val();
var numdays= $("#day_select").val();
var errorToReturn = '';

if(c_val=="")
{
  errorToReturn += 'please, specify your destination ';
}
 

   if (dayVal == '') {
            if (errorToReturn != '') {
                errorToReturn += 'and your arrival date ';
            } else {
                errorToReturn += 'please, specify your arrival date ';
            }
			
        }
  if (numdays == '') {
            if (errorToReturn != '') {
                errorToReturn += 'and No.of days ';
            } else {
                errorToReturn += 'please, specify your No. of days ';
            }
        }
 
 
if (errorToReturn != '') {
            TTAlert({
                msg: errorToReturn,
                type: 'alert',
                btn1: 'ok',
                btn2: '',
                btn2Callback: null
            });
            return;
        }
		else{loadStep(0);
		$("#planner_form").submit();
		}

});

    });
	
	
// Country onclick


function loadStep2(number) {
    switch (number) {
        case 1:
            goTo2('1');
            break;
        case 2:
            goTo2('2');
            break;
        case 3:
            goTo2('3');
            break;
        case 4:
            goTo2('4');
            break;

      case 5:
            goTo2('5');
            break;
			
			 case 6:
            goTo2('6');
            break;
        default:
            var current = $(".plSteps.active").attr('data-value');
            
			var next;
            if (current != 6) {
                next = parseInt(current) + 1;
            }
            if (next == 1 || next == 2 || next == 3 || next == 4 || next == 5 || next == 6) {
                loadStep2(next);
            }
            break;
    }
}
function goTo2(number) {
   
    $(".gray").removeClass('active');
    $(".gray[data-value='" + number +"']").addClass('active');
   
    $(".plRightSteps").removeClass('active');
    $(".plRightSteps[data-value='" + number +"']").addClass('active');
}

