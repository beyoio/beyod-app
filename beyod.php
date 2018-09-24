<?php
/**
 * @var \beyod\event\Event Yii::$app->eventLooper
 */
defined('APP_ENV') or define('APP_ENV', isset($_SERVER['APP_ENV']) ? $_SERVER['APP_ENV'] : 'dev');

(php_sapi_name() !='cli') && exit("Can only run in command line.");

(version_compare(phpversion(), '5.6') < 0) && exit("require php 5.6+");

defined('YII_DEBUG') or define('YII_DEBUG', APP_ENV === 'dev');
defined('YII_ENV') or define('YII_ENV', APP_ENV);

define('YII_ENABLE_ERROR_HANDLER', true);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

Yii::setAlias('@runtime', __DIR__.'/runtime');
Yii::setAlias('@app', __DIR__);
Yii::setAlias('@vendor', __DIR__.'/vendor');
Yii::setAlias('@beyod', __DIR__.'/vendor/beyod');

$config = require __DIR__ . '/config/main.php';

$application = new \yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
