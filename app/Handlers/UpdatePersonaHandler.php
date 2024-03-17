// app/Handlers/UpdatePersonaHandler.php

namespace App\Handlers;

use Slim\Psr7\Response;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use App\Models\Credential;
use App\Models\Persona;

class UpdatePersonaHandler
{
    public function __invoke($request, $response, $args)
    {
        // Obtención de credenciales desde la base de datos
        $brand = $args['brand'];
        $credentials = Credential::where('brand', $brand)->first();

        if (!$credentials) {
            return $response->withJson([
                'estado' => 0,
                'mensaje' => 'Credenciales no encontradas'
            ]);
        }

        // Generación del token JWT
        $jwtPayload = [
            'client_id' => $credentials->client_id,
            'exp' => strtotime('+1 hour') // Definir la expiración del token
        ];

        $jwtToken = JWT::encode($jwtPayload, $credentials->secret_id);

        // Consumo del Web Service
        $client = new Client();

        try {
            $responseWebService = $client->request('POST', 'https://example.com/webservice', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $jwtToken
                ]
            ]);

            $data = json_decode($responseWebService->getBody(), true);
        } catch (\Exception $e) {
            return $response->withJson([
                'estado' => 0,
                'mensaje' => 'Error en la solicitud al webservice'
            ]);
        }

        // Actualización de datos en la tabla Personas
        $personaId = $args['id'];
        $persona = Persona::find($personaId);

        if (!$persona) {
            return $response->withJson([
                'estado' => 0,
                'mensaje' => 'Persona no encontrada'
            ]);
        }

        // Actualizar los datos de la persona con los valores del webservice
        $persona->nombre = $data['nombre'];
        $persona->apellido = $data['apellido'];
        $persona->edad = $data['edad'];
        $persona->telefono = $data['telefono'];

        // Guardar los cambios en la base de datos
        $persona->save();

        return $response->withJson([
            'estado' => 1,
            'mensaje' => 'Datos actualizados correctamente'
        ]);
    }
}
