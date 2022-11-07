<?php

namespace NewFlightBundle\vendors\sabre\requests;

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

    public function passengerDetailsRequest($passengerDetailsRequest) {

        $passengerNameRecord = $passengerDetailsRequest->getPassengerNameRecordModel();
        $passengerDetails = $passengerNameRecord->getPassengerDetailsModel();

        if(!empty($passengerDetailsRequest->getPassportCheck()))
        {
            $pnr = $passengerDetailsRequest->getPassengerNameRecordModel()->getPNR();

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
            foreach($passengerDetails as $key => $passengerDetail)
            {

                $passportExpiry = $passengerDetail->getPassportExpiry();
                $passportNo = $passengerDetail->getPassportNumber();
                $passportIssueCountry = $passengerDetail->getPassportIssuingCountry()->getCode();
                $passportNationalityCountry = $passengerDetail->getPassportNationalityCountry()->getCode();
                $dob = $passengerDetail->getDateOfBirth();
                $gender = $passengerDetail->getGender();
                $nameNumber = $key;
                $firstname = $passengerDetail->getFirstName();
                $surname = $passengerDetail->getSurname();

                $pnrRequest .= <<<EOR
                        <AdvancePassenger SegmentNumber="A">
                            <Document ExpirationDate="$passportExpiry" Number="$passportNo" Type="P">
                                <IssueCountry>$passportIssueCountry</IssueCountry>
                                <NationalityCountry>$passportNationalityCountry</NationalityCountry>
                            </Document>
                            <PersonName DateOfBirth="$dob" Gender="$gender" NameNumber="$nameNumber">
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
        $specialRequirement = preg_replace('/[^A-Za-z0-9 \-]/', '', $passengerNameRecord->getSpecialRequirement());
        $mobileCountryCode = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passengerNameRecord->getMobileCountryCode());
        $mobile = preg_replace('/[^0-9.]+/', '', $mobileCountryCode->getDialingCode() . $passengerNameRecord->getMobileNumber());
        $alternativeNumber = preg_replace('/[^0-9.]+/', '', $passengerNameRecord->getAlternativeNumber());
        $email = $passengerNameRecord->getEmail();
        $airlines = $passengerDetailsRequest->getMarketingAirlineModel()->getAirlineCode();
        $airlineHosted = ($airlines == 'AA') ? 'true' : 'false';
        $membershipId = !empty($passengerNameRecord->getMembershipId()) ? $passengerNameRecord->getMembershipId() : "";
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
        foreach ($passengerDetails as $passengerDetail) {
            if ($passengerDetail->getType() !== $type)
                $record++;
            $pnrRequest .= <<<EOR
            <Link NameNumber="$num.1" Record="$record"/>
EOR;
                    $type = $passengerDetail->getType();

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
                foreach ($passengerDetails as $passengerDetail) {
                    $firstname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail->getFirstName()));
                    $surname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail->getSurname()));
                    $dob = $passengerDetail->getDateOfBirth();
                    $gender = ($passengerDetail->getGender() == 0) ? 'M' : 'F';
                    if ($passengerDetail->getType() === 'INF') {
                        $gender .= 'I';
                        $number = $adtNumber++;
                    }
                    $nameNumber = $number . '.1';

                    if(!empty($passengerDetail->getPassportNumber())){

                        $passportNo = $passengerDetail->getPassportNumber();
                        $passportExpiry = $passengerDetail->getPassportExpiry();
                        $issueCountry = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passengerDetail->getPassportIssuingCountry()->getId());
                        $nationalityCountry = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passengerDetail->getPassportNationalityCountry()->getId());

                        $passportIssueCountry = $issueCountry->getCode();
                        $passportNationalityCountry = $nationalityCountry->getCode();

                    $pnrRequest .= <<<EOR
                        <AdvancePassenger SegmentNumber="A">
                            <Document ExpirationDate="$passportExpiry" Number="$passportNo" Type="P">
                                <IssueCountry>$passportIssueCountry</IssueCountry>
                                <NationalityCountry>$passportNationalityCountry</NationalityCountry>
                            </Document>
                            <PersonName DateOfBirth="$dob" Gender="$gender" NameNumber="$nameNumber">
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
                       <PersonName DateOfBirth="$dob" Gender="$gender" NameNumber="$nameNumber">
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
                $infNumber = 0;
                foreach ($passengerDetails as $passengerDetail) {
                    if ($passengerDetail->getType() === 'INF') {
                        $infNumber++;
                        $infNameNumber = $infNumber . '.1';
                        $firstname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail->getFirstName()));
                        $surname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail->getSurname()));
                        $dob = $passengerDetail->getDateOfBirth();
                        $pnrRequest .= <<<EOR
                  <Service SegmentNumber="A" SSR_Code="INFT">
                     <PersonName NameNumber="$infNameNumber"/>
                     <Text>$surname/$firstname/$dob</Text>
                     <VendorPrefs>
                        <Airline Hosted="$airlineHosted"/>
                     </VendorPrefs>
                  </Service>
EOR;
                    }
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
                foreach ($passengerDetails as $passengerDetail) {
                    $personNameNumber = $personNumber . '.1';
                    $passengerType = $passengerDetail->getType();
                    $firstname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail->getFirstName()));
                    $surname = strtoupper(preg_replace('/[^A-Za-z\-]/', '', $passengerDetail->getSurname()));
                    $infant = "";
                    if ($passengerDetail->getType() === 'INF') {
                        $infant = 'Infant="true"';
                    }
                    $pnrRequest .= <<<EOR
                <PersonName NameNumber="$personNameNumber" PassengerType="$passengerType" $infant>
                    <GivenName>$firstname</GivenName>
                    <Surname>$surname</Surname>
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
