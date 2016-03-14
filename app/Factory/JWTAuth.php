<?php


namespace App\Factory;


use Firebase\JWT\JWT;

class JWTAuth
{

    protected static $table = 'users';

    protected static $alg = 'HS256';
    protected static $exp = 60;
    protected static $password_field = 'password';

//    protected $jti =


    public static function attempt($credentials = [], $password_field = '')
    {
        if (!empty($password_field)) {
            self::$password_field = $password_field;
        }
        $userId = self::authUser($credentials);
        if ($userId > 0) {
            return self::generateToken($userId);
        }
    }


    public function validate($token = '')
    {

    }

    public function invalidate()
    {
//        JWT::
    }

    public function refresh()
    {

    }

    private function blacklist()
    {

    }

    private static function generateToken($userId)
    {
        try {

            $secretKey = getenv('API_KEY');
            if (empty($secretKey)) {
                header('HTTP/1.0 501 Not Implemented');
                die('Set the API_KEY in the env file.');
            }
            $alg = getenv('JWT_ALG');
            if (!empty($alg)) {
                self::$alg = $alg;
            }
            $exp = getenv('JWT_EXP'); //retrieve the expiration from env file
            if ($exp > 0) {
                self::$exp = $exp;
            }
            $tokenId = base64_encode(mcrypt_create_iv(32));
            $now = time();
            $expire = strtotime('+ ' . self::$exp . ' minutes');// Adding the minutes set for expiration
            $issuer = getenv('APP_URL'); // Retrieve the issuer from env file

            $claims = [
                'iat' => $now, // Issued at: time when the token was generated
                'jti' => $tokenId, // Json Token Id: an unique identifier for the token
                'iss' => $issuer, // Issuer
                'nbf' => $now, // Not before
                'exp' => $expire, // Expire
                'sub' => $userId
            ];

            $token = JWT::encode(
                $claims, //Data to be encoded in the JWT
                $secretKey, // The signing key
                self::$alg // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
            );

            return $token;
        } catch (\Exception $e) {
            header('HTTP/1.0 500 Internal Server Error');
            die($e->getMessage());
        }
    }

    private static function authUser($credentials = [])
    {
        try {
            $pdo = new DB();
            $table = getenv('USERS_TABLE');
            if (!empty($table)) {
                self::$table = $table;
            }
            $sql = 'SELECT * FROM ' . self::$table;
            $array = [];
            $password = '';
            if (isset($credentials[self::$password_field])) {
                $password = $credentials[self::$password_field];
            }
            foreach ($credentials as $key => $value) {
                if ($key == self::$password_field) {
                    continue;
                }
                if (count($array) == 0) {
                    $sql .= ' WHERE ';
                } else {
                    $sql .= ' AND ';
                }
                $sql .= '`' . $key . '`=:' . $key;
                $array[':' . $key] = $value;
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($array);
            $user = $stmt->fetchObject();
            if (
                $user &&
                !empty(self::$password_field) &&
                !password_verify($password, $user->{self::$password_field})
            ) {
                header('HTTP/1.0 422 Unprocessable Entity');
                die('Invalid Credentials.');
            } else {
                return $user->id;
            }
        } catch (\PDOException $e) {
            header('HTTP/1.0 500 Internal Server Error');
            die($e->getMessage());
        }

    }

}