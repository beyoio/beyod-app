<?php

use yii\helpers\Markdown;

$config = [
    'id' => 'beyod',
    'basePath' => dirname(__DIR__).'/app',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'timeZone' => 'PRC',
    'enableCoreCommands' => false,
    
    'controllerMap' => [
        'server' => [
            'class' => 'beyod\ServerController',
         ]
    ],
    
    'components' => [
        'server' => [
            'class' => 'app\MyServer',
            'pid_file' => '@runtime/beyod.pid',
            'server_id' => 1,
            'worker_num' => 4, //工作进程数 建议为CPU个数的1~4倍
            'daemonize' => true, //以后台方服务方式运行
            'user' => 'nobody', //工作进程的用户账号
            'group' => 'nobody',//同上，工作进程的用户组
            'rlimit_nofile' => 65535,//设置系统nofile参数，建议此值为64K以上，以胜任高并发连接环境
            'memory_limit' => '512MB',//重设工作进程的内存上限，默认128M无法容纳大量连接
            //配置网络服务
            'listeners' => [
                'https' => [
                    'class' => 'beyod\Listener',
                    'listen' => 'tcp://0.0.0.0:9723',//监听的协议和端口
                    'handler' => [//网络事件回调处理器
                        'class' => 'beyod\protocol\http\Handler',
                        'document_root' => __DIR__.'/../app/webroot',
                        'callback' =>[
                            '#\.md$#' => function($event, $req, $res, $path){
                            $res->body = Markdown::process(file_get_contents($path), 'gfm-comment');
                            return true;
                            },
                         ]
                    ],
                    'parser'  => [
                        'class'=>'beyod\protocol\http\Parser',
                        'max_packet_size' => 1048576*4, //限制单个请求最大字节为4MB, 默认为8M,
                        'max_header_size' => 2048, //限制http请求header上限为2KB, 默认4KB
                        
                    ],
                    // ssl 参数，必须配置listen前缀为ssl/tls
                    /*'ssl' => [
                        'local_cert' => __DIR__.'/server.pem', //证书文件路径
                        'passphrase' => 'beyodpass',//证书密码
                        'allow_self_signed'=>true, //允许自签名证书
                        'verify_peer' =>false //不验证客户端的证书
                    ]*/
                ],
                
                'websocket' => [
                    'listen' => 'tcp://0.0.0.0:9724',
                    'handler' => [
                        'class' => 'app\ChatHandler',
                    ],
                    
                    'parser' => 'beyod\protocol\websocket\Parser',
                ],
                
                'text' => [
                    'class' => 'beyod\Listener',
                    'listen' => 'tcp://0.0.0.0:9725',
                    'parser' =>  'beyod\protocol\text\Parser',
                    'handler' => 'beyod\protocol\text\Handler'
                ],
                
                //订阅服务器
                'dispatch' => [
                    'class' => 'beyod\Listener',
                    'listen' => 'tcp://0.0.0.0:9726',
                    'parser' => 'beyod\dispatcher\Parser',
                    'handler' => 'beyod\dispatcher\Handler'
                ],
            ],
        ],
        
        //libevent组件配置
        'eventLooper' => [
            'class' => 'beyod\event\Event',
        ],
        
        'errorHandler' => [
            'class'=>'beyod\helpers\ErrorHandler',
        ],
        
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=test;charset=utf8',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'commandClass' => 'beyod\db\Command',
        ],
        
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'log' => [
            'traceLevel' => 3,
            'FlushInterval' => 1,
            'targets' => [
                'default' => [
                    'class' => 'beyod\helpers\FileTarget',
                    'logFile' => '@runtime/logs/beyod.log',
                    'levels' => ['error', 'warning', 'info'],
                ],
                
                'debug' => [
                    'class' => 'beyod\helpers\FileTarget',
                    'enabled' => APP_ENV === 'dev',
                    'logFile' => '@runtime/logs/debug.log',
                    'levels' => ['error', 'warning', 'info', 'trace'],
                ],
                
                //console debug mode
                'console' => [
                    'class' => 'beyod\helpers\ConsoleTarget',
                    'logVars' => [],
                    'enabled' => false,
                    'exportInterval' =>1,
                    'levels' => ['error', 'warning', 'info','trace'],
                ],
            ],
        ],
        
    ],
    'params' => [
        
    ],
];

return \yii\helpers\ArrayHelper::merge(
    $config, 
    require __DIR__.'/'.YII_ENV.'/'.basename(__FILE__)
);

