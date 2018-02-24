#!/bin/bash
BASE_PATH=$(cd `dirname $0`; pwd)

cd $BASE_PATH
command=`which docker-compose`

# 启动
sudo $command -f docker-compose.yaml stop
sudo $command -f docker-compose.yaml up -d --build

