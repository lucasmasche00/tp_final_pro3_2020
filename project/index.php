<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use App\Controllers\UsuarioController;
use App\Controllers\EmpleadoController;
use App\Controllers\ComandaController;
use App\Controllers\MesaController;
use App\Controllers\AlimentoController;
use App\Controllers\PedidoController;
use App\Controllers\EncuestaController;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\DepurarJsonMiddleware;
use App\Middlewares\SocioMiddleware;
use App\Middlewares\SocioMozoMiddleware;
use App\Middlewares\SocioPreparadoresMiddleware;
use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/tp_proyecto');

date_default_timezone_set('America/Argentina/Buenos_Aires');
//date('Y-m-d H:i:s');

$app->post('/login[/]', UsuarioController::class . ":GenerarToken");

$app->post('/registro[/]', UsuarioController::class . ":RegistroInsert");

$app->group('/usuarios', function (RouteCollectorProxy $group) {

    $group->get('/{id}', UsuarioController::class . ":GetOne");

    $group->get('[/]', UsuarioController::class . ":GetAll");

    $group->post('[/]', UsuarioController::class . ":Insert");
    
    $group->put('/{id}', UsuarioController::class . ":Update");

    $group->delete('/{id}', UsuarioController::class . ":Delete");
})->add(new SocioMiddleware)->add(new AuthMiddleware);

$app->group('/empleados', function (RouteCollectorProxy $group) {

    $group->get('/{id}', EmpleadoController::class . ":GetOne");

    $group->get('[/]', EmpleadoController::class . ":GetAll");

    $group->post('[/]', EmpleadoController::class . ":Insert");
    
    $group->put('/{id}', EmpleadoController::class . ":Update");

    $group->delete('/{id}', EmpleadoController::class . ":Delete");
})->add(new SocioMiddleware)->add(new AuthMiddleware);

$app->group('/comandas', function (RouteCollectorProxy $group) {

    $group->get('/{id}', ComandaController::class . ":GetOne");

    $group->get('[/]', ComandaController::class . ":GetAll");

    $group->post('[/]', ComandaController::class . ":Insert");
    
    $group->put('/{id}', ComandaController::class . ":Update");

    $group->delete('/{id}', ComandaController::class . ":Delete");
})->add(new SocioMiddleware)->add(new AuthMiddleware);

$app->group('/mesas', function (RouteCollectorProxy $group) {

    $group->get('/{id}', MesaController::class . ":GetOne");

    $group->get('[/]', MesaController::class . ":GetAll");

    $group->post('[/]', MesaController::class . ":Insert");
    
    $group->post('/{id}', MesaController::class . ":Update"); //ACA USA POST POR LA IMAGEN

    $group->delete('/{id}', MesaController::class . ":Delete");
})->add(new SocioMiddleware)->add(new AuthMiddleware);

$app->group('/alimentos', function (RouteCollectorProxy $group) {

    $group->get('/{id}', AlimentoController::class . ":GetOne");

    $group->get('[/]', AlimentoController::class . ":GetAll");

    $group->post('[/]', AlimentoController::class . ":Insert");
    
    $group->put('/{id}', AlimentoController::class . ":Update");

    $group->delete('/{id}', AlimentoController::class . ":Delete");
})->add(new SocioMiddleware)->add(new AuthMiddleware);

$app->group('/pedidos', function (RouteCollectorProxy $group) {

    $group->get('/{id}', PedidoController::class . ":GetOne");

    $group->get('[/]', PedidoController::class . ":GetAll");

    $group->post('[/]', PedidoController::class . ":Insert");
    
    $group->put('/{id}', PedidoController::class . ":Update");

    $group->delete('/{id}', PedidoController::class . ":Delete");
})->add(new SocioMiddleware)->add(new AuthMiddleware);

$app->group('/encuestas', function (RouteCollectorProxy $group) {

    $group->get('/{id}', EncuestaController::class . ":GetOne");

    $group->get('[/]', EncuestaController::class . ":GetAll");

    $group->post('[/]', EncuestaController::class . ":Insert");
    
    $group->put('/{id}', EncuestaController::class . ":Update");

    $group->delete('/{id}', EncuestaController::class . ":Delete");
})->add(new SocioMiddleware)->add(new AuthMiddleware);

$app->group('/mozo/', function (RouteCollectorProxy $group) {

    $group->post('crearComanda[/]', ComandaController::class . ":CrearComanda");

    $group->post('asignarPedidoALaComanda[/]', PedidoController::class . ":Insert");

    $group->post('entregarPedido[/]', PedidoController::class . ":EntregarPedido");

    $group->post('cobrarMesa[/]', MesaController::class . ":CobrarMesa");
})->add(new SocioMozoMiddleware)->add(new AuthMiddleware);

$app->group('/preparadores/', function (RouteCollectorProxy $group) {

    $group->get('verListaPendientes[/]', PedidoController::class . ":GetPendientes");

    $group->post('asignarPedidoAPreparador[/]', PedidoController::class . ":AsignarAPreparador");

    $group->post('terminarPreparacionDePedido[/]', PedidoController::class . ":TerminarPreparacionDePedido");
})->add(new SocioPreparadoresMiddleware)->add(new AuthMiddleware);

$app->group('/clientes/', function (RouteCollectorProxy $group) {

    $group->get('consultarComanda/{mesaCode}/{comandaCode}[/]', ComandaController::class . ":ConsultarComanda");

    $group->post('responderEncuesta[/]', EncuestaController::class . ":Insert");
});

$app->group('/socios/', function (RouteCollectorProxy $group) {

    $group->get('ingresosAlSistema[/]', UsuarioController::class . ":GetIngresosAlSistema");

    $group->get('cantidadDeOperacionesPorSector[/]', EmpleadoController::class . ":GetCantidadOperacionesPorSector");

    $group->get('cantidadDeOperacionesPorSectorYEmpleado[/]', EmpleadoController::class . ":GetCantidadOperacionesPorSectorYEmpleado");

    $group->get('cantidadDeOperacionesPorEmpleado[/]', EmpleadoController::class . ":GetCantidadOperacionesPorEmpleado");

    $group->get('productoMasVendido[/]', PedidoController::class . ":GetAlimentoMasVendido");

    $group->get('productoMenosVendido[/]', PedidoController::class . ":GetAlimentoMenosVendido");

    $group->get('pedidosEntregadosFueraDeTiempo[/]', PedidoController::class . ":GetPedidosFueraDeTiempo");
    
    $group->get('pedidosCancelados[/]', PedidoController::class . ":GetPedidosCancelados");

    $group->get('mesaMasUsada[/]', MesaController::class . ":GetMesaMasUsada");
    
    $group->get('mesaMenosUsada[/]', MesaController::class . ":GetMesaMenosUsada");

    $group->get('mesaMasFacturo[/]', MesaController::class . ":GetMesaMasFacturo");
    
    $group->get('mesaMenosFacturo[/]', MesaController::class . ":GetMesaMenosFacturo");
    
    $group->get('mesaMayorFacturaIndividual[/]', MesaController::class . ":GetMesaMayorFacturaIndividual");
    
    $group->get('mesaMenorFacturaIndividual[/]', MesaController::class . ":GetMesaMenorFacturaIndividual");
    
    $group->get('mesaFacturoPorFechas/{codigoMesa}/{fechaDesde}/{fechaHasta}[/]', MesaController::class . ":GetMesaFacturoPorFechas");

    $group->get('mejoresComentarios[/]', EncuestaController::class . ":GetMejoresComentarios");

    $group->get('peoresComentarios[/]', EncuestaController::class . ":GetPeoresComentarios");
})->add(new SocioMiddleware)->add(new AuthMiddleware);

$app->add(new DepurarJsonMiddleware);

$app->addBodyParsingMiddleware();

$app->run();
?>