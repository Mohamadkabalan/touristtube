<?php

namespace FlightBundle\vendors\sabre\requests\v1;

use Doctrine\ORM\EntityManager;

class SabrePassengerDetailsRequest extends SabreRequestHeader {

    /**
     *
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $entityManager) {
        $this->em = $entityManager;
    }

    public function passengerDetailsRequest($request, $marketingAirline = "") {

        if(isset($request['passportCheck']) && $request['passportCheck'])
        {
            $pnr = $request['pnr'];

            $pnrRequest = <<<EOR
<SOAP-ENV:Body>
        <PassengerDetailsRQ xmlns="http://services.sabre.com/sp/pd/v3_3" version="3.3.0" IgnoreOnError="false" HaltOnError="false">
           <PostProcessing IgnoreAfter="false" RedisplayReservation="true" UnmaskCreditCard="false" />
           <PreProcessing IgnoreBefore="true">
              <UniqueID ID="$pnr" />
           </PreProcessing>
     <SpecialReqDetails>
      <SpecialServiceRQ>
         <SpecialServiceInfo>
EOR;
            foreach($request as $key => $passportDetails)
            {

                $passportExpiry = $request['passportInfo']['ExpiryDate']->format('Y-m-d');
                $passportNo = $request['passportNo'];
                $passportIssueCountry = $request['passportInfo']['issueCountryCode'];
                $passportNationalityCountry = $request['passportInfo']['nationalityCountryCode'];
                $year = $request['passportExpiry']['year'];
                $month = $request['passportExpiry']['month'];
                $day = $request['passportExpiry']['day'];
                $gender = $request['gender'];
                $nameNumber = $key;
                $firstname = $request['firstName'];
                $surname = $request['lastName'];

                $pnrRequest .= <<<EOR
                        <AdvancePassenger SegmentNumber="A">
                            <Document ExpirationDate="$passportExpiry" Number="$passportNo" Type="P">
                                <IssueCountry>$passportIssueCountry</IssueCountry>
                                <NationalityCountry>$passportNationalityCountry</NationalityCountry>
                            </Document>
                            <PersonName DateOfBirth="$year-$month-$day" Gender="$gender" NameNumber="$nameNumber">
                              <GivenName>$firstname</GivenName>
                              <Surname>$surname</Surname>
                           </PersonName>
                           <VendorPrefs>
                              <Airline Hosted="false" />
                           </VendorPrefs>
                        </AdvancePassenger>
EOR;
            }

            $pnrRequest .= <<<EOR
                        
         </SpecialServiceInfo>
      </SpecialServiceRQ>
   </SpecialReqDetails>
    </PassengerDetailsRQ>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;
        }else{

        $num = 1;
        $record = 0;
        $type = '';
        $specialRequirement = preg_replace('/[^A-Za-z0-9 \-]/', '', $request['specialRequirement']);
        $mobileCountryCode = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($request['mobileCountryCode']);
        $mobile = preg_replace('/[^0-9.]+/', '', $mobileCountryCode->getDialingCode() . $request['mobile']);
        $alternativeNumber = preg_replace('/[^0-9.]+/', '', $request['alternativeNumber']);
        $email = $request['email'];
        $airlines = $marketingAirline;
        $airlineHosted = ($airlines == 'AA') ? 'true' : 'false';
        $membershipId = isset($request['membership_id']) ? $request['membership_id'] : "";
        // $programID = $airlines.$membershipId;
		$programID = $airlines;



        $pnrRequest = <<<EOR
<SOAP-ENV:Body>
    <PassengerDetailsRQ version="3.3.0" HaltOnError="true" IgnoreOnError="true" xmlns="http://services.sabre.com/sp/pd/v3_3">
        <PostProcessing IgnoreAfter="false" RedisplayReservation="true">
	    <ARUNK_RQ/>
	    <EndTransactionRQ>
	       <EndTransaction Ind="true"/>
	       <Source ReceivedFrom="TOURIST TUBE SWS"/>
	    </EndTransactionRQ>
        </PostProcessing>  
              
        <PriceQuoteInfo>
EOR;
        foreach ($request['passengerDetails'] as $passengerDetail) {
            if ($passengerDetail['type'] !== $type)
                $record++;
            $pnrRequest .= <<<EOR
            <Link NameNumber="$num.1" Record="$record"/>
EOR;
                    $type = $passengerDetail['type'];

                    $num++;
                }
                $pnrRequest .= <<<EOR
        </PriceQuoteInfo>
                        
        <SpecialReqDetails>
EOR;
            if ($specialRequirement != "") {
                $pnrRequest .= <<<EOR
            <AddRemarkRQ>
                <RemarkInfo>
                    <Remark Type="General">
                        <Text>$specialRequirement</Text>
                    </Remark>
                </RemarkInfo>
            </AddRemarkRQ>
EOR;
            }
                $pnrRequest .= <<<EOR
            <SpecialServiceRQ>
                <SpecialServiceInfo>
EOR;
                $number = 1;
                $adtNumber = 1;
                foreach ($request['passengerDetails'] as $passengerDetail) {
                    $firstname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail['firstName']));
                    $surname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail['surname']));
                    $year = $passengerDetail['dateOfBirth']['year'];
                    $month = (intval($passengerDetail['dateOfBirth']['month']) < 10) ? '0' . $passengerDetail['dateOfBirth']['month'] : $passengerDetail['dateOfBirth']['month'];
                    $day = (intval($passengerDetail['dateOfBirth']['day']) < 10) ? '0' . $passengerDetail['dateOfBirth']['day'] : $passengerDetail['dateOfBirth']['day'];
                    $gender = ($passengerDetail['gender'] == 0) ? 'M' : 'F';
                    if ($passengerDetail['type'] === 'INF') {
                        $gender .= 'I';
                        $number = $adtNumber++;
                    }
                    $nameNumber = $number . '.1';

                    if(isset($passengerDetail['passportNo']) && !empty($passengerDetail['passportNo'])){

                        $passportNo = $passengerDetail['passportNo'];

                        $passengerDetail['passportExpiry']['month'] = (intval($passengerDetail['passportExpiry']['month']) < 10) ? '0' . $passengerDetail['passportExpiry']['month'] : $passengerDetail['passportExpiry']['month'];
                        $passengerDetail['passportExpiry']['day'] = (intval($passengerDetail['passportExpiry']['day']) < 10) ? '0' . $passengerDetail['passportExpiry']['day'] : $passengerDetail['passportExpiry']['day'];

                        $passportExpiry = implode("-", $passengerDetail['passportExpiry']);
                        $issueCountry = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passengerDetail['passportIssueCountry']);
                        $nationalityCountry = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passengerDetail['passportNationalityCountry']);

                        $passportIssueCountry = $issueCountry->getCode();
                        $passportNationalityCountry = $nationalityCountry->getCode();

                    $pnrRequest .= <<<EOR
                        <AdvancePassenger SegmentNumber="A">
                            <Document ExpirationDate="$passportExpiry" Number="$passportNo" Type="P">
                                <IssueCountry>$passportIssueCountry</IssueCountry>
                                <NationalityCountry>$passportNationalityCountry</NationalityCountry>
                            </Document>
                            <PersonName DateOfBirth="$year-$month-$day" Gender="$gender" NameNumber="$nameNumber">
                              <GivenName>$firstname</GivenName>
                              <Surname>$surname</Surname>
                           </PersonName>
                            <VendorPrefs>
                                <Airline Hosted="$airlineHosted"/>
                             </VendorPrefs>
                        </AdvancePassenger>
EOR;

                    }else{

                        $pnrRequest .= <<<EOR
                    <SecureFlight SegmentNumber="A">
                       <PersonName DateOfBirth="$year-$month-$day" Gender="$gender" NameNumber="$nameNumber">
                          <GivenName>$firstname</GivenName>
                          <Surname>$surname</Surname>
                       </PersonName>
                        <VendorPrefs>
                            <Airline Hosted="$airlineHosted"/>
                         </VendorPrefs>
                    </SecureFlight>
EOR;

                    }
                    $number++;
                }
                $infNumber = 1;
                $passNumber = 1;
                foreach ($request['passengerDetails'] as $passengerDetail) {
                    if ($passengerDetail['type'] === 'INF') {

                        $firstname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail['firstName']));
                        $surname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail['surname']));
                        $dob = date("dMy", strtotime($passengerDetail['dateOfBirth']['year'] . '-' . $passengerDetail['dateOfBirth']['month'] . '-' . $passengerDetail['dateOfBirth']['day']));
                        $pnrRequest .= <<<EOR
                  <Service SegmentNumber="A" SSR_Code="INFT">
                     <PersonName NameNumber="$infNumber.1"/>
                     <Text>$surname/$firstname/$dob</Text>
                     <VendorPrefs>
                        <Airline Hosted="$airlineHosted"/>
                     </VendorPrefs>
                  </Service>
EOR;
                        $infNumber+=1;
                    }else{
                        if (isset($passengerDetail['receiveAlerts']) ) {

                            if($passengerDetail['receiveAlerts']){

                                // default to PNR contact
                                $passengerPhone = $mobile;
                                $passengerEmail = $email;

                                $passengerPhoneExists = (isset($passengerDetail['phone']) && strlen(trim($passengerDetail['phone'])));
                                $passengerEmailExists = (isset($passengerDetail['email']) && strlen(trim($passengerDetail['email'])));

                                if ((!isset($passengerDetail['usePhoneEmail']) || !$passengerDetail['usePhoneEmail']) && $passengerPhoneExists && $passengerEmailExists)
                                {
                                    $passengerPhone = $passengerDetail['phone'];
                                    $passengerEmail = $passengerDetail['email'];
                                }

                                $passengerEmail = strtoupper(str_replace('@', '//', $passengerEmail));

                                $pnrRequest .= <<<EOR
	<Service SSR_Code="CTCM" SegmentNumber="A">
        <PersonName NameNumber="$passNumber.1"/>
        <Text>$passengerPhone</Text>
    </Service>
	<Service SSR_Code="CTCE" SegmentNumber="A">
        <PersonName NameNumber="$passNumber.1"/>
        <Text>$passengerEmail</Text>
    </Service>
EOR;
                            }
                        }else{
                            $pnrRequest .= <<<EOR
<Service SSR_Code="CTCR" SegmentNumber="A">
        <PersonName NameNumber="$passNumber.1"/>
        <Text>REFUSED</Text>
    </Service>						
EOR;
                        }
                    }


                    $passNumber+=1;
                }
                $pnrRequest .= <<<EOR
               </SpecialServiceInfo>
            </SpecialServiceRQ>
        </SpecialReqDetails>

        <TravelItineraryAddInfoRQ>
            <AgencyInfo>
                <Ticketing TicketType="7T-"/>
            </AgencyInfo>
            <CustomerInfo>
                <ContactNumbers>
                    <ContactNumber Phone="$mobile" PhoneUseType="H" />
EOR;
                if ($alternativeNumber != "") {
                    $pnrRequest .= <<<EOR
                    <ContactNumber Phone="$alternativeNumber" PhoneUseType="H" />
EOR;
                }
                $pnrRequest .= <<<EOR
                </ContactNumbers>
EOR;
                if ($membershipId != "") {
                $pnrRequest .= <<<EOR
                <CustLoyalty MembershipID="$membershipId" NameNumber="1.1" ProgramID="$programID" />
EOR;
                }
                $pnrRequest .= <<<EOR
                <Email Address="$email" NameNumber="1.1" Type="CC" />
EOR;
                $personNumber = 1;
                foreach ($request['passengerDetails'] as $passengerDetail) {
                    $personNameNumber = $personNumber . '.1';
                    $passengerType = $passengerDetail['type'];
                    $passengerGivenName = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail['firstName']));
                    $passengerSurname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail['surname']));
                    $infant = "";
                    if ($passengerDetail['type'] === 'INF') {
                        $infant = 'Infant="true"';
                    }
                    $pnrRequest .= <<<EOR
                <PersonName NameNumber="$personNameNumber" PassengerType="$passengerType" $infant>
                    <GivenName>$passengerGivenName</GivenName>
                    <Surname>$passengerSurname</Surname>
                </PersonName>
EOR;
                    $personNumber++;
                }
                $pnrRequest .= <<<EOR
            </CustomerInfo>
        </TravelItineraryAddInfoRQ>
    </PassengerDetailsRQ>
</SOAP-ENV:Body>
</SOAP-ENV:Envelope>
EOR;
        }
        return $pnrRequest;
    }

}
