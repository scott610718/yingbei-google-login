// 這是需要部署到 Firebase Cloud Functions 的代碼

const functions = require('firebase-functions');
const admin = require('firebase-admin');
const { google } = require('googleapis');
const axios = require('axios');

admin.initializeApp();

// 配置 OAuth 客戶端
const oauth2Client = new google.auth.OAuth2(
    process.env.GOOGLE_CLIENT_ID,
    process.env.GOOGLE_CLIENT_SECRET,
    process.env.REDIRECT_URI
);

// 交換授權碼獲取令牌
exports.exchangeAuthCode = functions.https.onCall(async (data, context) => {
    try {
        const { authCode } = data;
        
        if (!authCode) {
            throw new Error('未提供授權碼');
        }
        
        // 交換授權碼獲取令牌
        const tokenResponse = await oauth2Client.getToken(authCode);
        const tokens = tokenResponse.tokens;
        
        // 驗證 ID 令牌
        const ticket = await oauth2Client.verifyIdToken({
            idToken: tokens.id_token,
            audience: process.env.GOOGLE_CLIENT_ID
        });
        
        const payload = ticket.getPayload();
        const userId = payload['sub'];
        const email = payload['email'];
        const name = payload['name'] || '';
        const picture = payload['picture'] || '';
        
        // 創建自定義令牌用於 Firebase Auth 登入
        const customToken = await admin.auth().createCustomToken(userId, {
            email,
            name,
            picture
        });
        
        return {
            success: true,
            idToken: tokens.id_token,
            customToken: customToken,
            user: {
                id: userId,
                email,
                name,
                picture
            }
        };
    } catch (error) {
        console.error('授權碼交換錯誤:', error);
        
        return {
            success: false,
            error: error.message || '授權碼交換失敗'
        };
    }
});
