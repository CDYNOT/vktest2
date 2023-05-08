<?php
/**
 * @var array $eventsNameList
 * @var array $eventsDateList
 * @var array $eventsIpList
 */

// Константа для подключения базы данных,
// для наполнения справочников актуальными значениями из бд
define('CORE_PATH', dirname(__FILE__) . '/api/core/');

// Вспомогательные функции для заполнения форм исходными данными из таблиц
require_once 'demo/functions.php';

// Графический интерфейс для тестирования приложения
?>
<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>Тест API - Новиков Антон Олегович @CDYNOT</title>

    <link rel="stylesheet" href="demo/css/styles.css">
</head>
<body>
    <div class="wrap">
        <div class="cont">
            <div class="api">
                <div class="block_title">Демонстрация работы API</div>

                <div class="box flex">

                    <div class="data">
                        <div class="controls flex" id="controls">
                            <div class="loading"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                            <div class="post">
                                <div class="subtitle">Добавить запись</div>

                                <form class="form" id="create">
                                    <div class="line">
                                        <label for="event2">Выберите событие</label>
                                        <div class="field">
                                            <select id="event2" name="event" class="select" required>
                                                <?php foreach($eventsNameList as $value): ?>
                                                    <option value="<?= $value ?>"><?= $value ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="line">
                                        <label for="ip2">Кастомный ip</label>
                                        <div class="field">
                                            <input type="text" class="input" name="ip" id="ip2" value="" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="xxx.xxx.xxx.xxx">
                                        </div>
                                    </div>

                                    <div class="line">
                                        <div class="field">
                                            <input type="checkbox" name="auth" id="auth2" value="1">
                                            <label for="auth2">Пользователь авторизован</label>
                                        </div>
                                    </div>

                                    <div class="submit flex">
                                        <button type="button" class="submit_btn add" onclick="createEventFunc()">Добавить запись</button>
                                    </div>

                                    <div class="submit flex">
                                        <button type="button" class="submit_btn" onclick="getAllEventsFunc()">Посмотреть все записи</button>
                                    </div>

                                    <div class="message">
                                        Для удобства, выводятся все записи из таблицы <strong>events</strong>, где вместо <strong>name_id</strong> подставлен <strong>name</strong> из таблицы <strong>events_name</strong><br>
                                        Структуры таблиц представлены ниже
                                    </div>
                                </form>
                            </div>


                            <div class="get">
                                <div class="subtitle">Получить данные</div>

                                <form class="form" id="get">
                                    <div class="line">
                                        <label for="event">Выберите событие(я)</label>
                                        <div class="field">
                                            <select id="event" name="event[]" class="select scroll" multiple required>
                                                <?php foreach($eventsNameList as $value): ?>
                                                    <option value="<?= $value ?>"><?= $value ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="line">
                                        <label for="date">Выберите дату</label>
                                        <div class="field">
                                            <select id="date" name="date" class="select">
                                                <option value="" selected>--Выберите дату--</option>
                                                <?php foreach($eventsDateList as $value): ?>
                                                    <option value="<?= $value ?>"><?= $value ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="line">
                                        <label for="ip">Выберите ip</label>
                                        <div class="field">
                                            <select id="ip" name="ip" class="select">
                                                <option value="" selected>--Выберите ip--</option>
                                                <?php foreach($eventsIpList as $value): ?>
                                                    <option value="<?= $value ?>"><?= $value ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="line">
                                        <div class="field">
                                            <input type="checkbox" name="auth" id="auth" value="1">
                                            <label for="auth">Пользователь авторизован</label>
                                        </div>
                                    </div>

                                    <div class="submit flex">
                                        <button type="button" class="submit_btn" onclick="getEventsFunc()">Получить</button>
                                    </div>
                                </form>
                            </div>


                            <div class="text">
                                Структура таблицы <span>events_name</span>: <strong>id (primary), name</strong><br>
                                Структура таблицы <span>events</span>: <strong>id (primary), name_id (foreign), auth, ip, date</strong>
                            </div>
                        </div>

                    </div>

                    <div class="data">
                        <div class="result">
                            <div class="subtitle">Результат запроса к API<span id="send_event"></span></div>
                            <textarea class="response scroll" id="response" placeholder="Здесь будет выведен ответ сервера в json" readonly></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="demo/js/scripts.js"></script>
</body>
</html>



