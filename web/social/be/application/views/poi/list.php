<h1><?= $title?>   </h1>
<br />
<div>
    <input type="text" id="txtName" placeholder="Filter by name..."/>
    <input type="text" id="txtCountryCode" placeholder="Filter by country code..." <?php if(isset($cc)) echo 'value="'.$cc.'"'; ?>/>
    <input type="text" id="txtCityName" placeholder="Filter by city..."/>
    <button type="button" id="btnFilter">Search</button>
    <button type="button" id="btnReset">Reset</button>
</div>
<br />
<div id="listContainer">
 <?php  $this->load->view('poi/ajax_list'); 
    
//    if($this->uri->segment(2) == 'index'){ 
//            $this->load->view('poi/ajax_list'); 
//       }else{
//           $this->load->view('poi/addedByUserList');
//    }        
  ?>
</div>