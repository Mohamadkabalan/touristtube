var objSelected = '';

$(document).ready(function () {
    $(document).on('click', ".cancel", function () {
	$('.fancybox-close').click();
    });
    $(document).on('change', ".optChek", function () {
	var data_value = $(this).attr('data-value');
	if($(this).is(":checked")) {
	    $('.optChek').each(function(){
		var $this = $(this);
		if( $this.attr('data-value')==data_value ){
		    $this.prop( "checked", true );
		}
	    });
	    if( data_value == 'all' ){
                $('.optChek').removeClass('active');
                $('.existing_bag_item').show();
		
		$('.optChek').each(function(){
		    var $this = $(this);
		    if( $this.attr('data-value') != 'all' ){
			$this.prop( "checked", false );
		    }
		});
            }else{
		$('.optChekAll').prop( "checked", false );
		updateBagItemDisplay();
            }
	}else{
	    $('.optChek').each(function(){
		var $this = $(this);
		if( $this.attr('data-value') != 'all' && $this.attr('data-value')==data_value ){
		    $this.prop( "checked", false );
		}
	    });
	    if( data_value != 'all' ){
		if( !$('.optChek').is(":checked")) {
		    $('.optChekAll').prop( "checked", true );		    
		}
		updateBagItemDisplay();
	    }else{
		$('.optChekAll').prop( "checked", true );
	    }
	}
    });
    $('.optChekAll').prop( "checked", true );
    $('.optChekAll').change();
    //opening pop up for edit_bag_item
    $('.edit_bag_item').each(function () {
	var $this = $(this);
	var itemid = $this.attr('data-id');
	$this.fancybox({
	    padding: 0,
	    margin: 0,
	    beforeLoad: function () {
		showTTOverlay("popup_edit_itemid");
		$('#popup_edit_itemid').html('');
		getBagItem(itemid);
		objSelected = $this.closest('.existing_bag_item');
		$('.fancybox-close').click();
	    }
	});
    });

    //opening of the pop up for edit
    $('.edit_bag').each(function () {
	var $this = $(this);
	var bagId = $this.attr('data-id');

	$this.fancybox({
	    padding: 0,
	    margin: 0,
	    beforeLoad: function () {
		$('#popup_creatid').html('');
		showTTOverlay("popup_editid");
		$.ajax({
		    url: $('#getBagInfo').val(),
		    type: 'POST',
		    data: {bagId: bagId},
		    success: function (result) {
			hideTTOverlay();
			$('#popup_editid').html('');
			var data = result.data;
			try {
			} catch (Ex) {
			    return;
			}
			if (result.success) {
			    var str = '<div class="edit_your_bag">' + Translator.trans('edit your bag') + '</div>';
			    str += '<div><input type="text" id="name" value="' + data.cb_name + '" class="bageditname"></div>';
			    str += '<div><button type="button" class="delete deletebag" data-id="' + data.cb_id + '">' + Translator.trans('Delete bag') + '</button></div>';
			    str += '<div><button type="button" class="cancel cancelBag" data-id="' + data.cb_id + '">' + Translator.trans('Cancel') + '</button></div>';
			    str += '<div><button type="button" class="save savebagedit" data-id="' + data.cb_id + '">' + Translator.trans('Save') + '</button></div>';
			    str += '<div class="bag_edit">' + $('.redlinks_discover').html() + '</div>';
			    str += '<div class="bag_edit marginbotton10">' + $('.redlinks_hotels').html() + '</div>';
			    objSelected = $this.closest('.existing_bag_item');
			    $('#popup_editid').html(str);
			} else {
			    TTAlert({
				msg: result.message,
				type: 'alert',
				btn1: t('ok'),
				btn2: '',
				btn2Callback: null
			    });
			    $('.fancybox-close').click();
			}
		    },
		    error: function (error) {
			alert('error; ' + eval(error));
		    }
		});
	    }
	});
    });

    $('#addNewbag').each(function () {
	var $this = $(this);
	$this.fancybox({
	    padding: 0,
	    margin: 0,
	    beforeLoad: function () {
		$('#popup_creatid').html('');
		var str = '<div class="edit_your_bag">' + Translator.trans('add a new bag') + '</div>';
		str += '<div class="creat_input"><input type="text" id="name" value="" class="addnewbagname"></div>';
		str += '<div><button type="submit" class="add add_new_bag">' + Translator.trans('ADD') + '</button></div>';
		$('#popup_creatid').html(str);
	    }
	});
    });

    $(document).on('click', ".add_new_bag", function () {
	var name = $('.addnewbagname').val();
	if (name == '') {
	    TTAlert({
		msg: Translator.trans('invalid name'),
		type: 'alert',
		btn1: t('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    $('.fancybox-close').click();
	    return;
	}

	$.ajax({
	    url: $('#addBagPath').val(),
	    type: 'POST',
	    data: {name: name},
	    success: function (data) {
		try {
		} catch (Ex) {
		    return;
		}
		if (data.success) {
		    $('.fancybox-close').click();
		    document.location.reload();
		} else {
		    TTAlert({
			msg: Translator.trans(data.message),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		}
	    },
	    error: function (error) {
		alert('error; ' + eval(error));
	    }
	});
    });

    $(document).on('click', ".savebagedit", function () {
	var id = $(this).attr('data-id');
	var name = $('.bageditname').val();
	if (name == '') {
	    TTAlert({
		msg: Translator.trans('Invalid name.'),
		type: 'alert',
		btn1: t('ok'),
		btn2: '',
		btn2Callback: null
	    });
	    $('.fancybox-close').click();
	    return;
	}

	$.ajax({
	    url: $('#editBagPath').val(),
	    type: 'POST',
	    data: {id: id, name: name},
	    success: function (data) {
		try {
		} catch (Ex) {
		    return;
		}
		if (data.success) {
		    objSelected.find('.bgname').html(name);
		} else {
		    TTAlert({
			msg: Translator.trans(data.message),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		}
	    },
	    error: function (error) {
		alert('error; ' + eval(error));
	    }
	});
	$('.fancybox-close').click();
    });

    $(document).on('click', ".savebagitemedit", function () {
	var id = $(this).attr('data-id');
	var bagid = $('.changebagname').val();

	$.ajax({
	    url: $('#updateBagItemPath').val(),
	    type: 'POST',
	    data: {id: id, bagId: bagid},
	    success: function (data) {
		try {
		} catch (Ex) {
		    return;
		}
		if (data.success) {
		    document.location.reload();
		} else {
		    TTAlert({
			msg: Translator.trans(data.message),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		}
	    },
	    error: function (error) {
		alert('error; ' + eval(error));
	    }
	});

	$('.fancybox-close').click();
    });

    $(document).on('click', ".deletebagitem", function () {
	var id = $(this).attr('data-id');
	$.ajax({
	    url: $('#deleteBagItemPath').val(),
	    type: 'POST',
	    data: {id: id},
	    success: function (data) {
		try {
		} catch (Ex) {
		    return;
		}
		if (data.success) {
		    objSelected.remove();
		    objSelected = '';
		} else {
		    TTAlert({
			msg: Translator.trans(data.message),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		}
	    },
	    error: function (error) {
		alert('error; ' + eval(error));
	    }
	});
	$('.fancybox-close').click();
    });

    $(document).on('click', ".deletebag", function () {
	var id = $(this).attr('data-id');
	$.ajax({
	    url: $('#deleteBagPath').val(),
	    type: 'POST',
	    data: {id: id},
	    success: function (data) {
		try {
		} catch (Ex) {
		    return;
		}
		if (data.success) {
		    objSelected.remove();
		    objSelected = '';
		} else {
		    TTAlert({
			msg: Translator.trans(data.message),
			type: 'alert',
			btn1: t('ok'),
			btn2: '',
			btn2Callback: null
		    });
		}
	    },
	    error: function (error) {
		alert('error; ' + eval(error));
	    }
	});
	$('.fancybox-close').click();
    });
});

function getBagItem(itemid) {
    $.ajax({
	url: $('#getBagItemPath').val(),
	type: 'POST',
	data: {id: itemid},
	success: function (result) {
	    hideTTOverlay();
	    try {
	    } catch (Ex) {
		return;
	    }
	    if (result.success) {
		var data = result.data;
		var bagList = data.baglist;
		var str = '<div class="edit_your_bag">' + Translator.trans('edit this item') + '</div>';

		str += '<div><label>' + Translator.trans('bag') + '</label>';
		str += '<select class="changebagname">';

		$.each(bagList, function (key, value) {
		    $selected = '';
		    if (itemid == value.id) {
			$selected = ' selected="selected"';
		    }
		    str += '<option value="' + value.id + '"' + $selected + '>' + Translator.trans(value.name) + '</option>';
		});
		str += '</select></div>';
		str += '<div><button type="submit" class="delete deletebagitem" data-id="' + itemid + '">' + Translator.trans('delete item') + '</button></div>';
		str += '<div><button type="submit" class="cancel">' + Translator.trans('CANCEL') + '</button></div>';
		str += '<div><button type="submit" class="save savebagitemedit" data-id="' + itemid + '"> ' + Translator.trans('SAVE') + '</button></div>';
		$('#popup_edit_itemid').html(str);
	    } else {
		TTAlert({
		    msg: result.message,
		    type: 'alert',
		    btn1: t('ok'),
		    btn2: '',
		    btn2Callback: null
		});
	    }
	},
	error: function (error) {
	    alert('error; ' + eval(error));
	}
    });
}
function updateBagItemDisplay(){
    $('.existing_bag_item').hide();
    $('.optChek').each(function(){
        var $this = $(this);
        if( $this.is(":checked") && $this.attr('data-value') == 'all' ){
            $('.existing_bag_item').show();
        }else if($this.is(":checked")) $('.existing_bag_item[data-type='+$this.attr('data-value')+']').show();
    });
}