<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="{{ url('admin/website') }}">{{$website->website_name}}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="{{ url('/') }}">回首頁</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            功能選單
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ url('admin/users')}}">會員系統</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ url('admin/test/subject')}}">測驗科目</a>
            <a class="dropdown-item" href="{{ url('admin/test/items')}}">測驗項目</a>
          </div>
        </li>
      </ul>
    </div>
      <button type="button" class="btn btn-outline-primary" onclick="location.href='{{url('logout')}}'">Log out</button>
  </nav>