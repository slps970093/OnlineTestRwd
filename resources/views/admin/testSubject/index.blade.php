<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{$website->website_name}}－測驗科目</title>
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
        <div class="modal" tabindex="-1" role="dialog" id="createModel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">新增測驗主題</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form id="createForm">
              <div class="modal-body">
                  <label for="subject_name">主題名稱</label>
                  <input type="text" name="subject_name" class="form-control">
              </div>
              <div class="modal-footer">
                <!--
                <button type="button" class="btn btn-primary">Save changes</button>
                  -->
                <input type="submit" class="btn btn-primary" value="建立測驗主題">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
              </form>   
            </div>
          </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="updateModel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">更新測驗主題</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form id="updateForm">
              <div class="modal-body">
                  {{ method_field('PATCH') }}
                  <input type="hidden" name="id">
                  <label for="subject_name">主題名稱</label>
                  <input type="text" name="subject_name" class="form-control">
              </div>
              <div class="modal-footer">
                <!--
                <button type="button" class="btn btn-primary">Save changes</button>
                  -->
                <input type="submit" class="btn btn-primary" value="修改主題名稱">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
              </form>   
            </div>
          </div>
        </div>
        <div class="modal" tabindex="-1" role="dialog" id="deleteModel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">刪除測驗主題</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form id="deleteForm">
              <div class="modal-body">
                {{ method_field('delete') }}
                <input type="hidden" name="id">
                <div id="deleteInfo">
                    你確定要刪除&nbsp;<b>@{{ subject_name }}</b>&nbsp;主題資料嗎？
                </div>
              </div>
              <div class="modal-footer">
                <!--
                <button type="button" class="btn btn-primary">Save changes</button>
                  -->
                <input type="submit" class="btn btn-primary" value="刪除測驗科目">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
              </form>   
            </div>
          </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8" id="app">
                    <div v-show="isSuccess" class="alert alert-success" role="alert">
                      操作順利完成，稍後將自動重新整理頁面！
                    </div>
                    <button type="button" class="btn btn-default" onclick="model_Create();">新增</button>
                    <table class="table">
                        <tr>
                            <td>測驗科目</td>
                            <td>操作</td>
                        </tr>
                        @foreach($result as $row)
                        <tr>
                            <td>{{ $row->subjects_name }}</td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                  <button type="button" class="btn btn-secondary" onclick="model_Update({{ $row->id }})">修改</button>
                                  <button type="button" class="btn btn-secondary" onclick="model_Delete({{ $row->id}})">刪除</button>
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
            var data_id,web_action;
            $.validator.setDefaults({
                debug:true,
                submitHandler: function() {
                    switch(web_action){
                        case "create":
                            ajax_create();
                            break;
                        case "update":
                            ajax_update();
                            break;
                        case "delete":
                            console.log("delete");
                            ajax_delete();
                            break;
                    }
                }
            });
            var app = new Vue({
                el: "#app",
                data:{
                    isSuccess: false
                }
            });
            var deleteInfo = new Vue({
                el: "#deleteInfo",
                data:{
                    subject_name: null
                }
            });
            function model_Create(){
                web_action = "create";
                $('#createModel').modal({
                  keyboard: false
                })
                cleanFrom();
                $("#createForm").validate({
                    rules:{
                        'subject_name': "required"
                    }
                });
            }
            function model_Update(id){
                web_action = "update";
                data_id = id;
                $('#updateModel').modal({
                  keyboard: false
                })
                cleanFrom();
                ajax_read(id);                
                $("#updateForm").validate({
                    rules:{
                        'id': "required",
                        'subject_name': "required"
                    }
                });
            }
            function model_Delete(id){
                web_action = "delete";
                data_id = id;
                $('#deleteModel').modal({
                  keyboard: false
                })
                cleanFrom();
                ajax_read(id);
                $("#deleteForm").validate({
                    rules:{
                        'id': "required"
                    }
                });
            }
            function cleanFrom(){
                $("input[name=id]").val("");
                $("input[name=subject_name]").val("");
            }
            function ajax_create(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ url('admin/test/subject') }}',
                    data: $("#createForm").serialize(),
                    type: "post",
                    success: function(){
                        $('#createModel').modal('hide');
                        location.reload(5000);
                        Vue.set(app,'isSuccess',true);
                    },
                    error: function(){
                        alert("請求失敗，發生錯誤");
                    }
                });
            }
            function ajax_update(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                }); 
                $.ajax({
                    url: '{{ url('admin/test/subject/')}}/'+data_id,
                    data: $("#updateForm").serialize(),
                    type: "post",
                    success: function(){
                        $('#updateModel').modal('hide');
                        location.reload(5000);
                        Vue.set(app,'isSuccess',true);
                    },
                    error: function(){
                        alert("請求失敗，發生錯誤");
                    }
                });
            }
            function ajax_delete(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ url('admin/test/subject/') }}/'+data_id,
                    data:$("#deleteForm").serialize(),
                    type: "post",
                    success: function(){
                        $('#deleteModel').modal('hide');
                        location.reload(5000);
                        Vue.set(app,'isSuccess',true);
                    },
                    error: function(){
                        alert("請求失敗，發生錯誤");
                    }
                });
            }
            function ajax_read(id){
                $.ajax({
                    url: '{{ url('admin/ajax/test/subject/') }}/'+data_id,
                    type: "get",
                    success: function(res){
                        $("input[name=id]").val(res['id'])
                        $("input[name=subject_name]").val(res['name']);
                        if(web_action == "delete"){
                            Vue.set(deleteInfo,'subject_name',res['name']);
                        }
                    },
                    error: function(){
                        alert("請求失敗，發生錯誤"); 
                    }
                });
            }
        </script>
    </body>
</html>