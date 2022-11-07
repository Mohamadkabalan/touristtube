var controller = 'hotels';
var masonryoptions = {itemSelector: '.grid-item', isFitWidth: true, percentPosition: true};
$(function(){
    $('#facilitiesContainer').on('click', '.typeCheck', function(){
        var $this = $(this);
        var typeId = $this.attr('data-id');
        var status = $this.is(':checked');
//        console.log($this.is(':checked'));
//        console.log(typeId);
        $('.facilityCheck[data-type-id='+typeId+']').prop('checked', status);
    });
    $("#btnSave").click(function(){
        var checked = $('.facilityCheck:checked');
        var facility_ids = [];
        var hotel_id = $('#hotel_id').val();
        for(var i = 0;i<checked.length;i++){
            var $item = $(checked[i]);
            facility_ids.push($item.attr('data-id'));
        }
        $.loadingBlockShow({
            imgPath: 'media/images/icon.gif',
            text: 'Please Wait',
            style: {
              position: 'fixed',
              width: '100%',
              height: '100%',
//              background: 'rgba(255, 255, 255, .8)',
              opacity: 0.4,
              background: 'gray',
              left: 0,
              top: 0,
              zIndex: 10000
            }
        });


        $.ajax({
            url: './' + controller + '/save_hotel_facilities',
            type: 'post',
            success     : function (res)
            {
                $.loadingBlockHide();
//                alert('Changes applied successfully');
            },
            data: { id: hotel_id, facilities: facility_ids }
        });
    });
    $('#facilitiesContainer').masonry(masonryoptions);
});