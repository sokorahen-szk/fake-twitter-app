<?php

namespace Src\Controller;

use Src\Common\Request;
use Src\Common\ViewManager;
use Src\Common\SessionManager;
use Src\Common\Validation;
use Src\RepoInterface\ITweetRepository;
use Src\RepoInterface\IFollowRepository;

class MainController extends BaseController
{
    /**
     * @var Src\RepoInterface\ITweetRepository
     */
    private $tweetRepository;

    /**
     * @var Src\RepoInterface\IFollowRepository
     */
    private $followRepository;

    /**
     * @var Src\Common\SessionManager
     */
    private $sessionManager;

    public function __construct(
        ITweetRepository $tweetRepository,
        IFollowRepository $followRepository,
        SessionManager $sessionManager
    ) {
        $this->sessionManager = $sessionManager;
        $this->followRepository = $followRepository;
        $this->tweetRepository = $tweetRepository;
    }

    /**
     * トップ画面
     *
     * @return void
     */
    public function index(Request $request)
    {
        $mode = null;
        if (Validation::validate(
            [
                "mode" => ["require", "alpha_number", "in:following"],
            ],
            (array) $request->get()
        )
        ) {
            $mode = @$request->input("mode");
        }

        if ($mode === "following" && $this->sessionManager->check()) {
            $followes = $this->followRepository->listByUserId($this->sessionManager->getUserId());
            $followUserIds = $followes->pluck("follow_user_id")->toArray();
            $tweets = $this->tweetRepository->listByUserIds($followUserIds);
        } else {
            // フォロー中ユーザから取得しない場合は、最新のツイートを複数件取得する。
            $tweets = $this->tweetRepository->list();
        }

        ViewManager::render(
            "index",
            [
                "myUserId" => $this->sessionManager->getUserId(),
                "isLogin" => $this->sessionManager->check(),
                "tweets" => $tweets->toArray(),
                "mode" => $mode,
            ]
        );
    }
}
