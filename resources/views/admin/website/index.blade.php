<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>{{ $website->website_name }}－網站基本設定</title>
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
        <div class="container">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <h1>網站資訊修改</h1><hr>
                    <form id="webform">
                        {{ method_field('patch') }}
                        <label for="site_name">網站名稱</label>
                        <input type="text" name="site_name" class="form-control" value="{{ $result->website_name }}"><br>
                        <label for="description">網站描述</label>
                        <input type="text" name="description" class="form-control" value="{{ $result->description }}"><br>
                        <label for="keyword">關鍵字</label>
                        <input type="text" name="keyword" class="form-control" value="keyword"><br>
                        <input type="submit" name="submit" class="btn btn-default" value="送出">
                    </form>
                    <script type="text/javascript">
                        $("#webform").validate({
                            submitHandler:function(){
                                $.ajaxSetup({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                                $.ajax({
                                    url: '{{ url('admin/website') }}',
                                    type: 'post',
                                    data: $("#webform").serialize(),
                                    success: function(){
                                        alert("更新成功！");
                                        location.reload();
                                    },
                                    error: function(){
                                        alert("更新失敗！");
                                    }
                                });
                            },
                            rules:{
                                site_name: 'required',
                                description: 'required'
                            }
                        });
                    </script>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </body>
</html>