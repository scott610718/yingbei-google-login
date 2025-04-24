// token-server.js
const express = require('express');
const cors = require('cors');
const axios = require('axios');
const app = express();

app.use(cors());
app.use(express.json());

app.post('/token', async (req, res) => {
    try {
        const { code, client_id, redirect_uri } = req.body;
        const client_secret = process.env.GOOGLE_CLIENT_SECRET; // 從環境變數讀取
        
        const response = await axios.post('https://oauth2.googleapis.com/token', {
            code,
            client_id,
            client_secret,
            redirect_uri,
            grant_type: 'authorization_code'
        });
        
        res.json(response.data);
    } catch (error) {
        console.error('Token exchange error:', error.response?.data || error.message);
        res.status(500).json({ error: 'Failed to exchange token' });
    }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Token server running on port ${PORT}`));
