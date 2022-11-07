<h1><?= $title?>   </h1>
<br />
<div>
    <input type="text" id="txtName" placeholder="Filter by name..."/>
    <input type="text" id="txtKeyword" placeholder="Filter by keyword..."/>
    <button type="button" id="btnFilter">Search</button>
    <button type="button" id="btnReset">Reset</button>
</div>
<br />
<div id="listContainer">
    <?php $this->load->view('hotels/ajax_hotel_search');
//        if($this->uri->segment(2) == 'index'){ 
//            $this->load->view('hotel/ajax_list');
//        }else{
//            $this->load->view('hotel/addedByUserList');
//        }
    ?>
</div>