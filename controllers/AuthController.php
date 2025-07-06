<?php
require_once './models/User.php';

class AuthController {
    private $model;

    public function __construct($db) {
        $this->model = new User($db);
    }

    public function login($username, $password) {
        return $this->model->login($username, $password);
    }

    public function register($username, $password) {
        return $this->model->register($username, $password);
    }
}
