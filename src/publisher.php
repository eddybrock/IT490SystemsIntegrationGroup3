<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/src/myfunctions.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

$host = '192.168.192.185';

$port = '5672';

$pass = 'admin';

$user = 'admin';

$vhost = '/';

$exchange = 'Login'; //set exchange

$dbNum = rand(1, 3);

$queue = '';
$binding_key = '';

switch ($dbNum) {
    case 1:
        $queue = 'loginToDB'; //set queue
        $binding_key = 'lTDB';
        break;
    case 2:
        $queue = 'loginToDB2'; //set queue
        $binding_key = 'lTDB2';
        break;
    case 3:
        $queue = 'loginToDB3'; //set queue
        $binding_key = 'lTDB3';
        break;
    default:
        $queue = 'loginToDB'; //set queue
        $binding_key = 'lTDB';
        break;

}

//$_SESSION["dbNum"] = $dbNum;

$connection = new AMQPStreamConnection($host, $port, $user, $pass, $vhost);
$channel = $connection->channel();

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
$channel->queue_declare($queue, false, true, false, false);

/*
    name: $exchange
    type: direct
    passive: false
    durable: true // the exchange will survive server restarts
    auto_delete: false //the exchange won't be deleted once the channel is closed.
*/

$channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

$channel->queue_bind($queue, $exchange);

$flag = false;
$username = GET ("username" ,$flag);
$email = GET ("email", $flag);
$password = GET ("password", $flag);
//Checks if amount is Negative and exit if is.
if ($flag) {exit(header ("<br>Failed: empty input field."));};	

$messageBody = json_encode([

    "email" => "$email",

    "username" =>"$username",

    "password"=>"$password",

]);

$message = new AMQPMessage($messageBody, ['content_type' => 'application/json', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
$channel->basic_publish($message, $exchange, $binding_key);

$channel->close();
$connection->close();

//header("Location: ../src/index.html");
header("Location: ../src/waitForReg.php");