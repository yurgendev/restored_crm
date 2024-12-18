<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

$application = new yii\console\Application($config);

$authKey = Yii::$app->security->generateRandomString();
$passwordHash = Yii::$app->security->generatePasswordHash('admin');

echo "Auth Key: " . $authKey . "\n";
echo "Password Hash: " . $passwordHash . "\n";