<h1><?= $title?>   </h1>
<br />
<div>
    <input type="text" id="txtName" placeholder="Filter by name..."/>
    <input type="text" id="txtCountryCode" placeholder="Filter by country code..."/>
<!--    <input type="text" id="txtCountry" placeholder="Filter by country..."/>-->
    <select id="txtCountry" style="height:29px;"> 
        <option value="">Filter by country code</option>
        <?php foreach($country as $co){ ?>
            <option value="<?php echo $co->hotelCountryCode; ?>"><?php echo $co->hotelCountryName; ?></option>
        <?php  } ?>
    </select>
    <input type="text" id="txtCity" placeholder="Filter by city..." <?php if(isset($ci)) echo 'value="'.$ci.'"'; ?>/>
    <button type="button" id="btnFilter">Search</button>
    <button type="button" id="btnReset">Reset</button>
</div>
<br />
<div id="listContainer">
    <?php $this->load->view('hotel/ajax_list');
//        if($this->uri->segment(2) == 'index'){ 
//            $this->load->view('hotel/ajax_list');
//        }else{
//            $this->load->view('hotel/addedByUserList');
//        }
    ?>
</div>