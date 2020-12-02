<?php
session_start();
require dirname(__DIR__) . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;

$login= false;

$host = '192.168.192.185';

$port = '5672';

$pass = 'admin';

$user = 'admin';

$vhost = '/';

$exchange = 'Login'; //set exchange

$queue = array('dbToLogin', 'dbToLogin2', 'dbToLogin3');

$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
$channel = $connection->channel();

$connection2 = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
$channel2 = $connection2->channel();

$connection3 = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
$channel3 = $connection3->channel();

/*
    The following code is the same both in the consumer and the producer.
    In this way we are sure we always have a queue to consume from and an
        exchange where to publish messages.
*/

/*
    name: $queue
    passive: false
    durable: true // the queue will survive server restarts
    exclusive: false // the queue can be accessed in other channels
    auto_delete: false //the queue won't be deleted once the channel is closed.
*/
$channel->queue_declare($queue[0], false, true, false, false);

$channel2->queue_declare($queue[1], false, true, false, false);

$channel3->queue_declare($queue[2], false, true, false, false);

/*
    name: $exchange
    type: direct
    passive: false
    durable: true // the exchange will survive server restarts
    auto_delete: false //the exchange won't be deleted once the channel is closed.
*/

$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel->queue_bind($queue[0], $exchange);

$channel2->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel2->queue_bind($queue[1], $exchange);

$channel3->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel3->queue_bind($queue[2], $exchange);

/**
 * @param \PhpAmqpLib\Message\AMQPMessage $message
 */
function process_message($message)
{

    $message->ack();

    // Send a message with the string "quit" to cancel the consumer.
    if ($message->body === 'Correct username and password') {
        $login = true;
        $message->getChannel()->basic_cancel($message->getConsumerTag());
    }
}
/*
    queue: Queue from where to get the messages
    consumer_tag: Consumer identifier
    no_local: Don't receive messages published by this consumer.
    no_ack: If set to true, automatic acknowledgement mode will be used by this consumer. See https://www.rabbitmq.com/confirms.html for details.
    exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
    nowait:
    callback: A PHP Callback
*/


$channel->basic_consume($queue[0], $consumerTag, false, false, false, false, 'process_message');

$channel2->basic_consume($queue[1], $consumerTag, false, false, false, false, 'process_message');

$channel3->basic_consume($queue[2], $consumerTag, false, false, false, false, 'process_message');

/**
 * @param \PhpAmqpLib\Channel\AMQPChannel $channel
 * @param \PhpAmqpLib\Connection\AbstractConnection $connection
 */
function shutdown($channel, $connection)
{
    $channel->close();
    $connection->close();
}

register_shutdown_function('shutdown', $channel, $connection);

register_shutdown_function('shutdown', $channel2, $connection2);

register_shutdown_function('shutdown', $channel3, $connection3);

// Loop as long as the channel has callbacks registered

while ($channel ->is_consuming()) {
    $channel->wait();
}
while ($channel2 ->is_consuming()) {
    $channel2->wait();
} 
while ($channel3 ->is_consuming()) {
    $channel3->wait();
}

if($login)
{
    echo 'Login.';
    //header("Location: ../src/index.php");
}
else
{
    $_SESSION['username'] = '';
    echo 'Could not add user, username is already in use.';
    //header("Location: ../src/pages/login.html");
}
