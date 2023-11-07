<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <title>ツイッター（偽）｜<?=$userId?></title>

        <script
          src="https://code.jquery.com/jquery-3.7.1.min.js"
          integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
          crossorigin="anonymous"
        ></script>
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
      <div class="row mb-3">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-auto">
                  <img src="https://placehold.jp/150x150.png" class="rounded-circle"/>
                </div>
                <div class="col">
                  <h5 class="card-title mt-2 mb-2">
                    <a href="/profile/<?=$userId?>"><?=h($userName)?></a> @<?=$userId?>
                  </h5>
                  <div class="follow-btn-frame">
                    <?php if ($isLogin && $userId !== $myUserId): ?>
                      <button class="btn btn-primary" id="follow_event">フォロー</button>
                    <?php endif; ?>
                  </div>
                  <script>
                    $(() => {
                      $.get("/follow/<?=$userId?>", (res) => {
                        if (res.data.is_follow) $("#follow_event")
                        .removeClass("btn-primary")
                        .addClass("btn-secondary")
                        .addClass("followed")
                        .text("フォロー中");
                      })

                      $("#follow_event").on("click", () => {
                        $("#follow_event").prop("disabled", true);
                        if ($("#follow_event").hasClass("followed")) {
                          $.post( "/unfollow/<?=$userId?>", () => {})
                          .done(() => {
                            $("#follow_event")
                              .removeClass("btn-secondary")
                              .removeClass("followed")
                              .addClass("btn-primary")
                              .text("フォロー");
                          })
                          .always( () => {
                            // アンフォロー完了後にボタンのdisableを解除
                            $("#follow_event").prop("disabled", false);
                          })
                        } else {
                          $.post( "/follow/<?=$userId?>", () => {})
                          .done( () => {
                            $("#follow_event")
                              .removeClass("btn-primary")
                              .addClass("followed")
                              .addClass("btn-secondary")
                              .text("フォロー中");
                          })
                          .always( () => {
                            // フォロー完了後にボタンのdisableを解除
                            $("#follow_event").prop("disabled", false);
                          })
                        }
                      });

                    })
                  </script>
                  <div class="row">
                    <div class="col-auto">フォロー: <span id="follow_count">0</span></div>
                    <div class="col">フォロワー: <span id="follower_count">0</span></div>
                  </div>

                  <script>
                    $( () => {
                      $.get("/follow/<?=$userId?>/analytics", (res) => {
                        $("#follow_count").text(res.data.follow_count);
                        $("#follower_count").text(res.data.follower_count);
                      })
                    })
                  </script>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php if ($isLogin && $userId === $myUserId): ?>
      <div class="mt-3 mb-3">
        <div>
          <label for="message" class="form-label">ツイート</label>
          <textarea class="form-control" name="message" rows="3" id="tweet_message" placeholder="なんでも書いてみよう"></textarea>
          <small class="d-block mt-1">(<span id="tweet_message_count">0</span>/120) ※ ツイートは120文字まで入力できます。</small>
          <button class="btn btn-primary mt-2 text-center" id="tweet_event" disabled>ツイートする</button>
        </div>
        <script>
          $( () => {
            $("#tweet_message").on("keyup", function () {
              if ($(this).val().length > 0) $("#tweet_event").prop("disabled", false);
              if ($(this).val().length < 1 || $(this).val().length > 120) $("#tweet_event").prop("disabled", true);

              $("#tweet_message_count").text($(this).val().length);
            })

            $("#tweet_event").on("click", () => {
              $.post( "/tweet", {message: $("#tweet_message").val()})
              .done( () => {
                // NOTE: そのまま画面リロードを入れてもいいが、ユーザ体験を作るため一旦ツイート完了メッセージを表示して、
                // ユーザにツイート完了した事を感じてほしいからアラート出してみる
                alert("ツイート完了");

                // NOTE: 本来なら、ツイート一覧を再取得してDOM操作で更新するが、今回はフロントに注力しないため、
                // 画面リロードを行い、バックエンドからデータの再取得を行うものとする。
                location.reload();
              })
            });
          })
        </script>
      </div>
      <?php endif; ?>

      <?php foreach ($tweets as $tweet):?>
      <div class="card mb-3">
        <div class="card-header">
          <div class="row">
            <div class="col-auto">
              <img src="https://placehold.jp/32x32.png" class="rounded-circle"/>
            </div>
            <div class="col p-1">
              <a href="/profile/<?=$tweet["user"]["id"]?>"><?=h($tweet["user"]["name"])?></a>
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