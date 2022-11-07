<h1><?= $title?>   </h1>
<br />
<div>
    <input type="text" id="txtName" placeholder="Filter by name..."/>
    <input type="text" id="txtCountryCode" placeholder="Filter by country code..."/>
    <input type="text" id="txtCountry" placeholder="Filter by country..."/>
    <input type="text" id="txtCity" placeholder="Filter by city..." <?php if(isset($ci)) echo 'value="'.$ci.'"'; ?>/>
    <button type="button" id="btnFilter">Search</button>
    <button type="button" id="btnReset">Reset</button>
</div>
<br />
<div id="listContainer">
 <?= $this->load->view('hotel/m_ajax_list') ?>
</div>