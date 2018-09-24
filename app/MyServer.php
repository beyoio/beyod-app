<?php

namespace app;

use Yii;
use beyod\Server;
use beyod\dispatcher\Client;
use beyod\MessageEvent;
use beyod\IOEvent;
use beyod\protocol\redis\Request;
use yii\db\Query;

class MyServer extends Server
{
    
    /**
     * @var \beyod\dispatcher\Client Subscription client config.
     */
    public $push_client = [
        'target' => 'tcp://127.0.0.1:3306', //push server address
        'reconnect_interval' => 1,
        'timeout' => 10,
    ];
    
    public $redis_server = 'tcp://192.168.1.19:6379';
    
    public $dispatch_server = 'tcp://127.0.0.1:7734';
    
    
    public $redisClient;
    
    public $client;
    
    
    public function onWorkerStart($workerId, $GPID)
    {
        parent::onWorkerStart($workerId, $GPID);
        //$this->connectDispatcher();
        //$this->connectRedisServer();
        
        /**
        Yii::$app->eventLooper->addInterval(5000, function(){
            echo time(), " ";
        });
        **/
    }
    
    protected function connectRedisServer()
    {
        $options = [
            'target' => $this->redis_server,
            'reconnect_interval' => 5, //连接异常断开, 5秒后重连
            'timeout' => 10, //连接超时10秒
        ];
        
        $this->redisClient = new \beyod\protocol\redis\Client($options);
        
        //收到数据时的回调
        $this->redisClient->on(Server::ON_MESSAGE, function($event){
            /** 
             * @var MessageEvent $event
             * @var Request $event->message  
             * */
            print_r($event->message);
            
        });
        
        //连接成功之后就可以发送数据了
        $this->redisClient->on(Server::ON_CONNECT, function(IOEvent $event){
            Yii::debug($event->sender.' connect ok ');
            
            $this->redisClient->set('a',serialize($_SERVER));
            $this->redisClient->get();
        });
        
        //尝试连接
        $this->redisClient->connect();
    }
    
    /**
     * 连接订阅服务器
     */
    protected function connectDispatcher()
    {
        $options = [
            'target' => 'tcp://192.168.0.2491',
            'reconnect_interval' => 6,
        ];
        
        $this->client = new Client($options);
        $this->client->on(Server::ON_CONNECT, function($event) {
            $this->client->subscribe([$this->getGpid()]);
        });
        
        $this->client->on(Server::ON_MESSAGE, function(MessageEvent $event){
            print_r($event->message);
        });
        
        $this->client->connect();
    }
}