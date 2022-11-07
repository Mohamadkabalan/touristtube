<?php

namespace HotelBundle\Model;

class HotelServiceConfig
{
    //put your code here
    var $isRest      = false;
    var $page;
    var $pageSrc     = '';
    var $currency;
    var $transactionSourceId;
    var $userAgent;
    var $templates   = array();
    var $routes      = array();
    var $useTTApi    = false;
    var $prepaidOnly = false;
    var $infoSource  = '';
    var $preview360  = false;

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getIsRest()
    {
        return $this->isRest;
    }

    public function setIsRest($isRest)
    {
        $this->isRest = $isRest;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function setPage($page)
    {
        $this->page = $page;
    }

    public function getPageSrc()
    {
        return $this->pageSrc;
    }

    public function setPageSrc($pageSrc)
    {
        $this->pageSrc = $pageSrc;
    }

    public function getTransactionSourceId()
    {
        return $this->transactionSourceId;
    }

    public function setTransactionSourceId($transactionSourceId)
    {
        $this->transactionSourceId = $transactionSourceId;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    public function getTemplate($templateName)
    {
        return (isset($this->templates[$templateName]) && !empty($this->templates[$templateName])) ? $this->templates[$templateName] : '';
    }

    public function getTemplates()
    {
        return $this->templates;
    }

    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    public function addRoute($routeName, $routeUrl)
    {
        $this->routes[$routeName] = $routeUrl;
        return $this;
    }

    public function getRoute($routeName)
    {
        return (isset($this->routes[$routeName]) && !empty($this->routes[$routeName])) ? $this->routes[$routeName] : '';
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    public function isUseTTApi()
    {
        return $this->useTTApi;
    }

    public function setUseTTApi($useTTApi)
    {
        $this->useTTApi = $useTTApi;
    }

    public function isPrepaidOnly()
    {
        return $this->prepaidOnly;
    }

    public function setPrepaidOnly($prepaidOnly)
    {
        $this->prepaidOnly = $prepaidOnly;
        return $this;
    }

    public function getInfoSource()
    {
        return $this->infoSource;
    }

    public function setInfoSource($infoSource)
    {
        $this->infoSource = $infoSource;
        return $this;
    }

    public function isPreview360()
    {
        return $this->preview360;
    }

    public function setPreview360($preview360)
    {
        $this->preview360 = boolval($preview360);
        return $this;
    }
}
