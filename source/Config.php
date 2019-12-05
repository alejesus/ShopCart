<?php
define('ROOT', __DIR__);
define('HOME', 'urlsite');
define('VERIFING_PURCHASE_FLOW', true);

define('CODIGO_CONTRATO_CORREIOS', '08082650');
define('SENHA_CONTRATO_CORREIOS', '564321');
define('API_SHIPPING_URL', 'http://ws.correios.com.br/calculador/');
define('API_SHIPPING_KEY', ['nCdEmpresa' => CODIGO_CONTRATO_CORREIOS, 'sDsSenha' => SENHA_CONTRATO_CORREIOS]);
define('API_SHIPPING_ENDPOINT_PRECO_PRAZO', 'CalcPrecoPrazo.aspx');
define('API_SHIPPING_ENDPOINT_PRECO', 'CalcPreco.aspx');
define('API_SHIPPING_ENDPOINT_PRAZO', 'CalcPrazo.aspx');

define('ENV_PAGSEGURO', 'sandbox'); // Ambiente

if (ENV_PAGSEGURO == 'sandbox') {

    define('API_PAYMENT_URL', 'https://ws.sandbox.pagseguro.uol.com.br/');
    define('EMAIL_PAGSEGURO', 'your_email');
    define('TOKEN_PAGSEGURO', 'your_token_sandbox');
    define('URL_DIRECTPAYMENT_PAGSEGURO', 'https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js');

} else {

    define('API_PAYMENT_URL', 'https://ws.pagseguro.uol.com.br/');
    define('EMAIL_PAGSEGURO', 'your_email');
    define('TOKEN_PAGSEGURO', 'your_token_production');
    define('URL_DIRECTPAYMENT_PAGSEGURO', 'https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js');

}

/** Headers **/
define('HEADER_X', 'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1');
define('HEADER_XML', 'Content-Type: application/xml; charset=ISO-8859-1');

/** Keys **/
define('API_PAYMENT_KEY', ['email' => EMAIL_PAGSEGURO, 'token' => TOKEN_PAGSEGURO]);

/** Endpoints **/
define('API_PAYMENT_ENDPOINT_SESSIONS', 'v2/sessions');
define('API_PAYMENT_ENDPOINT_TRANSACTIONS', 'v2/transactions');
define('API_PAYMENT_ENDPOINT_TRANSACTIONS_V3', 'v3/transactions');
define('API_PAYMENT_ENDPOINT_CANCELS', 'v2/transactions/cancels');
define('API_PAYMENT_ENDPOINT_REFUNDS', 'v2/transactions/refunds');
define('API_PAYMENT_ENDPOINT_NOTIFICATIONS', 'v3/transactions/notifications');

define('NO_INTEREST_INSTALLMENTS', '4');

define('SHIPMENT_TYPE', [
    'PAC'   => 1,
    'SEDEX' => 2,
]);

/** MySQL BD **/

define('INFO_BD', [
    'driver'     => 'mysql',
    'host'       => 'localhost',
    'port'       => '3306',
    'dbname'     => 'test',
    'user'       => 'root',
    'pass'       => '',
    'attributes' => [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_CASE               => PDO::CASE_NATURAL,
    ],
]);

function asset(string $path): string
{
    return HOME . "/themes/assets/{$path}";
}

function path(string $path): string
{
    return dirname(ROOT, 1) . "/themes/assets/{$path}";
}
