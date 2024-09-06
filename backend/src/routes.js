const express = require('express');
const routes = express.Router();

routes.post('/login', (req, res) => {
    const {email, password} = req.body;
    res.send('Login endpoint');
});

module.exports = routes;