<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{$website->website_name}}-測驗項目</title>
    <meta name="description" content="{{ $website->description }}">
    <meta name="keyword" content="{{ $website->keyword }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/testItem.css') }}">
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/popper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vue.min.js') }}"></script> 
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/localization/messages_zh_TW.min.js') }}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>
  <body>
    <div tabindex="-1" role="dialog" id="testModal" class="modal">
      <div role="document" class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">即將開始測驗！</h5>
          </div>
          <div class="modal-body">
            <h3>你即將開始進行測驗，點擊開始測驗後即可開始進行測驗</h3>
            <hr>
            <div id="test-Content">
                <table class="table">
                    <tr> 
                        <td>測驗名稱</td>
                        <td>@{{ title }}</td>
                    </tr>
                    <tr>
                        <td>測驗內容</td>
                        <td>@{{ content }}</td>
                    </tr>
                </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" onclick="readon_question()" class="btn btn-primary">開始測驗</button>
            <button type="button" data-dismiss="modal" class="btn btn-secondary">關閉</button>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      @section('nav')
        @include('nav.index')
      @show
      <div class="Itemlist">
        <h3>請點擊題庫後開始進行測驗</h3>
        <hr>
        <table class="table">
          <tr>
            <td>開始測驗</td>
            <td>題庫名稱</td>
            <td>測驗類型</td>
            <td>測驗說明</td>
          </tr>
          @foreach($items_result as $row)
          <tr>
            <td>
              <button type="button" onclick="test_model({{$row->id}})" class="btn btn-primary btn-lg">開始測驗</button>
            </td>
            <td>{{ $row->item_name }}</td>
            <td>
                @if($row->isRepeat == 0)
                <b>可重複測驗，成績取前次較高者登錄</b>
                @else
                <b>允許測驗一次</b>
                @endif
            </td>
            <td>{{ $row->content }}</td>
          </tr>
          @endforeach
        </table>
      </div>
      <footer>
        <p>程式設計：小周</p>
      </footer>
    </div>
  </body>
  <script type="text/javascript">
    var item_id = null;
    var testShow = new Vue({
        el: "#test-Content",
        data:{
            title: null,
            content: null
        }
    });
    function test_model(id){
        item_id = id;
        $('#testModal').modal('show');
        $.ajax({
            url: '{{ url('student/ajax/items/')}}/'+item_id,
            type: 'get',
            success: function(msg){
                Vue.set(testShow,"title",msg['item_name']);
                Vue.set(testShow,"content",msg['content']);
            },
            error: function(){
                alert("題目資訊擷取失敗，請重新整理頁面後再試一次，多次出現此畫面請聯絡系統管理員");
            }
        });
    }
    function readon_question(){
        if(typeof item_id == 'number'){
            $.ajax({
                url: '{{ url('student/question/rand') }}/'+item_id,
                type: 'get',
                success: function(msg){
                  window.location.href= msg['href'];
                },
                error: function(jqXHR, textStatus, errorThrown){
                  alert("題庫產生失敗，系統訊息"+errorThrown['status']);
                }
            });
        }
    }
  </script>
</html>