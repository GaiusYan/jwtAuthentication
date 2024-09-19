<?php

use User\User;
use User\userModel;

class JwtAuthenticationFilter{

     const SECRET_KEY = "404E635266556A5B6E3272367538782F4428472B4B6250645367566B6070";

    private userModel $userModel;
     public function __construct(userModel $userModel){
         $this->userModel = $userModel;
     }
    public function base64UrlEncode($data): array|string
    {
        if(is_array($data)){
            $data = json_encode($data);
        }
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }


    public function generateToken(AuthenticationRequest $request): ?String{

        $userExist = $this->userModel->findByEmailAndPassword($request->getUsername(),$request->getPassword());

        if (count($userExist) > 0){
            $payload = [
                'userId' => $userExist[0]->id,
                'iat' => time(),
                'exp' => time() + 3600
            ];
            return $this::generateJwt($payload);
        }
        return null;
    }


    public function base64UrlDecode($data): false|string
    {
        $remainder = strlen($data) % 4 ;
        if($remainder){
            $padlen = 4 - $remainder;
            $data .= str_repeat('=',$padlen);

        }
        return base64_decode(strtr($data,'-_','+/'));
    }

    public function generateJwt($payload): string
    {
        $header = json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]);

        //Encoder Header and payload
        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        //signature 
        $signature = hash_hmac('sha256',"$base64UrlHeader.$base64UrlPayload",self::SECRET_KEY,true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        //return JWT
        return "$base64UrlHeader.$base64UrlPayload.$base64UrlSignature";
    }


    public function explodeJwt($jwt): array {
         return explode(".", $jwt);
    }




    public function verifyJwt($jwt){

        //separation des différentes parties du JWT 
        $parts = explode('.',$jwt);
        if(count($parts) !== 3){
            return false;//JWt est invalide
        }

        list($base64UrlHeader,$base64UrlPayload,$base64UrlSignature) = $parts;

        //recalculer la signature avec le secret
        $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::SECRET_KEY, true);
        $base64UrlSignatureCheck = $this->base64UrlEncode($signature);


        // Décoder le payload
        $payload = json_decode($this->base64UrlDecode($base64UrlPayload), true);

         // Vérifier l'expiration du token
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false; // Le token a expiré
        }

        // Retourner le payload si tout est correct
        return $payload;
    }


}


/*//this test allow to return JWT
$jwtAuthenticationFilter = new JwtAuthenticationFilter();
$payload = [
    'iss' => 'http://localhost:80/auth',
    'iat' => time(),
    'exp' => time() + 3600,
    'userId' => 123
];
$jwtGenerated = $jwtAuthenticationFilter->generateJwt($payload);

$payloadGenerated = $jwtAuthenticationFilter->verifyJwt($jwtGenerated);

if($payloadGenerated){
    echo "Token valide. Payload : ";
    print_r($payloadGenerated);
}else{
    echo "Token invalide ou expiré.";
}*/