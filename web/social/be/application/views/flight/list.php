<h1><?= $title?>   </h1>
<br />
<div>



<form class="form-inline">
    <div class="form-group">
    	<input type="text" size="20" class="form-control " id="txtPnr" placeholder="Filter by PNR..."/>
    </div>
    <div class="form-group">
    	<input type="text" size="20" class="form-control" id="txtEmail" placeholder="Filter by email..."/>
    </div>
    <div class="form-group">	
    	<input type="text" size="20" class="form-control" id="txtFname" placeholder="Filter by firstname..."/>
    </div>
    <div class="form-group">    
        <input type="text" size="20" class="form-control" id="txtSname" placeholder="Filter by surname..."/>
    </div>

    <div class="form-group">    
        <select id="txtStatus" class="form-control" style="margin-top: -10px;">
          <option value="0"> ---Status---  </option>
          <option value="SUCCESS"> SUCCESS </option>
          <option value="PENDING"> PENDING </option>
          <option value="CANCELLED"> CANCELLED </option>
        </select>
    </div>
    <div class="form-group" style="margin-top: -10px;">
        <button class="btn btn-primary" type="button" id="btnFilter">Search</button>
        <button class="btn btn-default" type="button" id="btnReset">Reset</button>
    </div>
</form>


</div>
<br />
<div id="listContainer">
 <?php  $this->load->view('flight/ajax_list');?>
</div>