<nav class="navbar navbar-expand-lg navbar-light bg-light"><a href="{{ URL::to('/') }}" class="navbar-brand">{{$website->website_name}}</a>
    <div type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></div>
    <div id="navbarSupportedContent" class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
        @if(!Auth::check())
        <li class="nav-item"> <a href="{{ url('login') }}" class="nav-link">請先登入</a></li>
        @endif
        @if(Auth::check())
        <li class="nav-item dropdown"><a href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">測驗科目</a>
            <div aria-labelledby="navbarDropdown" class="dropdown-menu">
            @foreach($subject as $row)
            <a href="{{ url('student/items')."/".$row->id }}" class="dropdown-item">{{ $row->subjects_name }}</a>
            @endforeach
            </div>
        </li>
        <li class="nav-item"> <a href="{{ url('student/user') }}" class="nav-link">用戶中心</a></li>
        @if(!Gate::allows('isAdmin'))
        <li class="nav-item"> <a href="{{ url('student/score') }}" class="nav-link">成績查詢</a></li>
        @endif
        @if(Gate::allows('isAdmin'))
        <li class="nav-item"> <a href="{{ url('admin/website') }}" class="nav-link">管理者介面</a></li>
        @endif
        <li class="nav-item"> <a href="{{ url('logout') }}" class="nav-link">登出</a></li>
        @endif
        </ul>
    </div>
</nav>