<?php

require_once(__DIR__ . "/source/vendor/autoload.php");

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

use Dotenv\Dotenv;
use Bramus\Router\Router;

use Src\Controller\UserController;
use Src\Controller\FollowController;
use Src\Controller\TweetController;
use Src\Controller\MainController;
use Src\Common\Request;
use Src\Common\SessionManager;
use Src\Repository\FollowRepository;
use Src\Repository\UserRepository;
use Src\Repository\TweetRepository;

$dotenv = Dotenv::createImmutable(__DIR__ . "/source");
$dotenv->load();

$capsule = new Capsule();
$capsule->addConnection([
    "driver"    => "mysql",
    "host"      => $_ENV['DB_HOST'],
    "database"  => $_ENV['DB_NAME'],
    "username"  => $_ENV['DB_USER_NAME'],
    "password"  => $_ENV['DB_USER_PASSWORD'],
    "charset"   => "utf8",
    "collation" => "utf8_unicode_ci",
    "prefix"    => "",
]);

$capsule->setEventDispatcher(new Dispatcher(new Container()));
$capsule->setAsGlobal();
$capsule->bootEloquent();

$tweetRepository = new TweetRepository();
$userRepository = new UserRepository();
$followRepository = new FollowRepository();
$sessionManager = new SessionManager();

$mainController = new MainController($tweetRepository, $followRepository, $sessionManager);
$userController = new UserController($userRepository, $tweetRepository, $sessionManager);
$tweetController = new TweetController($tweetRepository, $sessionManager);
$followController = new FollowController($followRepository, $sessionManager);

$request = new Request();
$router = new Router();

// TODO:
// * Rest API を意識したパス設計する
// * 画面用のコントローラーや、WebAPI用のコントローラーは分ける
//
$router->get("/", function () use ($mainController, $request) {
    $mainController->index($request);
});

$router->get("/login", function () use ($userController) {
    $userController->login();
});

$router->post("/login", function () use ($userController, $request) {
    $userController->postLogin($request);
});

$router->get("/logout", function () use ($userController) {
    $userController->getLogout();
});

$router->get("/register", function () use ($userController) {
    $userController->register();
});

$router->post("/register", function () use ($userController, $request) {
    $userController->postRegister($request);
});

$router->get("/profile/([0-9a-zA-Z]+)/", function ($userName) use ($userController) {
    $userController->profile($userName);
});

$router->post("/tweet", function () use ($tweetController, $request) {
    $tweetController->postTweet($request);
});

$router->get("/follow/([0-9a-zA-Z]+)/", function ($followUserId) use ($followController) {
    $followController->getFollow($followUserId);
});

$router->post("/follow/([0-9a-zA-Z]+)/", function ($followUserId) use ($followController) {
    $followController->postFollow($followUserId);
});

$router->get("/follow/([0-9a-zA-Z]+)/analytics", function ($userId) use ($followController) {
    $followController->getAnalytics($userId);
});

$router->post("/unfollow/([0-9a-zA-Z]+)/", function ($followedUserId) use ($followController) {
    $followController->postUnfollow($followedUserId);
});

$router->run();

// グローバル関数
function h(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}
