<?php

namespace Security;


use Base\Exception;

class Jwt
{
    private static string $privateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCzklJMZySwbrRS1ZlU8POxEDLPXHqgCYBjRRHkm7XvgNSTiOCK
tLceBeXgrTh8syTCBpjSOb9XzybZa+jX4zDFKxrWMKRlzjSQ+g3g/tFjkjOOi1d6
tyIc5B9ZfOl/SeUVR7pKCdyskUp/ILdX8Ric3xq+pGye2FRBe6mmynTveQIDAQAB
AoGAUVZEqtDJYJU0XpTbuArQBvK0YRKdhKHpIo3llewl049COnV0Z7+FdjbrsgIk
JO+sBaqhTA/WKLru+OqU9Dy3GMWXtGG5vqRJX4VznxOWm7OxmXtYzQ9P3hX8oCnn
uNoeCdYn+Cz9oNqIGOEZ6MLX3AY9md2ctyoROz1QGuzq5IUCQQDeliePH9UC8bkh
rqc3QpkGs4zF3wZcbUC4V+ol3TKXhkksgJyK+7wboHhggl/PpeMq3ckJE7AWb4Ju
ppA+6qGnAkEAzochra9fE7gGsRAmFKzeaWg/IXnvLu6pmhBSm/rP/PfEpR91T7Us
pyg1HqYdpY1Uy408/CDT3IMWN2MJDGrJ3wJAboAOnYAJCJAl/zGsc8ONIoWKckT4
7HOUeK+0Xx3D2vNgakZi8KBuTyzH9eljVTueXg6Cmg37EfhDwVjRLVrf6wJAH8zI
z8CACD4+sYbGBkrz2BEYH2RyAqz26mT3A1NkMRRJcA9e9c30uSxEuZpkWDhhxRHT
QRMg7AQ3KIIUQ5gxywJBAKYVCbgJO35ptyr/7Akj6239Z7Xp0qpX5WwI2LapLyO1
QV3l0h5mjkMwV9S7KaKqG5ZXRahmrxDy1iNHgSMwNcc=
-----END RSA PRIVATE KEY-----
EOD;

    private static string $publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCzklJMZySwbrRS1ZlU8POxEDLP
XHqgCYBjRRHkm7XvgNSTiOCKtLceBeXgrTh8syTCBpjSOb9XzybZa+jX4zDFKxrW
MKRlzjSQ+g3g/tFjkjOOi1d6tyIc5B9ZfOl/SeUVR7pKCdyskUp/ILdX8Ric3xq+
pGye2FRBe6mmynTveQIDAQAB
-----END PUBLIC KEY-----
EOD;


    public static function encode($data): string
    {
        $time = time(); //当前时间
        $payload = [
            'iss' => '', //签发者 可选
            'aud' => '', //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            'nbf' => $time, //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time + 7200, //过期时间,这里设置2个小时
            'data' => $data
        ];
        return \Firebase\JWT\JWT::encode($payload, self::$privateKey, "RS256");
    }

    /**
     * @throws Exception
     */
    public static function decode($jwt): array
    {
        $decoded = [];
        try {
            //\Firebase\JWT\JWT::$leeway = 60;//当前时间减去60，给时间留点余地
            $decoded = \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key(self::$publicKey, "RS256")); //RS256方式，这里要和签发的时候对应
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            throw new Exception("token错误", 5005001);
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            throw new Exception("token还未生效", 5005002);
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            throw new Exception("token已经过期", 5005003);
        } catch (\Exception $e) {  //其他错误
            throw new Exception($e->getMessage(), 5005004);
        }

        return (array)$decoded;
    }

}