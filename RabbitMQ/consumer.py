#!/usr/bin/env python
import pika
import time
import sys

connection = pika.BlockingConnection(
    pika.ConnectionParameters(host='localhost')
)

channel = connection.channel()
#

original_stdout = sys.stdout

with open('consuming.txt', 'w') as f:
    sys.stdout=f
    def callback (ch, method,properties,body):
        print("[x] Received %r" % body.decode())
        time.sleep(body.count(b'.'))
        print(" [x] Done")

channel.basic_consume(
    queue='TestReadWriteQueue', on_message_callback=callback, auto_ack=True
)

channel.start_consuming()
