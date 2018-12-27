<?php
ob_start();
$files = glob("*.md");
sort($files, SORT_NATURAL );
array_unshift($files, $files[27]);
array_pop($files);
?>
<!doctype html>
<html lang="zh" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1"/>
    <meta name="renderer" content="webkit"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <meta name="description" content="beyod 高性能分布式、事件驱动、异步非阻塞php socket网络应用框架"/>
    <meta name="keywords" content="php socket libevent event-driven"/>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>beyod 参考文档</title>
  </head>
  <body class="h-100">
  <div class="container-fluid h-100">
  	<div class="row h-100">
    	<div class="col-3 h-100" style="overflow:auto;">
        <h4>beyod 开发文档</h4>
        <ul class="h-100 ml-0 pl-2">
        	<li>离线文档下载</li>
        	<li><a href="beyod-docs-v1.zip">html格式</a></li>
        	<li><a href="beyod-docs-v1.chm">chm格式</a></li>
        	<?php foreach($files as $i => $f){
            
            $f = preg_replace("#^[\d\-\.]+#", "", $f);
            $f = preg_replace("#\.md+#", "", $f);
            $page = $i+1;
            ?>
        	<li><a class="text-dark" target="main" href="<?=$page?>.html"><?=mb_convert_encoding(preg_replace("#^[\d\-\.]+#", "", $f), 'utf-8', 'gbk')?></a></li>
            <?php }?>
            
            
        </ul>
        
        </div>
    	<div class="col h-100 p-0">
        <iframe src="1.html" frameborder="0" id="main" name="main"></iframe>
        </div>
  	</div>
  </div>
  
    

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
  </body>
</html>
<?php
$s = ob_get_clean();
file_put_contents('./index.html', $s);

require 'Parser.php';

$parser = new Parser();

foreach($files as $i => $f){
	ob_start();
	ob_flush();
	$f2 = preg_replace("#^[\d\-\.]+#", "", $f);
  $page = $i+1;
    
    $title = mb_convert_encoding(preg_replace("#^[\d\-\.]+#", "", $f), 'utf-8','gbk');
    $title = basename($title);
    
    $content = file_get_contents($f);
    $content = $parser->makeHtml($content);
    include 'page.tpl';
    $s = ob_get_clean();
    
    file_put_contents($page.'.html', $s);
}

include 'index.html';

$zip = new ZipArchive();
$res = $zip->open('beyod-docs-v1.zip', ZIPARCHIVE::OVERWRITE|ZIPARCHIVE::CREATE);
//$zip->addGlob('*.html');
$options = ['add_path' => 'beyod-docs-v1/', 'remove_all_path'=>true] ;
$zip->addGlob('*.html',0, $options);

$options = ['add_path' => 'beyod-docs-v1/assets/css/', 'remove_all_path'=>true] ;
$zip->addGlob('assets/css/*', 0, $options);

$options = ['add_path' => 'beyod-docs-v1/assets/js/', 'remove_all_path'=>true] ;
$zip->addGlob('assets/js/*', 0, $options);

$options = ['add_path' => 'beyod-docs-v1/assets/images/', 'remove_all_path'=>true] ;
$zip->addGlob('assets/images/*', 0, $options);
$zip->close();
