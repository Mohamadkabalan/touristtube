<?php

namespace DealBundle\Model;

/**
 * DealReview contains the attributes for reviews.
 * These reviews are used for Tour Reviews section of tourDetails.
 * We have an attribute $reviews in main class DealReponse for this.
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealReview
{
    private $reviewId = '';
    private $comment  = '';
    private $rating   = '';
    private $owner    = '';
    private $country  = '';
    private $date     = '';

    /**
     * Get reviewId
     * @return String
     */
    function getReviewId()
    {
        return $this->reviewId;
    }

    /**
     * Get comment
     * @return String
     */
    function getComment()
    {
        return $this->comment;
    }

    /**
     * Get rating
     * @return String
     */
    function getRating()
    {
        return $this->rating;
    }

    /**
     * Get owner
     * @return String
     */
    function getOwner()
    {
        return $this->owner;
    }

    /**
     * Get country
     * @return String
     */
    function getCountry()
    {
        return $this->country;
    }

    /**
     * Get date
     * @return String
     */
    function getDate()
    {
        return $this->date;
    }

    /**
     * Set reviewId
     * @param String $reviewId
     */
    function setReviewId($reviewId)
    {
        $this->reviewId = $reviewId;
    }

    /**
     * Set comment
     * @param String $comment
     */
    function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Set rating
     * @param String $rating
     */
    function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * Set owner
     * @param String $owner
     */
    function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * Set country
     * @param String $country
     */
    function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Set date
     * @param String $date
     */
    function setDate($date)
    {
        $this->date = $date;
    }
}