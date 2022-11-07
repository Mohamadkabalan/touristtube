<?php
include_once ( '../vendor/autoload.php' );
//echo 'starting';
ini_set('display_errors', 1);
//include("heart.php");
use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange = 'router';
$queue = 'msgs';
$consumerTag = 'consumer';

$connection = new AMQPStreamConnection('localhost', 5672, 'tt', 'touristtube');
$channel = $connection->channel();

$channel->queue_declare($queue, false, true, false, false);

$channel->exchange_declare($exchange, 'direct', false, true, false);
$channel->queue_bind($queue, $exchange);

function process_message($message)
{
    echo "\n--------\n";
    echo $message->body;
    echo "\n--------\n";
    $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
    // Send a message with the string "quit" to cancel the consumer.
    if ($message->body === 'quit') {
        $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
    }
    if ($message->body == 'stop'){
        $message->delivery_info['channel']->basic_cancel($message->delivery_info['consumer_tag']);
    }
    sleep(5);
}

$channel->basic_consume($queue, $consumerTag, false, false, false, false, 'process_message');

function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}
register_shutdown_function('shutdown', $channel, $connection);
//while(true){
//    
//}
//sleep(5);
//print_r(count($channel->callbacks));

while (count($channel->callbacks)) {
    $channel->wait();
}


exit;

$channel->queue_declare('hello', false, false, false, false);

//echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
};
print_r($channel);
//$channel->basic_consume('hello', '', false, true, false, false, $callback);

//echo count($channel->callbacks);

//while(count($channel->callbacks)) {
//    $channel->wait();
//}