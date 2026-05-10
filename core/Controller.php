<?php

class Controller
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function view($path, $data = [])
    {
        extract($data);
        require_once __DIR__ . '/../app/Views/layouts/main.php';
    }
}
