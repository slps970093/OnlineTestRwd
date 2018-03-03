<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{$website->website_name}}-學生成績</title>
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
    <div class="container">
      @section('nav')
        @include('nav.index')
      @show
      <div class="Itemlist">
        <table class="table">
            <tr>
                <td>科目</td>
                <td>分數</td>
            </tr>
            @foreach($result as $row)
            <tr>
                <td>{{$row->item->item_name}}</td>
                <td>{{$row->score}}</td>
            </tr>
            @endforeach
        </table>
      </div>
      <footer>
        <p>程式設計：小周</p>
      </footer>
    </div>
  </body>
</html>