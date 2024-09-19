<?php

use Connection\Connection;
use User\User;
use User\userModel;
use User\UserService;

include 'Connection/connection.php';
include 'User/User.php';
include 'User/Dao.php';
include 'User/userModel.php';
include 'User/UserService.php';
include 'SecurityConfig/SecurityConfig.php';
include "SecurityConfig/JwtAuthenticationFilter.php";
include 'SecurityConfig/AuthenticationRequest.php';
include 'SecurityConfig/AuthenticationException.php';
include 'SecurityConfig/AuthenticationResponse.php';

$request = json_decode(file_get_contents("php://input"));
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$userModel = new UserModel(new Connection());
$JwtAuthenticationFilter = new JwtAuthenticationFilter($userModel);
$authenticationRequest = new AuthenticationRequest();
$userService = new UserService($userModel, $JwtAuthenticationFilter);


    if($requestMethod == "POST" && explode('=', $requestUri)[1] == "authenticate"){
       $authenticationRequest->setUsername($request->username);
       $authenticationRequest->setPassword($request->password);
       echo json_encode($userService->authenticate($authenticationRequest));
    }


    if ($requestMethod == "POST" && explode('=', $requestUri)[1] == 'checkToken') {
        $headers = getallheaders();
        echo json_encode($userService->verifyToken($headers));
    }

    if ($requestMethod == "POST" && explode('=', $requestUri)[1] == 'uploadFile') {

    }