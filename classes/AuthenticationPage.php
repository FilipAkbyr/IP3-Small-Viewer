<?php

class AuthenticationPage extends Page
{
    protected ?Employee $user;

    protected function prepareData(): void
    {
        session_start();

        $this->user = Employee::getLoginHash($_SESSION['user']->login, $_SESSION['user']->pass);

        if ($this->user == null) {
            throw new ForbiddenException();
        }
    }

    protected function pageHeader() : string {
        return MustacheProvider::get()->render("page_header", ['logged' => $this->user != null]);
    }
}