<?php

namespace ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\DependencyInjection\ContainerInterface;
//use TTBundle\Entity\Webgeocities;
//use TTBundle\Entity\CmsCountries;
//use TTBundle\Entity\DiscoverHotels;
//use Symfony\Component\Validator\Mapping\ClassMetadata;
//use Symfony\Component\Validator\Constraints\NotBlank;
//use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\HttpFoundation\JsonResponse;
//require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class DiscoverController extends DefaultController {

    public function __construct() {
        
    }

    public function rabbitMqSendAction() {
        $connection = new AMQPStreamConnection('localhost', 80, '', '');
        $channel = $connection->channel();


        $channel->queue_declare('hello', false, false, false, false);

        $msg = new AMQPMessage('Hello World!');
        $channel->basic_publish($msg, '', 'hello');

        echo " [x] Sent 'Hello World!'\n";

        $channel->close();
        $connection->close();
        $ret = "Iam Done with this Rabbit Mq Sending things :P";
        $res = new Response(json_encode($ret));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

    public function rabbitMqReceiveAction() {
        
        $connection = new AMQPStreamConnection('tt.com', 80, '', '');
        $channel = $connection->channel();
        $channel->queue_declare('hello', false, false, false, false);
        echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
        $callback = function($msg) {
            echo " [x] Received ", $msg->body, "\n";
        };
        $channel->basic_consume('hello', '', false, true, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
        $ret = "Iam Done with this Rabbit Mq Sending things :P";
        $res = new Response(json_encode($ret));
        $res->headers->set('Content-Type', 'application/json');

        return $res;
    }

}