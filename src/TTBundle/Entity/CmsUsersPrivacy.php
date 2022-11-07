<?php

namespace TTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CmsUsersPrivacy
 *
 * @ORM\Table(name="cms_users_privacy", indexes={@ORM\Index(name="user_id", columns={"user_id"})})
 * @ORM\Entity
 */
class CmsUsersPrivacy
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
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_notifications", type="boolean", nullable=false)
     */
    private $emailNotifications = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_notifications", type="boolean", nullable=false)
     */
    private $tuberNotifications = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_commentedcontent", type="boolean", nullable=false)
     */
    private $emailCommentedcontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_commentedcontent", type="boolean", nullable=false)
     */
    private $tuberCommentedcontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_likedcontent", type="boolean", nullable=false)
     */
    private $tuberLikedcontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_sharedcontent", type="boolean", nullable=false)
     */
    private $tuberSharedcontent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_ratedmedia", type="boolean", nullable=false)
     */
    private $tuberRatedmedia = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_mentionedcomment", type="boolean", nullable=false)
     */
    private $emailMentionedcomment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_mentionedcomment", type="boolean", nullable=false)
     */
    private $tuberMentionedcomment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_likedcomment", type="boolean", nullable=false)
     */
    private $tuberLikedcomment = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_joinedevent", type="boolean", nullable=false)
     */
    private $emailJoinedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_joinedevent", type="boolean", nullable=false)
     */
    private $tuberJoinedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_commentedevent", type="boolean", nullable=false)
     */
    private $emailCommentedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_commentedevent", type="boolean", nullable=false)
     */
    private $tuberCommentedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_likedevent", type="boolean", nullable=false)
     */
    private $tuberLikedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_sharedevent", type="boolean", nullable=false)
     */
    private $tuberSharedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_commentedecho", type="boolean", nullable=false)
     */
    private $emailCommentedecho = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_commentedecho", type="boolean", nullable=false)
     */
    private $tuberCommentedecho = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_likedecho", type="boolean", nullable=false)
     */
    private $tuberLikedecho = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_reechoedecho", type="boolean", nullable=false)
     */
    private $tuberReechoedecho = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_invitedchannel", type="boolean", nullable=false)
     */
    private $emailInvitedchannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_invitedchannel", type="boolean", nullable=false)
     */
    private $tuberInvitedchannel = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_invitedevent", type="boolean", nullable=false)
     */
    private $emailInvitedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_invitedevent", type="boolean", nullable=false)
     */
    private $tuberInvitedevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_friendrequest", type="boolean", nullable=false)
     */
    private $emailFriendrequest = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_friendrequest", type="boolean", nullable=false)
     */
    private $tuberFriendrequest = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_addedfriend", type="boolean", nullable=false)
     */
    private $tuberAddedfriend = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_followed", type="boolean", nullable=false)
     */
    private $tuberFollowed = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_updatedcanceledevent", type="boolean", nullable=false)
     */
    private $emailUpdatedcanceledevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="tuber_updatedcanceledevent", type="boolean", nullable=false)
     */
    private $tuberUpdatedcanceledevent = '1';

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
     * @ORM\Column(name="privacy_likesrates", type="boolean", nullable=false)
     */
    private $privacyLikesrates = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_sharescomments", type="boolean", nullable=false)
     */
    private $privacySharescomments = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_channelcreated", type="boolean", nullable=false)
     */
    private $privacyChannelcreated = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_channelevent", type="boolean", nullable=false)
     */
    private $privacyChannelevent = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_friends", type="boolean", nullable=false)
     */
    private $privacyFriends = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_eventsharescomments", type="boolean", nullable=false)
     */
    private $privacyEventsharescomments = '1';

    /**
     * @var boolean
     *
     * @ORM\Column(name="privacy_guests_invite", type="boolean", nullable=false)
     */
    private $privacyGuestsInvite = '1';



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
     * Set userId
     *
     * @param integer $userId
     *
     * @return CmsUsersPrivacy
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set emailNotifications
     *
     * @param boolean $emailNotifications
     *
     * @return CmsUsersPrivacy
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
     * Set tuberNotifications
     *
     * @param boolean $tuberNotifications
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberNotifications($tuberNotifications)
    {
        $this->tuberNotifications = $tuberNotifications;

        return $this;
    }

    /**
     * Get tuberNotifications
     *
     * @return boolean
     */
    public function getTuberNotifications()
    {
        return $this->tuberNotifications;
    }

    /**
     * Set emailCommentedcontent
     *
     * @param boolean $emailCommentedcontent
     *
     * @return CmsUsersPrivacy
     */
    public function setEmailCommentedcontent($emailCommentedcontent)
    {
        $this->emailCommentedcontent = $emailCommentedcontent;

        return $this;
    }

    /**
     * Get emailCommentedcontent
     *
     * @return boolean
     */
    public function getEmailCommentedcontent()
    {
        return $this->emailCommentedcontent;
    }

    /**
     * Set tuberCommentedcontent
     *
     * @param boolean $tuberCommentedcontent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberCommentedcontent($tuberCommentedcontent)
    {
        $this->tuberCommentedcontent = $tuberCommentedcontent;

        return $this;
    }

    /**
     * Get tuberCommentedcontent
     *
     * @return boolean
     */
    public function getTuberCommentedcontent()
    {
        return $this->tuberCommentedcontent;
    }

    /**
     * Set tuberLikedcontent
     *
     * @param boolean $tuberLikedcontent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberLikedcontent($tuberLikedcontent)
    {
        $this->tuberLikedcontent = $tuberLikedcontent;

        return $this;
    }

    /**
     * Get tuberLikedcontent
     *
     * @return boolean
     */
    public function getTuberLikedcontent()
    {
        return $this->tuberLikedcontent;
    }

    /**
     * Set tuberSharedcontent
     *
     * @param boolean $tuberSharedcontent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberSharedcontent($tuberSharedcontent)
    {
        $this->tuberSharedcontent = $tuberSharedcontent;

        return $this;
    }

    /**
     * Get tuberSharedcontent
     *
     * @return boolean
     */
    public function getTuberSharedcontent()
    {
        return $this->tuberSharedcontent;
    }

    /**
     * Set tuberRatedmedia
     *
     * @param boolean $tuberRatedmedia
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberRatedmedia($tuberRatedmedia)
    {
        $this->tuberRatedmedia = $tuberRatedmedia;

        return $this;
    }

    /**
     * Get tuberRatedmedia
     *
     * @return boolean
     */
    public function getTuberRatedmedia()
    {
        return $this->tuberRatedmedia;
    }

    /**
     * Set emailMentionedcomment
     *
     * @param boolean $emailMentionedcomment
     *
     * @return CmsUsersPrivacy
     */
    public function setEmailMentionedcomment($emailMentionedcomment)
    {
        $this->emailMentionedcomment = $emailMentionedcomment;

        return $this;
    }

    /**
     * Get emailMentionedcomment
     *
     * @return boolean
     */
    public function getEmailMentionedcomment()
    {
        return $this->emailMentionedcomment;
    }

    /**
     * Set tuberMentionedcomment
     *
     * @param boolean $tuberMentionedcomment
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberMentionedcomment($tuberMentionedcomment)
    {
        $this->tuberMentionedcomment = $tuberMentionedcomment;

        return $this;
    }

    /**
     * Get tuberMentionedcomment
     *
     * @return boolean
     */
    public function getTuberMentionedcomment()
    {
        return $this->tuberMentionedcomment;
    }

    /**
     * Set tuberLikedcomment
     *
     * @param boolean $tuberLikedcomment
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberLikedcomment($tuberLikedcomment)
    {
        $this->tuberLikedcomment = $tuberLikedcomment;

        return $this;
    }

    /**
     * Get tuberLikedcomment
     *
     * @return boolean
     */
    public function getTuberLikedcomment()
    {
        return $this->tuberLikedcomment;
    }

    /**
     * Set emailJoinedevent
     *
     * @param boolean $emailJoinedevent
     *
     * @return CmsUsersPrivacy
     */
    public function setEmailJoinedevent($emailJoinedevent)
    {
        $this->emailJoinedevent = $emailJoinedevent;

        return $this;
    }

    /**
     * Get emailJoinedevent
     *
     * @return boolean
     */
    public function getEmailJoinedevent()
    {
        return $this->emailJoinedevent;
    }

    /**
     * Set tuberJoinedevent
     *
     * @param boolean $tuberJoinedevent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberJoinedevent($tuberJoinedevent)
    {
        $this->tuberJoinedevent = $tuberJoinedevent;

        return $this;
    }

    /**
     * Get tuberJoinedevent
     *
     * @return boolean
     */
    public function getTuberJoinedevent()
    {
        return $this->tuberJoinedevent;
    }

    /**
     * Set emailCommentedevent
     *
     * @param boolean $emailCommentedevent
     *
     * @return CmsUsersPrivacy
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
     * Set tuberCommentedevent
     *
     * @param boolean $tuberCommentedevent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberCommentedevent($tuberCommentedevent)
    {
        $this->tuberCommentedevent = $tuberCommentedevent;

        return $this;
    }

    /**
     * Get tuberCommentedevent
     *
     * @return boolean
     */
    public function getTuberCommentedevent()
    {
        return $this->tuberCommentedevent;
    }

    /**
     * Set tuberLikedevent
     *
     * @param boolean $tuberLikedevent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberLikedevent($tuberLikedevent)
    {
        $this->tuberLikedevent = $tuberLikedevent;

        return $this;
    }

    /**
     * Get tuberLikedevent
     *
     * @return boolean
     */
    public function getTuberLikedevent()
    {
        return $this->tuberLikedevent;
    }

    /**
     * Set tuberSharedevent
     *
     * @param boolean $tuberSharedevent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberSharedevent($tuberSharedevent)
    {
        $this->tuberSharedevent = $tuberSharedevent;

        return $this;
    }

    /**
     * Get tuberSharedevent
     *
     * @return boolean
     */
    public function getTuberSharedevent()
    {
        return $this->tuberSharedevent;
    }

    /**
     * Set emailCommentedecho
     *
     * @param boolean $emailCommentedecho
     *
     * @return CmsUsersPrivacy
     */
    public function setEmailCommentedecho($emailCommentedecho)
    {
        $this->emailCommentedecho = $emailCommentedecho;

        return $this;
    }

    /**
     * Get emailCommentedecho
     *
     * @return boolean
     */
    public function getEmailCommentedecho()
    {
        return $this->emailCommentedecho;
    }

    /**
     * Set tuberCommentedecho
     *
     * @param boolean $tuberCommentedecho
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberCommentedecho($tuberCommentedecho)
    {
        $this->tuberCommentedecho = $tuberCommentedecho;

        return $this;
    }

    /**
     * Get tuberCommentedecho
     *
     * @return boolean
     */
    public function getTuberCommentedecho()
    {
        return $this->tuberCommentedecho;
    }

    /**
     * Set tuberLikedecho
     *
     * @param boolean $tuberLikedecho
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberLikedecho($tuberLikedecho)
    {
        $this->tuberLikedecho = $tuberLikedecho;

        return $this;
    }

    /**
     * Get tuberLikedecho
     *
     * @return boolean
     */
    public function getTuberLikedecho()
    {
        return $this->tuberLikedecho;
    }

    /**
     * Set tuberReechoedecho
     *
     * @param boolean $tuberReechoedecho
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberReechoedecho($tuberReechoedecho)
    {
        $this->tuberReechoedecho = $tuberReechoedecho;

        return $this;
    }

    /**
     * Get tuberReechoedecho
     *
     * @return boolean
     */
    public function getTuberReechoedecho()
    {
        return $this->tuberReechoedecho;
    }

    /**
     * Set emailInvitedchannel
     *
     * @param boolean $emailInvitedchannel
     *
     * @return CmsUsersPrivacy
     */
    public function setEmailInvitedchannel($emailInvitedchannel)
    {
        $this->emailInvitedchannel = $emailInvitedchannel;

        return $this;
    }

    /**
     * Get emailInvitedchannel
     *
     * @return boolean
     */
    public function getEmailInvitedchannel()
    {
        return $this->emailInvitedchannel;
    }

    /**
     * Set tuberInvitedchannel
     *
     * @param boolean $tuberInvitedchannel
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberInvitedchannel($tuberInvitedchannel)
    {
        $this->tuberInvitedchannel = $tuberInvitedchannel;

        return $this;
    }

    /**
     * Get tuberInvitedchannel
     *
     * @return boolean
     */
    public function getTuberInvitedchannel()
    {
        return $this->tuberInvitedchannel;
    }

    /**
     * Set emailInvitedevent
     *
     * @param boolean $emailInvitedevent
     *
     * @return CmsUsersPrivacy
     */
    public function setEmailInvitedevent($emailInvitedevent)
    {
        $this->emailInvitedevent = $emailInvitedevent;

        return $this;
    }

    /**
     * Get emailInvitedevent
     *
     * @return boolean
     */
    public function getEmailInvitedevent()
    {
        return $this->emailInvitedevent;
    }

    /**
     * Set tuberInvitedevent
     *
     * @param boolean $tuberInvitedevent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberInvitedevent($tuberInvitedevent)
    {
        $this->tuberInvitedevent = $tuberInvitedevent;

        return $this;
    }

    /**
     * Get tuberInvitedevent
     *
     * @return boolean
     */
    public function getTuberInvitedevent()
    {
        return $this->tuberInvitedevent;
    }

    /**
     * Set emailFriendrequest
     *
     * @param boolean $emailFriendrequest
     *
     * @return CmsUsersPrivacy
     */
    public function setEmailFriendrequest($emailFriendrequest)
    {
        $this->emailFriendrequest = $emailFriendrequest;

        return $this;
    }

    /**
     * Get emailFriendrequest
     *
     * @return boolean
     */
    public function getEmailFriendrequest()
    {
        return $this->emailFriendrequest;
    }

    /**
     * Set tuberFriendrequest
     *
     * @param boolean $tuberFriendrequest
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberFriendrequest($tuberFriendrequest)
    {
        $this->tuberFriendrequest = $tuberFriendrequest;

        return $this;
    }

    /**
     * Get tuberFriendrequest
     *
     * @return boolean
     */
    public function getTuberFriendrequest()
    {
        return $this->tuberFriendrequest;
    }

    /**
     * Set tuberAddedfriend
     *
     * @param boolean $tuberAddedfriend
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberAddedfriend($tuberAddedfriend)
    {
        $this->tuberAddedfriend = $tuberAddedfriend;

        return $this;
    }

    /**
     * Get tuberAddedfriend
     *
     * @return boolean
     */
    public function getTuberAddedfriend()
    {
        return $this->tuberAddedfriend;
    }

    /**
     * Set tuberFollowed
     *
     * @param boolean $tuberFollowed
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberFollowed($tuberFollowed)
    {
        $this->tuberFollowed = $tuberFollowed;

        return $this;
    }

    /**
     * Get tuberFollowed
     *
     * @return boolean
     */
    public function getTuberFollowed()
    {
        return $this->tuberFollowed;
    }

    /**
     * Set emailUpdatedcanceledevent
     *
     * @param boolean $emailUpdatedcanceledevent
     *
     * @return CmsUsersPrivacy
     */
    public function setEmailUpdatedcanceledevent($emailUpdatedcanceledevent)
    {
        $this->emailUpdatedcanceledevent = $emailUpdatedcanceledevent;

        return $this;
    }

    /**
     * Get emailUpdatedcanceledevent
     *
     * @return boolean
     */
    public function getEmailUpdatedcanceledevent()
    {
        return $this->emailUpdatedcanceledevent;
    }

    /**
     * Set tuberUpdatedcanceledevent
     *
     * @param boolean $tuberUpdatedcanceledevent
     *
     * @return CmsUsersPrivacy
     */
    public function setTuberUpdatedcanceledevent($tuberUpdatedcanceledevent)
    {
        $this->tuberUpdatedcanceledevent = $tuberUpdatedcanceledevent;

        return $this;
    }

    /**
     * Get tuberUpdatedcanceledevent
     *
     * @return boolean
     */
    public function getTuberUpdatedcanceledevent()
    {
        return $this->tuberUpdatedcanceledevent;
    }

    /**
     * Set emailNewstt
     *
     * @param boolean $emailNewstt
     *
     * @return CmsUsersPrivacy
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
     * @return CmsUsersPrivacy
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
     * Set privacyLikesrates
     *
     * @param boolean $privacyLikesrates
     *
     * @return CmsUsersPrivacy
     */
    public function setPrivacyLikesrates($privacyLikesrates)
    {
        $this->privacyLikesrates = $privacyLikesrates;

        return $this;
    }

    /**
     * Get privacyLikesrates
     *
     * @return boolean
     */
    public function getPrivacyLikesrates()
    {
        return $this->privacyLikesrates;
    }

    /**
     * Set privacySharescomments
     *
     * @param boolean $privacySharescomments
     *
     * @return CmsUsersPrivacy
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
     * Set privacyChannelcreated
     *
     * @param boolean $privacyChannelcreated
     *
     * @return CmsUsersPrivacy
     */
    public function setPrivacyChannelcreated($privacyChannelcreated)
    {
        $this->privacyChannelcreated = $privacyChannelcreated;

        return $this;
    }

    /**
     * Get privacyChannelcreated
     *
     * @return boolean
     */
    public function getPrivacyChannelcreated()
    {
        return $this->privacyChannelcreated;
    }

    /**
     * Set privacyChannelevent
     *
     * @param boolean $privacyChannelevent
     *
     * @return CmsUsersPrivacy
     */
    public function setPrivacyChannelevent($privacyChannelevent)
    {
        $this->privacyChannelevent = $privacyChannelevent;

        return $this;
    }

    /**
     * Get privacyChannelevent
     *
     * @return boolean
     */
    public function getPrivacyChannelevent()
    {
        return $this->privacyChannelevent;
    }

    /**
     * Set privacyFriends
     *
     * @param boolean $privacyFriends
     *
     * @return CmsUsersPrivacy
     */
    public function setPrivacyFriends($privacyFriends)
    {
        $this->privacyFriends = $privacyFriends;

        return $this;
    }

    /**
     * Get privacyFriends
     *
     * @return boolean
     */
    public function getPrivacyFriends()
    {
        return $this->privacyFriends;
    }

    /**
     * Set privacyEventsharescomments
     *
     * @param boolean $privacyEventsharescomments
     *
     * @return CmsUsersPrivacy
     */
    public function setPrivacyEventsharescomments($privacyEventsharescomments)
    {
        $this->privacyEventsharescomments = $privacyEventsharescomments;

        return $this;
    }

    /**
     * Get privacyEventsharescomments
     *
     * @return boolean
     */
    public function getPrivacyEventsharescomments()
    {
        return $this->privacyEventsharescomments;
    }

    /**
     * Set privacyGuestsInvite
     *
     * @param boolean $privacyGuestsInvite
     *
     * @return CmsUsersPrivacy
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
}
