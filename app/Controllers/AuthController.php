<?php


namespace App\Controllers;


use App\Factory\JWTAuth;

class AuthController extends Controller
{

    /**
     * Sign in method
     * @param $request
     * @param $response
     * @return mixed
     */
    public function signin($request, $response)
    {
        try {
            if (true) {
                $status = 200;
                $message = 'Signin successful';
            } else {
                $status = 422;
                $message = 'Invalid credentials';
            }

            return $this->xhr($response, $message, $status);
        } catch (\Exception $e) {
            return $this->xhr($response, $e->getMessage(), $e->getCode());
        }
    }

    public function signout($request, $response)
    {

        $array = [
            'data' => [
                'code'    => 200,
                'message' => 'signout!?'
            ]
        ];

        return $response->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->write(json_encode($array));

    }

}