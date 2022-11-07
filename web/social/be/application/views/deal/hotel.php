<div class="title">Hotel detailed Info</div>
<br/>
<div class="sect-date">
<div class="info-nb">1</div>
    <div class="info-desp">hotel</div>
    <div class="edit-deal"><?= anchor("$controller/view/".$deal->id, 'edit');?></div>
    <table class="table">
        <tr>
            <th>name of the hotel</th>
            <td>Four Seasons Istanbul at the Bosphorus</td>
        </tr>
        <tr>
            <th>stars</th>
            <td>5</td>
        </tr>
        <tr class="lasttr">
            <th>photo</th>
            <td>X</td>
        </tr>
    </table>
</div>


<div class="sect-date">
    <div class="info-nb">2</div>
    <div class="info-desp">room’s list</div>
     <div class="add-deal"><?= anchor("$controller/view/".$deal->id, 'add');?></div>
    <table class="table">
        <tr>
            <th>type of room </th>
            <th>photo</th>
            <th>price</th>
            <th>currency </th>
            <th>rooms left</th>
            <th>#of persons</th>
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
            <td></td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'edit');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr>
            <td>1001 Nights Dinner Show</td>
            <td>X</td>
            <td>34</td>
            <td>&euro;</td>
            <td>15</td>
            <td>4</td>
            <td></td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'edit');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
        <tr class="lasttr">
            <td>1001 Nights Dinner Show</td>
            <td>X</td>
            <td>34</td>
            <td>&euro;</td>
            <td>15</td>
            <td>4</td>
            <td></td>
            <td class="link"><?= anchor("$controller/view/".$deal->id, 'edit');?></td>    
            <td class="link"><?= anchor("$controller/ajax_delete_day/".$day->id, "Delete", array('class'=>'deleteAct'));?></td> 
        </tr>
    </table>
</div>
<br/>
<br/>
<br/>
