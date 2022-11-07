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
            if ($passengerDetail['type'] != $type)
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
            if ($specialRequirement) {
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
                    $firstname = strtoupper(preg_replace('/[^A-Za-z\- ]/', '', $passengerDetail['firstName']));
                    $surname = strtoupper(preg_replace('/[^A-Za-z\- ]/', '', $passengerDetail['surname']));

					$passengerDetail['dateOfBirth']['month'] = str_pad($passengerDetail['dateOfBirth']['month'], 2, '0', STR_PAD_LEFT);
					$passengerDetail['dateOfBirth']['day'] = str_pad($passengerDetail['dateOfBirth']['day'], 2, '0', STR_PAD_LEFT);
                    $dob_string = implode('-', $passengerDetail['dateOfBirth']);

					$gender_correction = array('0' => 'M', '1' => 'F');

					if (isset($gender_correction[$passengerDetail['gender']]))
						$gender = $gender_correction[$passengerDetail['gender']];
					else
						$gender = $passengerDetail['gender'];

                    if ($passengerDetail['type'] == 'INF') {
                        $gender .= 'I';
                        $number = $adtNumber++;
                    }
                    $nameNumber = $number . '.1';

					$passportNo = null;
					$idNo = null;

					if (isset($passengerDetail['passportNo']) && !empty(trim($passengerDetail['passportNo'])))
						$passportNo = trim($passengerDetail['passportNo']);

					if (isset($passengerDetail['idNo']) && !empty(trim($passengerDetail['idNo'])))
						$idNo = trim($passengerDetail['idNo']);

					if ($passportNo || $idNo)
					{
                        $nationalityCountry = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passengerDetail['passportNationalityCountry']);
						$passportNationalityCountry = $nationalityCountry->getCode();

						$passengerDetail['passportExpiry']['month'] = str_pad($passengerDetail['passportExpiry']['month'], 2, '0', STR_PAD_LEFT);
						$passengerDetail['passportExpiry']['day'] = str_pad($passengerDetail['passportExpiry']['day'], 2, '0', STR_PAD_LEFT);
						$passportExpiry = implode("-", $passengerDetail['passportExpiry']);

                       if ($passportNo)
					   {
						   $issueCountry = $this->em->getRepository('TTBundle:CmsCountries')->findOneById($passengerDetail['passportIssueCountry']);

							$passportIssueCountry = $issueCountry->getCode();

							$pnrRequest .= <<<EOR
							<AdvancePassenger SegmentNumber="A">
								<Document ExpirationDate="$passportExpiry" Number="$passportNo" Type="P">
									<IssueCountry>$passportIssueCountry</IssueCountry>
									<NationalityCountry>$passportNationalityCountry</NationalityCountry>
								</Document>
								<PersonName DateOfBirth="$dob_string" Gender="$gender" NameNumber="$nameNumber">
									<GivenName>$firstname</GivenName>
									<Surname>$surname</Surname>
								</PersonName>
								<VendorPrefs>
									<Airline Hosted="$airlineHosted"/>
								</VendorPrefs>
							</AdvancePassenger>
							<Service SegmentNumber="A" SSR_Code="FOID">
								<PersonName NameNumber="$nameNumber"/>
								<Text>PP$passportNationalityCountry$passportNo</Text>
								<VendorPrefs>
									<Airline Hosted="$airlineHosted"/>
								</VendorPrefs>
							</Service>
EOR;
					   }
					   else if ($idNo)
					   {
						   /*
						   Using else statement because we received the following error when we submit multiple consecutive Service elements:: .SSR FOID EXISTS FOR AT LEAST ONE PASSENGER REQUESTED
						   With a single Service element having multiple Text elements, we received the following error::
						   Message validation failed. Errors: [cvc-complex-type.2.4.a: Invalid content was found starting with element 'Text'. One of '{"http://services.sabre.com/sp/pd/v3_3":VendorPrefs}' is expected.]
						   */
							$pnrRequest .= <<<EOR
							<AdvancePassenger SegmentNumber="A">
								<Document ExpirationDate="$passportExpiry" Number="$idNo" Type="I">
									<IssueCountry>$passportNationalityCountry</IssueCountry>
									<NationalityCountry>$passportNationalityCountry</NationalityCountry>
								</Document>
								<PersonName DateOfBirth="$dob_string" Gender="$gender" NameNumber="$nameNumber">
									<GivenName>$firstname</GivenName>
									<Surname>$surname</Surname>
								</PersonName>
								<VendorPrefs>
									<Airline Hosted="$airlineHosted"/>
								</VendorPrefs>
							</AdvancePassenger>
							<Service SegmentNumber="A" SSR_Code="FOID">
								<PersonName NameNumber="$nameNumber"/>
								<Text>NI$idNo</Text>
								<VendorPrefs>
									<Airline Hosted="$airlineHosted"/>
								</VendorPrefs>
							</Service>
EOR;
					   }

                    } else {

                        $pnrRequest .= <<<EOR
                    <SecureFlight SegmentNumber="A">
                       <PersonName DateOfBirth="$dob_string" Gender="$gender" NameNumber="$nameNumber">
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
                foreach ($request['passengerDetails'] as $passengerDetail) {
                    if ($passengerDetail['type'] == 'INF') {
                        $infNumber++;
                        $infNameNumber = $infNumber . '.1';
                        $firstname = strtoupper(preg_replace('/[^A-Za-z\- ]/', '', $passengerDetail['firstName']));
                        $surname = strtoupper(preg_replace('/[^A-Za-z\- ]/', '', $passengerDetail['surname']));
                        $dob = date('dMy', strtotime($passengerDetail['dateOfBirth']['year'] . '-' . str_pad($passengerDetail['dateOfBirth']['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($passengerDetail['dateOfBirth']['day'], 2, '0', STR_PAD_LEFT)));

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
                foreach ($request['passengerDetails'] as $passengerDetail) {
                    $personNameNumber = $personNumber . '.1';
                    $passengerType = $passengerDetail['type'];
                    $passengerGivenName = strtoupper(preg_replace('/[^A-Za-z\- ]/', '', $passengerDetail['firstName']));
                    $passengerSurname = strtoupper(preg_replace('/[^A-Za-z\- ]/', '', $passengerDetail['surname']));
                    $infant = "";
                    if ($passengerDetail['type'] == 'INF') {
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
        return $pnrRequest;
    }

}
