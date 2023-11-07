<?php

namespace Src\Controller;

use Src\Common\Request;
use Src\Common\JsonResponseManager;
use Src\Common\SessionManager;
use Src\Common\Validation;
use Src\RepoInterface\ITweetRepository;
use Carbon\Carbon;

class TweetController extends BaseController
{
    /**
     * @var Src\Common\SessionManager
     */
    private $sessionManager;

    /**
     * @var Src\RepoInterface\ITweetRepository
     */
    private $tweetRepository;

    public function __construct(ITweetRepository $tweetRepository, SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
        $this->tweetRepository = $tweetRepository;
    }

    /**
     * ツイートAPI
     *
     * @param Src\Common\Request $request
     * @return void
     */
    public function postTweet(Request $request)
    {
        // ログインしていない場合
        if (!$this->sessionManager->check()) {
            JsonResponseManager::response(403, ["ログインされていません。"]);
        }

        if (!Validation::validate(
            [
                "message" => ["max:120"]
            ],
            (array) $request->post()
        )
        ) {
            JsonResponseManager::response(400, Validation::errors());
        }

        $now = Carbon::now();
        $this->tweetRepository->create(
            $this->sessionManager->getUserId(),
            $request->input("message", "POST"),
            $now
        );

        JsonResponseManager::response(200);
    }
}
