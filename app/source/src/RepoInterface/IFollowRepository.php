<?php

namespace Src\RepoInterface;

use Carbon\Carbon;

interface IFollowRepository
{
    /**
     * @param string $userId
     * @param string $followUserId
     * @return void
     */
    public function follow(string $userId, string $followUserId, Carbon $followedAt): void;

    /**
     * @param string $userId
     * @param string $followUserId
     * @return void
     */
    public function unfollow(string $userId, string $followUserId): void;

    /**
     * @param string $userId
     * @param string $followUserId
     */
    public function find(string $userId, string $followUserId);

    /**
     * @param string $userId
     */
    public function analytics(string $userId);

    /**
     * @param string $userId
     */
    public function listByUserId(string $userId);

    /**
     * @param string $userId
     */
    public function listByFollowUserId(string $userId);
}
