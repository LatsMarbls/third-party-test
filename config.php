<?php
$env = file_exists(__DIR__ . '/.env') ? parse_ini_file(__DIR__ . '/.env') : [];

$w3p_id   = $env['W3P_ID'] ?? getenv('W3P_ID') ?: '15082683';
$w3p_key  = $env['W3P_KEY'] ?? getenv('W3P_KEY') ?: '12345';
$wsdl     = $env['SOAP_WSDL'] ?? getenv('SOAP_WSDL') ?: 'http://statara2.alliancewebpos.net/appserv/app/w3p/w3p.wsdl';
$endpoint = $env['SOAP_ENDPOINT'] ?? getenv('SOAP_ENDPOINT') ?: 'http://statara2.alliancewebpos.net/appserv/app/w3p/W3PSoapServer.php';
