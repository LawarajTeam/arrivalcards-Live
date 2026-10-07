<?php
/**
 * Realtime visitor heartbeat + live count.
 * Called every 30s by the footer on each open page; returns real data only.
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/analytics_functions.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

// Release the session lock early (heartbeat only needs to read/set the visitor id)
recordVisitorHeartbeat();
session_write_close();

$online = getOnlineVisitors();
if ($online === null) {
    http_response_code(503);
    echo json_encode(['success' => false]);
    exit;
}

echo json_encode(['success' => true, 'online' => $online]);
