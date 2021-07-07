#!/bin/bash
# redis集群启动、停止、重启脚本

if [ "$1" == "-h" ] || [ "$1" == "--help" ] || [ -z $1 ];
then
    echo
    echo "介绍: redis集群 启动|停止|重启|状态";
    echo "用法: sh cluster.sh [start|stop|reload|status]";
    exit;
fi;

path_arr=(
  "/usr/local/redis/cluster/6382/redis.conf"
  "/usr/local/redis/cluster/6383/redis.conf"
  "/usr/local/redis/cluster/6384/redis.conf"
  "/usr/local/redis/cluster/6385/redis.conf"
  "/usr/local/redis/cluster/6386/redis.conf"
  "/usr/local/redis/cluster/6387/redis.conf"
);

function start() {
    stop;

    for path in ${path_arr[@]};
    	  do
        /usr/local/redis/cluster/redis-server ${path} >/dev/null 2>&1;
    done
}

function stop() {
    ps aux | grep redis | grep -v grep | awk -F " " '{print $2}' | xargs kill -9 >/dev/null 2>&1;
}

function reload() {
    start;
}

function status() {
    ps aux | grep redis | grep -v grep;
}

if [ "$1" == "start" ]
then
    start && echo "success";
fi;

if [ "$1" == "stop" ]
then
    start && echo "success";
fi;

if [ "$1" = "reload" ]
then
    start && echo "success";
fi;

if [ "$1" = "status" ]
then
    status;
fi;