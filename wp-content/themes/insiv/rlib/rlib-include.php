<?php

 
         require_once('recurly.php');
    
    // Required for the API
        Recurly_Client::$subdomain = 'insiv';
        Recurly_Client::$apiKey = 'f953526bdd7340b785b5d8c293e20e28';

        // Optional for Recurly.js:
        Recurly_js::$privateKey = '3ae450e02a6841e8a1cc69a6b3dd7a08';
?>
