<h1><?= $title?>   </h1>
<br />
<div>
    <input type="text" id="txtName" placeholder="Filter by title..."/>
    <input type="text" id="txtCountryCode" placeholder="Filter by country code..."/>
    <input type="text" id="txtCity" placeholder="Filter by city..." <?php if(isset($ci)) echo 'value="'.$ci.'"'; ?>/> 
	<!--Added a new text box video id for to filter list using video id by sushma mishra on 28-08-2015 -->
	<input type="text" id="txtVideoId" placeholder="Filter by video id..."/>
    <input type="text" id="txtHashId" placeholder="Filter by hash id..."/>
    <button type="button" id="btnFilter">Search</button>
    <button type="button" id="btnReset">Reset</button>
</div>
<br />
<div id="listContainer">
 <?= $this->load->view('ml/ajax_list') ?>
</div>