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
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
<script src="js/jquery.min.js"></script>
<title>上传测试</title>
</head>

<body>
<div class="container">
<br>
<form method="post" enctype="multipart/form-data">
<input name="user[name]" value="张三" />
<input name="user[age]" value="17" />
<input type="file" name="p[1]" />
<input type="file" name="p[im]" /><br />
<input type="file" name="de" /><br />
<input type="submit" />

</form>
<pre>
<?php
print_r($_POST);
print_r($_GET);
print_r($_FILES);
?>
</pre>

</div>
</body>
</html>
