// public/index.php

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App(['settings' => require __DIR__ . '/../config/settings.php']);

$container = $app->getContainer();

// Configuración de Eloquent ORM
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection(require __DIR__ . '/../config/database.php');
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Incluir las rutas definidas en app/routes.php
(require __DIR__ . '/../app/routes.php')($app);

$app->run();
