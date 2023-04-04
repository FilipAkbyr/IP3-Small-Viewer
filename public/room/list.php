<?php
require_once "../../bootstrap/bootstrap.php";

class RoomListPage extends AuthenticationPage
{
    public string $title = "Seznam místností";

    protected function pageBody(): string
    {



        //získám data o místnostech
        $rooms = Room::all();

        $html = MustacheProvider::get()->render("room_list", [
            "rooms" => $rooms,
            "admin" => $this->user->admin,

        ]);
        //vyrenderuju

        return $html;
    }


}

$page = new RoomListPage();
$page->render();