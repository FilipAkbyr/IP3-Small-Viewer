<?php
require_once "../../bootstrap/bootstrap.php";

class EmployeeListPage extends AuthenticationPage
{
    public string $title = "Seznam lidí";

    protected function pageBody(): string
    {


        //získám data o místnostech
        $employees = Employee::all();

        foreach ($employees as $value) {
            $room = Room::findByID($value->room);
            $value->room_no = $room->no;
            $value->room_name = $room->name;
        }

        $html = MustacheProvider::get()->render("employee_list",
            ["employees" => $employees,
                "enabled_admin" => $this->user->admin,

            ]
        );
        //vyrenderuju

        return $html;
    }


}

$page = new EmployeeListPage();
$page->render();