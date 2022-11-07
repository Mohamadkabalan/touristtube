<?php

namespace TTBundle\Model;

class AwsS3List extends AwsS3
{
    private $maxKeys;
    private $token;
    private $searchString;

    public function __construct()
    {
        parent::__construct();

        $this->maxKeys      = 0;
        $this->token        = '';
        $this->searchString = '';
    }

    public function getMaxKeys()
    {
        return intval($this->maxKeys);
    }

    function getToken()
    {
        return trim($this->token);
    }

    public function getSearchString()
    {
        return $this->searchString;
    }

    public function getSearchStringRegExPattern()
    {
        $toreturn = "";
        if (!empty($this->getSearchString())) {
            $toreturn = preg_replace('/([^a-z0-9_\*])/mi', '\\\\$1', $this->getSearchString());
            $toreturn = str_replace('*', '(.*)', $toreturn);

            $toreturn = "/^({$toreturn})$/mi";
        }

        return $toreturn;
    }

    public function setMaxKeys($maxKeys)
    {
        $this->maxKeys = $maxKeys;
        return $this;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function setSearchString($searchString)
    {
        $this->searchString = trim($searchString);
        return $this;
    }

    public function toArray()
    {
        $toreturn = [
            'Bucket' => $this->getBucket(),
            'Prefix' => $this->getKey()
        ];

        if ($this->getMaxKeys() > 0) {
            $toreturn['MaxKeys'] = $this->getMaxKeys();
        }

        if (!empty($this->getToken())) {
            $toreturn['ContinuationToken'] = $this->getToken();
        }

        return $toreturn;
    }
}
