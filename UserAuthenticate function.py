#!/usr/bin/env python
import pika, os, sys
import mysql.connector
import json

def main():
#connect to the mysql database 
    mydb = mysql.connector.connect(user="user1", password="Password1234", host = "localhost", database="StockGame")
    mycursor = mydb.cursor()

#set up a connection to rabbitMQ
#channel: is used to recieve messages from a chosen queue and listen to the queue called "hello"
#channel2: is used to talk to a chosen queue and talks to the queue called "confrim"
	connection = pika.BlockingConnection(pika.ConnectionParameters(host='localhost'))
    channel = connection.channel()
    channel2 = connection.channel()

#these lines tell rabbitMQ to create the queues and can be deleted if the queues already exist
	channel.queue_declare(queue='hello')
    channel2.queue_declare(queue='confrim')
	
	
	
	
	

#the function callback is called when a message is recieve from "the queue" 
#the function reads the message (which should be the username and password in JSON) and then is sent to the database to authenticate
#if the username and password match a record in the database mycursor.rowcount will hold the value 1 and send "True" to rabbitMQ 
#if there is no match or somehow there more then one record chosen callback will send "False" to rabbitMQ
    def callback(ch, method, properties, body):
        info = ("%r" % body)
        info = info.strip("'")
        info = json.loads(info)
        query = ("SELECT * FROM Users WHERE username =%s AND password =%s")
        mycursor.execute(query, (info["username"], info["password"],))
        result = mycursor.fetchall()
        if mycursor.rowcount == 1:
            channel2.basic_publish(exchange='', routing_key='confrim', body='True')
            connection.close()
        else:
            channel2.basic_publish(exchange='', routing_key='confrim', body='False')
            connection.close()
    
#these two lines start the consume process on the queue "hello"    
    channel.basic_consume(queue='hello', on_message_callback=callback, auto_ack=True)
	channel.start_consuming()

#keeps the script from closing out which allows for multiple messages to come thru
if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print('Stop')
        try:
            sys.exit(0)
        except SystemExit:
            os._exit(0)
