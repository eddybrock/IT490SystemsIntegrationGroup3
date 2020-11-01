#!/usr/bin/env python
import pika, sys, os

#connect to the RabbitMQ Server
connection = pika.BlockingConnection(pika.ConenctionParameters(host='localhost'))
channel = connection.channel()

#declare the queue in case it doesnt already exist
channel.queue_declare(queue='Backend')

def callbackfunc(ch, method, properties, text):
    print(" [X] Received message from sender: %r" % text)
    
channel.basic_consumer(callbackfunc,
                        queue='Backend',
                        no_ack=True)
                            
print(' [*] Waiting to get messages from sender. To exit press CTRL+C')
channel.start_consuming()
