<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{asset('dist/css/letter.css')}}">
    <link href="{{asset('dist/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css"/>
</head>
<body>
<div>
    <?php $i = 0;?>
    @foreach(array_chunk($result,5) as $r)
        @include('HRM::Letter.'.$view,['result'=>$r])
        <?php $i++ ?>
    @endforeach
</div>

<script src="{{asset('plugins/jQuery/jQuery-2.1.4.min.js')}}" type="text/javascript"></script>
<script src="{{asset('dist/js/bangla_calender.js')}}" type="text/javascript"></script>
<script>
    $(function () {
        $(document).on('click', '#print-report', function (e) {
            e.preventDefault();
            window.print();
        });
    });
    jQuery(document).ready(function ($) {
        $.each($("td.jsDateConvert span"), function (index, value) {
            var dateText = $(value).html();
            dateText = dateText.replace(new RegExp(/[\n\s\r\t]+/g), '');
            dateText = dateText.split(" ");
            dateText = dateText[0].split("/");
            $(value).html(convertToBanglaDate(dateText[2], dateText[1], dateText[0]));
        });
    });
</script>
</body>
</html>