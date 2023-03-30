<?php

class Key {

    public ?int $key_id;
    public ?int $employee;
    public ?int $room;

    public function __construct(array $rawData = [])
    {
        $this->hydrate($rawData);
    }

    private function hydrate(array $rawData): void
    {
        if (array_key_exists('key_id', $rawData)) {
            $this->key_id = $rawData['key_id'];
        }
        if (array_key_exists('employee', $rawData)) {
            $this->employee = $rawData['employee'];
        }
        if (array_key_exists('room', $rawData)) {
            $this->room = $rawData['room'];
        }
    }

    static function allKeysOfEmployee(int $employee_id) : array {
        $query = "SELECT * FROM `key` WHERE `employee` = :employee";
        $stmt = PDOProvider::get()->prepare($query);
        $stmt->execute(['employee' => $employee_id]);
        return array_map(
            function ($item) {
                return new Key($item);
            }, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function employeeHasKey($employee_id, $room_id)
    {
        $query = "SELECT * from `key` WHERE `employee` =:employee and `room` = :room";
        $stmt = PDOProvider::get()->prepare($query);
        $stmt->execute(['employee' => $employee_id, 'room' =>$room_id]);
        return $stmt->rowCount() > 0;
    }

    public function insert() : bool
    {
        $query = "INSERT INTO `key` (`employee`, `room`) VALUES (:employee, :room);";
        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'employee' => $this->employee,
            'room' => $this->room,
        ]);
    }

    public static function deleteById(int $key_id) : bool
    {
        $query = "DELETE FROM `key` WHERE `key_id` = :key_id";

        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'key_id' => $key_id,
        ]);
    }
}