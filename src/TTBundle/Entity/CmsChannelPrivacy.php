<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsChannelPrivacy
 *
 * @ORM\Table(name="cms_channel_privacy")
 * @ORM\Entity
 */
class CmsChannelPrivacy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="channelid", type="integer", nullable=false)
     */
    private $channelid;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_notifications", type="boolean", nullable=false)
     */
    private $emailNotifications = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_notifications", type="boolean", nullable=false)
     */
    private $channelNotifications = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_sponsorschannel", type="boolean", nullable=false)
     */
    private $emailSponsorschannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_sponsorschannel", type="boolean", nullable=false)
     */
    private $channelSponsorschannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_sponsorsevent", type="boolean", nullable=false)
     */
    private $emailSponsorsevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_sponsorsevent", type="boolean", nullable=false)
     */
    private $channelSponsorsevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_cancelsponsoring", type="boolean", nullable=false)
     */
    private $emailCancelsponsoring = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_cancelsponsoring", type="boolean", nullable=false)
     */
    private $channelCancelsponsoring = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_connects", type="boolean", nullable=false)
     */
    private $emailConnects = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_connects", type="boolean", nullable=false)
     */
    private $channelConnects = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="email_acceptsinvitation", type="integer", nullable=false)
     */
    private $emailAcceptsinvitation = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_acceptsinvitation", type="boolean", nullable=false)
     */
    private $channelAcceptsinvitation = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_invites", type="boolean", nullable=false)
     */
    private $emailInvites = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_invites", type="boolean", nullable=false)
     */
    private $channelInvites = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_commentscontent", type="boolean", nullable=false)
     */
    private $emailCommentscontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_commentscontent", type="boolean", nullable=false)
     */
    private $channelCommentscontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_likescontent", type="boolean", nullable=false)
     */
    private $emailLikescontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_likescontent", type="boolean", nullable=false)
     */
    private $channelLikescontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_sharedcontent", type="boolean", nullable=false)
     */
    private $emailSharedcontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_sharedcontent", type="boolean", nullable=false)
     */
    private $channelSharedcontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_ratedmedia", type="boolean", nullable=false)
     */
    private $emailRatedmedia = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_ratedmedia", type="boolean", nullable=false)
     */
    private $channelRatedmedia = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_repliedcomments", type="boolean", nullable=false)
     */
    private $emailRepliedcomments = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_repliedcomments", type="boolean", nullable=false)
     */
    private $channelRepliedcomments = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_repliedcomments_someone", type="boolean", nullable=false)
     */
    private $emailRepliedcommentsSomeone = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_repliedcomments_someone", type="boolean", nullable=false)
     */
    private $channelRepliedcommentsSomeone = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_likedcomment", type="boolean", nullable=false)
     */
    private $emailLikedcomment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_likedcomment", type="boolean", nullable=false)
     */
    private $channelLikedcomment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_likedcomment_someone", type="boolean", nullable=false)
     */
    private $emailLikedcommentSomeone = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_likedcomment_someone", type="boolean", nullable=false)
     */
    private $channelLikedcommentSomeone = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_sharedchannel", type="boolean", nullable=false)
     */
    private $emailSharedchannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_sharedchannel", type="boolean", nullable=false)
     */
    private $channelSharedchannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_reportedconnections", type="boolean", nullable=false)
     */
    private $emailReportedconnections = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_reportedconnections", type="boolean", nullable=false)
     */
    private $channelReportedconnections = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_joinsevent", type="boolean", nullable=false)
     */
    private $emailJoinsevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_joinsevent", type="boolean", nullable=false)
     */
    private $channelJoinsevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_commentedevent", type="boolean", nullable=false)
     */
    private $emailCommentedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_commentedevent", type="boolean", nullable=false)
     */
    private $channelCommentedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_likedevent", type="boolean", nullable=false)
     */
    private $emailLikedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_likedevent", type="boolean", nullable=false)
     */
    private $channelLikedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_sharedevent", type="boolean", nullable=false)
     */
    private $emailSharedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_sharedevent", type="boolean", nullable=false)
     */
    private $channelSharedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_acceptedinvitation_event", type="boolean", nullable=false)
     */
    private $emailAcceptedinvitationEvent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_acceptedinvitation_event", type="boolean", nullable=false)
     */
    private $channelAcceptedinvitationEvent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_subchannelrequest", type="boolean", nullable=false)
     */
    private $emailSubchannelrequest = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="channel_addsubchannel", type="boolean", nullable=false)
     */
    private $channelAddsubchannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_newsTT", type="boolean", nullable=false)
     */
    private $emailNewstt = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_updates_media", type="boolean", nullable=false)
     */
    private $emailUpdatesMedia = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_sponsoring", type="boolean", nullable=false)
     */
    private $privacySponsoring = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_sharing", type="boolean", nullable=false)
     */
    private $privacySharing = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_invitations", type="boolean", nullable=false)
     */
    private $privacyInvitations = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_social", type="boolean", nullable=false)
     */
    private $privacySocial = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_eventsponsors", type="boolean", nullable=false)
     */
    private $privacyEventsponsors = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_sponsoring_event", type="boolean", nullable=false)
     */
    private $privacySponsoringEvent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_sharescomments", type="boolean", nullable=false)
     */
    private $privacySharescomments = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_guests_invite", type="boolean", nullable=false)
     */
    private $privacyGuestsInvite = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_log", type="boolean", nullable=false)
     */
    private $privacyLog = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_mysponsored_channels", type="boolean", nullable=false)
     */
    private $privacyMysponsoredChannels = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_other_channels", type="boolean", nullable=false)
     */
    private $privacyOtherChannels = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_mysponsored_events", type="boolean", nullable=false)
     */
    private $privacyMysponsoredEvents = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_activechannels", type="boolean", nullable=false)
     */
    private $privacyActivechannels = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_activetubers", type="boolean", nullable=false)
     */
    private $privacyActivetubers = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_sharescomments_content", type="boolean", nullable=false)
     */
    private $privacySharescommentsContent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_log_TTPage", type="boolean", nullable=false)
     */
    private $privacyLogTtpage = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_besubchannel", type="boolean", nullable=false)
     */
    private $privacyBesubchannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_beparentchannel", type="boolean", nullable=false)
     */
    private $privacyBeparentchannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_mysub_channels", type="boolean", nullable=false)
     */
    private $privacyMysubChannels = '1';

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", nullable=false)
     */
    private $published = '1';



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
     * Set channelid
     *
     * @param integer $channelid
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelid($channelid)
    {
        $this->channelid = $channelid;

        return $this;
    }

    /**
     * Get channelid
     *
     * @return integer
     */
    public function getChannelid()
    {
        return $this->channelid;
    }

    /**
     * Set emailNotifications
     *
     * @param boolean $emailNotifications
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailNotifications($emailNotifications)
    {
        $this->emailNotifications = $emailNotifications;

        return $this;
    }

    /**
     * Get emailNotifications
     *
     * @return boolean
     */
    public function getEmailNotifications()
    {
        return $this->emailNotifications;
    }

    /**
     * Set channelNotifications
     *
     * @param boolean $channelNotifications
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelNotifications($channelNotifications)
    {
        $this->channelNotifications = $channelNotifications;

        return $this;
    }

    /**
     * Get channelNotifications
     *
     * @return boolean
     */
    public function getChannelNotifications()
    {
        return $this->channelNotifications;
    }

    /**
     * Set emailSponsorschannel
     *
     * @param boolean $emailSponsorschannel
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailSponsorschannel($emailSponsorschannel)
    {
        $this->emailSponsorschannel = $emailSponsorschannel;

        return $this;
    }

    /**
     * Get emailSponsorschannel
     *
     * @return boolean
     */
    public function getEmailSponsorschannel()
    {
        return $this->emailSponsorschannel;
    }

    /**
     * Set channelSponsorschannel
     *
     * @param boolean $channelSponsorschannel
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelSponsorschannel($channelSponsorschannel)
    {
        $this->channelSponsorschannel = $channelSponsorschannel;

        return $this;
    }

    /**
     * Get channelSponsorschannel
     *
     * @return boolean
     */
    public function getChannelSponsorschannel()
    {
        return $this->channelSponsorschannel;
    }

    /**
     * Set emailSponsorsevent
     *
     * @param boolean $emailSponsorsevent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailSponsorsevent($emailSponsorsevent)
    {
        $this->emailSponsorsevent = $emailSponsorsevent;

        return $this;
    }

    /**
     * Get emailSponsorsevent
     *
     * @return boolean
     */
    public function getEmailSponsorsevent()
    {
        return $this->emailSponsorsevent;
    }

    /**
     * Set channelSponsorsevent
     *
     * @param boolean $channelSponsorsevent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelSponsorsevent($channelSponsorsevent)
    {
        $this->channelSponsorsevent = $channelSponsorsevent;

        return $this;
    }

    /**
     * Get channelSponsorsevent
     *
     * @return boolean
     */
    public function getChannelSponsorsevent()
    {
        return $this->channelSponsorsevent;
    }

    /**
     * Set emailCancelsponsoring
     *
     * @param boolean $emailCancelsponsoring
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailCancelsponsoring($emailCancelsponsoring)
    {
        $this->emailCancelsponsoring = $emailCancelsponsoring;

        return $this;
    }

    /**
     * Get emailCancelsponsoring
     *
     * @return boolean
     */
    public function getEmailCancelsponsoring()
    {
        return $this->emailCancelsponsoring;
    }

    /**
     * Set channelCancelsponsoring
     *
     * @param boolean $channelCancelsponsoring
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelCancelsponsoring($channelCancelsponsoring)
    {
        $this->channelCancelsponsoring = $channelCancelsponsoring;

        return $this;
    }

    /**
     * Get channelCancelsponsoring
     *
     * @return boolean
     */
    public function getChannelCancelsponsoring()
    {
        return $this->channelCancelsponsoring;
    }

    /**
     * Set emailConnects
     *
     * @param boolean $emailConnects
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailConnects($emailConnects)
    {
        $this->emailConnects = $emailConnects;

        return $this;
    }

    /**
     * Get emailConnects
     *
     * @return boolean
     */
    public function getEmailConnects()
    {
        return $this->emailConnects;
    }

    /**
     * Set channelConnects
     *
     * @param boolean $channelConnects
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelConnects($channelConnects)
    {
        $this->channelConnects = $channelConnects;

        return $this;
    }

    /**
     * Get channelConnects
     *
     * @return boolean
     */
    public function getChannelConnects()
    {
        return $this->channelConnects;
    }

    /**
     * Set emailAcceptsinvitation
     *
     * @param integer $emailAcceptsinvitation
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailAcceptsinvitation($emailAcceptsinvitation)
    {
        $this->emailAcceptsinvitation = $emailAcceptsinvitation;

        return $this;
    }

    /**
     * Get emailAcceptsinvitation
     *
     * @return integer
     */
    public function getEmailAcceptsinvitation()
    {
        return $this->emailAcceptsinvitation;
    }

    /**
     * Set channelAcceptsinvitation
     *
     * @param boolean $channelAcceptsinvitation
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelAcceptsinvitation($channelAcceptsinvitation)
    {
        $this->channelAcceptsinvitation = $channelAcceptsinvitation;

        return $this;
    }

    /**
     * Get channelAcceptsinvitation
     *
     * @return boolean
     */
    public function getChannelAcceptsinvitation()
    {
        return $this->channelAcceptsinvitation;
    }

    /**
     * Set emailInvites
     *
     * @param boolean $emailInvites
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailInvites($emailInvites)
    {
        $this->emailInvites = $emailInvites;

        return $this;
    }

    /**
     * Get emailInvites
     *
     * @return boolean
     */
    public function getEmailInvites()
    {
        return $this->emailInvites;
    }

    /**
     * Set channelInvites
     *
     * @param boolean $channelInvites
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelInvites($channelInvites)
    {
        $this->channelInvites = $channelInvites;

        return $this;
    }

    /**
     * Get channelInvites
     *
     * @return boolean
     */
    public function getChannelInvites()
    {
        return $this->channelInvites;
    }

    /**
     * Set emailCommentscontent
     *
     * @param boolean $emailCommentscontent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailCommentscontent($emailCommentscontent)
    {
        $this->emailCommentscontent = $emailCommentscontent;

        return $this;
    }

    /**
     * Get emailCommentscontent
     *
     * @return boolean
     */
    public function getEmailCommentscontent()
    {
        return $this->emailCommentscontent;
    }

    /**
     * Set channelCommentscontent
     *
     * @param boolean $channelCommentscontent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelCommentscontent($channelCommentscontent)
    {
        $this->channelCommentscontent = $channelCommentscontent;

        return $this;
    }

    /**
     * Get channelCommentscontent
     *
     * @return boolean
     */
    public function getChannelCommentscontent()
    {
        return $this->channelCommentscontent;
    }

    /**
     * Set emailLikescontent
     *
     * @param boolean $emailLikescontent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailLikescontent($emailLikescontent)
    {
        $this->emailLikescontent = $emailLikescontent;

        return $this;
    }

    /**
     * Get emailLikescontent
     *
     * @return boolean
     */
    public function getEmailLikescontent()
    {
        return $this->emailLikescontent;
    }

    /**
     * Set channelLikescontent
     *
     * @param boolean $channelLikescontent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelLikescontent($channelLikescontent)
    {
        $this->channelLikescontent = $channelLikescontent;

        return $this;
    }

    /**
     * Get channelLikescontent
     *
     * @return boolean
     */
    public function getChannelLikescontent()
    {
        return $this->channelLikescontent;
    }

    /**
     * Set emailSharedcontent
     *
     * @param boolean $emailSharedcontent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailSharedcontent($emailSharedcontent)
    {
        $this->emailSharedcontent = $emailSharedcontent;

        return $this;
    }

    /**
     * Get emailSharedcontent
     *
     * @return boolean
     */
    public function getEmailSharedcontent()
    {
        return $this->emailSharedcontent;
    }

    /**
     * Set channelSharedcontent
     *
     * @param boolean $channelSharedcontent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelSharedcontent($channelSharedcontent)
    {
        $this->channelSharedcontent = $channelSharedcontent;

        return $this;
    }

    /**
     * Get channelSharedcontent
     *
     * @return boolean
     */
    public function getChannelSharedcontent()
    {
        return $this->channelSharedcontent;
    }

    /**
     * Set emailRatedmedia
     *
     * @param boolean $emailRatedmedia
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailRatedmedia($emailRatedmedia)
    {
        $this->emailRatedmedia = $emailRatedmedia;

        return $this;
    }

    /**
     * Get emailRatedmedia
     *
     * @return boolean
     */
    public function getEmailRatedmedia()
    {
        return $this->emailRatedmedia;
    }

    /**
     * Set channelRatedmedia
     *
     * @param boolean $channelRatedmedia
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelRatedmedia($channelRatedmedia)
    {
        $this->channelRatedmedia = $channelRatedmedia;

        return $this;
    }

    /**
     * Get channelRatedmedia
     *
     * @return boolean
     */
    public function getChannelRatedmedia()
    {
        return $this->channelRatedmedia;
    }

    /**
     * Set emailRepliedcomments
     *
     * @param boolean $emailRepliedcomments
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailRepliedcomments($emailRepliedcomments)
    {
        $this->emailRepliedcomments = $emailRepliedcomments;

        return $this;
    }

    /**
     * Get emailRepliedcomments
     *
     * @return boolean
     */
    public function getEmailRepliedcomments()
    {
        return $this->emailRepliedcomments;
    }

    /**
     * Set channelRepliedcomments
     *
     * @param boolean $channelRepliedcomments
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelRepliedcomments($channelRepliedcomments)
    {
        $this->channelRepliedcomments = $channelRepliedcomments;

        return $this;
    }

    /**
     * Get channelRepliedcomments
     *
     * @return boolean
     */
    public function getChannelRepliedcomments()
    {
        return $this->channelRepliedcomments;
    }

    /**
     * Set emailRepliedcommentsSomeone
     *
     * @param boolean $emailRepliedcommentsSomeone
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailRepliedcommentsSomeone($emailRepliedcommentsSomeone)
    {
        $this->emailRepliedcommentsSomeone = $emailRepliedcommentsSomeone;

        return $this;
    }

    /**
     * Get emailRepliedcommentsSomeone
     *
     * @return boolean
     */
    public function getEmailRepliedcommentsSomeone()
    {
        return $this->emailRepliedcommentsSomeone;
    }

    /**
     * Set channelRepliedcommentsSomeone
     *
     * @param boolean $channelRepliedcommentsSomeone
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelRepliedcommentsSomeone($channelRepliedcommentsSomeone)
    {
        $this->channelRepliedcommentsSomeone = $channelRepliedcommentsSomeone;

        return $this;
    }

    /**
     * Get channelRepliedcommentsSomeone
     *
     * @return boolean
     */
    public function getChannelRepliedcommentsSomeone()
    {
        return $this->channelRepliedcommentsSomeone;
    }

    /**
     * Set emailLikedcomment
     *
     * @param boolean $emailLikedcomment
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailLikedcomment($emailLikedcomment)
    {
        $this->emailLikedcomment = $emailLikedcomment;

        return $this;
    }

    /**
     * Get emailLikedcomment
     *
     * @return boolean
     */
    public function getEmailLikedcomment()
    {
        return $this->emailLikedcomment;
    }

    /**
     * Set channelLikedcomment
     *
     * @param boolean $channelLikedcomment
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelLikedcomment($channelLikedcomment)
    {
        $this->channelLikedcomment = $channelLikedcomment;

        return $this;
    }

    /**
     * Get channelLikedcomment
     *
     * @return boolean
     */
    public function getChannelLikedcomment()
    {
        return $this->channelLikedcomment;
    }

    /**
     * Set emailLikedcommentSomeone
     *
     * @param boolean $emailLikedcommentSomeone
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailLikedcommentSomeone($emailLikedcommentSomeone)
    {
        $this->emailLikedcommentSomeone = $emailLikedcommentSomeone;

        return $this;
    }

    /**
     * Get emailLikedcommentSomeone
     *
     * @return boolean
     */
    public function getEmailLikedcommentSomeone()
    {
        return $this->emailLikedcommentSomeone;
    }

    /**
     * Set channelLikedcommentSomeone
     *
     * @param boolean $channelLikedcommentSomeone
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelLikedcommentSomeone($channelLikedcommentSomeone)
    {
        $this->channelLikedcommentSomeone = $channelLikedcommentSomeone;

        return $this;
    }

    /**
     * Get channelLikedcommentSomeone
     *
     * @return boolean
     */
    public function getChannelLikedcommentSomeone()
    {
        return $this->channelLikedcommentSomeone;
    }

    /**
     * Set emailSharedchannel
     *
     * @param boolean $emailSharedchannel
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailSharedchannel($emailSharedchannel)
    {
        $this->emailSharedchannel = $emailSharedchannel;

        return $this;
    }

    /**
     * Get emailSharedchannel
     *
     * @return boolean
     */
    public function getEmailSharedchannel()
    {
        return $this->emailSharedchannel;
    }

    /**
     * Set channelSharedchannel
     *
     * @param boolean $channelSharedchannel
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelSharedchannel($channelSharedchannel)
    {
        $this->channelSharedchannel = $channelSharedchannel;

        return $this;
    }

    /**
     * Get channelSharedchannel
     *
     * @return boolean
     */
    public function getChannelSharedchannel()
    {
        return $this->channelSharedchannel;
    }

    /**
     * Set emailReportedconnections
     *
     * @param boolean $emailReportedconnections
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailReportedconnections($emailReportedconnections)
    {
        $this->emailReportedconnections = $emailReportedconnections;

        return $this;
    }

    /**
     * Get emailReportedconnections
     *
     * @return boolean
     */
    public function getEmailReportedconnections()
    {
        return $this->emailReportedconnections;
    }

    /**
     * Set channelReportedconnections
     *
     * @param boolean $channelReportedconnections
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelReportedconnections($channelReportedconnections)
    {
        $this->channelReportedconnections = $channelReportedconnections;

        return $this;
    }

    /**
     * Get channelReportedconnections
     *
     * @return boolean
     */
    public function getChannelReportedconnections()
    {
        return $this->channelReportedconnections;
    }

    /**
     * Set emailJoinsevent
     *
     * @param boolean $emailJoinsevent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailJoinsevent($emailJoinsevent)
    {
        $this->emailJoinsevent = $emailJoinsevent;

        return $this;
    }

    /**
     * Get emailJoinsevent
     *
     * @return boolean
     */
    public function getEmailJoinsevent()
    {
        return $this->emailJoinsevent;
    }

    /**
     * Set channelJoinsevent
     *
     * @param boolean $channelJoinsevent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelJoinsevent($channelJoinsevent)
    {
        $this->channelJoinsevent = $channelJoinsevent;

        return $this;
    }

    /**
     * Get channelJoinsevent
     *
     * @return boolean
     */
    public function getChannelJoinsevent()
    {
        return $this->channelJoinsevent;
    }

    /**
     * Set emailCommentedevent
     *
     * @param boolean $emailCommentedevent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailCommentedevent($emailCommentedevent)
    {
        $this->emailCommentedevent = $emailCommentedevent;

        return $this;
    }

    /**
     * Get emailCommentedevent
     *
     * @return boolean
     */
    public function getEmailCommentedevent()
    {
        return $this->emailCommentedevent;
    }

    /**
     * Set channelCommentedevent
     *
     * @param boolean $channelCommentedevent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelCommentedevent($channelCommentedevent)
    {
        $this->channelCommentedevent = $channelCommentedevent;

        return $this;
    }

    /**
     * Get channelCommentedevent
     *
     * @return boolean
     */
    public function getChannelCommentedevent()
    {
        return $this->channelCommentedevent;
    }

    /**
     * Set emailLikedevent
     *
     * @param boolean $emailLikedevent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailLikedevent($emailLikedevent)
    {
        $this->emailLikedevent = $emailLikedevent;

        return $this;
    }

    /**
     * Get emailLikedevent
     *
     * @return boolean
     */
    public function getEmailLikedevent()
    {
        return $this->emailLikedevent;
    }

    /**
     * Set channelLikedevent
     *
     * @param boolean $channelLikedevent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelLikedevent($channelLikedevent)
    {
        $this->channelLikedevent = $channelLikedevent;

        return $this;
    }

    /**
     * Get channelLikedevent
     *
     * @return boolean
     */
    public function getChannelLikedevent()
    {
        return $this->channelLikedevent;
    }

    /**
     * Set emailSharedevent
     *
     * @param boolean $emailSharedevent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailSharedevent($emailSharedevent)
    {
        $this->emailSharedevent = $emailSharedevent;

        return $this;
    }

    /**
     * Get emailSharedevent
     *
     * @return boolean
     */
    public function getEmailSharedevent()
    {
        return $this->emailSharedevent;
    }

    /**
     * Set channelSharedevent
     *
     * @param boolean $channelSharedevent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelSharedevent($channelSharedevent)
    {
        $this->channelSharedevent = $channelSharedevent;

        return $this;
    }

    /**
     * Get channelSharedevent
     *
     * @return boolean
     */
    public function getChannelSharedevent()
    {
        return $this->channelSharedevent;
    }

    /**
     * Set emailAcceptedinvitationEvent
     *
     * @param boolean $emailAcceptedinvitationEvent
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailAcceptedinvitationEvent($emailAcceptedinvitationEvent)
    {
        $this->emailAcceptedinvitationEvent = $emailAcceptedinvitationEvent;

        return $this;
    }

    /**
     * Get emailAcceptedinvitationEvent
     *
     * @return boolean
     */
    public function getEmailAcceptedinvitationEvent()
    {
        return $this->emailAcceptedinvitationEvent;
    }

    /**
     * Set channelAcceptedinvitationEvent
     *
     * @param boolean $channelAcceptedinvitationEvent
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelAcceptedinvitationEvent($channelAcceptedinvitationEvent)
    {
        $this->channelAcceptedinvitationEvent = $channelAcceptedinvitationEvent;

        return $this;
    }

    /**
     * Get channelAcceptedinvitationEvent
     *
     * @return boolean
     */
    public function getChannelAcceptedinvitationEvent()
    {
        return $this->channelAcceptedinvitationEvent;
    }

    /**
     * Set emailSubchannelrequest
     *
     * @param boolean $emailSubchannelrequest
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailSubchannelrequest($emailSubchannelrequest)
    {
        $this->emailSubchannelrequest = $emailSubchannelrequest;

        return $this;
    }

    /**
     * Get emailSubchannelrequest
     *
     * @return boolean
     */
    public function getEmailSubchannelrequest()
    {
        return $this->emailSubchannelrequest;
    }

    /**
     * Set channelAddsubchannel
     *
     * @param boolean $channelAddsubchannel
     *
     * @return CmsChannelPrivacy
     */
    public function setChannelAddsubchannel($channelAddsubchannel)
    {
        $this->channelAddsubchannel = $channelAddsubchannel;

        return $this;
    }

    /**
     * Get channelAddsubchannel
     *
     * @return boolean
     */
    public function getChannelAddsubchannel()
    {
        return $this->channelAddsubchannel;
    }

    /**
     * Set emailNewstt
     *
     * @param boolean $emailNewstt
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailNewstt($emailNewstt)
    {
        $this->emailNewstt = $emailNewstt;

        return $this;
    }

    /**
     * Get emailNewstt
     *
     * @return boolean
     */
    public function getEmailNewstt()
    {
        return $this->emailNewstt;
    }

    /**
     * Set emailUpdatesMedia
     *
     * @param boolean $emailUpdatesMedia
     *
     * @return CmsChannelPrivacy
     */
    public function setEmailUpdatesMedia($emailUpdatesMedia)
    {
        $this->emailUpdatesMedia = $emailUpdatesMedia;

        return $this;
    }

    /**
     * Get emailUpdatesMedia
     *
     * @return boolean
     */
    public function getEmailUpdatesMedia()
    {
        return $this->emailUpdatesMedia;
    }

    /**
     * Set privacySponsoring
     *
     * @param boolean $privacySponsoring
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacySponsoring($privacySponsoring)
    {
        $this->privacySponsoring = $privacySponsoring;

        return $this;
    }

    /**
     * Get privacySponsoring
     *
     * @return boolean
     */
    public function getPrivacySponsoring()
    {
        return $this->privacySponsoring;
    }

    /**
     * Set privacySharing
     *
     * @param boolean $privacySharing
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacySharing($privacySharing)
    {
        $this->privacySharing = $privacySharing;

        return $this;
    }

    /**
     * Get privacySharing
     *
     * @return boolean
     */
    public function getPrivacySharing()
    {
        return $this->privacySharing;
    }

    /**
     * Set privacyInvitations
     *
     * @param boolean $privacyInvitations
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyInvitations($privacyInvitations)
    {
        $this->privacyInvitations = $privacyInvitations;

        return $this;
    }

    /**
     * Get privacyInvitations
     *
     * @return boolean
     */
    public function getPrivacyInvitations()
    {
        return $this->privacyInvitations;
    }

    /**
     * Set privacySocial
     *
     * @param boolean $privacySocial
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacySocial($privacySocial)
    {
        $this->privacySocial = $privacySocial;

        return $this;
    }

    /**
     * Get privacySocial
     *
     * @return boolean
     */
    public function getPrivacySocial()
    {
        return $this->privacySocial;
    }

    /**
     * Set privacyEventsponsors
     *
     * @param boolean $privacyEventsponsors
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyEventsponsors($privacyEventsponsors)
    {
        $this->privacyEventsponsors = $privacyEventsponsors;

        return $this;
    }

    /**
     * Get privacyEventsponsors
     *
     * @return boolean
     */
    public function getPrivacyEventsponsors()
    {
        return $this->privacyEventsponsors;
    }

    /**
     * Set privacySponsoringEvent
     *
     * @param boolean $privacySponsoringEvent
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacySponsoringEvent($privacySponsoringEvent)
    {
        $this->privacySponsoringEvent = $privacySponsoringEvent;

        return $this;
    }

    /**
     * Get privacySponsoringEvent
     *
     * @return boolean
     */
    public function getPrivacySponsoringEvent()
    {
        return $this->privacySponsoringEvent;
    }

    /**
     * Set privacySharescomments
     *
     * @param boolean $privacySharescomments
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacySharescomments($privacySharescomments)
    {
        $this->privacySharescomments = $privacySharescomments;

        return $this;
    }

    /**
     * Get privacySharescomments
     *
     * @return boolean
     */
    public function getPrivacySharescomments()
    {
        return $this->privacySharescomments;
    }

    /**
     * Set privacyGuestsInvite
     *
     * @param boolean $privacyGuestsInvite
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyGuestsInvite($privacyGuestsInvite)
    {
        $this->privacyGuestsInvite = $privacyGuestsInvite;

        return $this;
    }

    /**
     * Get privacyGuestsInvite
     *
     * @return boolean
     */
    public function getPrivacyGuestsInvite()
    {
        return $this->privacyGuestsInvite;
    }

    /**
     * Set privacyLog
     *
     * @param boolean $privacyLog
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyLog($privacyLog)
    {
        $this->privacyLog = $privacyLog;

        return $this;
    }

    /**
     * Get privacyLog
     *
     * @return boolean
     */
    public function getPrivacyLog()
    {
        return $this->privacyLog;
    }

    /**
     * Set privacyMysponsoredChannels
     *
     * @param boolean $privacyMysponsoredChannels
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyMysponsoredChannels($privacyMysponsoredChannels)
    {
        $this->privacyMysponsoredChannels = $privacyMysponsoredChannels;

        return $this;
    }

    /**
     * Get privacyMysponsoredChannels
     *
     * @return boolean
     */
    public function getPrivacyMysponsoredChannels()
    {
        return $this->privacyMysponsoredChannels;
    }

    /**
     * Set privacyOtherChannels
     *
     * @param boolean $privacyOtherChannels
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyOtherChannels($privacyOtherChannels)
    {
        $this->privacyOtherChannels = $privacyOtherChannels;

        return $this;
    }

    /**
     * Get privacyOtherChannels
     *
     * @return boolean
     */
    public function getPrivacyOtherChannels()
    {
        return $this->privacyOtherChannels;
    }

    /**
     * Set privacyMysponsoredEvents
     *
     * @param boolean $privacyMysponsoredEvents
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyMysponsoredEvents($privacyMysponsoredEvents)
    {
        $this->privacyMysponsoredEvents = $privacyMysponsoredEvents;

        return $this;
    }

    /**
     * Get privacyMysponsoredEvents
     *
     * @return boolean
     */
    public function getPrivacyMysponsoredEvents()
    {
        return $this->privacyMysponsoredEvents;
    }

    /**
     * Set privacyActivechannels
     *
     * @param boolean $privacyActivechannels
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyActivechannels($privacyActivechannels)
    {
        $this->privacyActivechannels = $privacyActivechannels;

        return $this;
    }

    /**
     * Get privacyActivechannels
     *
     * @return boolean
     */
    public function getPrivacyActivechannels()
    {
        return $this->privacyActivechannels;
    }

    /**
     * Set privacyActivetubers
     *
     * @param boolean $privacyActivetubers
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyActivetubers($privacyActivetubers)
    {
        $this->privacyActivetubers = $privacyActivetubers;

        return $this;
    }

    /**
     * Get privacyActivetubers
     *
     * @return boolean
     */
    public function getPrivacyActivetubers()
    {
        return $this->privacyActivetubers;
    }

    /**
     * Set privacySharescommentsContent
     *
     * @param boolean $privacySharescommentsContent
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacySharescommentsContent($privacySharescommentsContent)
    {
        $this->privacySharescommentsContent = $privacySharescommentsContent;

        return $this;
    }

    /**
     * Get privacySharescommentsContent
     *
     * @return boolean
     */
    public function getPrivacySharescommentsContent()
    {
        return $this->privacySharescommentsContent;
    }

    /**
     * Set privacyLogTtpage
     *
     * @param boolean $privacyLogTtpage
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyLogTtpage($privacyLogTtpage)
    {
        $this->privacyLogTtpage = $privacyLogTtpage;

        return $this;
    }

    /**
     * Get privacyLogTtpage
     *
     * @return boolean
     */
    public function getPrivacyLogTtpage()
    {
        return $this->privacyLogTtpage;
    }

    /**
     * Set privacyBesubchannel
     *
     * @param boolean $privacyBesubchannel
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyBesubchannel($privacyBesubchannel)
    {
        $this->privacyBesubchannel = $privacyBesubchannel;

        return $this;
    }

    /**
     * Get privacyBesubchannel
     *
     * @return boolean
     */
    public function getPrivacyBesubchannel()
    {
        return $this->privacyBesubchannel;
    }

    /**
     * Set privacyBeparentchannel
     *
     * @param boolean $privacyBeparentchannel
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyBeparentchannel($privacyBeparentchannel)
    {
        $this->privacyBeparentchannel = $privacyBeparentchannel;

        return $this;
    }

    /**
     * Get privacyBeparentchannel
     *
     * @return boolean
     */
    public function getPrivacyBeparentchannel()
    {
        return $this->privacyBeparentchannel;
    }

    /**
     * Set privacyMysubChannels
     *
     * @param boolean $privacyMysubChannels
     *
     * @return CmsChannelPrivacy
     */
    public function setPrivacyMysubChannels($privacyMysubChannels)
    {
        $this->privacyMysubChannels = $privacyMysubChannels;

        return $this;
    }

    /**
     * Get privacyMysubChannels
     *
     * @return boolean
     */
    public function getPrivacyMysubChannels()
    {
        return $this->privacyMysubChannels;
    }

    /**
     * Set published
     *
     * @param integer $published
     *
     * @return CmsChannelPrivacy
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer
     */
    public function getPublished()
    {
        return $this->published;
    }
}
