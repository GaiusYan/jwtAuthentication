<?php

class AuthenticationRequest
{
    public String $username;
    public String $password;

    /**
     * @return mixed
     */
    public function getUsername(): String
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername(String $username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword(): String
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword(String $password): void
    {
        $this->password = $password;
    }


}