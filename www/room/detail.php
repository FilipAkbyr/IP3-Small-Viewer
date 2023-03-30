<?php
require_once "../../bootstrap/bootstrap.php";

class RoomDetailPage extends AuthenticationPage
{
    private $room;
    private $employees;

    protected function prepareData(): void
    {
        parent::prepareData();

        //na koho se ptá (příp chyba)
        $room_id = filter_input(INPUT_GET, 'room_id', FILTER_VALIDATE_INT);

        if (!$room_id) {
            throw new BadRequestException();
        }

        //vytáhnu místnost
        $this->room = Room::findByID($room_id);

        //mám ho? (příp chyba)
        if (!$this->room){
            throw new NotFoundException();
        }

        $this->title = htmlspecialchars( "Místnost {$this->room->no} ({$this->room->name})" );

        //získám lidi
        $this->employees = Room::allEmployeesOfRoom($room_id);
    }

    protected function pageBody(): string
    {
        //ukážu místnost
        return MustacheProvider::get()->render("room_detail", ["room" => $this->room, 'employees' => $this->employees]);
    }
}

$page = new RoomDetailPage();
$page->render();