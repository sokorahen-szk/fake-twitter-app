<?php

namespace Src\RepoInterface;

use Carbon\Carbon;

interface ITweetRepository
{
    public function list();

    /**
     * @param string $userId
     */
    public function listByUserId(string $userId);

    /**
     * @param array $userIds
     */
    public function listByUserIds(array $userIds);

    /**
     * @param string $userId
     * @param string $message
     * @param Carbon\Carbon $tweetedAt
     *
     * @return void
     */
    public function create(string $userId, string $message, Carbon $tweetedAt): void;
}
