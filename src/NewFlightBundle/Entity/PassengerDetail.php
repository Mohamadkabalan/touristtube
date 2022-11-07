<?php

namespace NewFlightBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use TTBundle\Entity\CmsCountries;

/**
 * PassengerDetail
 * 
 * @ORM\Entity
 * @ORM\Table(name="passenger_detail")
 */
class PassengerDetail {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="pnr_id", type="string", length=11, nullable=false)
     */
    private $pnrId;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * ) 
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 100,
     *      maxMessage = "Your surname cannot be longer than {{ limit }} characters"
     * )
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255, nullable=false)
     */
    private $surname;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 3
     * )
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=3, nullable=false)
     */
    private $type;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=30, nullable=false)
     */
    private $gender;

    /**
     * @Assert\NotBlank()
     * @Assert\DateTime()
     * @var \DateTime
     *
     * @ORM\Column(name="dob", type="datetime", nullable=false)
     */
    private $dateOfBirth;

    /**
     * @Assert\NotBlank()
     * @var string
     *
     * @ORM\Column(name="fare_calc_line", type="string", length=255, nullable=false)
     */
    private $fareCalcLine;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_number", type="string", length=100)
     */
    private $ticketNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_rph", type="string", length=2)
     */
    private $ticketRph;

    /**
     * @var string
     *
     * @ORM\Column(name="ticket_status", type="string", length=30)
     */
    private $ticketStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="leaving_baggage_info", type="string", length=30)
     */
    private $leavingBaggageInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="retuning_baggage_info", type="string", length=30)
     */
    private $returningBaggageInfo;

    /**
     * @ORM\ManyToOne(targetEntity="PassengerNameRecord", inversedBy="passengerDetails")
     * @ORM\JoinColumn(name="pnr_id", referencedColumnName="id")
     */
    private $passengerNameRecord;

    /**
     * @var string
     *
     * @ORM\Column(name="passport_no", type="string", length=30, nullable=true)
     */
    private $passportNo;

    /**
    * @Assert\DateTime()
     * @var \DateTime
     *
     * @ORM\Column(name="passport_expiry", type="datetime", nullable=false)
     */
    private $passportExpiry;

    /**
     * @var string
     *
     * @ORM\Column(name="passport_issue_country", type="string", length=3, nullable=true)
     */
    private $passportIssueCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="passport_nationality_country", type="string", length=3, nullable=true)
     */
    private $passportNationalityCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="id_no", type="string", length=30, nullable=true)
     */
    private $idNo;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get passenger name record ID
     *
     * @return string
     */
    public function getPnrId() {
        return $this->pnrId;
    }

    /**
     * Set passenger name record ID
     *
     * @param string $pnrId
     */
    public function setPnrId($pnrId) {
        $this->pnrId = $pnrId;
    }

    /**
     * Get first name
     *
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * Set first name
     *
     * @param string $firstName
     */
    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname() {
        return $this->surname;
    }

    /**
     * Set surname
     *
     * @param string $surname
     */
    public function setSurname($surname) {
        $this->surname = $surname;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * Set gender
     *
     * @param string $gender
     */
    public function setGender($gender) {
        $this->gender = $gender;
    }

    /**
     * Get date of birth
     *
     * @return string
     */
    public function getDateofBirth() {
        return $this->dateOfBirth;
    }

    /**
     * Set date of birth
     *
     * @param string $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth) {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * Get fare calculation line
     *
     * @return string
     */
    function getFareCalcLine() {
        return $this->fareCalcLine;
    }

    /**
     * Set fare calculation line
     *
     * @param string $fareCalcLine
     */
    function setFareCalcLine($fareCalcLine) {
        $this->fareCalcLine = $fareCalcLine;
    }

    /**
     * Get ticket number
     *
     * @return string
     */
    function getTicketNumber() {
        return $this->ticketNumber;
    }

    /**
     * Set ticket number
     *
     * @param string $ticketNumber
     */
    function setTicketNumber($ticketNumber) {
        $this->ticketNumber = $ticketNumber;
    }

    /**
     * Get ticket RPH
     *
     * @return string
     */
    function getTicketRph() {
        return $this->ticketRph;
    }

    /**
     * Set ticket RPH
     *
     * @param string $ticketRph
     */
    function setTicketRph($ticketRph) {
        $this->ticketRph = $ticketRph;
    }

    /**
     * Get ticket status
     *
     * @return string
     */
    function getTicketStatus() {
        return $this->ticketStatus;
    }

    /**
     * Set ticket status
     *
     * @param string $ticketStatus
     */
    function setTicketStatus($ticketStatus) {
        $this->ticketStatus = $ticketStatus;
    }

    /**
     * Get leaving baggage info
     *
     * @return string
     */
    function getLeavingBaggageInfo() {
        return $this->leavingBaggageInfo;
    }

    /**
     * Set leaving baggage info
     *
     * @param string $leavingBaggageInfo
     */
    function setLeavingBaggageInfo($leavingBaggageInfo) {
        $this->leavingBaggageInfo = $leavingBaggageInfo;
    }

    /**
     * Get returning baggage info
     *
     * @return string
     */
    function getReturningBaggageInfo() {
        return $this->returningBaggageInfo;
    }

    /**
     * Set returning baggage info
     *
     * @param string $returningBaggageInfo
     */
    function setReturningBaggageInfo($returningBaggageInfo) {
        $this->returningBaggageInfo = $returningBaggageInfo;
    }

    /**
     * Get passenger Name Record
     *
     * @return PassengerNameRecord
     */
    public function getPassengerNameRecord() {
        return $this->passengerNameRecord;
    }

    /**
     * Set passenger name record
     *
     * @param PassengerNameRecord $passengerNameRecord
     */
    public function setPassengerNameRecord(PassengerNameRecord $passengerNameRecord) {
        $this->passengerNameRecord = $passengerNameRecord;
    }

    /**
     * Get passportNo
     *
     * @return string
     */
    public function getPassportNo() {
        return $this->passportNo;
}

    /**
     * Set passportNo
     *
     * @param string $passport
     */
    public function setPassportNo($passportNo) {
        $this->passportNo = $passportNo;
    }

    /**
     * Get passportExpiry
     *
     * @return string
     */
    public function getPassportExpiry() {
        return $this->passportExpiry;
    }

    /**
     * Set passportExpiry
     *
     * @param string $passportExpiry
     */
    public function setPassportExpiry($passportExpiry) {
        $this->passportExpiry = $passportExpiry;
    }

    /**
     * Get passportIssueCountry
     *
     * @return string
     */
    public function getPassportIssueCountry() {
        return $this->passportIssueCountry;
    }

    /**
     * Set passportIssueCountry
     *
     * @param string $passportIssueCountry
     */
    public function setPassportIssueCountry(CmsCountries $passportIssueCountry) {
        $this->passportIssueCountry = $passportIssueCountry->getCode();
    }

    /**
     * Get passportNationalityCountry
     *
     * @return string
     */
    public function getPassportNationalityCountry() {
        return $this->passportNationalityCountry;
    }

    /**
     * Set passportNationalityCountry
     *
     * @param string $passportNationalityCountry
     */
    public function setPassportNationalityCountry(CmsCountries $passportNationalityCountry) {
        $this->passportNationalityCountry = $passportNationalityCountry->getCode();
    }

    /**
     * Get idNo
     *
     * @return string
     */
    public function getIdNo() {
        return $this->idNo;
    }

    /**
     * Set idNo
     *
     * @param string $passport
     */
    public function setIdNo($idNo) {
        $this->idNo = $idNo;
    }

}
