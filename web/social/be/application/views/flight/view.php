<?php
    $controller = $this->router->class;
?>

<br/>
<table width="100%">
    <tr>
        <td><h1><?php echo $title?> </h1></td>
        <td align="right">
            <button type="button" data-toggle="modal" id="priceAdjust" class="btn btn-primary" style="color: #fff;background-color: #428bca;border-color: #357ebd;">Cancellation Fee</button>
        </td>
    </tr>
</table>
<hr/>
<br/>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" style="z-index: 9999;" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document" style="width: 700px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cancellation Fee</h4>
      </div>
      <div class="modal-body">
            
        <form>
          <input type="hidden" class="form-control" id="pnr_id" value="<?php echo $pnr->id ;?>">  
          <div class="form-group">
            <label for="addfee">Additional Fee</label>
            <input type="text" class="form-control" id="add_fee">
          </div>
          <div class="form-group">
            <label for="paymenturl">Customer Email</label>
            <input type="text" class="form-control" id="customer_email" value="<?php echo  $pnr->email ;?>">
          </div>
          

          <div class="form-group">
            <label for="paymenturl">Message Body</label>
<textarea class="form-control" rows="15" id="message_body"></textarea>
          </div>

        </form>

      </div>
      <div class="modal-footer">
        <img id="imgLoading" style="display: none" src="/media/images/ajax-loader.gif"/>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" style="margin-top: -3px;" id="saveAddFee" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<table width="100%">
        <tbody>
            <tr>
                <td align="right"><h1>PNR: <?php echo $pnr->pnr ;?></h1></td>
            </tr>
        </tbody>
    </table> 

<div style="width:700px;">
    <h4>Passenger Name Record</h4>
    <br/>

    <table class="table table-striped table-bordered" width="100%">
        <tbody>
            <tr>
                <th>Firstname</th> 
                <td><?php echo ucfirst( $pnr->first_name );?></td>
            </tr>
            <tr>
                <th>Surname</th> 
                <td><?php echo ucfirst( $pnr->surname );?></td>
            </tr>
            <tr>
                <th>Payment UUID</th> 
                <td><?php echo $pnr->payment_uuid ;?></td>
            </tr>
            <tr>
                <th>Country of residence</th> 
                <td><?php echo $pnr->country_of_residence;?></td>
            </tr>
            <tr>
                <th>Email</th> 
                <td><?php echo  $pnr->email ;?></td>
            </tr>
            <tr>
                <th>Mobile</th> 
                <td><?php echo $pnr->mobile ;?></td>
            </tr>
            <tr>
                <th>Alternative number</th> 
                <td><?php echo $pnr->alternative_number ;?></td>
            </tr>
            <tr>
                <th>Special requirement</th> 
                <td><?php echo $pnr->special_requirement ;?></td>
            </tr>
            <tr>
                <th>Status</th> 
                <td>
                    <?php if ( $pnr->status == "SUCCESS" ):?>
                        <span class="label label-success"><?php echo ucfirst( $pnr->status );?></span>
                    <?php elseif ( $pnr->status == "PENDING" ): ?>
                        <span class="label label-primary"><?php echo ucfirst( $pnr->status );?></span>
                    <?php elseif ( $pnr->status == "CANCELLED" ): ?>
                        <span class="label label-warning"><?php echo ucfirst( $pnr->status );?></span>
                    <?php else: ?>
                        <span class="label label-default"><?php echo ucfirst( $pnr->status );?></span>
                    <?php endif; ?>    
                </td>
            </tr>
            <tr>
                <th>Date booked</th> 
                <td><?php echo ucfirst( $pnr->creation_date );?></td>
            </tr>
        </tbody>
    </table>    
</div>
<br/>
<br/>
<div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#pass_details" aria-controls="pass_details" role="tab" data-toggle="tab">Passenger Details</a></li>
    <li role="presentation"><a href="#flight_info" aria-controls="flight_info" role="tab" data-toggle="tab">Flight Info</a></li>
    <li role="presentation"><a href="#flight_details" aria-controls="flight_details" role="tab" data-toggle="tab">Flight Details</a></li>
    <li role="presentation"><a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">Payments</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="pass_details">
        <br/>
        <br/>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td style="background-color: #ddd">Firstname</td>
                    <td style="background-color: #ddd">Surname</td>
                    <td style="background-color: #ddd">Type</td>
                    <td style="background-color: #ddd">Gender</td>
                    <td style="background-color: #ddd">Dob</td>
                    <td style="background-color: #ddd">Fare calcline</td>
                    <td style="background-color: #ddd">Ticket number</td>
                    <td style="background-color: #ddd"h>Ticket rph</td>
                    <td style="background-color: #ddd">Ticket status</td>
                    <td style="background-color: #ddd">Leaving baggage info</td>
                    <td style="background-color: #ddd">Returning baggage info</td>
                </tr>
            </thead>
            <tbody>
                   <?php foreach( $passenger_details as $passenger_detail ):?>
                    <tr> 
                        <td><?php echo $passenger_detail['first_name']; ?></td>
                        <td><?php echo $passenger_detail['surname']; ?></td>
                        <td><?php echo $passenger_detail['type']; ?></td>
                        <td><?php echo $passenger_detail['gender']; ?></td>
                        <td><?php echo $passenger_detail['dob']; ?></td>
                        <td><?php echo $passenger_detail['fare_calc_line']; ?></td>
                        <td><?php echo $passenger_detail['ticket_number']; ?></td>
                        <td><?php echo $passenger_detail['ticket_rph']; ?></td>
                        <td><?php echo $passenger_detail['ticket_status']; ?></td>
                        <td><?php echo $passenger_detail['leaving_baggage_info']; ?></td>
                        <td><?php echo $passenger_detail['returning_baggage_info']; ?></td>
                    </tr>
                <?php endforeach;?>     
            </tbody>
        </table> 

        <br/>
        <br/>
    </div>
    <div role="tabpanel" class="tab-pane" id="flight_info">
        <br/>
        <br/>
        <table class="table table-bordered">
        <thead>
            <tr>
                <td style="background-color: #ddd">Price</td>
                <td style="background-color: #ddd">Display price</td>
                <td style="background-color: #ddd">Base fare</td>
                <td style="background-color: #ddd">Display base fare</td>
                <td style="background-color: #ddd">Taxes</td>
                <td style="background-color: #ddd">Display taxes</td>
                <td style="background-color: #ddd">Currency</td>
                <td style="background-color: #ddd">Display currency</td>
                <td style="background-color: #ddd">Refundable</td>
                <td style="background-color: #ddd">One way</td>
                <td style="background-color: #ddd">Multi destination</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $flighinfo as $finfo ):?>
                <tr> 
                    <td><?php echo $finfo['price']; ?></td>
                    <td><?php echo $finfo['display_price']; ?></td>
                    <td><?php echo $finfo['base_fare']; ?></td>
                    <td><?php echo $finfo['display_base_fare']; ?></td>
                    <td><?php echo $finfo['taxes']; ?></td>
                    <td><?php echo $finfo['display_taxes']; ?></td>
                    <td><?php echo $finfo['currency']; ?></td>
                    <td><?php echo $finfo['display_currency']; ?></td>
                    <td>
                        <?php if ( $finfo['refundable'] > 0 ):?>
                            Yes
                        <?php else: ?>
                            No
                        <?php endif; ?> 
                    </td>
                    <td>
                        <?php if ( $finfo['one_way'] > 0 ):?>
                            Yes
                        <?php else: ?>
                            No
                        <?php endif; ?> 
                    </td>
                    <td>
                        <?php if ( $finfo['multi_destination'] > 0 ):?>
                            Yes
                        <?php else: ?>
                            No
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach;?>        
        </tbody>
        </table>  

        <br/>
        <br/>
    </div>
    <div role="tabpanel" class="tab-pane" id="flight_details">
        <br/>
        <br/>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td style="background-color: #ddd">Segment #</td>
                    <td style="background-color: #ddd">Departure airport</td>
                    <td style="background-color: #ddd">Arrival airport</td>
                    <td style="background-color: #ddd">Departure datetime</td>
                    <td style="background-color: #ddd">Arrival datetime</td>
                    <td style="background-color: #ddd">Airline</td>
                    <td style="background-color: #ddd">Operating airline</td>
                    <td style="background-color: #ddd">Flight number</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach( $flights_details as $flight_detail ):?>
                    <tr> 
                        <td><?php echo $flight_detail['segment_number']; ?></td>
                        <td><?php echo $flight_detail['departure_airport']; ?></td>
                        <td><?php echo $flight_detail['arrival_airport']; ?></td>
                        <td><?php echo $flight_detail['departure_datetime']; ?></td>
                        <td><?php echo $flight_detail['arrival_datetime']; ?></td>
                        <td><?php echo $flight_detail['airline']; ?></td>
                        <td><?php echo $flight_detail['operating_airline']; ?></td>
                        <td><?php echo $flight_detail['flight_number']; ?></td>
                    </tr>
                <?php endforeach;?>        
            </tbody>
        </table>  
    </div>

    <div role="tabpanel" class="tab-pane" id="payments">
        <br/>
        <br/>
        <div style="width:700px;">
            <table class="table table-striped table-bordered" width="60%">
                <tbody>
                    <tr>
                        <td>UUID</td> 
                        <td><?php echo  $payment->uuid; ?></td>
                    </tr>
                    <tr>
                        <td>Merchant Reference</td> 
                        <td><?php echo $payment->merchant_reference; ?></td>
                    </tr>
                    <tr>
                        <td>Fort ID</td> 
                        <td><?php echo $payment->fort_id; ?></td>
                    </tr>
                    <tr>
                        <td>User ID</td> 
                        <td><?php echo $payment->user_id; ?></td>
                    </tr>
                    <tr>
                        <td>Command</td> 
                        <td><?php echo  $payment->command; ?></td>
                    </tr>
                    <tr>
                        <td>Token Name</td> 
                        <td><?php echo $payment->token_name; ?></td>
                    </tr>
                    <tr>
                        <td>Customer IP</td> 
                        <td><?php echo $payment->customer_ip; ?></td>
                    </tr>
                    <tr>
                        <td>Language</td> 
                        <td><?php echo $payment->language; ?></td>
                    </tr>
                    <tr>
                        <td>Status</td> 
                        <td>
                               
                        </td>
                    </tr>
                    <tr>
                        <td>Response Code</td> 
                        <td><?php echo $payment->response_code; ?></td>
                    </tr>
                    <tr>
                        <td>Response Message</td> 
                        <td><?php echo $payment->response_message; ?></td>
                    </tr>
                    <tr>
                        <td>Remember Me</td> 
                        <td><?php echo $payment->remember_me; ?></td>
                    </tr>
                    <tr>
                        <td>Type</td> 
                        <td><?php echo $payment->type; ?></td>
                    </tr>
                    <tr>
                        <td>Device Fingerprint</td> 
                        <td><?php echo $payment->device_fingerprint; ?></td>
                    </tr>
                    <tr>
                        <td>Mobile SDK</td> 
                        <td><?php echo $payment->mobile_sdk; ?></td>
                    </tr>
                    <tr>
                        <td>User Agent</td> 
                        <td><?php echo $payment->user_agent; ?></td>
                    </tr>
                    <tr>
                        <td>Creation Date</td> 
                        <td><?php echo $payment->creation_date; ?></td>
                    </tr>
                    <tr>
                        <td>Updated Date</td> 
                        <td><?php echo $payment->updated_date; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>  

    </div>
  </div>

</div>

<br/>
<br/><br/>
<br/>


