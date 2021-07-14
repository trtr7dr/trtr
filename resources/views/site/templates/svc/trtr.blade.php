
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Королевство ТырТыр</title>
        <meta name="description" value="Жизнь и смерть королевства ТырТыр Семь Дыр">
        <meta name="keywords" valeu="жизнь, эмуляция жизни, симуляция жизни, программные миры, тыртыр 7 дыр, trtr7dr">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <link rel="shortcut icon" href="{{asset('assets/trtr.ico')}}" type="image/x-icon">
        <link rel="stylesheet" href="{{asset('/css/style.css')}}"> 
        <link rel="stylesheet" href="{{asset('css/bootstrap/css/bootstrap.css')}}">
        <link rel="stylesheet" href="{{asset('/css/svc/trtr.css')}}">
    </head>
    <body>

        <div class=" col-md-8">
            <h1>Королевство ТырТыр Семь дыр {{$data['world']->id}}<span class="page_project"><a href="/aboutrtr">Что это?</a></span></h1>
        </div>
        <div class="info col-md-2">
            <code><span id="watch"></span></code><br>
            <img src="/assets/trtr/suns.svg" id="day">
            Волшебной пыли: <b>{{$data['world']->dust}}</b> <br>
            Моментов: <b>{{$data['world']->instant}}</b><br>
            Население: <b>{{count($data['alive'])}}</b><br>
            Правитель: <b><a href="/look/{{$data['ukrf']->id}}">{{$data['ukrf_k']}}</a></b><br>
            Религия: <b><a href="/sacrificer/god/{{$data['god']->id}}">{{$data['god']->name}}</a></b><br>
            Смертей: <b>{{$data['world']->death}}</b> <br>
            Альтернатива: <a href="/trtr2">ТырТыр2</a> <br>

        </div>


        <br><br>

        <div class="col-md-12 map" >
            <div id="legend_but"><span onclick="edit_map('info')">Что где?</span></div>
            <div id='map_param'>
                {@foreach($data['alive'] as $el) "{{$el->id}}": "{{$el->position}}", @endforeach
            </div>
            @php $r = 0 @endphp
            @foreach($data['alive'] as $el)
            <div class="pers_map ">
                <div id="{{$el->id}}" class="pers_block">
                    @php $r = rand(1,7) @endphp
                    <a href="#line{{$el->id}}" onclick='show_line("line{{$el->id}}")'><img src="{{$el->figure}}standart.png" id="img{{$el->id}}" class="position{{$r}} creators{{$el->creators}}"></a>
                    <div class="m_text">{{$el->name[0]}}</div>
                </div>
            </div>
            @endforeach
                @if(count($data['alive']) > 35)
                <img src="/assets/trtr/map/35.png" id="trtr_map">
                @elseif(count($data['alive']) > 30)
                <img src="/assets/trtr/map/30.png" id="trtr_map">
                @elseif(count($data['alive']) > 20)
                <img src="/assets/trtr/map/20.png" id="trtr_map">
                @elseif(count($data['alive']) > 10)
                <img src="/assets/trtr/map/10.png" id="trtr_map">
                @elseif(count($data['alive']) > 5)
                <img src="/assets/trtr/map/5.png" id="trtr_map">
                @elseif(count($data['alive']) >= 1)
                <img src="/assets/trtr/map/1.png" id="trtr_map">
                @endif
            <img src="/assets/trtr/map/trtr_info.png" id="info_map" class="no_info">

        </div>

        <table>
            <tr class="legend">
                <td>Имя</td>
                <td>Лет</td>
                <td>Локация</td>
                <td>Работа</td>
                <td>Любовь</td>
                <td>Жизнь</td>
            </tr>
            @foreach($data['alive'] as $el)
            <tr id="line{{$el->id}}" name="line{{$el->id}}" class="tr_line">
                <td class="name">{{$el->sub_name}} {{$el->name[0]}} {{$el->nick_name}}</td>
                <td style="text-align: center">{{$el->old}}</td>
                <td class="location">{{$data['location'][$el->position - 1]->name}} ({{$el->steps}})</td>
                <td class="tnum">{{$el->work}}</td>
                <td class="tnum">{{$el->love}}</td>
                <td class="tnum">{{$el->live}}</td>
            </tr>

            @endforeach
        </table>

        <div class="row">
            <img src="/assets/trtr/tr.png" width="100%">
        </div>


        <div class="row">
            <div class="col-md-12 live_alive gradient">

                @foreach($data['alive'] as $el)
                <div class="pers_list col-md-2">
                    <a href="#line{{$el->id}}" onclick='show_line("line{{$el->id}}")'><img src="{{$el->figure}}standart.png" class="port{{$el->creators}}"></a>
                    <div class="per_text">
                        @if($el->creators === 1)
                        <img src="/assets/trtr/creators/v.svg" class="creators" />
                        @elseif($el->creators === 2)
                        <img src="/assets/trtr/creators/w.svg" class="creators" />
                        @endif
                        @if($el->king === 1)
                        <span class="king">♕
                            @endif
                            {{$el->sub_name}}
                            {{$el->name[0]}}
                            {{$el->nick_name}}
                            @if($el->king === 1)
                            ♕</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-md-12 live_alive gradient_dark">
                @foreach($data['deadmen'] as $el)
                <div class="pers_list col-md-1">
                    <img src="{{$el->figure}}standart.png" >
                    <div class="per_text">
                        @if($el->king === 1)
                        ♔
                        @endif
                        {{$el->name[0]}}
                        @if($el->king === 1)
                        ♔
                        @endif
                    </div>
                </div>
                @endforeach
                <a href="/night"><img src="/assets/trtr/items/grob.png" id="grob" /></a>
            </div>
        </div>

        <div class="news">
            @foreach($data['news'] as $el)
            <div class="one_news">〉{!!$el->result!!}</div>
            @endforeach
            <br>
            <div class="render">{{ $data['news']->render()}}</div>
        </div>

        <br><br><br>


        <script src="{{asset('/js/jquery-3.2.1.min.js')}}"></script>
        <script src="{{asset('/js/imagesloaded.pkgd.min.js')}}"></script>
        <script src="{{asset('/js/TweenMax.min.js')}}"></script>
        <script src="{{asset('/js/jquery.ba-dotimeout.min.js')}}"></script>
        <script src="{{asset('/js/svc/paralax.js')}}"></script>
        <script src="{{asset('/js/svc/trtr.js')}}"></script>

        @include('site.footer')

    </body>
</html>
