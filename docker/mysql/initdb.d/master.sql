SET CHARACTER_SET_CLIENT = utf8;
SET CHARACTER_SET_CONNECTION = utf8;

CREATE DATABASE IF NOT EXISTS `test-fake-twitter-app-db`;
USE `test-fake-twitter-app-db`;

-- ユーザテーブル
CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(16) PRIMARY KEY NOT NULL,
    name VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    avator_image VARCHAR(255),
    introduction VARCHAR(255),
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);

-- ツイートテーブル
CREATE TABLE IF NOT EXISTS tweets (
    id INT PRIMARY KEY auto_increment,
    user_id VARCHAR(16) NOT NULL,
    message TEXT NOT NULL,
    tweeted_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_tweets_user_id (user_id)
);

-- フォローテーブル
CREATE TABLE IF NOT EXISTS followes (
    user_id VARCHAR(16) NOT NULL,
    follow_user_id VARCHAR(16) NOT NULL,
    followed_at DATETIME NOT NULL,
    PRIMARY KEY(user_id,follow_user_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (follow_user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- -- フォロー集計ビュー
-- CREATE VIEW analytics_followes AS
-- SELECT user_id, SUM(follow_count) AS follow_count, SUM(follower_count) AS follower_count FROM
-- (
--     SELECT user_id, count(*) AS follow_count, 0 AS follower_count FROM followes
--     GROUP BY user_id

--     UNION ALL

--     SELECT follow_user_id AS user_id, 0 AS follow_count, count(*) AS follower_count FROM followes
--     GROUP BY follow_user_id
-- ) AS tb
-- GROUP BY user_id;
