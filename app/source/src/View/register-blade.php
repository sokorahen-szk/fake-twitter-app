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
      <h2 class="pt-2 pb-2">新規登録</h2>
      <small class="text-danger"><?=@$error_message?></small>
      <form action="/register" method="post">
        <div class="mb-3">
          <label for="name" class="form-label">ユーザID</label>
          <input type="text" class="form-control" name="id" placeholder="新しく作るユーザIDを入力"  maxlength="16">
          <small class="d-block">※ ユーザ名は半角英数字のみ使用可能で、ユーザ名の長さは16文字以下</small>
          <small class="text-danger"><?=@$error_id?></small>
        </div>
        <div class="mb-3">
          <label for="name" class="form-label">ユーザ名</label>
          <input type="text" class="form-control" name="name" placeholder="新しく作るユーザ名を入力"  maxlength="20">
          <small class="d-block">※ ユーザ名の長さは20文字以下</small>
          <small class="text-danger"><?=@$error_name?></small>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">パスワード</label>
          <input type="password" class="form-control" name="password" placeholder="新しく作るパスワード">
          <small class="d-block">※ パスワードは8文字以上で入力してください。</small>
          <small class="text-danger"><?=@$error_password?></small>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">パスワード（再入力）</label>
          <input type="password" class="form-control" name="password_confirm" placeholder="新しく作るパスワード（再入力）">
          <small class="text-danger"><?=@$error_password_confirm?></small>
        </div>
        <button type="submit" class="btn btn-primary">アカウントを作成する</button>
      </form>
    </div>
    </body>
</html>