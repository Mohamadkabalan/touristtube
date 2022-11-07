<?php

namespace DealBundle\Model;

/**
 * DealNote contains the frequently asked questions from activitiyDetails call
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealFaq
{
    private $question = '';
    private $answer   = '';

    /**
     * Get question
     * @return String
     */
    function getQuestion()
    {
        return $this->question;
    }

    /**
     * Get answer
     * @return String
     */
    function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set question
     * @param String $question
     */
    function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * Set answer
     * @param String $answer
     */
    function setAnswer($answer)
    {
        $this->answer = $answer;
    }
}