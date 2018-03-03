<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{$website->website_name}}-登入介面</title>
    <meta name="description" content="{{ $website->description }}">
    <meta name="keyword" content="{{ $website->keyword }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ URL::asset('css/login.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vue.min.js') }}"></script> 
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/localization/messages_zh_TW.min.js') }}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>
  <body>
    <div tabindex="-1" role="dialog" id="registerModel" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">新用戶註冊</h5>
          </div>
          <form id="registerForm">
            <div class="modal-body">
              <label for="username">使用者名稱</label>
              <input name="username" type="text" class="form-control"><br>
              <label for="password">密碼</label>
              <input name="password" type="password" class="form-control"><br>
              <label for="email">信箱</label>
              <input name="email" type="email" class="form-control"><br>
              <label for="last_name">姓</label>
              <input name="last_name" type="text" class="form-control"><br>
              <label for="first_name">名</label>
              <input name="first_name" type="text" class="form-control"><br>
              <label for="nickname">暱稱</label>
              <input name="nickname" type="text" class="form-control">
            </div>
            <div class="modal-footer">
              <input type="submit" value="送出" class="btn btn-primary">
              <button type="button" data-dismiss="modal" class="btn btn-secondary">關閉</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="container">
      @section('nav')
        @include('nav.index')
      @show
      <div class="login">
        <form action="{{ url('login') }}" method="post">
            {{ csrf_field() }}
            @if(isset($_GET['failed']))
                <div class="alert alert-warning" role="alert">登入失敗，請確認你的使用者名稱以及密碼是否正確</div>
            @endif
            @if(isset($_GET['logout']))
                <div class="alert alert-warning" role="alert">你已經成功登出</div>
            @endif
          <h3 style="text-align: center;">請先登入</h3>
          <hr>
          <label for="username">使用者名稱</label>
          <input name="username" type="text" class="form-control"><br>
          <label for="password">密碼</label>
          <input name="password" type="password" class="form-control"><br>
          <input type="checkbox" name="rememeber">記住我<br>
          <input type="submit" value="登入" class="btn btn-default">
        </form>或使用第三方帳戶登入<a href="{{ url('social/google/redirect') }}" class="btn btn-default">Google帳戶登入</a><br>
        <button type="button" onclick="register_model()" class="btn btn-default">註冊新使用者</button>
      </div>
      <footer>
        <p>程式設計：小周</p>
      </footer>
    </div>
  </body>
  <script type="text/javascript">
    // 建立新使用者
    $("#registerForm").validate({
        submitHandler: function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{ url('register') }}',
                data: $('#registerForm').serialize(),
                type: 'post',
                success: function(){
                    alert("註冊成功！請輸入帳號密碼進行登入動作");
                    setTimeout(loction.reload(),5000);
                    $('#registerModel').modal('hide');
                },
                failed: function(msg){
                    alert("發生錯誤，錯誤訊息："+msg['message']);
                }
            });
        },
        rules:{
            username: {
                required: true,
                maxlength: 30
            },
            password: {
                required: true,
                maxlength: 30,
                minlength: 5
            },
            email:{
                required: true,
                email: true
            },
            last_name:{
                required: true,
                minlength: 1,
                maxlength: 5
            },
            first_name:{
                required: true,
                minlength: 1,
                maxlength: 5
            },
            nickname: {
                required: true,
                maxlength: 8
            }
        }
    });
    function register_model(){
        $('#registerModel').modal('show');
    }
  </script>
</html>