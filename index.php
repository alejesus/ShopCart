<?php

require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('America/Cuiaba');

session_start();

use Source\core\Router;

$router = new Router();

/**
 * WEB
 * Web
 */
$router->group(null);
$router->get("/", "Web:home");
$router->get("/contact", "Web:contact");
$router->get("/about", "Web:about");

/**
 * ORDER
 * Order
 */
$router->group('/order');
$router->get("/", "WebOrder:home");
$router->get("/login", "WebOrder:login");
$router->get("/login/register", "WebOrder:register");
$router->get("/login/recover", "WebOrder:recover");


$router->get("/address", "WebOrder:address");
$router->get("/payment", "WebOrder:payment");
$router->get("/confirmation", "WebOrder:confirmation");

$router->post("/showorder", "WebOrder:showOrder");
$router->post("/verifyidentification", "WebOrder:checkAccessIdentification");

/**
 * CART
 * WebCart
 */
$router->group('/cart');
$router->post("/", "WebCart:cart");
$router->post("/add/{id}", "WebCart:add");
$router->post("/remove/{id}", "WebCart:remove");
$router->post("/clear", "WebCart:clear");
$router->post("/finishCart", "WebCart:finishCart");

/**
 * IDENTIFICATION
 * Identification
 */
$router->group('/identification');
$router->post("/showidentification", "WebIdentification:showIdentification");
$router->post("/signin", "WebIdentification:signIn");
$router->post("/signup", "WebIdentification:signUp");
$router->post("/recover", "WebIdentification:forgetLogin");

/**
 * ADDRESS
 * Address
 */
$router->group('/address');
$router->post("/showaddress", "WebAddress:showSession");
$router->post("/add", "WebAddress:add");

/**
 * PAYMENT
 * Payment
 */
$router->group('/payment');
$router->post("/session", "WebPayment:showSession");
$router->post("/createsession", "WebPayment:initSessionPay");
$router->post("/billet", "WebPayment:withBillet");
$router->post("/onlinedebit", "WebPayment:withOnlineDebt");
$router->post("/creditcard", "WebPayment:withCreditCard");

/**
 * CONFIRMATION
 * Confirmation
 */
$router->group('/confirmation');
$router->post("/clear", "WebConfirmation:clearOrder");

/**
 * DISPATCH
 */

$router->dispatch();

if ($router->error()) {
    echo $router->error();
}
