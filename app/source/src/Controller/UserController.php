<?php

namespace Src\Controller;

use Src\RepoInterface\IUserRepository;
use Src\RepoInterface\ITweetRepository;
use Src\Common\Request;
use Src\Common\SessionManager;
use Src\Common\ViewManager;
use Src\Common\Validation;
use Src\Exception\AlreadyLoginException;

class UserController extends BaseController
{
    /**
     * @var Src\RepoInterface\IUserRepository
     */
    private $userRepository;
    /**
     * @var Src\RepoInterface\ITweetRepository
     */
    private $tweetRepository;
    /**
     * @var Src\Common\SessionManager
     */
    private $sessionManager;
    public function __construct(
        IUserRepository $userRepository,
        ITweetRepository $tweetRepository,
        SessionManager $sessionManager
    ) {
        $this->userRepository = $userRepository;
        $this->tweetRepository = $tweetRepository;
        $this->sessionManager = $sessionManager;
    }

    /**
     * ログイン画面
     *
     * @return void
     */
    public function login()
    {
        // 既にログインしている場合、トップ画面に遷移
        if ($this->sessionManager->check()) {
            header("Location: /");
        }

        ViewManager::render("login");
    }

    /**
     * ログイン処理
     *
     * @param Src\Common\Request $request
     * @return void
     */
    public function postLogin(Request $request)
    {
        try {
            if (!Validation::validate(
                [
                    "id" => ["require", "max:16", "alpha_number"],
                    "password" => ["require"],
                ],
                (array) $request->post()
            )
            ) {
                return ViewManager::render("login", Validation::errors());
            }

            $user = $this->userRepository->find($request->input("id", "POST"));

            if (!$user || !password_verify($request->input("password", "POST"), $user->password)) {
                return ViewManager::render("login", ["error_message" => "ログインに失敗しました。ユーザ名とパスワードをご確認ください。"]);
            }

            $this->sessionManager->create($user->id, $user->name);

            header("Location: /");
        } catch (AlreadyLoginException | LoginFailedException $e) {
            ViewManager::render("error", ["error_message" => $e->getMessage()]);
        } catch (\Exception $e) {
            ViewManager::render("error", ["error_message" => "サーバーエラー"]);
        }
    }

    /**
     * ログアウト処理
     *
     * @return void
     */
    public function getLogout()
    {
        // ログインしていない場合、ログイン画面に遷移
        if (!$this->sessionManager->check()) {
            header("Location: /login");
        }

        $result = $this->sessionManager->destroy();
        // ログアウトが正常にできた場合、トップ画面に遷移
        if ($result) {
            header("Location: /");
        }
    }

    /**
     * 新規作成画面
     *
     * @return void
     */
    public function register()
    {
        // 既にログインしている場合、トップ画面に遷移
        if ($this->sessionManager->check()) {
            header("Location: /");
        }

        ViewManager::render("register");
    }

    /**
     * 新規作成画面
     *
     * @param Src\Common\Request $request
     * @return void
     */
    public function postRegister(Request $request)
    {
        // 既にログインしている場合、トップ画面に遷移
        if ($this->sessionManager->check()) {
            header("Location: /");
        }

        if (!Validation::validate(
            [
                "id" => ["require", "max:16", "alpha_number"],
                "name" => ["require", "max:20"],
                "password" => ["require", "min:8"],
                "password_confirm" => ["require", "re_password:password"],
            ],
            (array) $request->post()
        )
        ) {
            return ViewManager::render("register", Validation::errors());
        }

        // NOTE: 既に登録されているユーザかどうかチェックする
        // DDDだと、Service上で既に登録されているかチェックさせるけど、今回の課題だとMVCにフォーカスしているため、
        // Repositoryにその責務を持たせている。
        $findUser = $this->userRepository->find($request->input("id", "POST"));
        if ($findUser) {
            return ViewManager::render("register", ["error_message" => "既に登録されているユーザIDです。"]);
        }

        $encryptedPassword = password_hash($request->input("password", "POST"), PASSWORD_DEFAULT);
        $createdUser = $this->userRepository->create(
            $request->input("id", "POST"),
            $request->input("name", "POST"),
            $encryptedPassword
        );

        // アカウント新規作成後はログイン済みとするため、セッションに書き込む
        $this->sessionManager->create($createdUser->id, $createdUser->name);

        header("Location: /");
    }

    /**
     * ユーザプロフィール画面
     *
     * @param string $id
     * @return void
     */
    public function profile(string $id)
    {
        if (!Validation::validate(
            [
                "id" => ["alpha_number"]
            ],
            [
                "id" => $id
            ]
        )) {
            return ViewManager::render("error", ["error_message" => "ページが見つかりません。"]);
        }

        $findUser = $this->userRepository->find($id);
        if (!$findUser) {
            return ViewManager::render("error", ["error_message" => "ページが見つかりません。"]);
        }

        $tweets = $this->tweetRepository->listByUserId($findUser->id);

        ViewManager::render("profile", [
            "myUserId" => $this->sessionManager->getUserId(),
            "userId" => $findUser->id,
            "userName" => $findUser->name,
            "isLogin" => $this->sessionManager->check(),
            "tweets" => $tweets,
        ]);
    }
}
