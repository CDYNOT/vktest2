<?php
require_once CORE_PATH . 'config/config.php';
require_once CORE_PATH . 'classes/Api.php';

try {
    $api = new Api();
    $api->run();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}