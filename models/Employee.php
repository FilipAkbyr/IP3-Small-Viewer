<?php

class Employee
{

    public ?int $employee_id = null;
    public ?string $name = null;
    public ?string $surname = null;
    public ?string $job = null;
    public ?string $wage = null;
    public ?int $room = null;
    public ?string $login = null;
    public ?string $pass = null;
    public ?bool $admin = null;

    private static string $table = 'employee';

    public function __construct(array $rawData = [])
    {
        $this->hydrate($rawData);

    }

    private function hydrate(array $rawData): void
    {
        if (array_key_exists('employee_id', $rawData)) {
            $this->employee_id = $rawData['employee_id'];
        }
        if (array_key_exists('name', $rawData)) {
            $this->name = $rawData['name'];
        }
        if (array_key_exists('surname', $rawData)) {
            $this->surname = $rawData['surname'];
        }
        if (array_key_exists('job', $rawData)) {
            $this->job = $rawData['job'];
        }
        if (array_key_exists('wage', $rawData)) {
            $this->wage = $rawData['wage'];
        }
        if (array_key_exists('room', $rawData)) {
            $this->room = $rawData['room'];
        }
        if (array_key_exists('login', $rawData)) {
            $this->login = $rawData['login'];
        }
        if (array_key_exists('pass', $rawData)) {
            $this->pass = $rawData['pass'];
        }
        if (array_key_exists('admin', $rawData)) {
            $this->admin = $rawData['admin'];
        }
    }

    public static function getLogin($login, $pass)
    {
        $pdo = PDOProvider::get();
        $query = "SELECT * FROM `" . self::$table . "` WHERE login = :login AND pass = :pass";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':login' => $login, ':pass' => hash("sha256", $pass)]);

        if ($stmt->rowCount() < 1)
            return null;

        return new Employee($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public static function getLoginHash($login, $pass)
    {
        $pdo = PDOProvider::get();
        $query = "SELECT * FROM `" . self::$table . "` WHERE login = :login AND pass = :pass";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':login' => $login, ':pass' => $pass]);

        if ($stmt->rowCount() < 1)
            return null;

        return new Employee($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public static function deleteById(int $employeeId): bool
    {
        $query = "DELETE FROM `" . self::$table . "` WHERE `employee_id` = :employeeId";

        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'employeeId' => $employeeId,
        ]);
    }

    public static function findByID(int $id): Employee|null
    {
        $pdo = PDOProvider::get();
        $query = "SELECT * FROM `" . self::$table . "` WHERE `employee_id` = $id";
        $stmt = $pdo->query($query);

        if ($stmt->rowCount() < 1)
            return null;

        return new Employee($stmt->fetch(PDO::FETCH_ASSOC));
    }

    public static function all(array $sort = []): array
    {
        $pdo = PDOProvider::get();

        $query = "SELECT * FROM `" . self::$table . "` " . self::sortSQL($sort);
        $stmt = $pdo->query($query);

        $result = [];
        while ($employoee = $stmt->fetch(PDO::FETCH_ASSOC))
            $result[] = new Employee($employoee);

        return $result;
    }

    private static function sortSQL(array $sort): string
    {
        if (!$sort)
            return "";

        $sqlChunks = [];
        foreach ($sort as $column => $direction) {
            $sqlChunks[] = "`$column` $direction";
        }
        return "ORDER BY " . implode(" ", $sqlChunks);
    }

    public static function readPost(): Employee
    {
        $employee = new Employee();

        $employee->employee_id = filter_input(INPUT_POST, 'employee_id', FILTER_VALIDATE_INT);
        $employee->name = filter_input(INPUT_POST, 'name', FILTER_DEFAULT);
        $employee->surname = filter_input(INPUT_POST, 'surname', FILTER_DEFAULT);
        $employee->job = filter_input(INPUT_POST, 'job', FILTER_DEFAULT);
        $employee->room = filter_input(INPUT_POST, 'room', FILTER_VALIDATE_INT);
        $employee->wage = filter_input(INPUT_POST, 'wage', FILTER_VALIDATE_INT);
        $employee->login = filter_input(INPUT_POST, 'login', FILTER_DEFAULT);
        $employee->pass = filter_input(INPUT_POST, 'pass', FILTER_DEFAULT);
        $employee->admin = !filter_input(INPUT_POST, 'admin', FILTER_VALIDATE_BOOL);
        $employee->admin = !$employee->admin;

        return $employee;
    }

    public function validate(array &$errors = []): bool
    {
        if (is_string($this->name))
            $this->name = trim($this->name);
        if (!$this->name)
            $errors['name'] = "Jméno nemůže být prázdné";

        if (is_string($this->surname))
            $this->surname = trim($this->surname);
        if (!$this->surname)
            $errors['surname'] = "Příjmení nemůže být prázdné";

        if (is_string($this->job))
            $this->job = trim($this->job);
        if (!$this->job)
            $errors['job'] = "Pole práce nemůže být prázdné";

        if (is_string($this->wage))
            $this->wage = trim($this->wage);
        if (!$this->wage)
            $errors['wage'] = "Plat nemůže být prázdný";

        if (is_string($this->room))
            $this->room = trim($this->room);
        if (!$this->room)
            $errors['room'] = "Místnost nemůže být prázdná";

        if (is_string($this->login))
            $this->login = trim($this->login);
        if (!$this->login)
            $errors['login'] = "Login nemůže být prázdný";

        if (is_string($this->pass))
            $this->pass = trim($this->pass);
        if (!$this->pass)
            $errors['pass'] = "Heslo nemůže být prázdné";

        return count($errors) === 0;
    }

    public function update(): bool
    {
        $query = "UPDATE `" . self::$table . "` SET `name` = :name, `surname` = :surname, `job` = :job, `wage` = :wage, `room` =:room, `login` =:login, `pass` =:pass, `admin`=:admin  WHERE `employee_id`=:employee_id;";
        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'employee_id' => $this->employee_id,
            'name' => $this->name,
            'surname' => $this->surname,
            'job' => $this->job,
            'wage' => $this->wage,
            'room' => $this->room,
            'login' => $this->login,
            'pass' => hash('sha256', $this->pass),
            'admin' => $this->admin,
        ]);

    }

    public function insert(): bool
    {
        $query = "INSERT INTO `" . self::$table . "` (`name`, `surname`, `job`, `wage`, `room`, `login`, `pass`, `admin`) VALUES (:name, :surname, :job, :wage, :room, :login, :pass, :admin)";
        $pdo = PDOProvider::get();

        $stmt = $pdo->prepare($query);
        return $stmt->execute([
            'name' => $this->name,
            'surname' => $this->surname,
            'job' => $this->job,
            'wage' => $this->wage,
            'room' => $this->room,
            'login' => $this->login,
            'pass' => hash('sha256', $this->pass),
            'admin' => $this->admin,
        ]);

    }
}