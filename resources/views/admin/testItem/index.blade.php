<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{$website->website_name}}－測驗項目</title>
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
                <h5 class="modal-title">新增測驗項目</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form id="createForm">
                  <div class="modal-body">
                    <label for="test_create_id">測驗科目名稱(*)</label>
                    <select name="test_create_id" class="form-control"></select><br>
                    <label for="item_name">測驗項目名稱(*)</label>
                    <input type="text" name="item_name" class="form-control"><br>
                    <label for="content">簡介</label>
                    <input type="text" name="content" class="form-control"><br>
                    <label for="isRepeat">可重複作答(*)</label>
                    <select name="isRepeat" class="form-control">
                        <option value="0">是</option>
                        <option value="1">否</option>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <input type="submit" name="submit" class="btn btn-primary" value="送出">
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
                <h5 class="modal-title">修改測驗項目</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form id="updateForm">
                  <div class="modal-body">
                    {{ method_field('patch') }}
                    <input type="hidden" name="id">
                    <label for="test_create_id">測驗科目名稱(*)</label>
                    <select name="test_create_id" class="form-control"></select><br>
                    <label for="item_name">測驗項目名稱(*)</label>
                    <input type="text" name="item_name" class="form-control"><br>
                    <label for="content">簡介</label>
                    <input type="text" name="content" class="form-control"><br>
                    <label for="isRepeat">可重複作答(*)</label>
                    <select name="isRepeat" class="form-control">
                        <option value="0">是</option>
                        <option value="1">否</option>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <input type="submit" name="submit" class="btn btn-primary" value="送出">
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
                <h5 class="modal-title">刪除測驗項目</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form id="deleteForm">
                  <div class="modal-body" id="deleteView">
                    <input type="hidden" name="id">
                    {{ method_field('delete') }}
                    你確定要刪除 @{{ item_name }} (測驗科目：@{{ test_create }})
                  </div>
                  <div class="modal-footer">
                    <input type="submit" name="submit" class="btn btn-primary" value="送出">
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
                    <div class="alert alert-success" role="alert" v-show="isSuccess">
                      操作順利完成
                    </div>
                    <button type="button" class="btn btn-default" onclick="model_Create()">新增考試科目</button>
                    <table class="table">
                        <tr>
                            <td>課程</td>
                            <td>考試科目</td>
                            <td>重複測驗</td>
                            <td>操作</td>
                        </tr>
                        @foreach($result as $row)
                        <tr>
                            <td>{{$row->test_Subject->subjects_name}}</td>
                            <td>{{$row->item_name}}</td>
                            <td>
                                @if($row->isRepeat != 0)
                                    不可重複測驗
                                @else
                                    可重複測驗
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                  <button type="button" class="btn btn-secondary" onclick="window.open('{{ url('admin/score')."/".$row->id }}')">查看分數</button>
                                  <button type="button" class="btn btn-secondary" onclick="window.open('{{ url('admin/question/edit')."/".$row->id }}')">題庫編輯器</button>
                                  <button type="button" class="btn btn-secondary" onclick="model_Update({{$row->id}})">修改</button>
                                  <button type="button" class="btn btn-secondary" onclick="model_Delete({{$row->id}})">刪除</button>
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
            var app = new Vue({
               el: '#app',
               data: {
                   isSuccess: false
               }
            });
            var deleteView = new Vue({
                el: '#deleteView',
                data:{
                    item_name: null,
                    test_create: null
                }
            })
            var data_id,web_action;
            $.validator.setDefaults({
                debug: true,
                submitHandler: function() {
                  switch(web_action){
                      case "create":
                          ajax_create();
                          break;
                      case "update":
                          ajax_update();
                          break;
                      case "delete":
                          ajax_delete();
                          break;
                  }
                }
            });
            function model_Create(){
                web_action = "create";
                cleanForm();
                $('#createModel').modal({
                  keyboard: false
                })
                ajax_get_select();
                $('#createForm').validate({
                    rules:{
                        test_create_id:{
                            number: true,
                            required: true
                        },
                        item_name: 'required',
                        isRepeat: 'required'
                    }
                });
            }
            function model_Update(id){
                web_action = "update";
                cleanForm();
                $('#updateModel').modal({
                  keyboard: false
                })
                data_id = id;
                ajax_get_select();
                ajax_get_data();
                $('#updateForm').validate({
                    rules:{
                        id:{
                            number: true,
                            required: true
                        },
                        test_create_id:{
                            number: true,
                            required: true
                        },
                        item_name: 'required',
                        isRepeat: 'required'
                    }
                });
            }
            function model_Delete(id){
                web_action = "delete";
                cleanForm();
                $('#deleteModel').modal({
                  keyboard: false
                })
                data_id = id;
                ajax_get_data();
                $('#deleteForm').validate({
                    rules:{
                        id:{
                            number: true,
                            required: true
                        }
                    }
                });
            }
            function ajax_create(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '{{ url('admin/test/items/') }}',
                    data:$("#createForm").serialize(),
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
                    url: '{{ url('admin/test/items/') }}/'+data_id,
                    data:$("#updateForm").serialize(),
                    type: "post",
                    success: function(){
                        $('#deleteModel').modal('hide');
                        location.reload(5000);
                        Vue.set(app,'isSuccess',true);
                        data_id = null;
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
                    url: '{{ url('admin/test/items/') }}/'+data_id,
                    data:$("#deleteForm").serialize(),
                    type: "post",
                    success: function(){
                        $('#deleteModel').modal('hide');
                        location.reload(5000);
                        Vue.set(app,'isSuccess',true);
                        data_id = null;
                    },
                    error: function(){
                        alert("請求失敗，發生錯誤");
                    }
                });  
            }
            function ajax_get_select(){
                $.getJSON("{{ url('admin/ajax/test/subject') }}",function(result){
                    $.each(result,function(key,value){
                        console.log(value);
                        $("select[name='test_create_id']").append("<option value='"+value['id']+"'>"+value['name']+"</option>");
                    });
                })
                if(typeof data_id == "undefined" || typeof data_id == "object"){
                    $("select[name='test_create_id']").append("<option selected></option>");
                }
            }
            function ajax_get_data(){
                $.ajax({
                    url: '{{ url('admin/ajax/test/items/') }}/'+data_id,
                    success: function(data){
                        $("input[name='id']").val(data['id']);
                        $("select[name='test_create_id']").val(data['create_id']);
                        $("input[name='item_name']").val(data['item_name']);
                        $("input[name='content']").val(data['content']);
                        $("select[name='isRepeat']").val(data['isRepeat'])
                        Vue.set(deleteView,'item_name',data['item_name']);
                        Vue.set(deleteView,'test_create',data['create_name']);
                    }
                });
            }
            function cleanForm(){
                $("input[name='id']").val("");
                $("input[name='item_name']").val("");
                $("select[name='test_create_id']").empty();
            }
        </script>
    </body>
</html>