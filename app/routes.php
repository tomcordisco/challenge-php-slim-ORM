// app/routes.php

use Slim\App;
use App\Handlers\UpdatePersonaHandler;

return function (App $app) {
    $app->post('/update-persona/{id}/{brand}', UpdatePersonaHandler::class);
};
