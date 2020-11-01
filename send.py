#!/usr/bin/env python
import pika

connection = pika.BlockingConnection(pika.ConenctionParameters('lcoalhost'))
channel = connection.channel()

channel.queue_declare(queue='Backend')

channel.basic_publish(exchange=' ',
                      routing_key='Backend',
                      body='RabbitMq is cool!')
print(" [X] Sent'RabbitMQ is Cool'")

connection.close()
