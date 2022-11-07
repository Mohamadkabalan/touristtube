$(document).ready(function () {
    $(".allbutdiv").hide();
    $(".expand").click(function () {
        $(".allbutdiv").show();
        $(".expand").hide();
    });
    $(".allbutdiv").click(function () {
        $(".expand").show();
        $(".allbutdiv").hide();
    });
    
    $("#refineSearchSubmit").click(function(){
	var minPrice = parseInt($("#ex8").val());
	var maxPrice = parseInt($("#ex9").val());
         // validate minimum and maximum price
        if(minPrice >= maxPrice){
	    alert(Translator.trans("Minimum price must not exceed the maximum price."));
            return false;
        }else{
	    $(this).closest("form").submit();
	}
    });
    
    if($("#ex8").length)
    {
    var slider = new Slider("#ex8", {
        tooltip: 'always'
    });
    }
    
    if($("#ex9").length)
    {
        var slider = new Slider("#ex9", {
            tooltip: 'always'
        });
    }
    
    $('#addRow').click(function(){
        var apnd  = '<tr>';
            apnd += '<td><input type="text" id="fname" name="fname[]" class="form-control" value="" /></td>';
            apnd += '<td><input type="text" id="lname" name="lname[]" class="form-control" value="" /></td>';
            apnd += '<td><input type="text" id="age" name="age[]" class="form-control" value="" /></td>';
            apnd += '</tr>';
        $('#bookingTable').append(apnd);
    });
    
    $("#verify").click(function(){
        
        // validate form fields first
        if(!validateBookingFields()){
            return false;
        }
        
        var myData = new Object();
        myData['tourCode']  = $('#tourCode').val();
        myData['bookingDate'] = $('#bookingDate').val();
        myData['dealType']  = $('#dealType').val();
	myData['apiId']	    = $('#apiId').val();

	if ( $( "input[id='fname']" ).length ) {
        myData['fname'] = $("input[id='fname']").map(function(){
            return $(this).val();
        }).get();
	}

	if ( $( "input[id='lname']" ).length ) {
        myData['lname'] = $("input[id='lname']").map(function(){
            return $(this).val();
        }).get();
	}

	if ( $( "input[id='age']" ).length ) {
        myData['age'] = $("input[id='age']").map(function(){
            return $(this).val();
        }).get();
	}

	if ( $( "input[id='adults']" ).length ) {
	    myData['adults'] = $("input[id='adults']").val();
	}

	if ( $( "input[id='children']" ).length ) {
	    myData['children'] = $("input[id='children']").val();
	}

	if ( $( "input[id='infants']" ).length ) {
	    myData['infants'] = $("input[id='infants']").val();
	}

        var path = $('#availabilityUrl').val();

        $.ajax({
            url: path, 
            type: 'POST',
            data: myData,
            success: function(result){
                if(result.availability)
                {
		    $('#price').html('<strong>' + result.price + '<strong>');
                    $('#availabilityTxt').html('<font color="green"><strong>'+Translator.trans('Available!!!')+'</strong></font');
		    $('#departureTime').val(result.departureTime);
		    $('#totalPrice').val(result.price);
		    $('#priceId').val(result.priceId);
                    $('#verify').hide();
                    $('#cancel').show();
                    $('#continue').show();
                }
                else
                {
                    $('#availabilityTxt').html('<font color="red"><strong>' + result.errorMsg +'</strong></font');
                    $('#verify').show();
                    $('#cancel').hide();
                    $('#continue').hide();
                }
	    },
	    error: function (error) {
		alert('error; ' + eval(error));
	    }
});
    });

    $("#continue").click(function(){
         // validate form fields first
        if(!validateBookingFields()){
            return false;
        }
        //submit the form
        $(this).closest("form").submit();
    });

    $("#bookNow").click(function(){
         // validate form fields first
        if(!validateBookingFields()){
            return false;
        }
        //submit the form
	 $(this).prop('type', 'submit');
    });

    $("#cancel").click(function(){
        $('#verify').show();
        $('#cancel').hide();
        $('#bookNow').hide();
    }); 
    
    $('#bookingDate').datepicker({
	minDate:0
    });

    $('#back').click(function(){
	 window.location.href = generateLangURL('/dealSearch-'+$('#page').val());
});

    $('#backSearch').click(function(){
	 window.location.href = generateLangURL('/dealSearch-'+$('#page').val());
    });

    var today = new Date();
    if ( $( "#datesDealsContainer" ).length ) {
	$( "#datesDealsContainer" ).dateRangePicker({
	    autoClose: true,
	    showTopbar: false,
	    startDate: today,
	    getValue: function () {
		if ($('#startDate').val() && $('#endDate').val())
		    return $('#startDate').val() + ' to ' + $('#endDate').val();
		else
		    return '';
	    },
	    setValue: function (s, s1, s2) {
		$('#startDate').val(s1);
		$('#endDate').val(s2);
	    }
});
    }

    $('#dealsform').submit(function (event) {
        var ccity = $('#geo_city').val();
        if (ccity == '') {
            TTAlert({
                msg: Translator.trans('Invalid City or Destination'),
                type: 'alert',
                btn1: Translator.trans('ok'),
                btn2: '',
                btn2Callback: null
            });
            event.preventDefault();
            return;
        }
    });
});

function validateBookingFields(){
    /**
    * fieldname - the name of the input field
    * required - true if you dont want field to be empty
    * numeric - true if you want field to contain only numbers
    * displayName - the name youll display in the error message
    * 
    */
   var data = {
       "fields" : [ 
           {"fieldname" : "nameTitle", "required" : "true", "displayName" : Translator.trans("Name Title")}, 
           {"fieldname" : "fname[]",   "required" : "true", "displayName" : Translator.trans("First Name")}, 
           {"fieldname" : "lname[]",   "required" : "true", "displayName" : Translator.trans("Last Name")}, 
           {"fieldname" : "age[]",     "required" : "true", "displayName" : Translator.trans("Age"), "numeric" : "true"},
	   {"fieldname" : "adults",    "required" : "true", "displayName" : Translator.trans("Adults"), "numeric" : "true"},
	   {"fieldname" : "children",    "required" : "false", "displayName" : Translator.trans("Children"), "numeric" : "true"},
	   {"fieldname" : "infants",    "required" : "false", "displayName" : Translator.trans("Infants"), "numeric" : "true"},
	   {"fieldname" : "title",    "required" : "true", "displayName" : Translator.trans("Name Title")},
	   {"fieldname" : "firstName",    "required" : "true", "displayName" : Translator.trans("First Name")},
	   {"fieldname" : "lastName",    "required" : "true", "displayName" : Translator.trans("Last Name")},
	   {"fieldname" : "Gender",    "required" : "true", "displayName" : Translator.trans("Gender")},
	   {"fieldname" : "nameOfhotel",    "required" : "true", "displayName" : Translator.trans("Name Of Hotel OR Residence:")},
	   {"fieldname" : "hotelAddress",    "required" : "true", "displayName" : Translator.trans("Hotel OR Residence Address:")},
	   {"fieldname" : "emailAddress",    "required" : "true", "displayName" : Translator.trans("Email Address")},
	   {"fieldname" : "creditCardType",    "required" : "true", "displayName" : Translator.trans("Credit Card Type")},
	   {"fieldname" : "creditCardName",    "required" : "true", "displayName" : Translator.trans("Credit Card Name")},
	   {"fieldname" : "creditCardNum",    "required" : "true", "displayName" : Translator.trans("Credit Card Number"), "numeric" : "true"},
	   {"fieldname" : "creditCardExpiryDate",    "required" : "true", "displayName" : Translator.trans("Credit Card Expiry Date")},
	   {"fieldname" : "creditCardSecCode",    "required" : "true", "displayName" : Translator.trans("Credit Card Security Code"), "numeric" : "true"},
	   {"fieldname" : "creditCardBillingAddress",    "required" : "true", "displayName" : Translator.trans("Credit Card Billing Address")},
	   {"fieldname" : "creditCardBillingCity",    "required" : "true", "displayName" : Translator.trans("Credit Card Billing City")},
	   {"fieldname" : "creditCardBillingCountry",    "required" : "true", "displayName" : Translator.trans("Credit Card Billing Country")},
	   {"fieldname" : "creditCardBillingZip",    "required" : "true", "displayName" : Translator.trans("Credit Card Billing Zip Code"), "numeric" : "true"},
       ]
   };
   // pattern for numeric
   var patt = /[^0-9]/i;
   var errorMsg = '';
   var skipEndRow = false;
   var endFieldIndex = $("[name='fname[]']").length - 1;
   
   /**
    * Skip checking if end row of table is ALL EMPTY
    * These are all the fields found in the booking table. 
    */
    if($('#dealType').val() != 'ATTRACTIONS' &&  $( "[name='fname[]']" ).length && $("[name='fname[]']")[endFieldIndex].value.length == 0  
	&& $( "[name='lname[]']" ).length && $("[name='lname[]']")[endFieldIndex].value.length == 0 
	&& $( "[name='age[]']" ).length && $("[name='age[]']")[endFieldIndex].value.length == 0){
	    skipEndRow = true;
    }
   
   $.each(data, function(i){
       $.each(data[i], function(key, val){
           $("[name='"+val.fieldname+"']").each(function(j,k){
	       
	       /**
		* Skip checking if the situation above is fullfilled
		*/
	       if(j == endFieldIndex && skipEndRow){
		   return false;
	       }
	       
               // check if field exists
	       if($(this).length == 0){
		   return true;
	       }

               // check if field is empty
               var len = $(this).val().length;
               if(val.required && len === 0){
                   errorMsg += '\r\n'+val.displayName;
                   return false;
               }
               //check if field is numeric
               else if(typeof val.numeric !== 'undefined' && val.numeric && patt.test($(this).val()))
               {
                   errorMsg += '\r\n'+val.displayName;
                   return false;
               }
           });
       });
   });
   
   if(errorMsg !== ''){
       alert(Translator.trans('Invalid values for fields:\r\n')+errorMsg);
       return false;
   }
   
   return true;
}

function updateHiddenCity(){
     var gcity = $("#geo_city").attr("data-id");
    $("#webgeo_city").val(gcity);
}