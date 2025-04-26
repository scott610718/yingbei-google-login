# yingbei-google-login
# Google 一鍵登入系統

本專案實現了一個跨裝置的 Google 一鍵登入系統，透過手機掃描 QR Code 的方式，讓使用者能夠在電腦上快速登入 Google 帳號，無需手動輸入密碼。

## 功能特點

- 電腦端產生 QR Code
- 手機端掃描 QR Code
- 手機端選擇 Google 帳號登入
- 電腦端自動完成 Google 登入
- 支援一鍵開啟 Google 服務（已登入狀態）

## 系統架構

系統由兩個主要部分組成：

1. **電腦端 (desktop.html)**：
   - 生成 QR Code
   - 顯示登入狀態
   - 接收手機端傳來的 Google 憑證
   - 實現 Google 自動登入
   - 提供一鍵開啟 Google 服務功能

2. **手機端 (mobile.html)**：
   - 掃描 QR Code
   - 觸發 Google 登入流程
   - 將登入憑證傳送到電腦端

## 使用說明

### 電腦端設置

1. 開啟 `desktop.html`
2. 頁面將自動生成一個 QR Code
3. 等待手機端掃描

### 手機端設置

1. 開啟 `mobile.html`
2. 點擊「開始掃描」按鈕
3. 掃描電腦端顯示的 QR Code
4. 選擇要登入的 Google 帳號
5. 授權完成後，電腦端將自動登入

### 登入後使用

1. 電腦端顯示登入成功訊息
2. 點擊「開啟 Google」按鈕以已登入狀態開啟 Google 服務
3. 若要登出，點擊「登出」按鈕

## 技術實現

- **QR Code 生成與掃描**：使用 `qrcode-generator` 與 `html5-qrcode` 函式庫
- **資料傳輸**：透過 Firebase Realtime Database
- **身份驗證**：使用 Google Identity Services API
- **安全機制**：使用 JWT 令牌、臨時會話 ID 和 Firebase 安全規則

## 安裝與部署

1. 下載 `desktop.html` 和 `mobile.html` 檔案
2. 設置 Firebase 項目：
   - 創建 Firebase 項目
   - 啟用 Realtime Database
   - 設置 Firebase 安全規則
   - 將 Firebase 配置更新到兩個 HTML 檔案中
3. 設置 Google Cloud 項目：
   - 創建 OAuth 2.0 客戶端 ID
   - 設置授權的 JavaScript 來源
   - 將客戶端 ID 更新到兩個 HTML 檔案中

## 注意事項

- 本系統需要互聯網連接
- 手機端和電腦端需要能夠訪問 Firebase 和 Google API
- 系統使用 Firebase 進行通信，請確保 Firebase 配置正確
- 為保障安全，每次登入後會自動清除 Firebase 中的臨時數據

## 安全性考量

- 使用臨時會話 ID 確保每次登入的唯一性
- 僅傳輸必要的 Google 憑證，不存儲其他敏感資訊
- 使用 Firebase 安全規則限制數據訪問
- 登入成功後立即刪除 Firebase 中的臨時數據

## 常見問題

**Q: 為什麼我的 QR Code 掃描不成功？**  
A: 請確保手機的攝像頭權限已開啟，並且 QR Code 可以清晰拍攝。

**Q: 為什麼登入後開啟 Google 仍然顯示未登入？**  
A: 可能是因為瀏覽器的第三方 Cookie 設置問題。請確保你的瀏覽器允許第三方 Cookie。

**Q: 如何處理登入失敗的情況？**  
A: 請點擊「顯示除錯資訊」按鈕查看詳細錯誤訊息，然後重新掃描 QR Code 嘗試登入。

## 未來改進

- 增加登入過期時間控制
- 支援多設備同時登入管理
- 增加更多 Google 服務的直接開啟選項
- 優化使用者介面和體驗
- 增加錯誤處理和重試機制
