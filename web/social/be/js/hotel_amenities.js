var controller = 'hotels';

function add_new_amenity(id, name, has_count) {
    var count_input = '';
    if (has_count === '1') {
        count_input = '<div class="col-md-9">' +
                '<input style="width: 70px;" type="text" value="0" class="form-control">' +
                '</div>';
    }
    var html = '<div data-id="' + id + '" class="form-group amenity-container" style="overflow: hidden;">' +
            '<input class="remove_amenity" style="float: left" type="button" value="Remove" >' +
            '<label class="col-md-2 control-label">' + name + '</label>' +
            count_input +
            '</div>';
    $('#amenities_container').append(html);
}

$(function () {

    $('#add_amenity').on('click', function () {
        var selected_id = $('#amenity_select').val();
        if ($('#amenities_container').find('div[data-id=' + selected_id + ']').length == 0) {
            var selected_option = $($('#amenity_select')[0].selectedOptions[0]);
            var name = selected_option.html();
            var has_count = selected_option.attr('data-has-count');
            add_new_amenity(selected_id, name, has_count);
        }
    });

    $('#amenities_container').on('click', 'input.remove_amenity', function () {
        $(this).parent().remove();
    });

    $('#save_amenities').on('click', function () {
        var hotel_id = $('#hotel_id').val();
        console.log(hotel_id);
        var added_amenities = $('div.amenity-container');
        var amenities = [];
        $.each(added_amenities, function () {
            var amenity = $(this);
            var id = amenity.attr('data-id');
            var count_element = amenity.find('input:text');
            var count = 0;
            if (count_element.length > 0) {
                count = $(count_element[0]).val();
            }
            amenities.push(
                    {
                        id: id,
                        count: count
                    });
        });
        $.ajax({
            url: './' + controller + '/ajax_save_amenities',
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if(res.success === 1){
                    window.location = "./hotels/view/" + hotel_id;
                }
            },
            data: { hotel_id: hotel_id, amenities : amenities }
        });
    });
});