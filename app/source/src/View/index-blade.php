<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <title>ツイッター（偽）</title>
    </head>
    <body>
    <nav class="navbar bg-body-tertiary p-2">
      <div>
        <a class="navbar-brand" href="/">ツイッター（偽）</a>
      </div>
      <div>
        <?php if (!$isLogin): ?>
        <a class="btn btn-primary" href="/login">ログイン</a>
        <a class="btn btn-success" href="/register">新規登録</a>
        <?php else: ?>
        <div class="row">
          <div class="col-auto">
            <a class="btn btn-dark" href="/logout">ログアウト</a>
          </div>
          <div class="col">
            <a href="/profile/<?=$myUserId?>">
              <img src="https://placehold.jp/35x35.png" class="rounded-circle"/>
            </a>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </nav>
    <div class="container mt-2" style="max-width: 850px;">
    
      <nav class="nav nav-tabs">
        <a class="nav-link <?= is_null($mode) ? "active" : "";?>" aria-current="page" href="/">すべてのツイート</a>
        <?php if ($isLogin): ?>
          <a class="nav-link <?=$mode === "following" ? "active" : "";?>" href="?mode=following">フォロー中</a>
        <?php endif; ?>
      </nav>
      <?php foreach ($tweets as $tweet):?>
      <div class="card mb-3">
        <div class="card-header">
          <div class="row">
            <div class="col-auto">
              <img src="https://placehold.jp/32x32.png" class="rounded-circle"/>
            </div>
            <div class="col p-1">
              <a href="/profile/<?=$tweet["user"]["id"]?>"><?=h($tweet["user"]["name"])?></a>
              <small>@<?=$tweet["user"]["id"]?></small>
              <small><?=$tweet["tweeted_at"]?></small>
            </div>
          </div>
        </div>
        <div class="card-body">
          <blockquote class="blockquote mb-0">
          <?=nl2br($tweet["message"])?>
          </blockquote>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    </body>
</html>