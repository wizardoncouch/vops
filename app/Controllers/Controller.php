<?php


namespace App\Controllers;


use App\Factory\DB;
use Psr\Http\Message\ResponseInterface;

class Controller
{
    protected $db;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->db = new DB();
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->db = null;
    }

    /**
     * @param ResponseInterface $response
     * @param              $data
     * @param bool|integer $codeOrPaginate
     * @return \Illuminate\Http\JsonResponse
     */
    public function xhr(ResponseInterface $response, $data, $codeOrPaginate = false)
    {
        $code = 200;
        $paginate = false;
        if (!is_bool($codeOrPaginate)) {
            $code = $codeOrPaginate;
        } else {
            $paginate = $codeOrPaginate;
        }
        $array = [];
        if ((is_object($data) || is_array($data)) && count($data) > 0) {
            if ($paginate) {
                $array['paginator'] = [
                    'total_count' => count($data),
//                    'total_pages'  => ceil($data->total() / $data->perPage()),
//                    'current_page' => $data->currentPage(),
//                    'limit'        => $data->count()
                ];
            }
            $array['data'] = $data;
        } else {
            if (empty($data) || count($data) == 0) {
                $data = 'Empty result';
            }
            $array['text'] = $data;
        }

        $array['@meta'] = [
            'server_time'     => date('Y-m-d H:i:s'),
            'server_timezone' => date_default_timezone_get(),
            'api_version'     => '1.0'
        ];

        return $response->withStatus($code)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($array));
    }

}