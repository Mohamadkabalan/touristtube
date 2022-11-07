<?php

namespace TTBundle\Controller;

use TTBundle\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotoVideosController extends DefaultController {

    public function mediaNewAction() {
        return $this->render('photo_videos/media_new.twig', $this->data);
    }
}
