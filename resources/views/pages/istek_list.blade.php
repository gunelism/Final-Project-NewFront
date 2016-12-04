@extends('pages.layout')

@section('title', 'Bütün istəklər')

@section('content')
  <div id="breadcrumb">
    <div class="container">
       <div class="row">
         <div class="col-lg-12">
           <h1 class="text-left">Bütün İstəklər</h1>
         </div>
      </div>
    </div>
  </div>
  <section id="news">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <!-- News block -->
          @foreach ($datas as $data)
            @if($data->status=='1' && $data->type_id=='2')
              <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 padding0 thumbnail">
                <div class="news-block">
                  <div class="news-image col-lg-12 padding0">
                    <div class="news-type news-istek">
                      İstək
                    </div>
                    <a href="{{url('/single/'.$data->id)}}"><img src="{{url('/image/' .$data->shekiller[0]->imageName)}}" alt="İstək image" /></a>
                  </div>
                  <div class="news-content col-lg-12 padding0">
                    <div class="news-title">
                      <a href="{{url('/single/'.$data->id)}}">{{$data->title}}</a>
                    </div>
                    <div class="news-location col-lg-12">
                      <p><i class="fa fa-map-marker"></i> {{$data->location}}</p>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </div>
        </div>
      </div>
    </div>
  </section>
@endsection