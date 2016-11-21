<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{asset('dist/css/id-card.css')}}">
    <style>
        @font-face {
            font-family: banglFont;
            src: url('{{asset('dist/fonts/Siyamrupali.ttf')}}');
        }
        @font-face {
            font-family: engFont;
            src: url('{{asset('dist/fonts/LiberationSerif-Regular.ttf')}}');
        }
        .bangla-class{
            font-family: banglFont;
        }
        .bangla-class ul>li{
            font-size: .96em;
        }
        .bangla-class .card-footer-sing>div{
            font-size: 9px;
        }
    </style>
</head>
<body>
<div id="ansar-id-card-front" @if($type=='bng') class="bangla-class" @else style="font-family: engFont" @endif >

    <div class="card-header">
        <div class="card-header-left-part">
            <img src="{{asset('dist/img/ansar-vdp.png')}}" class="img-responsive">
        </div>
        <div class="card-header-right-part">
            <h4 style="@if($type=='bng') font-size: 1em @elseif($type=='eng') font-size:1em; @endif">{{$rd['title']}}</h4>
            <h5 style="font-size: 13px">{{$rd['id_no']}}
                : {{strcasecmp($type,'bng')==0?LanguageConverter::engToBng(GlobalParameter::generateSmartCard($ad->unit_code,$ad->ansar_id)):GlobalParameter::generateSmartCard($ad->unit_code,$ad->ansar_id)}}</h5>
        </div>
    </div>
    <div class="card-body">
        <img src="data:image/png;base64,{{DNS2D::getBarcodePNG(GlobalParameter::generateSmartCard($ad->unit_code,$ad->ansar_id),'QRCODE')}}"
             style="width: 50px;height: 50px;position: absolute;z-index: 3000;left: 58%;top: 44%">

        <div class="card-body-left">
            <ul>
                <li>{{$rd['name']}}<span class="pull-right">:</span></li>
                <li>{{$rd['rank']}}<span class="pull-right">:</span></li>
                <li>{{$rd['bg']}}<span class="pull-right">:</span></li>
                <li>{{$rd['unit']}}<span class="pull-right">:</span></li>
                <li>{{$rd['id']}}<span class="pull-right">:</span></li>
                <li>{{$rd['ed']}}<span class="pull-right">:</span></li>
            </ul>
        </div>
        <div class="card-body-middle">
            <ul>
                <li>{{$ad->name}}</li>
                <li>{{$ad->rank}}</li>
                <li>{{$ad->blood_group}}</li>
                <li>{{$ad->unit_name}}</li>
                <li>{{strcasecmp($type,'bng')==0?LanguageConverter::engToBng($id):$id}}</li>
                <li>{{strcasecmp($type,'bng')==0?LanguageConverter::engToBng($ed):$ed}}</li>
            </ul>
        </div>
        <div class="card-body-right">
            <img src="{{URL::to('/image').'?file='.$ad->profile_pic}}"
                  style="width: 80px">
        </div>
    </div>
    <div class="card-footer">
        <div class="card-footer-sing">
            <div><img src="{{URL::to('/image').'?file='.$ad->sign_pic}}"
                      style="width: 80px;height:10px"></div>
            <div>{{$rd['bs']}}</div>
        </div>
        <div class="card-footer-barcode">
            <img src="data:image/png;base64,{{DNS1D::getBarcodePNG(GlobalParameter::generateSmartCard($ad->unit_code,$ad->ansar_id),'C128')}}"
                 style="max-width: 100%">
        </div>
        <div class="card-footer-sing" style="float: right">
            <div><img src="{{URL::to('/image').'?file=data/authority/Signature.jpg'}}"
                      style="width: 80px;height:10px"></div>
            <div>{{$rd['is']}}</div>
        </div>
    </div>
    <h5 style="text-align: center;margin-top: 0;margin-bottom: 5px;font-size: 12px">{{$rd['footer_title']}}</h5>
</div>
<div>

</div>
</body>
</html>
