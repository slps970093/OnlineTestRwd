<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>{{$website->website_name}}</title>
    <meta name="description" content="{{ $website->description }}">
    <meta name="keyword" content="{{ $website->keyword }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/webpage.css')}}">
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
      <div class="bigPicture">
        <div class="content">
          <h1>線上測驗系統</h1>
        </div>
      </div>
      <div class="features-nav">
        <div class="features"><img src="{{ URL::asset('images/webbowser.jpg') }}" width="30%" height="30%">
          <h4>不需安裝，打開瀏覽器即可開始測驗</h4>
          <p>本測驗系統只需開啟瀏覽器即可使用，不需安裝任何軟體，並且支援CHROME/FIRFOX/MICREOSOFT EDGE 瀏覽器</p>
        </div>
        <div class="features"><img src="{{ URL::asset('images/mobile.png') }}" width="15%" height="30%">
          <h4>支援手機版線上測驗</h4>
          <p>本測驗系統支援手機線上測驗，讓你不需要在電腦桌前即可做線上測驗</p>
        </div>
      </div>
      <footer>
        <p>程式設計：小周</p>
      </footer>
    </div>
  </body>
</html>