<?php
require_once "../../bootstrap/bootstrap.php";

class EmployeeInsertPage extends CRUDPage
{

    protected Employee $employee;
    protected array $rooms;
    public string $title = "Založit nového zaměstnance";
    private array $errors;

    protected function prepareData(): void
    {
        parent::prepareData();
        $this->state = $this->getState();

        switch ($this->state) {
            case self::STATE_FORM_REQUEST:
                $this->employee = new employee();
                $this->errors = [];
                $this->rooms = Room::all();
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
                    $result = $this->employee->insert();
                    foreach ($roomsPost as $value)
                    {
                        $key = new Key();
                        $key->employee = $this->employee->employee_id;
                        $key->room = $value;
                        $key->insert();
                    }
                    //přesměrovat
                    $this->redirect(self::ACTION_INSERT, $result);
                } else {
                    //na formulář
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