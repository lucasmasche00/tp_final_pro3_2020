<?php
namespace App\Middlewares;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use stdClass;

class DepurarJsonMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler)
    {
        $response = $handler->handle($request);
        $jSend = (string) $response->getBody();
        
        $objJSend = json_decode($jSend);
        
        $newObj = self::Recursividad($objJSend);
        
        $newResponse = new Response();
        $newResponse->getBody()->write(json_encode($newObj));

        return $newResponse;
    }

    public static function Recursividad($obj)
    {
        $newObj = new stdClass();
        foreach ($obj as $key => $value)
        {
            if(!is_null($value))
            {
                if(is_object($value))
                {
                    $newObj->$key = self::Recursividad($value);
                }
                else
                {
                    $newObj->$key = $value;
                }
            }
        }
        return $newObj;
    }
}
?>