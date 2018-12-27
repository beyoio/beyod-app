<!doctype html>
<html lang="zh" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <title><?=$title?></title>
  </head>
  <body class="h-100">
  <div class="container-fluid h-100 p-2">
  	<?=$content?>
  </div>
  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/js/popper.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>

<script>
$(function(){
  var url = self.location;
  parent.$('a').removeClass('font-weight-bold');
  parent.$('a[href="<?=$page?>.html"]').addClass('font-weight-bold');
});
</script>  
  
  </body>
</html>