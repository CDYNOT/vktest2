<?php
require_once CORE_PATH . 'classes/DB.php';

/**
 * Класс реализации простейшего API, без авторизации и токенов
 * api получает и агрегирует данные из связных таблиц events и events_name
 * и возвращает ответ в формате json
 */
class Api
{
    /**
     * Имя метода класса, который необходимо запустить
     * @var string
     */
    public $action = '';

    private $requestUri = [];

    private $requestParams = [];

    private $connect = null;

    public function __construct()
    {
        $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->requestParams = $_REQUEST;
        $this->initConnection();
    }

    /**
     * Запускает работу api
     * @return void
     */
    public function run(): void
    {
        // Получаем название функции, которую нужно запустить,
        // она должна быть последняя в урле
        $this->action = $this->getAction(array_pop($this->requestUri));

        // Проверка, что перед action в урле стоит /api/
        if (array_pop($this->requestUri) !== 'api') {
            $this->responseError(404, 'API Not Found');
        }

        // Если метод существует, запускаем его
        if (method_exists($this, $this->action)) {
            $this->{$this->action}();
        } else {
            $this->responseError(405, 'Invalid API Method');
        }
    }

    /**
     * Возвращает агрегированную информацию по событиям,
     * в зависимости от переданных параметров
     * event - обязателен, может быть массивом или строкой
     * @return void
     */
    private function getEvents(): void
    {
        // Результирующий массив ответа
        $data = [];

        // Параметры:
        // Событие(я) array|string
        $event = $this->getClearParam('event');
        // Дата string
        $date = $this->getClearParam('date', 'string');
        // ip string
        $ip = $this->getClearParam('ip', 'string');
        // Авторизован ли пользователь int
        $auth = $this->getClearParam('auth', 'int');

        // Если передан event
        if ($event) {
            // Фильтрует по названию события и времени (если передано)
            $query = "
            SELECT t1.name AS event, COUNT(t2.id) AS count
            FROM events_name t1
            LEFT JOIN events t2 ON t1.id = t2.name_id WHERE t1.name ";

            if(is_array($event)) {
                foreach ($event as &$value) {
                    $value = "'{$value}'";
                }
                $query .= " IN (" . implode(',', $event) . ")";
            } else {
                $query .= " = '{$event}'";
            }

            // Если передан date
            if ($date) {
                $query .= " AND DATE(t2.date) = '{$date}'";
            }

            // Если передан ip
            if ($ip) {
                $query .= " AND t2.ip = '{$ip}'";
            }

            // Если передан auth
            if ($auth) {
                $query .= " AND t2.auth = '{$auth}'";
            }

            $query .= " GROUP BY t1.name";

            // Конец запроса
            $query .= ";";

            $st = $this->connect->prepare($query);
            $st->execute();

            while ($row = $st->fetch(PDO::FETCH_ASSOC))
            {
                $row['count'] = (int) $row['count'];
                $data[] = $row;
            }
            $data = [
                'status' => 'success',
                'response' => $data,
            ];
        } else {
            $data = [
                'status' => 'error',
                'msg' => 'required parameter not passed: event',
            ];
        }

        $this->response($data, 200);
    }

    /**
     * Создает событие по входным параметрам и возвращает id события в случае успеха
     * @return void
     */
    private function createEvent(): void
    {
        // Параметры:
        // Событие string
        $event = $this->getClearParam('event', 'string');
        // Авторизован ли пользователь int
        $auth = $this->getClearParam('auth', 'int');
        // Валидация переданного auth
        if ($auth !== 1) {
            $auth = 0;
        }
        // ip
        $ip = $this->getClearParam('ip', 'string');
        // Валидация переданного ip
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // Дата
        $date = date('Y-m-d H:i:s');

        if ($event) {
            $query = "
            INSERT INTO events (name_id, auth, ip, date)
            SELECT events_name.id, :auth, :ip, :date
            FROM events_name 
            WHERE events_name.name = :event;";

            $st = $this->connect->prepare($query);

            // Тут решил сделать вставку переменных таким образом
            $st->execute([
                ':auth' => $auth,
                ':ip' => $ip,
                ':date' => $date,
                ':event' => $event,
            ]);

            // Получаем id последней вставки в базу
            $insertId = (int) $this->connect->lastInsertId();

            if ($insertId > 0) {
                // Результирующий массив ответа
                $data = [
                    'status' => 'success',
                    'id' => $insertId,
                ];
            } else {
                // Результирующий массив ответа
                $data = [
                    'status' => 'error',
                    'msg' => 'parameters not correct',
                ];
            }

        } else {
            // Результирующий массив ответа
            $data = [
                'status' => 'error',
                'msg' => 'required parameter not passed: event',
            ];
        }
        $this->response($data, 201);
    }

    /**
     * Вспомогательный метод.
     * Возвращает агрегированную информацию по всем событиям для удобной проверки результата в demo
     * вместо поля 'name_id' из таблицы 'events' подставлено поле 'name' из таблицы 'events_name'
     * @return void
     */
    private function getAllEvents(): void
    {
        // Результирующий массив ответа
        $data = [];

        $query = "
        SELECT t1.id, t2.name, t1.auth, t1.ip, t1.date 
        FROM events t1 
        LEFT JOIN events_name t2 ON t1.name_id = t2.id;";

        $st = $this->connect->prepare($query);
        $st->execute();
        while ($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $row['id'] = (int) $row['id'];
            $row['auth'] = (int) $row['auth'];
            $data[] = $row;
        }
        $data = [
            'status' => 'success',
            'response' => $data,
        ];
        $this->response($data, 200);
    }

    /**
     * Функция получения и очистки переданных параметров
     * преобразует к переданному типу, если передан $type
     * @param $key
     * @param string $type
     * @return false|mixed|null
     */
    private function getClearParam($key, string $type = '')
    {
        $param = $this->getParam($key);
        if ($param) {
            if (is_array($param) && !empty($param)) {
                foreach($param as &$item) {
                    $item = htmlspecialchars(strip_tags($item));
                }
                $param = array_filter($param, 'strlen');
                $param = array_unique($param);
            } else {
                $param = htmlspecialchars(strip_tags($param));
            }

            // Если передан тип, приводим к нему
            if ($type) {
                // Если передан тип, то возвращаем точно не array
                if (is_array($param) && !empty($param)) {
                    $param = $param[0];
                }
                switch ($type) {
                    case 'string':
                        $param = (string) $param;
                        break;
                    case 'int':
                        $param = (int) $param;
                        break;
                }
            }
        }

        return $param;
    }

    /**
     * Функция извлечения переданных параметров
     * @param $key
     * @return false|mixed|null
     */
    private function getParam($key)
    {
        if ($key === false || !is_array($this->requestParams))
        {
            return null;
        }

        if (array_key_exists($key, $this->requestParams))
        {
            return $this->requestParams[$key];
        }
        return null;
    }

    /**
     * Возвращает название метода (action), который нужно запустить
     * @param string $action
     * @return string
     */
    private function getAction(string $action = ''): string
    {
        $action = explode('?', $action)[0];
        switch ($action) {
            case 'get':
                $method = 'getEvents';
                break;
            case 'getAll':
                $method = 'getAllEvents';
                break;
            case 'create':
                $method = 'createEvent';
                break;
            default:
                $method = '';
        }

        return $method;
    }

    /**
     * Отдает результат в виде json + код ответа
     * @param array $data
     * @param int $status
     * @param string $msg
     * @return void
     */
    private function response(array $data, int $status = 500, string $msg = ''): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Content-Type: application/json');
        header('HTTP/1.1 ' . $status . ' ' . $this->requestStatus($status) ?: $msg);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    /**
     * Отдает ответ об ошибке с кодом ошибке и телом
     * @param int $status
     * @param string $msg
     * @return void
     */
    private function responseError(int $status = 500, string $msg = ''): void
    {
        $this->response(
            [
                'status' => 'error',
                'error' => $msg,
            ],
            $status,
            $msg
        );
        die();
    }

    /**
     * Инициализирует соединение с БД
     * @return void
     */
    private function initConnection(): void
    {
        if (!$this->connect) {
            $db = new DB();
            $this->connect = $db->getConnect();
        }
    }

    /**
     * Возвращает статус запроса по коду
     * @param int $code
     * @return string
     */
    private function requestStatus(int $code): string
    {
        $status = array(
            200 => 'OK',
            201 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return $status[$code] ?? $status[500];
    }
}