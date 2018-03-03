<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>show student score</title>
    </head>
    <body>
        <table width="100%">
            <tr>
                <td>學生</td> 
                <td>測驗科目</td> 
                <td>分數</td>
            </tr>
            @foreach($result as $row)
            <tr>
                <td>{{$row->user->last_name}}{{$row->user->firet_name}}</td> 
                <td>{{$row->item->item_name}}</td> 
                <td>{{$row->score}}</td>
            </tr>
            @endforeach
        </table>
    </body>
</html>