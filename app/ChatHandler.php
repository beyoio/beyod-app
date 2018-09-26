<?php

namespace app;

use beyod\protocol\websocket\Response;
use beyod\CloseEvent;
use beyod\MessageEvent;

class ChatHandler extends \beyod\protocol\websocket\Handler 
{
    public $messageid=0;
    /**
     * 客户端握手成功后的回调, 向所有客户端发送新的列表
     * {@inheritDoc}
     * @see \beyod\protocol\websocket\Handler::onHandshaked()
     */
    public function onHandshaked($event)
    {
        $this->sendUserList($event);
    }
    
    /**
     * 
     * @param MessageEvent $event
     */
    protected function sendUserList($event){
        $users = [];
        
        
        foreach($event->sender->listener->connections as $id => $client){
            if($client->isClosed()) continue;
            $users[] = [
                'id' => $client->getId(),
                'nickname' => $client->getAttribute('nickname'),
            ];
        }
        
        $data = ['messageid'=>$this->messageid, 'type'=>'userlist', 'data'=>$users];
        
        $resp = new Response(json_encode($data, 320));
        
        foreach($event->sender->listener->connections as $client){
            if($client->isClosed()) continue;
            $client->send($resp);
        }
    }
    
    public function onClose(CloseEvent $event)
    {
        $this->sendUserList($event);
    }
    
    /**
     * 当收到消息时, 解析并广播形式发送
     * @param MessageEvent $event
     * 
     * {@inheritDoc}
     * @see \beyod\protocol\websocket\Handler::processData()
     */
    public function processData($event)
    {
        static $messageid = 0;
        
        $messageid++;
        
        parse_str($event->message->body, $req);
        
        $req['id'] = $event->sender->id;
        
        $req['time'] = date('Y-m-d H:i:s');
        
        $data = ['messageid' => $this->messageid, 'type'=>'message', 'data'=>$req];
        if(isset($req['nickname']) && $req['nickname']){
            $event->sender->setAttribute('nickname', $req['nickname']);
            $this->sendUserList($event);
        }
        
        $resp = new Response(json_encode($data, 320));
        $this->messageid++;
        
        foreach($event->sender->listener->connections as $client){
            if($client->isClosed()) continue;
            //if($client->id == $event->sender->id) continue; //不向自己发送
            $client->send($resp);
        }
    }
}