<?php
namespace App\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Models\Token;
use App\Models\JSend;
use Throwable;

class AuthMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $token = $_SERVER['HTTP_TOKEN'] ?? '';
        try
        {
            Token::DecodificarToken($token);

            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();

            $response = new Response();
            $response->getBody()->write($existingContent);

            return $response;
        }
        catch (Throwable $th)
        {
            $jSend = new JSend('error');
            $jSend->message = 'Token invalido';

            $response = new Response();
            $response->getBody()->write(json_encode($jSend));

            return $response->withStatus(403);
        }
    }
}
?>