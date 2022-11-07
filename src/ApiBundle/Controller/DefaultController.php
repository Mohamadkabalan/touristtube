<?php

namespace ApiBundle\Controller;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//use TTBundle\Entity\Webgeocities;
//use TTBundle\Entity\CmsCountries;
//use TTBundle\Entity\DiscoverHotels;
//use Symfony\Component\Validator\Mapping\ClassMetadata;
//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Type;
class DefaultController extends Controller
{
    private $language = 'en';
    private $timezone;
    private $user_id;
    
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->containerInitialized();
    }
    
    private function containerInitialized()
    {
        $request = Request::createFromGlobals();
        $this->language = $request->query->get('lang', 'en');
//        $this->language = $request->request->get('lang', 'en');
        $this->timezone = $request->request->get('timezone', '');
        $token = $request->request->get('S', '');
        if( $token == '' && $request->query->get('S', '')!='' ) $token = $request->query->get('S', '');        
        $ttdCRep = $this->get('ApiUserServices')->mobileIsLogged($token);
        $this->user_id = $ttdCRep;
    }

    public function getUserId(){
        return $this->user_id;
    }
    
    public function isLogged(){
        return $this->user_id != false;
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
   
    public function getTimezone()
    {
        return $this->timezone;
    }
    
    
    
    public function convertToJson($param) {
        $res = new Response();
        $res->setContent(json_encode($param));
        $res->headers->set('Content-Type', 'application/json');
        return $res;
    }

    public function publishVideoUpdateAction( Request $request )
    {
        $submit_post_get = array_merge($request->query->all(),$request->request->all());

        $year = intval( $submit_post_get['year'] );
        $week = intval( $submit_post_get['week'] );
        $name = ($submit_post_get['name'])? $submit_post_get['name']:'';
        $src = ($submit_post_get['src'])? $submit_post_get['src']:'dev';

        $logger = $this->get('logger');
        $logger->info("\nPublish Video Params:", $submit_post_get);
        $response = new JsonResponse();

        if( $src != 'dev' )
        {
            $name_array = explode('_', $name);
            $id_ext = $name_array[count($name_array)-1];

            $id_ext_array = explode('.', $id_ext);
            $id = intval($id_ext_array[0]);

            if( $id == 0 )
            {
                $response->setData(array(
                    'status' => '202',
                    'message' => 'Failed'
                ));
                $logger->info("\nPublish Video Params:", array('status'=>"Invalid video id") );

                return $response;
            }

            if( $year == 0 || $week == 0 )
            {
                $response->setData(array(
                    'status' => '202',
                    'message' => 'Failed'
                ));
                $logger->info("\nPublish Video Params:", array('status'=>"Invalid video year or week") );

                return $response;
            }

            $videoPath = $this->container->getParameter('CONFIG_VIDEO_UPLOADPATH');
            $relativepath = $year.'/'.$week.'/';
            $fullpath = $videoPath.$relativepath;
            if( !$this->get('PhotosVideosServices')->setMediaPublished( $id, 1, $relativepath, $fullpath ) )
            {
                $response->setData(array(
                    'status' => '202',
                    'message' => 'Failed'
                ));
                $logger->info("\nPublish Video Params:", array('status'=>"Couldn't save information.") );

                return $response;
            }
        }

        $response->setData(array(
            'status' => '200',
            'message' => 'Success'
        ));

        return $response;
    }

}