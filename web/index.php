<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

/* Режим разработки */
defined('YII_ENV_TEST') or define('YII_ENV_TEST', true);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

require __DIR__ . '/../functions.php';

(new yii\web\Application($config))->run();
