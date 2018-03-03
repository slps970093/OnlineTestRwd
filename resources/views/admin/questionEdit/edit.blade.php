<!DOCTYPE html>
<html>
  <head>   
    <meta charset="utf-8">
    <title>測驗系統編輯器</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="{{ asset('css/admin_edit_test.css')}}">
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('js/vue.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
  </head>
  <body>
    <div tabindex="-1" role="dialog" id="imageUpload" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">圖片上傳</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="imageDeleteForm">{{ method_field('DELETE') }}</form>
            <form id="imageUploadForm">
              <input type="file" name="file" class="form-control">
              <label for="title">標題</label>
              <input type="text" name="title" class="form-control">
              <label for="content">內容</label>
              <input type="text" name="content" class="form-control">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" onclick="ajax_Imagedelete()" class="btn btn-primary">刪除圖片</button>
            <button type="button" onclick="ajax_ImageUpload()" class="btn btn-primary">上傳圖片</button>
            <button type="button" data-dismiss="modal" class="btn btn-secondary">關閉</button>
          </div>
        </div>
      </div>
    </div>
    <div tabindex="-1" role="dialog" id="deleteQuestion" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">刪除問題</h5>
            <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">&times;</span></button>
          </div>
          <form id="questionDeleteForm">
            <div class="modal-body">
                你確定要刪除 @{{ qustion_name }} 這個問題嗎?<br>
                {{ method_field('DELETE') }}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" onclick="ajax_delete_question()">送出</button>
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
          <div class="editView">
            <div id="system-Message">
                <div class="alert alert-success" role="alert" v-if="ajaxSuccess">
                    操作順利完成，五秒後將自動重新整理！
                </div>
                <div class="alert alert-danger" role="alert" v-if="ajaxFailed">
                     操作失敗，請重新整理頁面再重試一次！
                </div>
            </div>
            <div class="createTestview">
              <form id="createTest" v-show="isShow">
                <h3>@{{ Web_title }}</h3>
                <div v-if="isUpdate">
                {{ method_field('PATCH') }}
                </div>
                <label for="title">測驗題目</label>
                <input type="text" name="title" class="form-control">
                <label for="option">選項</label>
                <select name="option" v-model="option_type" class="form-control">
                    @foreach($type as $types)
                        <option value="{{ $types->id }}">{{ $types->q_bank_type_name}}</option>
                    @endforeach
                </select>
                <div v-if="option_type == 1">
                  <label for="anster">答案</label>
                  <select name="anster" class="form-control">
                    <option value="0">正確</option>
                    <option value="1">錯誤</option>
                  </select>
                </div>
                <div v-if="option_type >= 2">
                    <h2>新增選項</h2>
                    <h6>剩餘可新增選項:@{{ 4-Option_count }} 次</h6>
                    <redio-component v-for="(val,index) in RedioData">
                        <div v-if="isUpdate">
                            <input type="text" name="redioName[]" v-bind:placeholder="val.message" class="form-control" v-bind:value="val.option_name">
                            <button type="button" v-on:click="Delete_input(index)">刪除選項</button><br>
                        </div>
                        <div v-if="isCreate">
                            <input type="text" name="redioName[]" v-bind:placeholder="val.message" class="form-control">
                            <button type="button" v-on:click="Delete_input(index)">刪除選項</button><br>
                        </div>
                    </redio-component>
                    <button type="button" v-on:click="createRedio()" v-if="Option_count < 4" class="btn btn-default">新增選項</button>
                </div>
                <button type="button" class="btn btn-default" onclick="ajax_create_question()" v-show="isCreate">送出請求</button>
                <button type="button" class="btn btn-default" onclick="ajax_update_question()" v-show="isUpdate">送出請求</button>                
              </form>
              <form id="CreateAnster" v-show="isShow">
                {{ method_field('PATCH') }}
                <input type="hidden" name="anster_type" v-model="anster_type">
                <div v-if="anster_type == 1">
                  <label for="anster">選擇答案</label>
                  <select name="anster" class="form-control">
                    <option value="0">是</option>
                    <option value="1">否</option>
                  </select>
                </div>
                <div v-if="anster_type == 2">
                  <label for="anster">選擇答案</label>
                  <select name="anster" class="form-control">
                    <option value="0">是</option>
                    <option value="1">否</option>
                  </select>
                </div>
                <div v-if="anster_type == 3">
                  <label for="anster">選擇複選答案</label>
                  <select name="anster[]" multiple="multiple" class="form-control">
                    <option value="0">是</option>
                    <option value="1">否</option>
                  </select>
                </div>
                <button type="button" class="form-control" onclick="ajax_create_anster()">送出請求</button>
              </form>
            </div>
            <hr>
            <div class="questionList">
              @foreach($result as $row)
              <h4>{{ $row->question }}</h4>
              @if(!empty($row->images->file_name))
                <img src="{{ asset("/uploads/".$row->images->file_path) }}" width="15%" height="15%">
              @endif
              @if($row['q_bank_type_id'] >= 2)
              <ul class="list-group">
                @foreach($row->options as $data)
                    @if($row->anster_type != "json")
                        @if($row->anster == $data['id'])
                        <li class="list-group-item active">{{ $data['option_name'] }}</li>
                        @else
                        <li class="list-group-item">{{ $data['option_name'] }}</li>
                        @endif
                    @else
                        @if(in_array($data['id'],json_decode($row->anster)))
                        <li class="list-group-item active">{{ $data['option_name'] }}</li>
                        @else
                        <li class="list-group-item">{{ $data['option_name'] }}</li>
                        @endif
                    @endif
                @endforeach
              </ul>
              @else
              <b>Anster:@if($row['anster'] == 0)
                  是</b> 
                  @else 
                  否</b> 
                  @endif<br>
              @endif
              <div role="group" aria-label="Basic example" class="btn-group">
                <button type="button" onclick="model_imageUpload({{$row['id']}})" class="btn btn-secondary">上傳圖片</button>
                <button type="button" onclick="model_question_delete({{$row['id']}})" class="btn btn-secondary">刪除題目</button>
                <button type="button" onclick="update_question({{$row['id']}})" class="btn btn-secondary">修改題目</button>
                <button type="button" onclick="ansterUpdate({{$row['id']}},{{$row['q_bank_type_id']}})" class="btn btn-secondary">修改答案</button>
              </div>
              <hr>
              @endforeach
            </div>
          </div>
        </div>
        <div class="col-md-2"></div>
      </div>
    </div>
  </body>
  <script type="text/javascript">
    var data_id = null; //data id
    var sysMsg = new Vue({
        el: '#system-Message',
        data: {
            'ajaxSuccess': false,
            'ajaxFailed' : false
        }
    });
    var CreateForm = new Vue({
        el: '#createTest',
        data:{
            isShow: true,
            Web_title: "新增題目",
            OptionShow: true,
            RedioData: [],
            option_type: null,
            Option_count: 0,
            isCreate: true,
            isUpdate: false
        },
        methods:{
            createRedio: function(){
                var data = {};
                data.message = 'please input string here!!';
                this.RedioData.push(data);
                this.Option_count = this.Option_count + 1;
            },
            Delete_input: function(index){
                this.RedioData.splice(index,1);
                this.Option_count = this.Option_count - 1;
            }
        }
    }); 
    var questionDeleteForm = new Vue({
        el: '#questionDeleteForm',
        data:{
            'qustion_name': null
        }
    });
    var CreateAnster = new Vue({
        el: '#CreateAnster',
        data:{
            isShow:false,
            anster_type:3,
        }
    })
    function ansterUpdate(id,type){
        if(typeof id == 'number' && typeof id != 'undefined' && typeof type == 'number' && typeof type != 'undefined'){
            data_id = id;
            ajax_read_ansterData();
            Vue.set(CreateForm,'isShow',false);
            Vue.set(CreateAnster,'anster_type',type);
            Vue.set(CreateAnster,'isShow',true);
        }
    }
    function model_imageUpload(id){
        $('#imageUpload').modal('show');
        data_id = id;
    }
    function model_question_delete(id){
        $('#deleteQuestion').modal('show');
        data_id = id;
        if(typeof data_id != 'undefined' && typeof data_id == 'number'){
            $.ajax({
                'url':'{{ url('question')}}/'+data_id,
                'type': 'get',
                'success': function(data){
                    Vue.set(questionDeleteForm,'qustion_name',data['question']);
                }
            })
        }
    }
    function update_question(id){
        data_id = id;
        ajax_get_question();
    }
    function ajax_create_question(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ url('admin/question/edit') }}/'+{{ $data_id }},
            type: 'post',
            data: $('#createTest').serialize(),
            success: function(data){
                data_id = data['data_id'];
                if(data['anster_type'] > 1){
                    Vue.set(CreateAnster,'anster_type',data['anster_type']);
                    Vue.set(CreateForm,'isShow',false);
                    Vue.set(CreateAnster,'isShow',true);
                    ajax_read_ansterData();
                }else{
                    Vue.set(CreateForm,'isShow',false);
                    Vue.set(sysMsg,'ajaxSuccess',true);
                    Vue.set(sysMsg,'ajaxFailed',false);
                    setTimeout("location.reload()",5000);
                }
            },
            error: function(xhr){
                Vue.set(sysMsg,'ajaxFailed',true);
                Vue.set(sysMsg,'ajaxSuccess',false);
            }
        })
    }
    function ajax_get_question(id){
        if(typeof data_id != 'undefined' && typeof data_id == 'number'){
            $.ajax({
                'url':'{{ url('admin/question')}}/'+data_id,
                'type': 'get',
                'success': function(data){
                    Vue.set(CreateForm,'isShow',true);
                    Vue.set(CreateForm,'Web_title','編輯題目');
                    Vue.set(CreateForm,'isCreate',false);
                    Vue.set(CreateForm,'isUpdate',true);
                    Vue.set(CreateAnster,'isShow',false);
                    $("select[name='option']").val(data['type']);
                    Vue.set(CreateForm,'option_type',data['type']);
                    $("input[name='title']").val(data['question']);
                    if(data['type'] >= 2){
                        if(typeof data['option'] != 'undefined'){
                            Vue.set(CreateForm,'RedioData',data['option']);
                            Vue.set(CreateForm,'Option_count',data['option'].length);
                        }
                    }
                }
            })
        }
    }
    function ajax_read_ansterData(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(typeof data_id != 'undefined' && typeof data_id == 'number'){
            $.ajax({
                url: '{{ url('admin/question/anster') }}/'+data_id,
                type: 'get',
                success: function(data){
                    $("select[name='anster']").empty();
                    $("select[name='anster[]']").empty();
                    data.forEach(function(result){
                        $("select[name='anster']").append("<option value='"+result['id']+"'>"+result['option_name']+"</option>");
                        $("select[name='anster[]']").append("<option value='"+result['id']+"'>"+result['option_name']+"</option>");
                    });
                    alert("請設定答案！");
                }
            })
        }
    }
    function ajax_create_anster(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: '{{ url('admin/question/anster') }}/'+data_id,
            type: 'PATCH',
            data: $('#CreateAnster').serialize(),
            success: function(){
                Vue.set(sysMsg,'ajaxSuccess',true);
                Vue.set(sysMsg,'ajaxFailed',false);
                setTimeout(location.reload(),5000);
            },
            error: function(){
                Vue.set(sysMsg,'ajaxFailed',true);
                Vue.set(sysMsg,'ajaxSuccess',false);
            }
        })
    }
    function ajax_update_question(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(typeof data_id != 'undefined' && typeof data_id == 'number'){
            $.ajax({
                url: '{{ url('admin/question/edit') }}/'+data_id,
                type: 'POST',
                data: $('#createTest').serialize(),
                success: function(data){
                    if(typeof data['data_id'] != 'undefined' && typeof data['data_id'] != 'number'){
                        Vue.set(CreateAnster,'anster_type',data['anster_type']);
                        Vue.set(CreateAnster,'isShow',true);
                        Vue.set(CreateForm,'isShow',false);
                        ajax_read_ansterData();
                    }else{
                        Vue.set(sysMsg,'ajaxSuccess',true);
                        Vue.set(sysMsg,'ajaxFailed',false);
                        setTimeout("location.reload()",5000);
                    }
                },
                error: function(){
                    Vue.set(sysMsg,'ajaxSuccess',false);
                    Vue.set(sysMsg,'ajaxFailed',true);
                }
            })
        }
    }
    function ajax_ImageUpload(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(typeof data_id != 'undefined' && typeof data_id == 'number'){
            $.ajax({
                url: '{{ url('admin/question/image/')}}/'+data_id,
                type: 'post',
                cache: false,
                processData: false,
                contentType: false,
                data: new FormData($('#imageUploadForm')[0]),
                success: function(){
                    Vue.set(sysMsg,'ajaxSuccess',true);
                    Vue.set(sysMsg,'ajaxFailed',false);
                    $('#imageUpload').modal('hide');
                    setTimeout("location.reload()",5000);
                },
                error: function(){
                    alert("檔案上傳失敗，請檢查檔案是否為JPG格式！");
                    Vue.set(sysMsg,'ajaxSuccess',false);
                    Vue.set(sysMsg,'ajaxFailed',true);
                }
            })
        }
    }
    function ajax_Imagedelete(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(typeof data_id != 'undefined' && typeof data_id == 'number'){
            $.ajax({
                url: '{{ url('admin/question/image/')}}/'+data_id,
                type: 'post',
                data: $('#imageDeleteForm').serialize(),
                success: function(){
                    Vue.set(sysMsg,'ajaxSuccess',true);
                    Vue.set(sysMsg,'ajaxFailed',false);
                    $('#imageUpload').modal('hide');
                    setTimeout("location.reload()",5000);
                },
                error: function(){
                    Vue.set(sysMsg,'ajaxSuccess',false);
                    Vue.set(sysMsg,'ajaxFailed',true);
                    $('#imageUpload').modal('hide');
                }
            })
        }
    }
    function ajax_updateAnster(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(typeof data_id != 'undefined' && typeof data_id == 'number'){
            $.ajax({
                url: '{{ url('admin/question/edit/') }}/'+data.id,
                type: 'post',
                data: $('#CreateAnster').serialize(),
                success: function(){
                    Vue.set(sysMsg,'ajaxSuccess',true);
                    Vue.set(sysMsg,'ajaxFailed',false);
                    setTimeout("location.reload()",5000);
                },
                error: function(){
                    Vue.set(sysMsg,'ajaxSuccess',false);
                    Vue.set(sysMsg,'ajaxFailed',true);
                }
            })
        }
    }
    function ajax_delete_question(){
        console.log("click");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if(typeof data_id != 'undefined' && typeof data_id == 'number'){
            $.ajax({
                url: '{{ url('admin/question/edit/') }}/'+data_id,
                type: 'post',
                data: $('#questionDeleteForm').serialize(),
                success: function(){
                    $('#deleteQuestion').modal('hide');
                    Vue.set(sysMsg,'ajaxSuccess',true);
                    Vue.set(sysMsg,'ajaxFailed',false);
                    setTimeout("location.reload()",5000);
                },
                error: function(){
                    Vue.set(sysMsg,'ajaxSuccess',false);
                    Vue.set(sysMsg,'ajaxFailed',true);
                }
            })
        }
    }
  </script>
</html>