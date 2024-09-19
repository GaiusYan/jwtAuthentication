<?php

class AuthenticationResponse
{
    public String $token;

    /**
     * @param String $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }


}