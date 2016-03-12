<?php


namespace App\Controllers;


use App\Factory\DB;

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
            $stmt = $this->db->query('SELECT * from `Logins` WHERE `id`=11');
            $result = $stmt->fetch(DB::FETCH_ASSOC);

            return $this->xhr($response, $result, 200);
        } catch (\PDOException $e) {
            return $this->xhr($response, $e->getMessage(), 500);
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