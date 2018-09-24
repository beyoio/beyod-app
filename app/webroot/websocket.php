<?php 
/** @var \beyod\protocol\http\Response $response */
/** @var \beyod\protocol\http\Request $request */
/** @var \beyod\protocol\http\Request $request */
/** @var array $_GET */
/** @var array $_POST */
/** @var array $_FILES */
/** @var array $_SERVER */
/** @var array $_COOKIE */

//启用gzip压缩输出(如果客户端支持)
$response->gzip = true;
?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<title>beyod websocket demo </title>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<script src="js/jquery.min.js"></script>
</head>

<body>
<div class="container">
<h3>websocket交互演示</h3>
<p>开启多个浏览器窗口，输入昵称和内容实时交互</p>
<form class="form">
<div class="form-group">
<textarea class="form-control" id="messages" rows="3" readonly></textarea>
</div>

<div id="userlist">
	
</div>

<div class="form-group input-group">
	<div class="input-group-prepend">
    	<input type="text" required id="nickname" name="nickname" placeholder="昵称" class="form-control">
    </div>
	<input type="text" required name="text" placeholder="内容" class="form-control">
    <div class="input-group-append">
    <button disabled id="send" type="submit">send</button>
    </div>
</div>
<input type="hidden" name="targets" id="targets">
</form>
<div id="status"></div>
<div id="info"></div>

</div>

<script>

$('form').submit(function(){
	var req = $(this).serialize();
	sendMessage(req);
	return false;
});



function sendMessage(content){
	
	if(window.socket.readyState == 1){
		window.socket.send( content );
	}
}

function onOpen(evt){
	$('#send').removeAttr('disabled');
	$('#status').html('连接已建立');
}


function onMessage(evt){
	$('#debug').html(evt.data);
	$('#info').html('收到消息 '+evt.data);
	var res = $.parseJSON(evt.data);
	
	switch(res['type']){
		case 'userlist':
			
			break;
		case 'message':
			var text = res['data']['nickname']+"\t"+res['data']['text']+"\n";
			$('#messages').append(text);
			break;
	}
}


function onClose(evt){
	console.log(evt);
	$('#send').attr('disabled',true);
	$('#status').html('连接断开 3秒后重连');
	var t = 3;
	
	var tid = setInterval(function(){
		t--;
		$('#status').html('连接断开 '+t+'秒后重连');
		if(t<=0) {
			clearInterval(tid);
			openSocket();
		}
	}, 1000);
	
}


function onError(evt){
	$('#status').html('发生错误'+evt);
}



$(function(){
	if(!window.WebSocket) return alert("浏览器不支持websocket");
	
	if (window.MozWebSocket) {
		window.WebSocket = window.MozWebSocket;
	}
		
	openSocket();
});


function openSocket(){
	$('#status').html('连接中');
	var target = 'ws://'+window.location.hostname+':9724';
	if(!window.socket || window.socket.readyState === undefined || window.socket.readyState > 1){
		window.socket = new WebSocket(target);
		socket.onopen = onOpen;
		socket.onmessage = onMessage;
		socket.onclose = onClose;
		socket.onerror = onError;
	}
}




</script>


</body>
</html>
