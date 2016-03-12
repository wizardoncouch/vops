<?php


namespace App;


use Slim\App;
use Slim\Container;

Class VerticalOps
{
    protected $app;
    protected $container;

    public function __construct()
    {
        $this->container = new Container();
        $this->app = new App($this->container);
    }

    public function fire()
    {
        $this->_logs();
        $this->_mainRoute();
        $this->_authRoutes();
        $this->app->run();
    }

    /**
     *
     */
    private function _mainRoute()
    {
        $this->app->get('/', function ($request, $response) {
            $content = file_get_contents(PUBLIC_FOLDER . DIRECTORY_SEPARATOR . 'index.html');

            return $response->getBody()->write($content);
        });
        $this->app->get('/home', 'App\Controllers\HomeController:index');
    }

    /**
     * auth related routes
     */
    private function _authRoutes()
    {
        $auth = $this->_authMiddleware();
        $app = $this->app;
        $this->app->group('/api/auth', function () use ($app, $auth) {
            $app->post('/signin', 'App\Controllers\AuthController:signin');//->setName('auth-signin');
            $app->get('/signout', 'App\Controllers\AuthController:signout')->add($auth);//->setName('auth-signout');
        });
    }


    private function _authMiddleware()
    {
        return function ($request, $response, $next) {
            echo '<pre>';
            var_dump($request);
            $response = $next($request, $response);

            return $response;
        };

    }

    private function _logs()
    {
        $this->container['errorHandler'] = function ($c) {
            return function ($request, $response, $exception) use ($c) {
                $data = [
                    'code'    => $exception->getCode(),
                    'message' => $exception->getMessage(),
                    'file'    => $exception->getFile(),
                    'line'    => $exception->getLine(),
                    'trace'   => explode("\n", $exception->getTraceAsString()),
                ];

                return $c->get('response')->withStatus(500)
                    ->withHeader('Content-Type', 'application/json')
                    ->write(json_encode($data));
            };
        };
    }

}