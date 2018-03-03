<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{$website->website_name}}-用戶管理</title>
    <meta name="description" content="{{ $website->description }}">
    <meta name="keyword" content="{{ $website->keyword }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/updateUser.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vue.min.js') }}"></script> 
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/localization/messages_zh_TW.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>
  <body>
    <div tabindex="-1" role="dialog" id="updatePassword" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">更改密碼</h5>
          </div>
          <form id="set_passwd">
            <div class="modal-body">
              {{ method_field("PATCH") }}
              <label for="password">密碼</label>
              <input type="password" name="password" id="password" class="form-control"><br>
              <label for="password2">請再輸入一次相同的密碼</label>
              <input type="password" name="password2" class="form-control"><br>
            </div>
            <div class="modal-footer">
              <input type="submit" name="submit" value="修改密碼" class="btn btn-primary">
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
    <div class="UserView">
        <h3>用戶資料修改</h3>
        <hr>
        <form id="myUserData">
          {{ method_field("PATCH") }} 
          <label for="last_name">姓氏</label>
          <input type="text" name="last_name" class="form-control" value="{{$result->last_name}}"><br>
          <label for="first_name">名字</label>
          <input type="text" name="first_name" class="form-control" value="{{$result->first_name}}"><br>
          <label for="email">電子信箱</label>
          <input type="email" name="email" class="form-control" value="{{$result->email}}"><br>
          <label for="nickname">暱稱</label>
          <input type="text" name="nickname" class="form-control" value="{{$result->nickname}}"><br>
          <input type="submit" name="subject" value="修改個人資料" class="btn btn-default">
          <p></p>
          <button type="button" onclick="modal_updatePassword()" class="btn btn-default">更改密碼</button>
        </form>
      </div>
      <footer>
        <p>程式設計：小周</p>
      </footer>
      <script type="text/javascript">
        function modal_updatePassword(){
            $('#updatePassword').modal('show');
        }
        $("#myUserData").validate({
            submitHandler: function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{url('student/user')}}',
                    data: $('#myUserData').serialize(),
                    type: 'post',
                    success: function(){
                        alert("更新完成！");
                        location.reload();
                    },
                    error: function(){
                        alert("發生錯誤");
                    }
                })
            },
            rules: {
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
        })
        $("#set_passwd").validate({
            submitHandler: function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ url('student/user/passsword') }}',
                    data: $("#set_passwd").serialize(),
                    type: 'post',
                    success: function(){
                        $('#updatePassword').modal('hide');
                        alert("密碼更新完成！");
                        location.reload();
                    },
                    error: function(){
                        alert("發生錯誤");
                    }
                })
            },
            rules: {
                password: {
                    required: true,
                    maxlength: 30,
                    minlength: 5
                },
                password2: {
                    required: true,
                    maxlength: 30,
                    minlength: 5,
                    equalTo: "#password"
                }
            }
        })
      </script>
    </div>
  </body>
</html>