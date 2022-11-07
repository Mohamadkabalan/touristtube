<h1><?= $title?>   </h1>
<br />
<div>
    <input type="text" id="txtName" placeholder="Filter by name..."/>
    <select id="txtStars" style="height:29px;"> 
        <option value="">Filter by stars</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
    </select>
<!--    <input type="text" id="txtCountry" placeholder="Filter by country..."/>-->
    <select id="txtCountry" style="height:29px;"> 
        <option value="">Filter by country</option>
        <?php foreach($country as $co){ ?>
            <option value="<?php echo $co->hotelCountryCode; ?>"><?php echo $co->hotelCountryName; ?></option>
        <?php  } ?>
    </select>
    <input type="text" name="typehotel" class="typehotel" id="typehotel" value="1" />
    <input type="text" id="txtCity" placeholder="Filter by city..." <?php if(isset($ci)) echo 'value="'.$ci.'"'; ?>/>
    <input type="text" id="txtId" placeholder="Filter by id..."/>
    <button type="button" id="btnFilter">Search</button>
    <button type="button" id="btnReset">Reset</button>
</div>
<br />
<div id="listContainer">
    <?php $this->load->view('hotelchain/ajax_list');
//        if($this->uri->segment(2) == 'index'){ 
//            $this->load->view('hotel/ajax_list');
//        }else{
//            $this->load->view('hotel/addedByUserList');
//        }
    ?>
</div>