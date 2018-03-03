<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{$website->website_name}}-測驗介面</title>
    <meta name="description" content="{{ $website->description }}">
    <meta name="keyword" content="{{ $website->keyword }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/quiz.css') }}">
    <script type="text/javascript" src="https://vuejs.org/js/vue.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="test/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/localization/messages_zh_TW.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  </head>
  <body>
    <header> 
      <h1>測驗題目：{{$item->item_name}}</h1>
    </header>
    <div class="quizView">
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        @foreach($result as $row)
          <h3>題目:{{ $row->question}}</h3>
          @if(!empty($row->images->file_name))
          <img src="{{ URL::asset('uploads/question')."/".$row->images->file_name }}" class="img-responce">
          @endif
          <form id="quiz_form">
          {{ method_field("PATCH") }}
          <input type="hidden" name="qid" value="{{ $row->id }}">
          @if($row->q_bank_type_id == 1)
            <table class="table">
                <tr>
                    <td>
                        @if($row->anster == 0 && !is_null($row->anster))
                        <input type="radio" name="anster" value="0" checked="true">
                        @else
                        <input type="radio" name="anster" value="0">
                        @endif
                    </td>
                    <td>是</td>
                </tr>
                <tr>
                    <td>
                        @if($row->anster == 1)
                        <input type="radio" name="anster" value="1" checked="true">
                        @else
                        <input type="radio" name="anster" value="1">
                        @endif
                    </td>
                    <td>否</td>
                </tr>
            </table>
          @endif
          @if($row->q_bank_type_id == 2)
          <table class="table">
                @foreach($row->options as $data)
                <tr>
                    <td>
                        @if($data->id == $row->anster)
                        <input type="radio" name="anster" value="{{ $data->id }}" checked="true">
                        @else
                        <input type="radio" name="anster" value="{{ $data->id }}">
                        @endif
                    </td>
                    <td>{{$data->option_name}}</td>
                </tr>
                @endforeach
          </table>
          @endif
          @if($row->q_bank_type_id == 3)
          <table class="table">
                @foreach($row->options as $data)
                <tr>
                    <td>
                        @if(!is_null($row->anster))
                            @if(in_array($data->id,json_decode($row->anster)))
                            <input type="checkbox" name="anster[]" value="{{ $data->id }}" checked="true">
                            @else
                            <input type="checkbox" name="anster[]" value="{{ $data->id }}">
                            @endif
                        @else
                        <input type="checkbox" name="anster[]" value="{{ $data->id }}">
                        @endif
                    </td>
                    <td>{{$data->option_name}}</td>
                </tr>
                @endforeach
          </table>
          @endif
        @endforeach
        <input type="submit" class="btn btn-primary btn-lg btn-block" value="送出答案"><br>
        </form>
        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="score_count()">結束測驗並計算分數</button>
        {{ $result->links('vendor/pagination/bootstrap-4') }}
        <script type="text/javascript">
            var question_count = {{ $count }};
            var this_question_number = {{ empty($_GET['page'])?"1":(int) $_GET['page'] }};
            $("#quiz_form").validate({
                submitHandler: function(){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ url('student/quiz') }}',
                        type: 'post',
                        data: $("#quiz_form").serialize(),
                        success: function(){
                            alert("答案已送出！");
                            if(question_count != this_question_number){
                                this_question_number++;
                                location.href="{{url('student/quiz')}}/{{$item->id}}?page="+this_question_number;
                            }
                        }
                    })
                },rules:{
                    'qid': 'required',
                    'anster': 'required'
                }
            })
            function score_count(){
                if(confirm("你確認要結束作答，確認後將開始計算成績")){
                    $.ajax({
                        url: '{{ url('student/count') }}',
                        type: 'get',
                        success: function(msg){
                            alert("測驗結束，本次成績為"+msg['score']+"分！");
                            window.location.href= msg['href'];
                        },
                        error: function(){
                            alert("System Error!");
                        }
                    });
                }
            }
        </script>
        </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </body>
</html>