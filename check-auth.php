<?php
header("Content-Type: application/json");

// 檢查請求參數
if (!isset($_GET['sessionId'])) {
    echo json_encode(['status' => 'error', 'message' => '缺少 sessionId 參數']);
    exit;
}

$sessionId = $_GET['sessionId'];
$fileName = 'sessions/' . $sessionId . '.json';

// 檢查授權檔案是否存在
if (file_exists($fileName)) {
    // 讀取授權資訊
    $authData = json_decode(file_get_contents($fileName), true);
    
    // 檢查授權狀態
    if (isset($authData['status']) && $authData['status'] === 'authorized') {
        // 檢查是否過期（15 分鐘）
        $expiryTime = $authData['timestamp'] + 900; // 15 分鐘 = 900 秒
        
        if (time() > $expiryTime) {
            // 授權已過期，刪除檔案
            unlink($fileName);
            echo json_encode(['status' => 'expired', 'message' => '授權已過期，請重新掃描']);
        } else {
            // 返回授權資訊
            echo json_encode([
                'status' => 'authorized',
                'googleToken' => $authData['googleToken'],
                'email' => $authData['email']
            ]);
            
            // 授權使用後刪除檔案（一次性使用）
            unlink($fileName);
        }
    } else {
        echo json_encode(['status' => 'waiting', 'message' => '等待授權中']);
    }
} else {
    echo json_encode(['status' => 'waiting', 'message' => '等待授權中']);
}
?>
