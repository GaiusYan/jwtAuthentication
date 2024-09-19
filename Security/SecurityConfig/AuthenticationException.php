<?php

class AuthenticationException
{

    public String $status;
    public String $message;
    public Array $data;

    /**
     * @param String $status
     * @param String $message
     * @param array $data
     */
    public function __construct(string $status, string $message, array $data)
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
    }


}