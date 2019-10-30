<?php

namespace GaeFlow;

class Cmds {

    static function killPort($port, $method = null) {
        $grep = $method === 'udp' ? 'UDP' : 'LISTEN';
        $i = $method === 'udp' ? 'udp' : 'tcp';
        $cmd = "lsof -i $i:$port | grep $grep | awk '{print $2}' | xargs kill -9";
        return exec($cmd);
    }

    static function buildIn($host, $port, $routerPath, $docroot = null) {
        $rootCmd = is_null($docroot) ? "" : "-t $docroot";
        $phpStartCommand = "php -S $host:$port $rootCmd $routerPath";
        $result = exec($phpStartCommand);
        return $result;
    }

}