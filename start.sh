#!/bin/sh

cd websdk && nohup node server.js > /var/log/node_server.log 2>&1 &

sleep 5  # 等待 10 秒，确保 node 服务器启动

php webman test:test start