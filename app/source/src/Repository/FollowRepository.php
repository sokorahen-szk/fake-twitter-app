<?php

namespace Src\Repository;

use Src\Model\Follow;
use Src\RepoInterface\IFollowRepository;
use Carbon\Carbon;

class FollowRepository implements IFollowRepository
{
    /**
     * @var Src\Model\Follow
     */
    private $client;

    public const RESULT_MAX_LIMIT = 100;

    public function __construct()
    {
        $this->client = new Follow();
    }

    public function follow(string $userId, string $followUserId, Carbon $followedAt): void
    {
        $this->client->create([
            "user_id" => $userId,
            "follow_user_id" => $followUserId,
            "followed_at" => $followedAt->toDateTimeString(),
        ]);
    }

    public function unfollow(string $userId, string $followUserId): void
    {
        $this->client->where("user_id", $userId)
            ->where("follow_user_id", $followUserId)
            ->delete(); // HACK: 物理削除でいいかなと思って、あえて論理削除する必要がないため、そのままレコードごと削除
    }

    public function find(string $userId, string $followUserId)
    {
        $follow = $this->client->where("user_id", $userId)
            ->where("follow_user_id", $followUserId)
            ->first();

        if (!$follow) {
            return null;
        }

        return $follow;
    }

    public function listByUserId(string $userId)
    {
        return $this->client->where("user_id", $userId)->get();
    }

    public function listByFollowUserId(string $userId)
    {
        return $this->client->where("follow_user_id", $userId)->get();
    }

    public function analytics(string $userId)
    {
        // NOTE: どのように集計した結果をユーザにフォロワーやフォローを返すか考えた。
        // * UPSERT使用した検討
        // 分析用のテーブル(analytics_followes)を作り、バッチ等で1分毎に全ユーザのデータを集計し、集計テーブルに書き込みを行う事を検討したが、
        // twitter(POST)はどうやらほぼリアルタイムでフォロー・アンフォローは反映されるようで、バッチ処理を用いた方法ではリアルタイムでの反映は
        // 難しい事から、この運用をやめた。
        //
        // * インメモリキャッシュ使用した検討
        // MemcachedやRedisのようなサービスを使って、SQLクエリの結果を格納しておき、そこからデータを取り出すことで、
        // DBの負荷対策であったり、高速にデータを取り出す方法を考えたが課題用に準備いただいた環境にソレはなかったため断念。
        //
        // **最終結果
        // 今回はデータ量も現段階では増える見込みがないため、複合主キーで設定されたuser_id, follow_user_idをuser_idで検索して返すようにした。
        // 今後運用していき、データ量が増えて耐えかねるようであれば、インメモリキャッシュ等の導入が望ましい。
        $followCount = self::listByUserId($userId)->count();
        $followerCount = self::listByFollowUserId($userId)->count();

        // NOTE: 本来なら、FollowRepositoryはfollowesテーブルだけのデータを返すべきところだけど、
        // 今回は設計に時間をかけれない事から、このまま配列でfollowCount, followerCountのデータを返す。
        return [
            "followCount" => $followCount,
            "followerCount" => $followerCount,
        ];
    }
}
