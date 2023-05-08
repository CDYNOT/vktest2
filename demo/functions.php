<?php
require_once CORE_PATH . 'config/config.php';
require_once CORE_PATH . 'classes/DB.php';

/**
 * Класс реализации демонстрации
 */
class Demo
{
    private $connect = null;

    public function __construct()
    {
        $db = new DB();
        $this->connect = $db->getConnect();
    }

    /**
     * Возвращает список событий из таблицы events_name
     * @return array
     */
    public function getEventsNameList(): array
    {
        $data = [];
        $query = "SELECT name FROM events_name;";
        $st = $this->connect->prepare($query);
        $st->execute();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row['name'];
        }
        return $data;
    }

    /**
     * Возвращает список уникальных дат (часы, минуты и секунды обрезаются) из таблицы events
     * @return array
     */
    public function getEventsDateList(): array
    {
        $data = [];
        $query = "SELECT DISTINCT DATE(date) as date FROM events;";
        $st = $this->connect->prepare($query);
        $st->execute();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row['date'];
        }
        return $data;
    }

    /**
     * Возвращает список уникальных ip адресов из таблицы events
     * @return array
     */
    public function getEventsIpList(): array
    {
        $data = [];
        $query = "SELECT DISTINCT ip FROM events;";
        $st = $this->connect->prepare($query);
        $st->execute();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row['ip'];
        }
        return $data;
    }
}

$demo = new Demo();
// Получаем список имен событий для заполнения формы
$eventsNameList = $demo->getEventsNameList();
// Получаем список уникальных дат для заполнения формы
$eventsDateList = $demo->getEventsDateList();
// Получаем список уникальных ip адресов для заполнения формы
$eventsIpList = $demo->getEventsIpList();