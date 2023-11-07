<?php

namespace Src\Repository;

use Src\Model\Tweet;
use Src\RepoInterface\ITweetRepository;
use Carbon\Carbon;

class TweetRepository implements ITweetRepository
{
    /**
     * @var Src\Model\Tweet
     */
    private $client;

    //
    // NOTE: 本来なら、between区を使って絞り込みをして表示したいところだけど、
    // 実装する時間の兼ね合いでそこは省略。
    // 最新のデータ（降順）でデータ100件分だけ表示する。
    //
    public const RESULT_MAX_LIMIT = 100;

    public function __construct()
    {
        $this->client = new Tweet();
    }

    public function list()
    {
        return $this->client->with(["user:id,name"])
            ->orderBy("tweeted_at", "desc") // 最新データ順
            ->limit(self::RESULT_MAX_LIMIT)
            ->get();
    }

    public function listByUserId(string $userId)
    {
        return $this->client->with(["user:id,name"])
            ->where("user_id", $userId)
            ->orderBy("tweeted_at", "desc") // 最新データ順
            ->limit(self::RESULT_MAX_LIMIT)
            ->get();
    }

    public function listByUserIds(array $userIds)
    {
        return $this->client->with(["user:id,name"])
            ->whereIn("user_id", $userIds)
            ->orderBy("tweeted_at", "desc") // 最新データ順
            ->limit(self::RESULT_MAX_LIMIT)
            ->get();
    }

    public function create(string $userId, string $message, Carbon $tweetedAt): void
    {
        $this->client->create([
            "user_id" => $userId,
            "message" => $message,
            "tweeted_at" => $tweetedAt->toDateTimeString(),
        ]);
    }
}
