<?php
/*
$host = '127.0.0.1';
$db = 'test_db';
$user = 'root';
$password = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=3306;dbname=$db;charset=$charset";

$options = [
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES => false,
];



$pdo = new \PDO($dsn, $user, $password, $options);

$pdo->prepare("INSERT INTO test_db.'user' (surname, 'name', middlename, sex, birthdate, phone) VALUES
('Степанюк','Антон','Андреевич','М','2022-07-15 00:00:00','89002003020')")->execute();

$data = $pdo->query('SELECT * FROM user')->fetchAll(\PDO::FETCH_ASSOC);

echo ('<pre>');
print_r($data);
echo ('</pre>');
die;*/

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
