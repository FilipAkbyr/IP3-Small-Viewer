<?php
require_once "../../bootstrap/bootstrap.php";

class EmployeeInsertPage extends CRUDPage
{
    protected Employee $employee;
    public string $title = "Upravit zaměstnance";
    private array $errors;
    protected array $rooms;

    protected function prepareData(): void
    {
        parent::prepareData();
        $this->state = $this->getState();

        switch ($this->state) {
            case self::STATE_FORM_REQUEST:
                $employee_id = filter_input(INPUT_GET, 'employee_id', FILTER_VALIDATE_INT);
                if (!$employee_id)
                    throw new BadRequestException();

                $this->employee = Employee::findByID($employee_id);
                if (!$this->employee)
                    throw new NotFoundException();

                $this->rooms = Room::all();
                foreach ($this->rooms as $value)
                {
                    $value->checked = Key::employeeHasKey($employee_id, $value->room_id);
                }

                $this->errors = [];
                break;

            case self::STATE_DATA_SENT:

                //načíst data
                $this->employee = Employee::readPost();
                $roomsPost = filter_input(INPUT_POST, 'rooms', FILTER_VALIDATE_INT, FILTER_REQUIRE_ARRAY);


                //zkontrolovat data
                $this->errors = [];

                if ($roomsPost === false)
                {
                    throw new BadRequestException();
                }
                foreach($roomsPost as $value)
                {
                    if(Room::findByID($value) == null)
                    {
                        throw new BadRequestException();
                    }
                }


                if ($this->employee->validate($this->errors)) {
                    //zpracovat
                    $result = $this->employee->update();


                    $keysOfEmployee = Key::allKeysOfEmployee($this->employee->employee_id);
                    foreach ($keysOfEmployee as $value)
                    {
                        Key::deleteById($value->key_id);
                    }

                    foreach ($roomsPost as $value)
                    {
                        $key = new Key();
                        $key->employee = $this->employee->employee_id;
                        $key->room = $value;
                        $key->insert();
                    }


                    //přesměrovat
                    $this->redirect(self::ACTION_UPDATE, $result);
                } else {
                    //na formulář - něco se nepovedlo
                    $this->state = self::STATE_FORM_REQUEST;
                }
                break;
        }
    }


    protected function pageBody(): string
    {
        return MustacheProvider::get()->render("employee_form",
            [
                'employee' => $this->employee,
                'errors' => $this->errors,
                'rooms' => $this->rooms,
            ]);
        //vyrenderuju
    }

    protected function getState(): int
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            return self::STATE_DATA_SENT;

        return self::STATE_FORM_REQUEST;
    }

}

$page = new EmployeeInsertPage();
$page->render();