<?php
class TableCreator
{
    private static $instance;
    private $pdo;

    private function __construct()
    {
        $this->create();
        $this->fill();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function create()
    {
        $this->pdo = new PDO('mysql:host=localhost;dbname=local_wiki', 'root', 'sunil');

        $query = "
            CREATE TABLE IF NOT EXISTS Test (
                id INT AUTO_INCREMENT PRIMARY KEY,
                script_name VARCHAR(25),
                start_time DATETIME,
                end_time DATETIME,
                result ENUM('normal', 'illegal', 'failed', 'success')
            )
        ";

        $this->pdo->exec($query);
    }

    private function fill()
    {
        $scriptNames = ['Script A', 'Script B', 'Script C'];
        $results = ['normal', 'illegal', 'failed', 'success'];

        $insertQuery = "
            INSERT INTO Test (script_name, start_time, end_time, result)
            VALUES (:script_name, :start_time, :end_time, :result)
        ";

        $stmt = $this->pdo->prepare($insertQuery);

        for ($i = 0; $i < 10; $i++) {
            $scriptName = $scriptNames[array_rand($scriptNames)];
            $startTime = date('Y-m-d H:i:s');
            $endTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $result = $results[array_rand($results)];

            $stmt->bindParam(':script_name', $scriptName);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->bindParam(':result', $result);

            $stmt->execute();
        }
    }

    public function get()
    {
        $query = "
            SELECT *
            FROM Test
            WHERE result IN ('normal', 'success')
        ";

        $stmt = $this->pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }
}
