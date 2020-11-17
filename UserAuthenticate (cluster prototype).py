#!/usr/bin/env python
import pika, os, sys
import time
import mysql.connector
import json
import random

def callback (ch, method, propertues, body):
    info = ("%r" % body)
    info = info.strip("'")
    info = json.loads(info)
    print (info)
    if "email" in info:
        add_users(info)
    else:
        authenticate(info)

def add_users (instruction):
    if "@gmail.com" in instruction["email"] or "@yahoo.com" in instruction["email"] or "@aol.com" in instruction["email"]:
        query = ("INSERT INTO Users (username, email, password) VALUES (%s, %s, %s)")
        mycursor.execute(query, (instruction["username"], instruction["email"], instruction["password"],))
        mydb.commit()
        randInt = random.randint(1,3)
        if randInt == 1:
            channel2.basic_publish(exchange='Login', routing_key='dbTL', body='user added')
        elif randInt == 2:
            channel2.basic_publish(exchange='Login', routing_key='dbTL2', body='user added')
        elif randInt == 3:
            channel2.basic_publish(exchange='Login', routing_key='dbTL3', body='user added')
        #channel2.basic_publish(exchange='Login', routing_key='btf', body='user added')
    else:
        channel2.basic_publish(exchange='Login', routing_key='dbTL', body='Invaild email, password, or username')

def authenticate (instruction):
    query = ("SELECT * FROM Users WHERE username =%s AND password =%s")
    mycursor.execute(query, (instruction["username"], instruction["password"],))
    result = mycursor.fetchall()
    if mycursor.rowcount == 1:
        if result[0][0] % 4 == 0:
            channel2.basic_publish(exchange='Login', routing_key='dbTL', body='Correct username and password')
        elif result[0][0] % 3 == 0:
            channel2.basic_publish(exchange='Login', routing_key='dbTL2', body='Correct username and password')
        elif result[0][0] % 2 == 0:
            channel2.basic_publish(exchange='Login', routing_key='dbTL3', body='Correct username and password')
        else:
            channel2.basic_publish(exchange='Login', routing_key='dbTL', body='Correct username and password')
    else:
        channel2.basic_publish(exchange='Login', routing_key='dbTL', body='Username and password are incorrect')

def main():
    global mydb
    global mycursor
    mydb = mysql.connector.connect(user="user1", password="Password1234", host = "localhost", database = "StockGame")
    mycursor = mydb.cursor()

    cred = pika.PlainCredentials('admin','admin')
    connection = pika.BlockingConnection(pika.ConnectionParameters(host = '192.168.192.185', credentials = cred))

    global channel
    global channel2
    channel = connection.channel()
    channel2 = connection.channel()

    #channel.queue_declare(queue='hello')
    #channel2.queue_declare(queue='dbToLogin')

    channel.basic_consume(queue='loginToDB', on_message_callback=callback, auto_ack=True)
    #channel.basic_consume(queue='hello', on_message_callback=callback, auto_ack=True)
    channel.start_consuming()


if __name__ == '__main__':
    try:
        main()
    except KeyboardInterrupt:
        print('Stop')
        try:
            sys.exit(0)
        except SystemExit:
            os._exit(0)

