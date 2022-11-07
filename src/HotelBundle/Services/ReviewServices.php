<?php

namespace HotelBundle\Services;

use TTBundle\Utils\Utils;
use Symfony\Component\DependencyInjection\ContainerInterface;
use HotelBundle\vendors\TrustYou\v5_41\TrustYouHandler;

class ReviewServices
{

    public function __construct(Utils $utils, ContainerInterface $container)
    {
        $this->TrustYouHandler = new TrustYouHandler($utils, $container);
    }

    public function getSeal($ty_id, $language = null)
    {
        return $this->TrustYouHandler->getSeal($ty_id, $language);
    }

    public function getMetaReview($ty_id, $language = null, $forPage = 'hotelDetails')
    {
        return $this->TrustYouHandler->getMetaReview($ty_id, $language, $forPage);
    }

    public function getBulk($ty_id, $language = null)
    {
        return $this->TrustYouHandler->getBulk($ty_id, $language);
    }
}
