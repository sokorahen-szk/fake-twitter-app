<?php

namespace Src\Controller;

use Src\Common\JsonResponseManager;
use Src\Common\SessionManager;
use Src\RepoInterface\IFollowRepository;
use Carbon\Carbon;

class FollowController extends BaseController
{
    /**
     * @var Src\Common\SessionManager
     */
    private $sessionManager;

    /**
     * @var Src\RepoInterface\IFollowRepository
     */
    private $followRepository;

    public function __construct(IFollowRepository $followRepository, SessionManager $sessionManager)
    {
        $this->followRepository = $followRepository;
        $this->sessionManager = $sessionManager;
    }

    public function getFollow(string $followUserId)
    {
        if (!$this->sessionManager->check()) {
            JsonResponseManager::response(403, ["ログインされていません。"]);
        }

        $follow = $this->followRepository->find(
            $this->sessionManager->getUserId(),
            $followUserId
        );

        JsonResponseManager::response(200, ["is_follow" => $follow ? true : false]);
    }

    public function postFollow(string $followUserId)
    {
        if (!$this->sessionManager->check()) {
            JsonResponseManager::response(403, ["ログインされていません。"]);
        }

        $follow = $this->followRepository->find(
            $this->sessionManager->getUserId(),
            $followUserId
        );
        if ($follow) {
            JsonResponseManager::response(400, ["既にフォローされています。"]);
        }

        $now = Carbon::now();
        $this->followRepository->follow(
            $this->sessionManager->getUserId(),
            $followUserId,
            $now
        );

        JsonResponseManager::response(200);
    }

    public function postUnfollow(string $followedUserId)
    {
        if (!$this->sessionManager->check()) {
            JsonResponseManager::response(403, ["ログインされていません。"]);
        }

        $this->followRepository->unfollow(
            $this->sessionManager->getUserId(),
            $followedUserId,
        );

        JsonResponseManager::response(200);
    }

    public function getAnalytics(string $userId)
    {
        if (!$this->sessionManager->check()) {
            JsonResponseManager::response(403, ["ログインされていません。"]);
        }

        $analytics = $this->followRepository->analytics(
            $userId
        );
        JsonResponseManager::response(200, [
            "follow_count" => $analytics["followCount"],
            "follower_count" => $analytics["followerCount"],
        ]);
    }
}
