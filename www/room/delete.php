<?php
require_once "../../bootstrap/bootstrap.php";

class RoomDeletePage extends CRUDPage
{

    protected function prepareData(): void
    {
        parent::prepareData();
        $roomId = filter_input(INPUT_POST, 'room_id', FILTER_VALIDATE_INT);
        if (!$roomId)
            throw new BadRequestException();

        $room = Room::findByID($roomId);
        if ($room == null){
            throw new NotFoundException();
        }

        $employees = Room::allEmployeesOfRoom($roomId);
        if (count($employees)) {
            throw new BadRequestException("Někdo bydlí v této místnosti");
        }

        $keys = Key::allKeysOfRoom($room);
        foreach ($keys as $value)
        {
            Key::deleteById($value->key_id);

        }


        $result = Room::deleteById($roomId);
        $this->redirect(self::ACTION_DELETE, $result);
    }


    protected function pageBody(): string
    {
        return "";
    }
}

$page = new RoomDeletePage();
$page->render();