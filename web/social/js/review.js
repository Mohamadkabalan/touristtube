$(function(){
    $(document).on('change', ".place", function() {
        var $this = $(this).closest('.radioOpt');
        setSelectedRadio($this);
    });
    
    $('.place').each(function () {
        var $this = $(this);
        var $id = $this.attr('id');
        if (document.getElementById($id).checked) {            
            var $thispr = $this.closest('.radioOpt');
            setSelectedRadio($thispr);
        }
    });
    //$('#Hotel.place').change();
    autocompleteReviews($('.nameOfPlace'));
});
function setSelectedRadio($this){
    var dataType = $this.attr('data-type');
    var dataStr = $this.attr('data-str');
    $('.nameOfPlace').attr('data-type',dataType);
    $('.searchDesc').html(dataStr);
}