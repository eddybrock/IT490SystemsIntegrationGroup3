#!/bin/bash
systemctl start rabbitmq-server mysql
systemctl status rabbitmq-server mysql
python3 /home/eddy/Front_End/manage.py runserver 192.168.1.9:5000
