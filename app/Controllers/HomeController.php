<?php


namespace App\Controllers;


use App\Factory\DB;
use App\Factory\JWTAuth;

class HomeController extends Controller
{

    /**
     * @param $request
     * @param $response
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function index($request, $response)
    {
        try {
            $credentials = [
                'username' => 'alexc@verticalops.com',
                'password' => '0-sum!',
                'active' => 1,
                'deleted' => 0
            ];
            $result = JWTAuth::attempt($credentials);
//            var_dump(\Firebase\JWT\JWT::decode($result, getenv('API_KEY'), [getenv('JWT_ALG')]));
//            die();
            return $this->xhr($response, $result);
        } catch (\PDOException $e) {
            header('HTTP/1.0 500 Internal Server Error');
            die($e->getMessage());
        }

    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     * @return mixed
     */
    public function create(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        return $response->getBody()->write('Create entry baby, create!');
    }
}