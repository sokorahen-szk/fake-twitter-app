<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <title>ツイッター（偽）｜ログイン</title>
    </head>
    <body>
    <nav class="navbar bg-body-tertiary p-2">
      <div>
        <a class="navbar-brand" href="/">ツイッター（偽）</a>
      </div>
      <div>
        <a class="btn btn-primary" href="/login">ログイン</a>
        <a class="btn btn-success" href="/register">新規登録</a>
      </div>
    </nav>
    <div class="container mt-2" style="max-width: 850px;">
      <h2 class="pt-2 pb-2">ログイン</h2>
      <small class="text-danger"><?=@$error_message?></small>
      <form action="/login" method="post">
        <div class="mb-3">
          <label for="id" class="form-label">ユーザID</label>
          <input type="text" class="form-control" name="id" maxlength="16">
          <small class="text-danger"><?=@$error_id?></small>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">パスワード</label>
          <input type="password" class="form-control" name="password">
          <small class="text-danger"><?=@$error_password?></small>
        </div>
        <button type="submit" class="btn btn-primary">ログインする</button>
      </form>
    </div>
    </body>
</html>