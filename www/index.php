<?php

require_once "../bootstrap/bootstrap.php";

class LoginPage extends Page
{
    protected $error = null;
    protected ?Employee $user = null;

    public function __construct()
    {
        $this->title = "Login";
    }

    protected function prepareData(): void
    {
        session_start();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!(isset ($_POST["username"]) and isset($_POST["password"]))) {
                throw new BadRequestException();
            }
            $user = Employee::getLogin($_POST["username"], $_POST["password"]);

            if ($user == null) {
                $this->error = "Chyba, uživatel nenalezen";
            }


            $_SESSION['user'] = $user;
        }

        if (isset($_SESSION['user']))
        {
            $this->user = $_SESSION['user'];
        }
    }

    protected function pageBody(): string
    {

        $data = [
            "error" => $this->error,
            "login" => $this->user != null,
            "user" => $this->user->login
            ];

        $renderPage = MustacheProvider::get()->render("login", $data);
        return $renderPage;
    }

    protected function pageHeader() : string {
        return MustacheProvider::get()->render("page_header", ['logged' => $this->user != null]);
    }
}

$page = new LoginPage();
$page->render();
