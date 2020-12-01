<?php
namespace App\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\Token;
use App\Models\JSend;

class SocioMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $token = $_SERVER['HTTP_TOKEN'] ?? '';

        $usuarioLogeado = Token::DecodificarToken($token);

        if($usuarioLogeado->tipo === 'socio')
        {
            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();

            $response = new Response();
            $response->getBody()->write($existingContent);

            return $response;
        }
        else
        {
            $jSend = new JSend('error');
            $jSend->message = 'Acceso no autorizado';

            $response = new Response();
            $response->getBody()->write(json_encode($jSend));

            return $response->withStatus(403);
        }
    }
}
?>