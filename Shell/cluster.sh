#!/bin/bash
# redis集群启动、停止、重启脚本
# 所有节点启动之后然后进行创建集群 ps: redis-cli --cluster create 127.0.0.1:6382 127.0.0.1:6383 127.0.0.1:6384 127.0.0.1:6385 127.0.0.1:6386 127.0.0.1:6387 --cluster-replicas 1

if [ "$1" == "-h" ] || [ "$1" == "--help" ] || [ -z $1 ];
then
    echo
    echo "介绍: redis集群 启动|停止|重启|状态";
    echo "用法: sh cluster.sh [start|stop|reload|status]";
    exit;
fi;

#redis根目录
redis_path="/usr/local/redis/";

#redis端口节点数组
redis_port_arr=(
    "6382"
    "6383"
    "6384"
    "6385"
    "6386"
    "6387"
);

#创建集群配置文件目录
if [ ! -d $redis_path"cluster" ]; then
  mkdir $redis_path"cluster";
fi

#创建集群配置文件，并且修改各文件相关配置
for port in ${redis_port_arr[@]};
    do
      if [ ! -f $redis_path"cluster/redis_${port}.conf" ]; then
          sed -e "98 s/6379/${port}/" -e "1393 s/#//" -e "1393 s/6379/${port}/" $redis_path"redis.conf" >> $redis_path"cluster/redis_${port}.conf";
      fi
done

#停止所有redis
function stop() {
    ps aux | grep redis | grep -v grep | awk -F " " '{print $2}' | xargs kill -9 >/dev/null 2>&1;
}

#启动redis节点及集群配置
function start() {
    stop;

    #启动节点
    for redis_conf in `ls $redis_path"cluster"`;
    	  do
        /usr/local/redis/redis-server $redis_path"cluster/"$redis_conf >/dev/null 2>&1;
    done

    for port in ${redis_port_arr[@]}; do
        hosts=$hosts" 127.0.0.1:"$port" "
    done

    #配置集群
    $redis_path"redis-cli" " --cluster create "$hosts" --cluster-replicas 1" >/dev/null 2>&1;
}

#redis重启
function reload() {
    start;
}

#redis状态查询
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