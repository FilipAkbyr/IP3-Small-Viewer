<?php
require_once "../../bootstrap/bootstrap.php";

class EmployeeDeletePage extends CRUDPage
{

    protected function prepareData(): void
    {
        parent::prepareData();
        $employeeId = filter_input(INPUT_POST, 'employee_id', FILTER_VALIDATE_INT);
        if (!$employeeId)
            throw new BadRequestException();

        $employee = Employee::findByID($employeeId);
        if ($employee == null){
            throw new NotFoundException();
        }


        $keys = Key::allKeysOfEmployee($employeeId);
        foreach ($keys as $value) {
            Key::deleteById($value->key_id);
        }

        $result = Employee::deleteById($employeeId);
        $this->redirect(self::ACTION_DELETE, $result);
    }


    protected function pageBody(): string
    {
        return "";
    }
}

$page = new EmployeeDeletePage();
$page->render();