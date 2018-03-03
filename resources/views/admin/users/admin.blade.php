<!DOCTYPE html>
<html>
  <head>
    <title>{{$website->website_name}}－會員資料管理</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.globel.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vue.min.js') }}"></script> 
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/localization/messages_zh_TW.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>
  <body>
    @include('nav.admin_nav')
    <div tabindex="-1" role="dialog" id="createModal" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">新增使用者資料</h5>
          </div>
          <form id="createUserForm">
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
              <label for="isAdmin">管理員權限</label>
              <select class="form-control" name="isAdmin">
                <option selected value="1">否</option>
                <option value="0">是</option>
              </select><br>
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
    <div tabindex="-1" role="dialog" id="updateModal" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">更新使用者資料</h5>
          </div>
          <form id="updateUserForm">
            <div class="modal-body">
              <input type="hidden" name="id">
              {{ method_field('PATCH') }}
              <label for="username">使用者名稱</label>
              <input name="username" type="text" class="form-control"><br>
              <label for="last_name">姓</label>
              <input name="last_name" type="text" class="form-control"><br>
              <label for="first_name">名</label>
              <input name="first_name" type="text" class="form-control"><br>
              <label for="email">信箱</label>
              <input name="email" type="email" class="form-control"><br>
              <label for="isAdmin">管理員權限</label>
              <select class="form-control" name="isAdmin">
                <option selected value="1">否</option>
                <option value="0">是</option>
              </select><br>
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
    <div tabindex="-1" role="dialog" id="updatePasswordModel" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">更新密碼</h5>
          </div>
          <form id="updatePasswordForm">
            <div class="modal-body">
              <input type="hidden" name="id">
              {{ method_field('PATCH') }}
              <div id="userMsg">
                <h4>請問你是否要更新 @{{username}} (@{{nickname}}) 的密碼</h4>
              </div>
              <label for="password">密碼</label>
              <input name="password" type="password" class="form-control">
            </div>
            <div class="modal-footer">
              <input type="submit" value="送出" class="btn btn-primary">
              <button type="button" data-dismiss="modal" class="btn btn-secondary">關閉</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div tabindex="-1" role="dialog" id="deleteModel" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">刪除使用者資料</h5>
          </div>
          <form id="deleteForm">
            <div class="modal-body">
              <input type="hidden" name="id">
              {{ method_field('DELETE') }}
              <div id="deleteuserMsg">
                <h4>請問你是否要刪除 @{{username}} (@{{nickname}}) 的資料</h4>
              </div>
            </div>
            <div class="modal-footer">
              <input type="submit" value="送出" class="btn btn-primary">
              <button type="button" data-dismiss="modal" class="btn btn-secondary">關閉</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
          <div id="sysMsg">
            <div v-if="isSuccess" role="alert" class="alert alert-success">操作順利完成！</div>
            <div v-if="isFailed" role="alert" class="alert alert-success">操作失敗請再試一次，如多次失敗請聯絡系統管理員！</div>
          </div>
          <form action="{{ url('admin/users/search') }}" method="get">
            <table class="table">
                <tr>
                    <td>搜尋使用者名稱：</td>
                    <td><input type="text" name="search" class="form-control"></td>
                    <td><input type="submit" name="submit" class="btn btn-default" value="送出"></td>
                </tr>
            </table>
          </form>
          <button type="button" onclick="model_create()" class="btn btn-default">新增會員資料</button><br>
          <table class="table">
            <tr>
              <td>用戶編號</td>
              <td>使用者名稱</td>
              <td>姓</td>
              <td>名</td>
              <td>管理權限</td>
              <td>暱稱</td>
              <td>信箱</td>
              <td>操作</td>
            </tr>
            @foreach($result as $row)
            <tr>
              <td>{{$row->id}}</td>
              <td>{{$row->username}}</td>
              <td>{{$row->last_name}}</td>
              <td>{{$row->first_name}}</td>
              <td>
                  @if($row->isAdmin != 0)
                  否
                  @else
                  是
                  @endif
              </td>
              <td>{{$row->nickname}}</td>
              <td>{{$row->email}}</td>
              <td>
                <div role="group" aria-label="action" class="btn-group">
                  <button type="button" onclick="model_update({{$row->id}})" class="btn btn-secondary">修改</button>
                  @if($row->id != 1)
                  <button type="button" onclick="model_resetpassword({{$row->id}})" class="btn btn-secondary">密碼修改</button>
                  <button type="button" onclick="model_delete({{$row->id}})" class="btn btn-secondary">刪除</button>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </table>
          {{ $result->links('vendor/pagination/bootstrap-4') }}
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
    <script type="text/javascript">
      // 建立新使用者
      $("#createUserForm").validate({
          submitHandler: function(){
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '{{ url('admin/users') }}',
                  data: $('#createUserForm').serialize(),
                  type: 'post',
                  success: function(){
                      setTimeout(location.reload(),5000);
                      Vue.set(sysMsg, isSuccess, true);
                      Vue.set(sysMsg, isFailed, false);
                      $('#createModal').modal('hide');
                  },
                  failed: function(){
                      alert("發生錯誤");
                      Vue.set(sysMsg, isFailed, true);
                      Vue.set(sysMsg, isSuccess, false);
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
              },
              isAdmin:{
                  required: true
              }
           }
      });
      // 更新密碼
      $("#updatePasswordForm").validate({
          submitHandler: function(){
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '{{ url('admin/users/password') }}/'+data_id,
                  data: $('#updatePasswordForm').serialize(),
                  type: 'post',
                  success: function(){
                      setTimeout(location.reload(),5000);
                      Vue.set(sysMsg, isSuccess, true);
                      Vue.set(sysMsg, isFailed, false);
                      $('#updatePasswordModel').modal('hide');
                  },
                  failed: function(){
                      alert("發生錯誤");
                      Vue.set(sysMsg, isFailed, true);
                      Vue.set(sysMsg, isSuccess, false);
                  }
              });
          },
           rules:{
              password: {
                  required: true,
                  maxlength: 30,
                  minlength: 5
              }
           }
      });
      // 更新用戶資料
      $("#updateUserForm").validate({
          submitHandler: function(){
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '{{ url('admin/users') }}/'+data_id,
                  data: $('#updateUserForm').serialize(),
                  type: 'post',
                  success: function(){
                      setTimeout(location.reload(),5000);
                      Vue.set(sysMsg, isSuccess, true);
                      Vue.set(sysMsg, isFailed, false);
                      $('#updateModal').modal('hide');
                  },
                  failed: function(){
                      alert("發生錯誤");
                      Vue.set(sysMsg, isFailed, true);
                      Vue.set(sysMsg, isSuccess, false);
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
              },
              isAdmin:{
                  required: true
              }
           }
      });
      //刪除使用者
      $("#deleteForm").validate({
          submitHandler: function(){
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });
              $.ajax({
                  url: '{{ url('admin/users') }}/'+data_id,
                  data: $('#deleteForm').serialize(),
                  type: 'post',
                  success: function(){
                      setTimeout(location.reload(),5000);
                      Vue.set(sysMsg, isSuccess, true);
                      Vue.set(sysMsg, isFailed, false);
                      $('#deleteModel').modal('hide');
                  },
                  failed: function(){
                      alert("發生錯誤");
                      Vue.set(sysMsg, isFailed, true);
                  }
              });
          },
          rules:{
              id: 'required'
          }
      });
      var sysMsg = new Vue({
          el: '#sysMsg',
          data:{
              'isSuccess': false,
              'isFailed': false
          }
      });
      deleteuserMsg
      var userMsg = new Vue({
          el: '#userMsg',
          data: {
              'username': null,
              'nickname': null
          }
      });
      var userMsg_delete = new Vue({
          el: '#deleteuserMsg',
          data: {
              'username': null,
              'nickname': null
          }
      });
      var data_id = "";
      function model_create(){
          clean_from();
          $('#createModal').modal('show');
      }
      function model_resetpassword(id){
          clean_from();
          data_id = id;
          ajax_get_data(id);
          $('#updatePasswordModel').modal('show');
      }
      function model_update(id){
          clean_from();
          data_id = id;
          ajax_get_data(id);
          $('#updateModal').modal('show');
      }
      function model_delete(id){
          clean_from();
          data_id = id;
          ajax_get_data(id);
          $('#deleteModel').modal('show');
      }
      function ajax_get_data(id){
          $.ajax({
              url: '{{ url('admin/users') }}/'+id,
              type: 'get',
              success: function(msg){
                  $("input[name='id']").val(msg['id']);
                  $("input[name='username']").val(msg['username']);
                  $("input[name='email']").val(msg['email']);
                  $("input[name='last_name']").val(msg['last_name']);
                  $("input[name='first_name']").val(msg['first_name']);
                  $("input[name='nickname']").val(msg['nickname']);
                  $("select[name='isAdmin']").val(msg['isAdmin']);
                  Vue.set(userMsg,"username",msg['username']);
                  Vue.set(userMsg,"nickname",msg['nickname']);
                  Vue.set(userMsg_delete,"username",msg['username']);
                  Vue.set(userMsg_delete,"nickname",msg['nickname']);
              },
              failed: function(){
                  alert("操作失敗，從遠端伺服器取得資料失敗，請再試一次");
              }
          });
      }
      function clean_from(){
          $("input[name='id']").val(null)
          $("input[name='username']").val(null)
          $("input[name='password']").val(null)
          $("input[name='email']").val(null)
          $("input[name='last_name']").val(null)
          $("input[name='first_name']").val(null)
          $("input[name='nickname']").val(null)
          $("select[name='isAdmin']").val(1);
      }
    </script>
  </body>
</html>