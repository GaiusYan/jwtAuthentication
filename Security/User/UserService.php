<?php

namespace User;
use AuthenticationResponse;
use AuthenticationException;

class UserService
{
    private userModel $userModel;
    private \JwtAuthenticationFilter $jwtAuthenticationFilter;
    const SECRET_KEY = "404E635266556A5B6E3272367538782F4428472B4B6250645367566B6070";
    public function __construct($userModel,$jwtAuthenticationFilter){
        $this->userModel = $userModel;
        $this->jwtAuthenticationFilter = $jwtAuthenticationFilter;
    }

    public function addUser(User $user): bool
    {
        $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));
        return $this->userModel->save($user);
    }


    public function verifyToken(array $headers): String|array
    {
        $authHeader = $headers['Authorization'] ?? '';

        if (!empty($authHeader)) {

            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $jwtToken = $matches[1];

                $parts = $this->jwtAuthenticationFilter->explodeJwt($jwtToken);
                if ($parts != 3){

                    list($base64UrlHeader,$base64UrlPayload,$base64UrlSignature) = $parts;
                    $signature = hash_hmac('sha256', "$base64UrlHeader.$base64UrlPayload", self::SECRET_KEY, true);
                    $base64UrlSignatureCheck = $this->jwtAuthenticationFilter->base64UrlEncode($signature);
                    $payload = json_decode($this->jwtAuthenticationFilter->base64UrlDecode($base64UrlPayload), true);

                    if (isset($payload['exp']) && $payload['exp'] < time()) {
                        return get_object_vars(
                            new AuthenticationException(
                                "401",
                                "Token expired",
                                [
                                    "succes" => false
                                ]
                            )
                        );
                    }else{
                        $user = $this->userModel->findById($payload['userId']);

                        return get_object_vars(
                            new AuthenticationException(
                                "200",
                                "Success",
                                [
                                    "succes" => true,
                                    get_object_vars((object)$user)
                                ]
                            )
                        );
                    }
                }else{
                    return get_object_vars(
                        new AuthenticationException(
                            "401",
                            'Mauvais token',
                            [
                                'success' => false
                            ]
                        )
                    );
                }
            }
        }
        return get_object_vars(
            new AuthenticationException(
                "401",
                "Non authorisé",
                [
                    "success" => false
                ]
            )
        );
    }

    public function authenticate(\AuthenticationRequest $authenticationRequest): String|array
    {

        $userExists = $this->userModel->findByEmailAndPassword(
            $authenticationRequest->getUsername(),
            $authenticationRequest->getPassword());

        if(count($userExists) > 0){
            return
               get_object_vars(new AuthenticationResponse(
                    $this->jwtAuthenticationFilter->generateToken($authenticationRequest)
                ));
        }
        return get_object_vars(
            new AuthenticationException(
                "401",
                "Cette utilisateur n'existe pas",
                [
                    "username" => $authenticationRequest->getUsername(),
                    "password" => $authenticationRequest->getPassword()
                ]
            ));

    }
}