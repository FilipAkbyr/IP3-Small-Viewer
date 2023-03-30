<?php
require_once "../../bootstrap/bootstrap.php";

class EmployeeListPage extends AuthenticationPage
{
    public string $title = "Seznam lidí";

    protected function pageBody(): string
    {



        //získám data o místnostech
        $employees = Employee::all();
        $html = MustacheProvider::get()->render("employee_list", ["employees" => $employees]);
        //vyrenderuju

        return $html;
    }


}

$page = new EmployeeListPage();
$page->render();