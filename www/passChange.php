<?php


require_once "../bootstrap/bootstrap.php";

class PassChangePage extends AuthenticationPage
{
    protected array $errors;

    protected function prepareData(): void
    {
        parent::prepareData();

        $this->title = "Změna Hesla";

        $this->errors = [];
        $accepted = true;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $oldpass = filter_input(INPUT_POST, 'oldpass', FILTER_DEFAULT);
            $newpass = filter_input(INPUT_POST, 'newpass', FILTER_DEFAULT);
            $newpassagain = filter_input(INPUT_POST, 'newpassagain', FILTER_DEFAULT);

            if ($this->user->pass != hash('sha256', $oldpass)) {
                $this->errors['oldpass'] = "Špatné heslo";
                $accepted = false;
            }

            if (!$newpass) {
                $this->errors['newpass'] = "Vyplň toto pole";
                $accepted = false;
            }

            if (!$newpassagain) {
                $this->errors['newpassagain'] = "Vyplň toto pole";
                $accepted = false;
            }

            if ($newpass != $newpassagain){
                $this->errors['passmatch'] = "Hesla se neshodují";
                $accepted = false;
            }

            if ($accepted == true) {

                $this->user->pass = $newpass;

                $this->user->update();

            }
        }
    }

    protected function pageBody(): string
    {
        return MustacheProvider::get()->render("pass_change", [
            'errors' => $this->errors,

        ]);
    }
}


$page = new PassChangePage();
$page->render();