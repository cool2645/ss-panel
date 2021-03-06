<?php

use App\Controllers;
use App\Middleware\Admin;
use App\Middleware\Api;
use App\Middleware\Auth;
use App\Middleware\Guest;
use App\Middleware\Mu;
use App\Middleware\reCaptcha;
use Slim\App;
use Zeuxisoo\Whoops\Provider\Slim\WhoopsMiddleware;

/***
 * The slim documents: http://www.slimframework.com/docs/objects/router.html
 */

// config
$debug = false;
if (defined("DEBUG")) {
    $debug = true;
}

// Make a Slim App
// $app = new App($c)
$app = new App([
    'settings' => [
        'debug' => $debug,
        'whoops.editor' => 'sublime'
    ]
]);
$app->add(new WhoopsMiddleware);

//$this->get('/pay/paypal', 'App\Controllers\PaymentController:paypal');
$app->post('/pay/eapay/async', 'App\Controllers\PaymentController:eapay_callback');
$app->get('/reCaptcha', 'App\Controllers\HomeController:reCaptcha');
$app->post('/reCaptcha', 'App\Controllers\HomeController:handleReCaptcha');

// Home
$app->group(null, function () {
    $this->get('/', 'App\Controllers\HomeController:newIndex');
    //$this->get('/', 'App\Controllers\HomeController:index');
    //$this->get('/code', 'App\Controllers\HomeController:code');
    //$this->get('/tos', 'App\Controllers\HomeController:tos');
    //$this->get('/scs', 'App\Controllers\HomeController:scs');
    //$this->get('/start', 'App\Controllers\HomeController:start');
    //$this->get('/node', 'App\Controllers\HomeController:node');
    //$this->get('/client', 'App\Controllers\HomeController:client');
    $this->get('/debug', 'App\Controllers\HomeController:debug');
    $this->post('/debug', 'App\Controllers\HomeController:postDebug');
})->add(new reCaptcha);

// User Center
$app->group('/user', function () {
    $this->get('', 'App\Controllers\UserController:index');
    $this->get('/', 'App\Controllers\UserController:index');
    $this->post('/checkin', 'App\Controllers\UserController:doCheckin');
    $this->get('/node', 'App\Controllers\UserController:node');
    $this->get('/node6/node', 'App\Controllers\UserController:node6');
    $this->get('/node/{id}', 'App\Controllers\UserController:nodeInfo');
    $this->get('/node6/node/{id}', 'App\Controllers\UserController:nodeInfo6');
    $this->get('/profile', 'App\Controllers\UserController:profile');
    $this->get('/invite', 'App\Controllers\UserController:invite');
    $this->post('/invite', 'App\Controllers\UserController:doInvite');
    $this->get('/edit', 'App\Controllers\UserController:edit');
    $this->post('/password', 'App\Controllers\UserController:updatePassword');
    $this->post('/sspwd', 'App\Controllers\UserController:updateSsPwd');
    $this->post('/method', 'App\Controllers\UserController:updateMethod');
    $this->post('/protocol', 'App\Controllers\UserController:updateProtocol');
    $this->post('/protocol-param', 'App\Controllers\UserController:updateProtocolParam');
    $this->post('/obfs', 'App\Controllers\UserController:updateObfs');
    $this->post('/obfs-param', 'App\Controllers\UserController:updateObfsParam');
    $this->post('/v2ray-uuid', 'App\Controllers\UserController:updateV2rayUUID');
    $this->post('/v2ray-alterid', 'App\Controllers\UserController:updateV2rayAlterID');
    $this->get('/sys', 'App\Controllers\UserController:sys');
    $this->get('/trafficlog', 'App\Controllers\UserController:trafficLog');
    $this->get('/kill', 'App\Controllers\UserController:kill');
    $this->post('/kill', 'App\Controllers\UserController:handleKill');
    $this->post('/freeze', 'App\Controllers\UserController:freeze');
    $this->get('/payment', 'App\Controllers\UserController:payment');
    //$this->post('/payment/eapay/mo', 'App\Controllers\PaymentController:newMonthTrans');
    //$this->post('/payment/eapay/da', 'App\Controllers\PaymentController:newDataTrans');
    $this->get('/logout', 'App\Controllers\UserController:logout');
})->add(new Auth());

// Auth
$app->group('/auth', function () {
    $this->get('/login', 'App\Controllers\AuthController:login');
    $this->post('/login', 'App\Controllers\AuthController:loginHandle');
    $this->get('/register', 'App\Controllers\AuthController:register');
    $this->post('/register', 'App\Controllers\AuthController:registerHandle');
    $this->post('/sendcode', 'App\Controllers\AuthController:sendVerifyEmail');
    $this->get('/logout', 'App\Controllers\AuthController:logout');
})->add(new Guest())->add(new reCaptcha);

// Password
$app->group('/password', function () {
    $this->get('/reset', 'App\Controllers\PasswordController:reset');
    $this->post('/reset', 'App\Controllers\PasswordController:handleReset');
    $this->get('/token/{token}', 'App\Controllers\PasswordController:token');
    $this->post('/token/{token}', 'App\Controllers\PasswordController:handleToken');
})->add(new Guest())->add(new reCaptcha);

// Admin
$app->group('/admin', function () {
    $this->get('', 'App\Controllers\AdminController:index');
    $this->get('/', 'App\Controllers\AdminController:index');
    $this->get('/sys', 'App\Controllers\AdminController:sysinfo');
    $this->get('/trafficlog', 'App\Controllers\AdminController:trafficLog');
    $this->get('/checkinlog', 'App\Controllers\AdminController:checkinLog');
    $this->post('/cleannodelog', 'App\Controllers\AdminController:cleanNodelog');
    $this->post('/cleanonlinelog', 'App\Controllers\AdminController:cleanOnlinelog');
    $this->post('/cleantrafficlog', 'App\Controllers\AdminController:cleantrafficlog');
    // app config
    $this->get('/config', 'App\Controllers\AdminController:config');
    $this->put('/config', 'App\Controllers\AdminController:updateConfig');
    // Node Mange
    $this->get('/node', 'App\Controllers\Admin\NodeController:index');
    $this->get('/node/create', 'App\Controllers\Admin\NodeController:create');
    $this->post('/node', 'App\Controllers\Admin\NodeController:add');
    $this->get('/node/{id}/edit', 'App\Controllers\Admin\NodeController:edit');
    $this->put('/node/{id}', 'App\Controllers\Admin\NodeController:update');
    $this->delete('/node/{id}', 'App\Controllers\Admin\NodeController:delete');
//    $this->get('/node/{id}/delete', 'App\Controllers\Admin\NodeController:deleteGet');

    // User Mange
    $this->get('/user', 'App\Controllers\Admin\UserController:index');
    $this->get('/user/{id}/edit', 'App\Controllers\Admin\UserController:edit');
    $this->put('/user/{id}', 'App\Controllers\Admin\UserController:update');
    $this->patch('/user/{id}/v2ray-uuid', 'App\Controllers\Admin\UserController:updateV2rayUUID');
    $this->put('/user/{id}/prolong', 'App\Controllers\Admin\UserController:extendPayment');
    $this->delete('/user/{id}', 'App\Controllers\Admin\UserController:delete');
    $this->get('/user/{id}/delete', 'App\Controllers\Admin\UserController:deleteGet');

    // Test
    $this->get('/test/sendmail', 'App\Controllers\Admin\TestController:sendMail');
    $this->post('/test/sendmail', 'App\Controllers\Admin\TestController:sendMailPost');

    $this->get('/sendmail', 'App\Controllers\AdminController:sendMail');
    $this->post('/sendmail', 'App\Controllers\AdminController:sendMailPost');

    $this->get('/profile', 'App\Controllers\AdminController:profile');
    $this->get('/invite', 'App\Controllers\AdminController:invite');
    $this->post('/invite', 'App\Controllers\AdminController:addInvite');
    $this->get('/logout', 'App\Controllers\AdminController:logout');
})->add(new Admin());

// Ping
$app->group('/ping', function () {
    $this->get('', 'App\Controllers\PingController:index');
    $this->get('/', 'App\Controllers\PingController:index');
    $this->post('/launch', 'App\Controllers\PingController:launch');
    $this->get('/status', 'App\Controllers\PingController:status');
    $this->post('/status', 'App\Controllers\PingController:status_proxy');
})->add(new Auth());

// res
$app->group('/ping', function () {
    $this->get('/captcha/{id}', 'App\Controllers\ResController:captcha');
});

// API
$app->group('/api', function () {
    $this->get('/token/{token}', 'App\Controllers\ApiController:token');
    $this->post('/token', 'App\Controllers\ApiController:newToken');
    $this->get('/node', 'App\Controllers\ApiController:node')->add(new Api());
    $this->get('/user/{id}', 'App\Controllers\ApiController:userInfo')->add(new Api());
    $this->get('/do', 'App\Controllers\ApiController:maintainPayment');
    $this->get('/recycle', 'App\Controllers\ApiController:cleanInactiveUsers');
    $this->get('/sendmail', 'App\Controllers\ApiController:sendReminderMail');
});

// mu
$app->group('/mu', function () {
    $this->get('/users', 'App\Controllers\Mu\UserController:index');
    $this->post('/users/{id}/traffic', 'App\Controllers\Mu\UserController:addTraffic');
    $this->post('/nodes/{id}/online_count', 'App\Controllers\Mu\NodeController:onlineUserLog');
    $this->post('/nodes/{id}/info', 'App\Controllers\Mu\NodeController:info');
})->add(new Mu());

// mu
$app->group('/mu/v2', function () {
    $this->get('/users', 'App\Controllers\MuV2\UserController:index');
    $this->post('/users/{id}/traffic', 'App\Controllers\MuV2\UserController:addTraffic');
    $this->get('/nodes/{id}/users', 'App\Controllers\MuV2\NodeController:users');
    $this->get('/nodes/{id}/v2rayUsers', 'App\Controllers\MuV2\NodeController:v2rayUsers');
    $this->post('/nodes/{id}/online_count', 'App\Controllers\MuV2\NodeController:onlineUserLog');
    $this->post('/nodes/{id}/info', 'App\Controllers\MuV2\NodeController:info');
    $this->post('/nodes/{id}/traffic', 'App\Controllers\MuV2\NodeController:postTraffic');
})->add(new Mu());

// res
$app->group('/res', function () {
    $this->get('/captcha/{id}', 'App\Controllers\ResController:captcha');
});

return $app;


