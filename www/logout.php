<?php

require_once "../bootstrap/bootstrap.php";

class LogoutPage extends Page
{
    public function __construct()
    {
        $this->title = "Logout";
    }

    protected function prepareData(): void
    {
        session_start();

        session_destroy();


    }
}

$page = new LogoutPage();
$page->render();
