<?php

/**
 * Класс реализации подключения к базе данных через PDO с драйвером mysql
 * настройки подключения вынесены в ../classes/config.php
 */
class DB
{
    // Хост
    private $host = DB_HOST;

    // Имя базы данных
    private $db_name = DB_NAME;

    // Пользователь БД
    private $username = DB_USER;

    // Пароль БД
    private $password = DB_PASSWORD;

    // Соединение
    public $connect;

    /**
     * Создает и возвращает соединение с БД
     * @return PDO
     */
    public function getConnect(): PDO
    {
        $this->connect = null;

        try {
            $this->connect = new PDO(
                "mysql:dbname={$this->db_name};host={$this->host}",
                $this->username,
                $this->password,
            );
        } catch (PDOException $e) {
            echo "Ошибка подключения к базе данных: {$e->getMessage()}";
        }

        return $this->connect;
    }
}