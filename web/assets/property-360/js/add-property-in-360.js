var property_add_attr_id_index = 1
$(document).ready(function () 
{   
    $(document).on('click',".btn_add_property" ,function()
    {
	var send_data_bool = true;
	$('.property_add_container').each(function(index, element)
	{
	    var $property_name = $(this).find("input[name=property_name]").val().trim();
	    if ( !validatePropertyInfoForm( $(this), index ) )
	    {
		send_data_bool = false;
		return false;
	    } else if( $property_name == '' )
	    {
		send_data_bool = false;
		return false;
	    }
	});
	
	if( send_data_bool )
	{
	    var $container = $('.property_container');
	    var $property_add_template = $('#property_add_template').html();	
	    $container.append($property_add_template);
	    property_add_attr_id_index++;
	    $('.property_add_container:last').attr('id', 'request-location-form'+property_add_attr_id_index );
	    setPropertyIndex();
	    addAutoCompleteListCurrentCity( $('.property_add_container:last').attr('id') );
	}
    });
    
    $(document).on('click',".btn_remove_property" ,function()
    {
	if( $('.property_add_container').length>1 ) 
	{
	    var $parent= $(this).closest('.property_add_container');
	    $parent.remove();
	    setPropertyIndex();
	}
    });
});

function setPropertyIndex(){
    $('.property_add_container').each(function(index, element) {
	$(this).find('.property_index').html( (index + 1) );
    });
}
