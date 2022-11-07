
//some global variables used in search pages

/**
 * which search page we are on
 * @type String
 */
var SEARCH_PAGE = '';

/**
 * which search category
 * @type String
 */
var SEARCH_CATEGORY;

/**
 * which global category are we in
 * @type integer
 */
var GLOBAL_CATEGORY = -1;

/**
 * which search page (pages to skip)
 * @type Number|Number
 */
var SearchPage = 0;

/**
 * what type of search to perform (a)ll,(v)ideo,(i)mages
 * @type String
 */
var SearchType;

/**
 * the search string
 * @type String
 */
var SearchString = '';

/**
 * if the search type popup is displayed
 * @type Number
 */
var searchflag = 0;

//////////////////////////////////////////

function GetSuggestLink(){
	return ReturnLink('/ajax/suggest.php?category='+ SEARCH_CATEGORY);
}

/**
 * sets the search page we are on
 * @param {string} sp
 */
function SearchPageSet(sp){
	SEARCH_PAGE = sp;
}

/**
 * gets which search page wer are on
 * @returns {string}
 */
function SearchPageGet(){
	return SEARCH_PAGE;
}

/**
 * sets the search page we are on
 * @param {string} sp
 */
function SearchCategorySet(sp){
	SEARCH_CATEGORY = sp;
}

/**
 * gets which search page wer are on
 * @returns {string}
 */
function SearchCategoryGet(){
	return SEARCH_CATEGORY;
}

/**
 * check if the event was for a alphanumeric (and space) keycode
 * @param {event} e
 * @returns {boolean}
 */
function checkChar(e) {
    var key;
    if (e.keyCode) key = e.keyCode;
    else if (e.which) key = e.which;
	if( (key == 8) || (key==46) ){
		//backspace or delete buttons
		return true;
	}
    if (/[^A-Za-z0-9 ]/.test(String.fromCharCode(key))) {
		return false;
    }else{
		return true;
	}
}

/**
 * global search results array
 */
var _TTSearchResults = new Array();

/**
 * resest the global search result arrays
 */
function SearchResultsReset(){
	_TTSearchResults = new Array();
}

/**
 * appends to the global search results array
 * @param search_string string
 */
function SearchResultsAppend(search_string){
	var search_strings = search_string.split(' ');
	$.each(search_strings,function(i,v){
		var vl = v.toLowerCase();
		if( $.inArray(vl,_TTSearchResults) === -1 ){
			_TTSearchResults.push(vl);
		}
	});
}

/**
 * attempts to complete the search results array
 */
function SearchResultsComplete(search_string){
	var final_res = '';
	var search_strings = search_string.toLowerCase().split(' ');
	$.each(search_strings,function(i,v1){
		$.each(_TTSearchResults,function(i,v2){
			if( v1 == v2 ){
				if( final_res != '') final_res += ' ';
				final_res += v1;
				return false;
			}else if( (v1.length != 0) && (v2.indexOf(v1) != -1) ){
				if( final_res != '') final_res += ' ';
				final_res += v2;
				return false;
			}
		});
	});
	return final_res;
}

/**
 * the ajax event
 * @type @exp;$@call;ajax
 */
var ajaxObject = null;

function AjaxCancelSearch(){
	if(ajaxObject != null){
		ajaxObject.abort();
		ajaxObject = null;
	}
}

function InitSorting(){
    //return false;
    var $optionSet = $('#sort-by'),
	$optionLinks = $optionSet.find('a');
	
	$('#sort-by').find('a').click(function(){
		$('#sort-by').hide();
	});
	
	$optionLinks.click(function(){
		
		var $this = $(this);
		
		if( $this.parent().parent().attr('id') == 'sort-by' ) $('#search_order').html($this.html());
		
		// don't proceed if already selected
		if ( $this.hasClass('selected') ) {
			return false;
		}
		
		$this.parent().parent().find('.selected').removeClass('selected');
		$this.addClass('selected');

		// make option object dynamically, i.e. { filter: '.my-filter-class' }
		var options = {},
		key = $this.parent().parent().attr('data-option-key'), /*go up to the ul*/
		value = $this.attr('data-option-value');
                $('#orderby').val(value);
                $('.SearchSubmit').click();
	});
}

var serach_press_timeout = null;

$(document).ready(function(){
	$('#SearchCategoryList').hide();
        InitSorting();
        autocompleteMedia($('#SearchField'));
	/*
	$('#SearchField').blur(function(){
		if( $(this).data('typed') ) return;
	$(this).val('Search');
	}).focus(function(){
		if( $(this).data('typed') ) return;
		$(this).val('');
	}).keyup(function(){
		$(this).data('typed',true);
	});*/
	
	
	
	$('#SearchCategoryList li').click(function(){
		SEARCH_CATEGORY = $(this).attr('data-type');
                //console.log(SEARCH_CATEGORY)
		$('.SearchCategoryBtn').html( $(this).html()+'<span class="SearchCategoryIcon"></span>' );
		$('#t').val(SEARCH_CATEGORY)
		//dont remove search string when category is selcted
		// $('#SearchField').data('typed',true);
		
		//$('#SearchField').val('');
		/*if( SEARCH_CATEGORY == 'u' ){
			$('#SearchForm').attr('action', ReturnLink('/tubers') );
		}else if( SEARCH_CATEGORY == 'h' ){
			$('#SearchForm').attr('action', ReturnLink('/search-location/type/hotel') );
		}else if( SEARCH_CATEGORY == 'r' ){
			$('#SearchForm').attr('action', ReturnLink('/search-location/type/restaurant') );
		}else{
			if( SEARCH_CATEGORY == 'i'){

			}else if( SEARCH_CATEGORY == 'v'){

			}else if($(this).val() == 'm'){

			}
			$('#SearchForm').attr('action', ReturnLink('/search') );
		}
		 */
                if(SEARCH_CATEGORY == 'u'){
                    $('#SearchForm').attr('action', ReturnLink('/tubers') );
                }
                else{
                    $('#SearchForm').attr('action', ReturnLink('/search') );
                }
                $('#SearchCategoryList').fadeOut();
		searchflag = 0;
           
	});

	$('.SearchCategoryBtn').click(function(){
		if(searchflag == 0){
			$('#SearchCategoryList').fadeIn();
			searchflag = 1;
		}else{
			$('#SearchCategoryList').fadeOut();
			searchflag = 0;
		}
	});
    $('#SearchCategoryList').on('mouseleave', function () {
        $('#SearchCategoryList').fadeOut();
        searchflag = 0;
    });
	
	
	$('#SearchForm').submit(function(e){
		/*
                 * var Cat = SEARCH_CATEGORY;

		var SS = $('input[name=ss]').val();
		var cur_action = $('#SearchForm').attr('action');
		var new_action = '';
		
		if($('#t').val() !=''){
			Cat = 'category';
		}
		
		if( Cat == 'v' ||  Cat == 'i' || Cat[0] == 'a'){
			//new_action = cur_action + '/SearchCategory/' + Cat + '/ss/' + SS;
			new_action = ReturnLink('/search/SearchCategory/' + Cat + '/ss/' + SS);
		}else if( Cat == 'h' ){
			new_action = ReturnLink('/search-location/type/hotel/search-string/' + SS);
		}else if( Cat == 'r' ){
			new_action = ReturnLink('/search-location/type/restaurant/search-string/' + SS);
		}else if( Cat == 'category' ){
			new_action = ReturnLink('/search/ss/' + SS + '/cat_id/'+$('#my_cat_id').val());
		}else{
			new_action = ReturnLink('/tubers/search-string/' + SS);
		}
		//submit new form and prevent old form from submitting
		e.preventDefault();
		setTimeout(function(){
			window.location = new_action;//	$('#SearchForm2').attr('action',new_action).submit();
		},10);
		return false;*/
	});
	/*
	in case 
		source: function( request, response ) {
	var term = request.term;
	if ( term in cache ) {
		response( cache[ term ] );
		return;
	}

	$.getJSON( "search.php", request, function( data, status, xhr ) {
		cache[ term ] = data;
		response( data );
	});
}*/
	/*$('#q').autocomplete({
		delay: 5,
		search: function(event, ui) {
			var $searchString = $('#q');
			var searchString = $searchString.val();
			if(searchString.length < 3){
                            event.preventDefault();
			}
			SearchResultsReset();
			$('#q').autocomplete( "option", "source", GetSuggestLink() );
		},
		focus: function( event, ui ) {
		//$(this).val(ui.item.right);
		return false;
		},
		select: function(event, ui) {
			var wrong = ui.item.wrong;
			var right = ui.item.right;
			var oldVal = $('#q').val();
			//var newVal = oldVal.replace(wrong,right); 
			var newVal = right;//oldVal.replace(wrong,right); 
			$('#q').val( newVal );
			clearTimeout(serach_press_timeout);
			event.preventDefault();
			if( (SearchPageGet() === 'search') && (( SearchCategoryGet() === 'i' ) || ( SearchCategoryGet() === 'v' ) || ( SearchCategoryGet() === 'a' )) ){
				//search page
				if( newVal.toLowerCase() == SearchString.toLowerCase() ) return;
				//AjaxLoadSearch(newVal.toLowerCase());
			}else{ //media page or others
				$('#SearchForm').submit();
			}
		}
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		SearchResultsAppend(item.right);
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>"+ item.label + "</a>" )
		.appendTo( ul );
	}*/
	
	var auto_submit = 1500;
	if( ( typeof SEARCH_PAGE != 'undefined') && (SEARCH_PAGE == 'index')  ) auto_submit = 2500;
						
	/*$('#q').keyup(function(event){
		
		if( serach_press_timeout != null ){
			clearTimeout(serach_press_timeout);
			AjaxCancelSearch();
			serach_press_timeout = null;
		}
		
		if( !checkChar(event) ) return false;
		
		serach_press_timeout = setTimeout(function(){
			
			if( typeof SEARCH_PAGE == 'undefined') return;
								
			var autocompleted = SearchResultsComplete($('#q').val());
			
			if( ( SearchPageGet() === 'index') && (autocompleted !== '') ){
				//$('#q').val(autocompleted);
				//$('#SearchForm').submit();
			}else if( (SearchPageGet() === 'search') && (autocompleted !== '') ){
				//$('#q').val(autocompleted);  //dont fill in autocomplete auto complete
				//AjaxLoadSearch(autocompleted);
			}
			
		},auto_submit);
	});*/
});