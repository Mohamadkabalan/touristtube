<?php

namespace TTBundle\Model;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ElasticSearchSC
{
    private $query;
    private $index;
    private $docType;
    private $url_source=NULL;
    private $host;
    private $hosts;
    private $port = 9200;
    //
    private $term = null;
    private $criteria = array();
    //
    
    
    public function getQuery() {
        return $this->query;
    }
    
    public function getIndex() {
        return $this->index;
    }
    
    public function getDocType() {
        return $this->docType;
    }
    
    public function getUrlSource() {
        return $this->url_source;
    }
    
    public function getHost() {
        return $this->host;
    }
    
    public function getHosts() {
        return $this->hosts;
    }
    
    public function getPort() {
        return $this->port;
    }
    
    public function getTerm() {
        return $this->term;
    }
    
    public function getCriteria() {
        return $this->criteria;
    }
    
    public function setQuery($query) {
        $this->query = $query;
    }
    
    public function setIndex($index) {
        $this->index = $index;
    }
    
    public function setDocType($docType) {
        $this->docType = $docType;
    }
    
    public function setUrlSource($url_source) {
        $this->url_source = $url_source;
    }
    
    public function setHost($host) {
        $this->host = $host;
    }
    
    public function setHosts($hosts) {
        $this->hosts = $hosts;
    }
    
    public function setPort($port) {
        $this->port = $port;
    }
    
    public function setTerm($term) {
        $this->term = $term;
    }
    
    public function setCriteria($criteria) {
        $this->criteria = $criteria;
    }
    
}
