<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// 處理預檢請求
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// 檢查是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => '只接受 POST 請求']);
    exit;
}

// 獲取 POST 數據
$data = json_decode(file_get_contents("php://input"), true);

// 驗證必要的資料
if (!isset($data['sessionId']) || !isset($data['googleToken']) || !isset($data['email'])) {
    echo json_encode(['success' => false, 'message' => '缺少必要的資料']);
    exit;
}

// 生成存儲檔案名稱
$fileName = 'sessions/' . $data['sessionId'] . '.json';

// 確保目錄存在
if (!file_exists('sessions')) {
    mkdir('sessions', 0755, true);
}

// 保存授權資訊
$authData = [
    'status' => 'authorized',
    'googleToken' => $data['googleToken'],
    'email' => $data['email'],
    'timestamp' => time()
];

// 寫入檔案
if (file_put_contents($fileName, json_encode($authData))) {
    echo json_encode(['success' => true, 'message' => '授權資訊已接收']);
} else {
    echo json_encode(['success' => false, 'message' => '無法保存授權資訊']);
}
?>
