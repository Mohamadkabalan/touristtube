<?php

namespace CorporateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * CmsUsers
 *
 * @ORM\Table(name="cms_users", indexes={@ORM\Index(name="YourCountry", columns={"YourCountry"}), @ORM\Index(name="YourUserName", columns={"YourUserName"})})
 * @ORM\Entity(repositoryClass="CorporateBundle\Repository\Admin\CmsUsersRepository")
 */
class CmsUsers
{
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
     * @ORM\Column(name="FullName", type="string", length=100, nullable=false)
     */
    private $fullname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fname", type="string", length=64, nullable=false)
     */
    private $fname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="lname", type="string", length=64, nullable=false)
     */
    private $lname;
    
    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1, nullable=false)
     */
    private $gender = 'O';
    
    /**
     * @var string
     *
     * @ORM\Column(name="YourEmail", type="string", length=100, nullable=false)
     */
    private $youremail;
    
    /**
     * @var string
     *
     * @ORM\Column(name="website_url", type="string", length=255, nullable=false)
     */
    private $websiteUrl;
    
    /**
     * @var string
     *
     * @ORM\Column(name="small_description", type="string", length=255, nullable=false)
     */
    private $smallDescription;
    
    /**
     * @var string
     *
     * @ORM\Column(name="YourCountry", type="string", length=2, nullable=true)
     */
    private $yourcountry;
    
    /**
     * @var string
     *
     * @ORM\Column(name="hometown", type="string", length=255, nullable=false)
     */
    private $hometown;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="city_id", type="integer", nullable=false)
     */
    private $cityId;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=128, nullable=false)
     */
    private $city;
    
    /**
     * @var string
     *
     * @ORM\Column(name="YourIP", type="string", length=20, nullable=false)
     */
    private $yourip;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="YourBday", type="date", nullable=false)
     */
    private $yourbday;
    
    /**
     * @var string
     *
     * @ORM\Column(name="YourUserName", type="string", length=128, nullable=false)
     */
    private $yourusername;
    
    /**
     * @var string
     *
     * @ORM\Column(name="profile_Pic", type="string", length=255, nullable=false)
     */
    private $profilePic;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="profile_id", type="bigint", nullable=false)
     */
    private $profileId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="corpo_user_profile_id", type="bigint", nullable=true)
     */
    private $corpoUserProfileId;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="display_age", type="boolean", nullable=false)
     */
    private $displayAge = '0';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="display_yearage", type="boolean", nullable=false)
     */
    private $displayYearage = '0';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="display_gender", type="boolean", nullable=false)
     */
    private $displayGender = '0';
    
    /**
     * @var string
     *
     * @ORM\Column(name="YourPassword", type="string", length=100, nullable=false)
     */
    private $yourpassword;
    
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;
    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=64, nullable=false)
     */
    private $salt;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="RegisteredDate", type="datetime", nullable=false)
     */
    private $registereddate;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="profile_views", type="integer", nullable=false)
     */
    private $profileViews = '0';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="notifs", type="integer", nullable=false)
     */
    private $notifs = '0';
    
    /**
     * @var string
     *
     * @ORM\Column(name="chkey", type="string", length=32, nullable=false)
     */
    private $chkey;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="n_flashes", type="integer", nullable=false)
     */
    private $nFlashes = '0';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="n_journals", type="integer", nullable=false)
     */
    private $nJournals;
    
    /**
     * @var string
     *
     * @ORM\Column(name="occupation", type="string", length=254, nullable=false)
     */
    private $occupation;
    
    /**
     * @var string
     *
     * @ORM\Column(name="employment", type="string", length=254, nullable=false)
     */
    private $employment;
    
    /**
     * @var string
     *
     * @ORM\Column(name="high_education", type="string", length=254, nullable=false)
     */
    private $highEducation;
    
    /**
     * @var string
     *
     * @ORM\Column(name="uni_education", type="string", length=255, nullable=false)
     */
    private $uniEducation;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="intrested_in", type="integer", nullable=false)
     */
    private $intrestedIn;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="display_interest", type="boolean", nullable=false)
     */
    private $displayInterest = '1';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="display_fullname", type="integer", nullable=false)
     */
    private $displayFullname = '1';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="contact_privacy", type="integer", nullable=false)
     */
    private $contactPrivacy;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="search_engine", type="integer", nullable=false)
     */
    private $searchEngine;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="feeds_privacy", type="integer", nullable=false)
     */
    private $feedsPrivacy;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="comment_privacy", type="integer", nullable=false)
     */
    private $commentPrivacy;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="isChannel", type="smallint", nullable=false)
     */
    private $ischannel = '0';
    
    /**
     * @var string
     *
     * @ORM\Column(name="otherEmail", type="string", length=100, nullable=false)
     */
    private $otheremail;
    
    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $longitude = '0';
    
    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=false)
     */
    private $latitude = '0';
    
    /**
     * @var string
     *
     * @ORM\Column(name="fb_token", type="string", length=255, nullable=false)
     */
    private $fbToken;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fb_user", type="string", length=255, nullable=false)
     */
    private $fbUser;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="chat_status", type="integer", nullable=false)
     */
    private $chatStatus = '0';
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime", nullable=false)
     */
    private $lastModified = 'CURRENT_TIMESTAMP';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="invites_max", type="integer", nullable=false)
     */
    private $invitesMax = '50';
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_corporate_account", type="boolean", nullable=false)
     */
    private $isCorporateAccount = false;
    
    /**
     * @var string
     *
     * @ORM\Column(name="corporate_account_pin", type="string", length=7, nullable=true)
     */
    private $corporateAccountPin;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="corpo_account_id", type="integer", nullable=true)
     */
    private $corpoAccountId;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cms_user_group_id", type="integer", nullable=true)
     */
    private $cmsUserGroupId;
    
    /**
     * @ORM\OneToOne(targetEntity="CmsUserGroup", inversedBy="cmsUsers")
     * @ORM\JoinColumn(name="cms_user_group_id", referencedColumnName="id")
     */
    private $cmsUserGroup;

    /**
     * @var integer
     *
     * @ORM\Column(name="allow_access_to_sub_accounts", type="integer", nullable=true)
     */
    private $allowAccessSubAcc;

    /**
     * @var integer
     *
     * @ORM\Column(name="allow_access_to_sub_accounts_users", type="integer", nullable=true)
     */
    private $allowAccessSubAccUsers;
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return CmsUsers
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
        
        return $this;
    }
    
    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname()
    {
        return $this->fullname;
    }
    
    /**
     * Set fname
     *
     * @param string $fname
     *
     * @return CmsUsers
     */
    public function setFname($fname)
    {
        $this->fname = $fname;
        
        return $this;
    }
    
    /**
     * Get fname
     *
     * @return string
     */
    public function getFname()
    {
        return $this->fname;
    }
    
    /**
     * Set lname
     *
     * @param string $lname
     *
     * @return CmsUsers
     */
    public function setLname($lname)
    {
        $this->lname = $lname;
        
        return $this;
    }
    
    /**
     * Get lname
     *
     * @return string
     */
    public function getLname()
    {
        return $this->lname;
    }
    
    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return CmsUsers
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        
        return $this;
    }
    
    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    /**
     * Set youremail
     *
     * @param string $youremail
     *
     * @return CmsUsers
     */
    public function setYouremail($youremail)
    {
        $this->youremail = $youremail;
        
        return $this;
    }
    
    /**
     * Get youremail
     *
     * @return string
     */
    public function getYouremail()
    {
        return $this->youremail;
    }
    
    /**
     * Set websiteUrl
     *
     * @param string $websiteUrl
     *
     * @return CmsUsers
     */
    public function setWebsiteUrl($websiteUrl)
    {
        $this->websiteUrl = $websiteUrl;
        
        return $this;
    }
    
    /**
     * Get websiteUrl
     *
     * @return string
     */
    public function getWebsiteUrl()
    {
        return $this->websiteUrl;
    }
    
    /**
     * Set smallDescription
     *
     * @param string $smallDescription
     *
     * @return CmsUsers
     */
    public function setSmallDescription($smallDescription)
    {
        $this->smallDescription = $smallDescription;
        
        return $this;
    }
    
    /**
     * Get smallDescription
     *
     * @return string
     */
    public function getSmallDescription()
    {
        return $this->smallDescription;
    }
    
    /**
     * Set yourcountry
     *
     * @param string $yourcountry
     *
     * @return CmsUsers
     */
    public function setYourcountry($yourcountry)
    {
        $this->yourcountry = $yourcountry;
        
        return $this;
    }
    
    /**
     * Get yourcountry
     *
     * @return string
     */
    public function getYourcountry()
    {
        return $this->yourcountry;
    }
    
    /**
     * Set hometown
     *
     * @param string $hometown
     *
     * @return CmsUsers
     */
    public function setHometown($hometown)
    {
        $this->hometown = $hometown;
        
        return $this;
    }
    
    /**
     * Get hometown
     *
     * @return string
     */
    public function getHometown()
    {
        return $this->hometown;
    }
    
    /**
     * Set cityId
     *
     * @param integer $cityId
     *
     * @return CmsUsers
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
        
        return $this;
    }
    
    /**
     * Get cityId
     *
     * @return integer
     */
    public function getCityId()
    {
        return $this->cityId;
    }
    
    /**
     * Set city
     *
     * @param string $city
     *
     * @return CmsUsers
     */
    public function setCity($city)
    {
        $this->city = $city;
        
        return $this;
    }
    
    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * Set yourip
     *
     * @param string $yourip
     *
     * @return CmsUsers
     */
    public function setYourip($yourip)
    {
        $this->yourip = $yourip;
        
        return $this;
    }
    
    /**
     * Get yourip
     *
     * @return string
     */
    public function getYourip()
    {
        return $this->yourip;
    }
    
    /**
     * Set yourbday
     *
     * @param \DateTime $yourbday
     *
     * @return CmsUsers
     */
    public function setYourbday($yourbday)
    {
        $this->yourbday = $yourbday;
        
        return $this;
    }
    
    /**
     * Get yourbday
     *
     * @return \DateTime
     */
    public function getYourbday()
    {
        return $this->yourbday;
    }
    
    /**
     * Set yourusername
     *
     * @param string $yourusername
     *
     * @return CmsUsers
     */
    public function setYourusername($yourusername)
    {
        $this->yourusername = $yourusername;
        
        return $this;
    }
    
    /**
     * Get yourusername
     *
     * @return string
     */
    public function getYourusername()
    {
        return $this->yourusername;
    }
    
    /**
     * Set profilePic
     *
     * @param string $profilePic
     *
     * @return CmsUsers
     */
    public function setProfilePic($profilePic)
    {
        $this->profilePic = $profilePic;
        
        return $this;
    }
    
    /**
     * Get profilePic
     *
     * @return string
     */
    public function getProfilePic()
    {
        return $this->profilePic;
    }
    
    /**
     * Set profileId
     *
     * @param integer $profileId
     *
     * @return CmsUsers
     */
    public function setProfileId($profileId)
    {
        $this->profileId = $profileId;
        
        return $this;
    }
    
    /**
     * Get profileId
     *
     * @return integer
     */
    public function getProfileId()
    {
        return $this->profileId;
    }

    /**
     * Set corpoUserProfileId
     *
     * @param integer $corpoUserProfileId
     *
     * @return CmsUsers
     */
    public function setCorpoUserProfileId($corpoUserProfileId)
    {
        $this->corpoUserProfileId = $corpoUserProfileId;

        return $this;
    }

    /**
     * Get corpoUserProfileId
     *
     * @return integer
     */
    public function getCorpoUserProfileId()
    {
        return $this->corpoUserProfileId;
    }

    /**
     * Set displayAge
     *
     * @param boolean $displayAge
     *
     * @return CmsUsers
     */
    public function setDisplayAge($displayAge)
    {
        $this->displayAge = $displayAge;
        
        return $this;
    }
    
    /**
     * Get displayAge
     *
     * @return boolean
     */
    public function getDisplayAge()
    {
        return $this->displayAge;
    }
    
    /**
     * Set displayYearage
     *
     * @param boolean $displayYearage
     *
     * @return CmsUsers
     */
    public function setDisplayYearage($displayYearage)
    {
        $this->displayYearage = $displayYearage;
        
        return $this;
    }
    
    /**
     * Get displayYearage
     *
     * @return boolean
     */
    public function getDisplayYearage()
    {
        return $this->displayYearage;
    }
    
    /**
     * Set displayGender
     *
     * @param boolean $displayGender
     *
     * @return CmsUsers
     */
    public function setDisplayGender($displayGender)
    {
        $this->displayGender = $displayGender;
        
        return $this;
    }
    
    /**
     * Get displayGender
     *
     * @return boolean
     */
    public function getDisplayGender()
    {
        return $this->displayGender;
    }
    
    /**
     * Set yourpassword
     *
     * @param string $yourpassword
     *
     * @return CmsUsers
     */
    public function setYourpassword($yourpassword)
    {
        $this->yourpassword = $yourpassword;
        
        return $this;
    }
    
    /**
     * Get yourpassword
     *
     * @return string
     */
    public function getYourpassword()
    {
        return $this->yourpassword;
    }
    
    /**
     * Set registereddate
     *
     * @param \DateTime $registereddate
     *
     * @return CmsUsers
     */
    public function setRegistereddate($registereddate)
    {
        $this->registereddate = $registereddate;
        
        return $this;
    }
    
    /**
     * Get registereddate
     *
     * @return \DateTime
     */
    public function getRegistereddate()
    {
        return $this->registereddate;
    }
    
    /**
     * Set profileViews
     *
     * @param integer $profileViews
     *
     * @return CmsUsers
     */
    public function setProfileViews($profileViews)
    {
        $this->profileViews = $profileViews;
        
        return $this;
    }
    
    /**
     * Get profileViews
     *
     * @return integer
     */
    public function getProfileViews()
    {
        return $this->profileViews;
    }
    
    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return CmsUsers
     */
    public function setPublished($published)
    {
        $this->published = $published;
        
        return $this;
    }
    
    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }
    
    /**
     * Set notifs
     *
     * @param integer $notifs
     *
     * @return CmsUsers
     */
    public function setNotifs($notifs)
    {
        $this->notifs = $notifs;
        
        return $this;
    }
    
    /**
     * Get notifs
     *
     * @return integer
     */
    public function getNotifs()
    {
        return $this->notifs;
    }
    
    /**
     * Set chkey
     *
     * @param string $chkey
     *
     * @return CmsUsers
     */
    public function setChkey($chkey)
    {
        $this->chkey = $chkey;
        
        return $this;
    }
    
    /**
     * Get chkey
     *
     * @return string
     */
    public function getChkey()
    {
        return $this->chkey;
    }
    
    /**
     * Set nFlashes
     *
     * @param integer $nFlashes
     *
     * @return CmsUsers
     */
    public function setNFlashes($nFlashes)
    {
        $this->nFlashes = $nFlashes;
        
        return $this;
    }
    
    /**
     * Get nFlashes
     *
     * @return integer
     */
    public function getNFlashes()
    {
        return $this->nFlashes;
    }
    
    /**
     * Set nJournals
     *
     * @param integer $nJournals
     *
     * @return CmsUsers
     */
    public function setNJournals($nJournals)
    {
        $this->nJournals = $nJournals;
        
        return $this;
    }
    
    /**
     * Get nJournals
     *
     * @return integer
     */
    public function getNJournals()
    {
        return $this->nJournals;
    }
    
    /**
     * Set occupation
     *
     * @param string $occupation
     *
     * @return CmsUsers
     */
    public function setOccupation($occupation)
    {
        $this->occupation = $occupation;
        
        return $this;
    }
    
    /**
     * Get occupation
     *
     * @return string
     */
    public function getOccupation()
    {
        return $this->occupation;
    }
    
    /**
     * Set employment
     *
     * @param string $employment
     *
     * @return CmsUsers
     */
    public function setEmployment($employment)
    {
        $this->employment = $employment;
        
        return $this;
    }
    
    /**
     * Get employment
     *
     * @return string
     */
    public function getEmployment()
    {
        return $this->employment;
    }
    
    /**
     * Set highEducation
     *
     * @param string $highEducation
     *
     * @return CmsUsers
     */
    public function setHighEducation($highEducation)
    {
        $this->highEducation = $highEducation;
        
        return $this;
    }
    
    /**
     * Get highEducation
     *
     * @return string
     */
    public function getHighEducation()
    {
        return $this->highEducation;
    }
    
    /**
     * Set uniEducation
     *
     * @param string $uniEducation
     *
     * @return CmsUsers
     */
    public function setUniEducation($uniEducation)
    {
        $this->uniEducation = $uniEducation;
        
        return $this;
    }
    
    /**
     * Get uniEducation
     *
     * @return string
     */
    public function getUniEducation()
    {
        return $this->uniEducation;
    }
    
    /**
     * Set intrestedIn
     *
     * @param integer $intrestedIn
     *
     * @return CmsUsers
     */
    public function setIntrestedIn($intrestedIn)
    {
        $this->intrestedIn = $intrestedIn;
        
        return $this;
    }
    
    /**
     * Get intrestedIn
     *
     * @return integer
     */
    public function getIntrestedIn()
    {
        return $this->intrestedIn;
    }
    
    /**
     * Set displayInterest
     *
     * @param boolean $displayInterest
     *
     * @return CmsUsers
     */
    public function setDisplayInterest($displayInterest)
    {
        $this->displayInterest = $displayInterest;
        
        return $this;
    }
    
    /**
     * Get displayInterest
     *
     * @return boolean
     */
    public function getDisplayInterest()
    {
        return $this->displayInterest;
    }
    
    /**
     * Set displayFullname
     *
     * @param integer $displayFullname
     *
     * @return CmsUsers
     */
    public function setDisplayFullname($displayFullname)
    {
        $this->displayFullname = $displayFullname;
        
        return $this;
    }
    
    /**
     * Get displayFullname
     *
     * @return integer
     */
    public function getDisplayFullname()
    {
        return $this->displayFullname;
    }
    
    /**
     * Set contactPrivacy
     *
     * @param integer $contactPrivacy
     *
     * @return CmsUsers
     */
    public function setContactPrivacy($contactPrivacy)
    {
        $this->contactPrivacy = $contactPrivacy;
        
        return $this;
    }
    
    /**
     * Get contactPrivacy
     *
     * @return integer
     */
    public function getContactPrivacy()
    {
        return $this->contactPrivacy;
    }
    
    /**
     * Set searchEngine
     *
     * @param integer $searchEngine
     *
     * @return CmsUsers
     */
    public function setSearchEngine($searchEngine)
    {
        $this->searchEngine = $searchEngine;
        
        return $this;
    }
    
    /**
     * Get searchEngine
     *
     * @return integer
     */
    public function getSearchEngine()
    {
        return $this->searchEngine;
    }
    
    /**
     * Set feedsPrivacy
     *
     * @param integer $feedsPrivacy
     *
     * @return CmsUsers
     */
    public function setFeedsPrivacy($feedsPrivacy)
    {
        $this->feedsPrivacy = $feedsPrivacy;
        
        return $this;
    }
    
    /**
     * Get feedsPrivacy
     *
     * @return integer
     */
    public function getFeedsPrivacy()
    {
        return $this->feedsPrivacy;
    }
    
    /**
     * Set commentPrivacy
     *
     * @param integer $commentPrivacy
     *
     * @return CmsUsers
     */
    public function setCommentPrivacy($commentPrivacy)
    {
        $this->commentPrivacy = $commentPrivacy;
        
        return $this;
    }
    
    /**
     * Get commentPrivacy
     *
     * @return integer
     */
    public function getCommentPrivacy()
    {
        return $this->commentPrivacy;
    }
    
    /**
     * Set ischannel
     *
     * @param integer $ischannel
     *
     * @return CmsUsers
     */
    public function setIschannel($ischannel)
    {
        $this->ischannel = $ischannel;
        
        return $this;
    }
    
    /**
     * Get ischannel
     *
     * @return integer
     */
    public function getIschannel()
    {
        return $this->ischannel;
    }
    
    /**
     * Set otheremail
     *
     * @param string $otheremail
     *
     * @return CmsUsers
     */
    public function setOtheremail($otheremail)
    {
        $this->otheremail = $otheremail;
        
        return $this;
    }
    
    /**
     * Get otheremail
     *
     * @return string
     */
    public function getOtheremail()
    {
        return $this->otheremail;
    }
    
    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return CmsUsers
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        
        return $this;
    }
    
    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }
    
    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return CmsUsers
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        
        return $this;
    }
    
    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }
    
    /**
     * Set fbToken
     *
     * @param string $fbToken
     *
     * @return CmsUsers
     */
    public function setFbToken($fbToken)
    {
        $this->fbToken = $fbToken;
        
        return $this;
    }
    
    /**
     * Get fbToken
     *
     * @return string
     */
    public function getFbToken()
    {
        return $this->fbToken;
    }
    
    /**
     * Set fbUser
     *
     * @param string $fbUser
     *
     * @return CmsUsers
     */
    public function setFbUser($fbUser)
    {
        $this->fbUser = $fbUser;
        
        return $this;
    }
    
    /**
     * Get fbUser
     *
     * @return string
     */
    public function getFbUser()
    {
        return $this->fbUser;
    }
    
    /**
     * Set chatStatus
     *
     * @param integer $chatStatus
     *
     * @return CmsUsers
     */
    public function setChatStatus($chatStatus)
    {
        $this->chatStatus = $chatStatus;
        
        return $this;
    }
    
    /**
     * Get chatStatus
     *
     * @return integer
     */
    public function getChatStatus()
    {
        return $this->chatStatus;
    }
    
    /**
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return CmsUsers
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;
        
        return $this;
    }
    
    /**
     * Get lastModified
     *
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }
    
    /**
     * Set invitesMax
     *
     * @param integer $invitesMax
     *
     * @return CmsUsers
     */
    public function setInvitesMax($invitesMax)
    {
        $this->invitesMax = $invitesMax;
        
        return $this;
    }
    
    /**
     * Get invitesMax
     *
     * @return integer
     */
    public function getInvitesMax()
    {
        return $this->invitesMax;
    }
    
    /**
     * Get isCorporateAccount
     *
     * @return boolean
     */
    public function getIsCorporateAccount()
    {
        return $this->isCorporateAccount;
    }
    
    /**
     * Set isCorporateAccount
     *
     * @param boolean $isCorporateAccount
     *
     * @return CmsUsers
     */
    public function setIsCorporateAccount($isCorporateAccount)
    {
        $this->isCorporateAccount = $isCorporateAccount;
        
        return $this;
    }
    
    /**
     * Get corporateAccountPin
     *
     * @return string
     */
    public function getCorporateAccountPin()
    {
        return $this->corporateAccountPin;
    }
    
    /**
     * Set corporateAccountPin
     *
     * @param string $corporateAccountPin
     *
     * @return CmsUsers
     */
    public function setCorporateAccountPin($corporateAccountPin)
    {
        $this->corporateAccountPin = $corporateAccountPin;
        
        return $this;
    }
    /*
     *
     * userinterface methods implementation
     *
     */
    
    public function getUsername()
    {
        return $this->yourusername;
    }
    
    public function getSalt()
    {
        return $this->salt;
    }
    
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }
    
    public function getPassword()
    {
        return $this->yourpassword;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function eraseCredentials()
    {
        
    }
    
    function getCorpoAccountId()
    {
        return $this->corpoAccountId;
    }
    
    function setCorpoAccountId($corpoAccountId)
    {
        $this->corpoAccountId = $corpoAccountId;
    }
    
    function getCmsUserGroupId()
    {
        return $this->cmsUserGroupId;
    }
    
    function setCmsUserGroupId($cmsUserGroupId)
    {
        $this->cmsUserGroupId = $cmsUserGroupId;
    }
    
    /**
     * Get cmsUserGroup->role
     *
     * @return array
     */
    public function getRoles()
    {
        $role = $this->cmsUserGroup->getRole();
        return array($role);
    }
    
    /**
     * Get cms_user_group
     *
     * @return CmsUserGroup
     */
    public function getCmsUserGroup()
    {
        return $this->cmsUserGroup;
    }
    
    /**
     * Set cms_user_group
     *
     * @param CmsUserGroup $cmsUserGroup
     */
    public function setCmsUserGroup(CmsUserGroup $cmsUserGroup)
    {
        $this->cmsUserGroup = $cmsUserGroup;
    }

    function getAllowAccessSubAcc()
    {
        return $this->allowAccessSubAcc;
    }
    
    function setAllowAccessSubAcc($allowAccessSubAcc)
    {
        $this->allowAccessSubAcc = $allowAccessSubAcc;
    }

    function getAllowAccessSubAccUsers()
    {
        return $this->allowAccessSubAccUsers;
    }
    
    function setAllowAccessSubAccUsers($allowAccessSubAccUsers)
    {
        $this->allowAccessSubAccUsers = $allowAccessSubAccUsers;
    }
}