<?php

namespace DealBundle\Model;

/**
 * DealNote contains the notes from activitiyDetails call
 *
 * @author Firas Bou Karroum <firas.boukarroum@touristtube.com>
 */
class DealNote
{
    private $title   = '';
    private $info    = '';
    private $note    = '';
    private $pdfPath = '';
    private $groupId = 0;

    /**
     * Get title
     * @return String
     */
    function getTitle()
    {
        return $this->title;
    }

    /**
     * Get info
     * @return String
     */
    function getInfo()
    {
        return $this->info;
    }

    /**
     * Get note
     * @return String
     */
    function getNote()
    {
        return $this->note;
    }

    /**
     * Get pdfPath
     * @return String
     */
    function getPdfPath()
    {
        return $this->pdfPath;
    }

    /**
     * Get groupId
     * @return Integer
     */
    function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * Set title
     * @param String $title
     */
    function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set info
     * @param String $info
     */
    function setInfo($info)
    {
        $this->info = $info;
    }

    /**
     * Set note
     * @param String $note
     */
    function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Set pdfPath
     * @param String $pdfPath
     */
    function setPdfPath($pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    /**
     * Set groupId
     * @param Integer $groupId
     */
    function setGroupId($groupId)
    {
        $this->groupId = $groupId;
    }
}