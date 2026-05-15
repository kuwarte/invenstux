<?php

require_once __DIR__ . '/../../core/Controller.php';

class TestAccountsController extends Controller
{
    public function index(): void
    {
        require_once __DIR__ . '/../Views/auth/test-accounts.php';
    }
}
