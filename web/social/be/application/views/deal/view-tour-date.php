<div class="title">Date details info</div>
<br/>
<div class="sect-date">
<div class="info-nb">1</div>
    <div class="info-desp">date</div>
    <div class="add-deal"><?= anchor("$controller/view/".$deal->id, 'edit');?></div>
    
<table class="table">
    <tr>
        <th>from</th>
        <td>friday 06 november   2015</td>
    </tr>
    <tr class="lasttr">
        <th>to</th>
        <td>friday 06 november   2015</td>
    </tr>
</table>
</div>

<div class="sect-date">
    <div class="info-nb">2</div>
    <div class="info-desp">destinations list</div>
    <table class="table">
        <tr>
            <th>destination’s name </th>
            <th>longitude</th>
            <th>latitude</th>
        </tr>
    </table>
</div>
<div class="country">istanbul</div>
<div class="sect-date hotel-description">
     <div class="boxicon"><img src="/media/images/box-icon.png" /></div>
    <div class="sect-name">hotel's list</div>
     <div class="add-deal"><?= anchor("$controller/view/".$deal->id, 'add');?></div>
    <table class="table">
        <tr>
            <th>hotel name </th>
            <th>stars</th>
            <th>pic</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>Ramada Encore Istanbul Bayrampasa</td>
            <td>4</td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr>
            <td>Ramada Encore Istanbul Bayrampasa</td>
            <td>4</td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr>
            <td>Ramada Encore Istanbul Bayrampasa</td>
            <td>4</td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr>
            <td>Ramada Encore Istanbul Bayrampasa</td>
            <td>4</td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
    </table>
    <div class="sect-name">optional tour’s list</div>
     <div class="add-deal"><?= anchor("$controller/view/".$deal->id, 'add');?></div>
    <table class="table">
        <tr>
            <th>optional tour name </th>
            <th>photo</th>
            <th>price</th>
            <th>currency </th>
            <th>seats left</th>
            <th>#of persons</th>
            <th>date</th>
            <th>time</th>
            <th>discount</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>1001 Nights Dinner Show</td>
            <td>X</td>
            <td>34</td>
            <td>&euro;</td>
            <td>15</td>
            <td>4</td>
            <td>07 nov 2015</td>
            <td> 21:00</td>
            <td></td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr>
            <td>1001 Nights Dinner Show</td>
            <td>X</td>
            <td>34</td>
            <td>&euro;</td>
            <td>15</td>
            <td>4</td>
            <td>07 nov 2015</td>
            <td> 21:00</td>
            <td></td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
    </table>
</div>
<br/>
<br/>
<br/>
<div class="country">capadoccia</div>
<div class="sect-date hotel-description">
     <div class="boxicon"><img src="/media/images/box-icon.png" /></div>
    <div class="sect-name">hotel's list</div>
     <div class="add-deal"><?= anchor("$controller/view/".$deal->id, 'add');?></div>
    <table class="table">
        <tr>
            <th>hotel name </th>
            <th>stars</th>
            <th>pic</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>Ramada Encore Istanbul Bayrampasa</td>
            <td>4</td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr>
            <td>Ramada Encore Istanbul Bayrampasa</td>
            <td>4</td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr>
            <td>Ramada Encore Istanbul Bayrampasa</td>
            <td>4</td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr class="lasttr">
            <td>Ramada Encore Istanbul Bayrampasa</td>
            <td>4</td>
            <td>X</td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
    </table>
    <div class="sect-name">optional tour’s list</div>
     <div class="add-deal"><?= anchor("$controller/view/".$deal->id, 'add');?></div>
    <table class="table">
        <tr>
            <th>optional tour name </th>
            <th>photo</th>
            <th>price</th>
            <th>currency </th>
            <th>seats left</th>
            <th>#of persons</th>
            <th>date</th>
            <th>time</th>
            <th>discount</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>1001 Nights Dinner Show</td>
            <td>X</td>
            <td>34</td>
            <td>&euro;</td>
            <td>15</td>
            <td>4</td>
            <td>07 nov 2015</td>
            <td> 21:00</td>
            <td></td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr class="lasttr">
            <td>1001 Nights Dinner Show</td>
            <td>X</td>
            <td>34</td>
            <td>&euro;</td>
            <td>15</td>
            <td>4</td>
            <td>07 nov 2015</td>
            <td> 21:00</td>
            <td></td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'Detail');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
    </table>
</div>
<br/>
<br/>
<br/>
<div class="country">capadoccia</div>
<div class="sect-date hotel-description">
    <div class="boxicon"><img src="/media/images/box-icon.png" /></div>
    <div class="sect-name">hotel's list</div>
     <div class="add-deal"><?= anchor("$controller/view/".$deal->id, 'add');?></div>
    <table class="table">
        <tr>
            <th>hotel name </th>
            <th>stars</th>
            <th>pic</th>
            <th></th>
            <th></th>
        </tr>
    </table>
    <div class="sect-name">optional tour’s list</div>
     <div class="add-deal"><?= anchor("$controller/view/".$deal->id, 'add');?></div>
    <table class="table">
        <tr>
            <th>optional tour name </th>
            <th>photo</th>
            <th>price</th>
            <th>currency </th>
            <th>seats left</th>
            <th>#of persons</th>
            <th>date</th>
            <th>time</th>
            <th>discount</th>
            <th></th>
            <th></th>
        </tr>
       
    </table>
</div>
<br/>
<br/>
<br/>
























<!--
<div>
    <div class="country-info">
        <span class="country-nb"> 1</span>-<span class="country-name">istanbul</span>
    </div>
    <div class="hotel_descp">
        <div class="hotel-name">
            <label>add hotel name</label>
            <input type="text" id="txtName"><span class="labelss"><a href="#">upload photo</a></span>
            <img src="" />
        </div>
        <div class="hotel-desc">
            <div class="row">
                <div class="room">
                    <label>add room name</label>
                    <input type="text" id="txtName" class="room-name"><span class="labelss"><a href="#">upload photo</a></span>
                    <img src="" />
                </div>
                <div>
                    <label>price</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>currency</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>availability</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label># of persons</label>
                    <input type="text" id="txtName">
                </div>
            </div>
            <div class="row">
                <div class="room">
                    <label>add room name</label>
                    <input type="text" id="txtName" class="room-name"><span class="labelss"><a href="#">upload photo</a></span>
                    <img src="" />
                </div>
                <div>
                    <label>price</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>currency</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>availability</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>#of persons</label>
                    <input type="text" id="txtName">
                </div>
            </div>
        </div>
        
    </div>
    <div class="hotel_descp last">
        <div class="hotel-name">
            <label>add hotel name</label><div class="add-deal"><a href="#">add hotel</a></div>
            <input type="text" id="txtName"><span class="labelss"><a href="#">upload photo</a></span>
           
        </div>
        <div class="hotel-desc">
            <div class="row">
                <div class="room">
                    <label>add room name</label>
                    <input type="text" id="txtName" class="room-name"><span class="labelss"><a href="#">upload photo</a></span>
                    
                </div>
                <div>
                    <label>price</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>currency</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>availability</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label># of persons</label>
                    <input type="text" id="txtName">
                </div>
            </div>
             <div class="row">
                <div class="room">
                    <label>add room name</label><div class="add-deal"><a href="#">add room</a></div>
                    <input type="text" id="txtName" class="room-name"><span class="labelss"><a href="#">upload photo</a></span>
                    
                </div>
                <div>
                    <label>price</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>currency</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>availability</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>#of persons</label>
                    <input type="text" id="txtName">
                </div>
            </div>
        </div>
    </div>
    <div class="optional-tour">
        <div class="tour-name">
            <label>add optionsl tour  name</label>
            <input type="text" id="txtName"><span class="labelss"><a href="#">upload photo</a></span>
           
        </div>
        <div>
            <label>price</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>currency</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>availability</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>#of persons</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>date</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>time</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>discount</label>
            <input type="text" id="txtName">
        </div>
    </div>
     <div class="optional-tour last">
        <div class="tour-name">
            <label>add optionsl tour  name</label><div class="add-deal"><a href="#">add optional tour</a></div>
            <input type="text" id="txtName"><span class="labelss"><a href="#">upload photo</a></span>
           
        </div>
        <div>
            <label>price</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>currency</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>availability</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>#of persons</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>date</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>time</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>discount</label>
            <input type="text" id="txtName">
        </div>
    </div>
        </div>

<div>
    <div class="country-info">
        <span class="country-nb"> 1</span>-<span class="country-name">cappadocia</span>
    </div>
    <div class="hotel_descp">
        <div class="hotel-name">
            <label>add hotel name</label>
            <input type="text" id="txtName"><span class="labelss"><a href="#">upload photo</a></span>
            <img src="" />
        </div>
        <div class="hotel-desc">
            <div class="row">
                <div class="room">
                    <label>add room name</label>
                    <input type="text" id="txtName" class="room-name"><span class="labelss"><a href="#">upload photo</a></span>
                    <img src="" />
                </div>
                <div>
                    <label>price</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>currency</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>availability</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label># of persons</label>
                    <input type="text" id="txtName">
                </div>
            </div>
            <div class="row">
                <div class="room">
                    <label>add room name</label>
                    <input type="text" id="txtName" class="room-name"><span class="labelss"><a href="#">upload photo</a></span>
                    <img src="" />
                </div>
                <div>
                    <label>price</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>currency</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>availability</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label># of persons</label>
                    <input type="text" id="txtName">
                </div>
            </div>
        </div>

    </div>
    <div class="hotel_descp last">
        <div class="hotel-name">
            <label>add hotel name</label><div class="add-deal"><a href="#">add hotel</a></div>
            <input type="text" id="txtName"><span class="labelss"><a href="#">upload photo</a></span>

        </div>
        <div class="hotel-desc">
            <div class="row">
                <div class="room">
                    <label>add room name</label>
                    <input type="text" id="txtName" class="room-name"><span class="labelss"><a href="#">upload photo</a></span>

                </div>
                <div>
                    <label>price</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>currency</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>availability</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label># of persons</label>
                    <input type="text" id="txtName">
                </div>
            </div>
            <div class="row">
                <div class="room">
                    <label>add room name</label><div class="add-deal"><a href="#">add room</a></div>
                    <input type="text" id="txtName" class="room-name"><span class="labelss"><a href="#">upload photo</a></span>

                </div>
                <div>
                    <label>price</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>currency</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label>availability</label>
                    <input type="text" id="txtName">
                </div>
                <div>
                    <label># of persons</label>
                    <input type="text" id="txtName">
                </div>
            </div>
        </div>
    </div>
    <div class="optional-tour">
        <div class="tour-name">
            <label>add optionsl tour  name</label>
            <input type="text" id="txtName"><span class="labelss"><a href="#">upload photo</a></span>

        </div>
        <div>
            <label>price</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>currency</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>availability</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>#of persons</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>date</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>time</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>discount</label>
            <input type="text" id="txtName">
        </div>
    </div>
    <div class="optional-tour last">
        <div class="tour-name">
            <label>add optionsl tour  name</label><div class="add-deal"><a href="#">add optional tour</a></div>
            <input type="text" id="txtName"><span class="labelss"><a href="#">upload photo</a></span>

        </div>
        <div>
            <label>price</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>currency</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>availability</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>#of persons</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>date</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>time</label>
            <input type="text" id="txtName">
        </div>
        <div>
            <label>discount</label>
            <input type="text" id="txtName">
        </div>
    </div>
</div>

-->
