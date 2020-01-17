#!/bin/bash
#############################################################################
# 使用帮助
if [ "-h" = "$1" ] || [ "--help" = "$1" ] || [ -z $1 ]
then
    echo
    echo "介绍: 任务管理 启动|停止|重启|状态"
    echo "用法: sh service.sh [start|stop|restart|status]"
    exit
fi

DAEMON_FILE="/data1/htdocs/openapi/app/jobs/Daemon.php"

function getpid() {
    local keywords=$1
    ps aux|grep $keywords|grep -v grep | awk '{print $2}'
}

function start() {
    nohup php $DAEMON_FILE >> /tmp/nohup.openapi.Daemon.log 2>&1 &
}

function stop() {
    while kill $(getpid $DAEMON_FILE) >/dev/null 2>&1; do
        sleep 0.5
    done
    return 0
}

if [ "start" = "$1" ]
then
    #启动daemon
    start && echo "succ"
fi

if [ "stop" = "$1" ]
then
    #回收daemon
    stop && echo "succ"
fi

if [ "reload" = "$1" ]
then
    #重启daemon
    stop && start && echo "succ"
fi

#返回running or stopped
if [ "status" = "$1" ]
then
    [[ $(getpid $DAEMON_FILE) != "" ]] && echo "running" || echo "stopped"
fi