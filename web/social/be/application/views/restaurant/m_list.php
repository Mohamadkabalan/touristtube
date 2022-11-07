<h1><?= $title?>   </h1>
<br />
<div>
    <input type="text" id="txtName" placeholder="Filter by name..."/>
    <input type="text" id="txtCountryCode" placeholder="Filter by country code..." <?php if(isset($cc)) echo 'value="'.$cc.'"'; ?>/>
    <input type="text" id="txtCity" placeholder="Filter by city..."/>
    <button type="button" id="btnFilter">Search</button>
    <button type="button" id="btnReset">Reset</button>
</div>
<br />
<div id="listContainer">
 <?= $this->load->view('restaurant/m_ajax_list') ?>
</div>