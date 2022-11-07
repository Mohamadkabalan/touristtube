<?php

namespace TTBundle\Utils;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class TTSerializerUtils{
    
    public function __construct()
    {
        
        $this->encoders    = array(new XmlEncoder(), new JsonEncoder());
        $this->normalizers = array(new GetSetMethodNormalizer());
        $this->serializer  = new Serializer($this->normalizers, $this->encoders);
        
    }
    
    /**
     * This function transforms any json to a given object
     */
    public function deserializeJsonToObject($json, $object)
    {
        
        $result = $this->serializer->deserialize($json, $object, 'json');
        return $result;
    }
    
}