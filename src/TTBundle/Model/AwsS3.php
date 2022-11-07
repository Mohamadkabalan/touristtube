<?php

namespace TTBundle\Model;

class AwsS3
{
    private $bucket;
    private $key;
    private $newKey;

    public function __construct()
    {
        $this->bucket = '';
        $this->key    = '';
        $this->newKey = '';
    }

    public function getBucket()
    {
        return $this->bucket;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getNewKey()
    {
        return $this->newKey;
    }

    public function setBucket($bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    public function setKey($key)
    {
        // Removes the leading slash of $key if sent
        $key = preg_replace("/^(\/)/m", '', $key);

        // Removes the trailing slash of $key if sent (hence replacing // with /)
        $key = preg_replace("/(\/){2,}/m", '/', $key);

        $this->key = $key;
        return $this;
    }

    public function setNewKey($newKey)
    {
        $this->newKey = $newKey;
        return $this;
    }
}
