#!/usr/bin/env Python
import pika
import sys

message = ' '.join(sys.argv[1:]) or "Hello World!"

connection = pika.BlockingConnection(
    pika.ConnectionParameters(host='localhost')
)

channel = connection.channel()


channel.queue_declare(queue='TestReadWriteQueue')

##remove this line if there are issues
channel.exchange_declare(exchange='testExchange', exchange_type='direct')

channel.basic_publish(exchange='testExchange', routing_key='sendToRWQueue', body=message)

print(" [x] Sent %r" % message)

channel.close()