<?php
require_once "../../bootstrap/bootstrap.php";

class EmployeeDetailPage extends AuthenticationPage
{
    private Employee $employee;
    private array $rooms;

    protected function prepareData(): void
    {
        parent::prepareData();

        //na koho se ptá (příp chyba)
        $employee_id = filter_input(INPUT_GET, 'employee_id', FILTER_VALIDATE_INT);

        if (!$employee_id) {
            throw new BadRequestException();
        }

        //vytáhnu místnost
        $this->employee = Employee::findByID($employee_id);

        //mám ho? (příp chyba)
        if (!$this->employee) {
            throw new NotFoundException();
        }

        $this->title = htmlspecialchars( "Zaměstnanec {$this->employee->no} ({$this->employee->name})" );

        //získám klíče
        $keys = Key::allKeysOfEmployee($this->employee->employee_id);
        $this->rooms = array_map(
            function ($item) {
                return Room::findByID($item->room);
            },
            $keys
        );
    }

    protected function pageBody(): string
    {

        $data = [
            "employee" => $this->employee,
            'rooms' => $this->rooms,
            'room' => Room::findByID($this->employee->room),
            ];
        //ukážu místnost
        return MustacheProvider::get()->render("employee_detail", $data);
    }
}

$page = new EmployeeDetailPage();
$page->render();