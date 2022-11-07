<?php

namespace TTBundle\Model;


class TTSearchCritiria
{
    /**
     * @var string
     */
    private $term;

    /**
     * @var string
     */
    private $excluded;

    /**
     * @var integer
     */
    private $limit; 

    /**
     * @var integer
     */
    private $page; 

    /**
     * @var integer
     */
    private $start;

    /**
     * @var string
     */
    private $sortOrder;

    /**
     * @var array
     */
    private $param = array();

    public function getTerm(){
        return $this->term;
    }

    public function setTerm($term){
        $this->term = $term;
       return $this;
    }

    public function getExcluded(){
        return $this->excluded;
    }

    public function setExcluded($excluded){
        $this->excluded = $excluded;
       return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setLimit($limit){
        $this->limit = $limit;
       return $this;
    }

    public function getPage(){
        return $this->page;
    }

    public function setPage($page){
        $this->page = $page;
       return $this;
    }

    public function getSortOrder(){
        return $this->sortOrder;
    }

    public function setSortOrder($sortOrder){
       $this->sortOrder = $sortOrder;
       return $this;
    }

    public function setStart($start){
        $this->start = $start;

        return $this;
    }

    public function getStart(){
        return $this->start;
    }

    public function setParam($param){
        $this->param = $param;

        return $this;
    }

    public function getParam(){
        return $this->param;
    }

    public function addParam($param, $value){ 
        $this->param[$param] = $value;
    }

}
