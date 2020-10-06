#!/bin/bash
systemctl start rabbitmq-server mysql
systemctl status rabbitmq-server mysql
bash ./startFE.sh
