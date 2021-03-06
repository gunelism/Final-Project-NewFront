@extends('pages.layout')

@section('title','Profil')

@section('content')

  <div id="breadcrumb">
  <div class="container">
     <div class="row">
        <div class="col-lg-12">
          <h1 class="text-left">Profilim</h1>
        </div>
        <div class="col-lg-9">
          <ul class="nav nav-tabs profil-navigatior">
            <li {{Request::is('profilim') ? "class=active" : ''}}><a data-toggle="tab" href="#profil-view">Profil görünüşü</a></li>
            <li {{Request::is('isteklerim') ? "class=active" : ''}}><a data-toggle="tab" href="#profil-isteklerim">İstəklərim</a></li>
            <li {{Request::is('desteklerim') ? "class=active" : ''}}><a data-toggle="tab" href="#profil-desteklerim">Dəstəklərim</a></li>
            <li {{Request::is('Destekolduqlarim') ? "class=active" : ''}}><a data-toggle="tab" href="#profil-destekolduqlarim">Dəstək olduqlarım</a></li>
            <li {{Request::is('Istekverdiklerim') ? " class=active" : ''}}><a data-toggle="tab" href="#profil-istekverdiklerim">İstək verdiklərim</a></li>
            <li {{Request::is('Bildirişlər') ? " class=active" : ''}}><a data-toggle="tab" href="#profil-notification">Bildirişlər</a></li>
            <li {{Request::is('tenzimlemeler') ? " class=active" : ''}}><a data-toggle="tab" href="#profil-settings">Tənzimləmələr</a></li>
          </ul>
        </div>
    </div>
  </div>
</div>

<section id="profil">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="tab-content">
          {{-- <================== PROFIL PART==================> --}}

          <div id="profil-view" class="tab-pane fade in {{Request::is('profilim') ? "active" : ''}}">
            <div class="col-lg-3 col-md-2 col-sm-2 col-xs-4 col-lg-offset-0 col-md-offset-0 col-sm-offset-0 col-xs-offset-4 padding0 profil-avatar">
              <img src="{{url('/image/'.Auth::user()->avatar)}}" alt="Avatar">
            </div>
            <div class="col-lg-9 col-sm-9 col-xs-6 col-lg-offset-0 col-md-offset-0 col-sm-offset-0 col-xs-offset-3 profil-name">
              <h2>{{Auth::user()->name}}</h2>
              <a href="{{url('/tenzimlemeler')}}"><h2 class="pull-right"><i class="fa fa-pencil-square-o"></i></h2></a>
              <hr>
            </div>
            <div class="col-lg-9 col-sm-9 col-xs-6 col-lg-offset-0 col-md-offset-0 col-sm-offset-0 col-xs-offset-3 profil-phone">
              <p><i class="fa fa-phone"></i> {{Auth::user()->phone}}</p>
            </div>
            <div class="col-lg-9 col-sm-9 col-sm-offset-2 col-xs-6 col-lg-offset-0 col-md-offset-0 col-sm-offset-0 col-xs-offset-3 profil-email">
              <p><i class="fa fa-envelope"></i> {{Auth::user()->email}}</p>
            </div>
            <div class="col-lg-9 col-md-9 col-md-offset-2 col-sm-9 col-sm-offset-2 col-xs-6 col-lg-offset-0 col-md-offset-0 col-sm-offset-0 col-xs-offset-3 profil-address">
              <p><i class="fa fa-map-marker"></i> {{Auth::user()->city}}</p>
            </div>
          </div>
          {{-- <================== PROFIL PART END ==================> --}}


          {{-- <================== ISTEKLERIM PART==================> --}}

          <div id="profil-isteklerim" class="tab-pane fade in{{Request::is('isteklerim') ? " active" : ''}}">
              @if (Session::has('istek_edited'))
                <div class="alert alert-success" role="alert">{{Session::get('istek_edited')}}</div>
              @endif
              {{-- <div class="table-responsive"> --}}
              @if ($istek == 0)
                <h1>İstəyiniz yoxdur</h1>
              @else
              <table class="table">
                <thead>
                  <tr>
                    <th>Dərc olunub?</th>
                    <th>Bitmə vaxtı</th>
                    <th>Başlıq</th>
                    <th>Təsvir</th>
                    <th>Şəkil</th>
                    <th>Yenilə & Sil</th>
                  </tr>
                </thead>
              @endif
                    @foreach ($Elan_all as $istekler)
                      <tbody>
                      @if ($istekler->user_id == Auth::user()->id && $istekler->type_id == '2')
                      <tr>
                        @php
                          $derc_status = 'Dərc olunmayıb';
                          $derc_icon = 'fa fa-times-circle-o fa-2x';
                          if ($istekler->status==1) {
															$derc_status = " Dərc olunub";
															$derc_icon = 'fa fa-check-circle-o fa-2x';
														}
                        @endphp
                        <td class="profil-status" title="{{$derc_status}}"><i class="{{$derc_icon}}"></i></td>
                        <td>{{$istekler->deadline}}</td>
                        <td>{{$istekler->title}}</td>
                        <td class="profil-subText">{{substr($istekler->about,0,100)}}...</td>
                        <td class="profil-photo"><img src="{{url('/image/'.$istekler->shekiller[0]->imageName)}}" class="img-responsive" alt="News image"></td>
                        <td class="profil-action">
                          <a href="{{url('/istek-edit/'.$istekler->id)}}" class="btn action-edit"><i class="fa fa-pencil-square"></i></a>
                          <a href="#" data-toggle="modal" data-target="#{{$istekler->id}}" class="btn action-delete"><i class="fa fa-trash"></i></a>
                        </td>
                      </tr>
                    </tbody>
                    {{-- For Delete Button Modal --}}
                    <div id="{{$istekler->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="modal-title text-center" id="myModalLabel">Əminsinizmi?</h4>
                            </div>
                            <div class="modal-body text-center">
                              <button class="btn btn-primary" type="button" class="close" data-dismiss="modal" aria-label="Close">Xeyir
                              </button>
                              <a href="{{url('/istek-delete/'.$istekler->id)}}" class="btn btn-danger">Bəli</a>
                            </div>
                          </div>
                      </div>
                    </div>
                    {{-- For Delete Button Modal END--}}
                      @endif
                    @endforeach
              </table>
            {{-- </div> --}}
          </div>


          {{-- <================== DESTEKLERIM PART ==================> --}}

          <div id="profil-desteklerim" class="tab-pane fade in{{Request::is('desteklerim') ? " active" : ''}}">
            {{-- <div class="table-responsive"> --}}
              <table class="table">
                @if ($destek == null)
                  <h1>Dəstəyiniz yoxdur</h1>
                @else
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Dərc olunub?</th>
                        <th>Bitmə vaxtı</th>
                        <th>Başlıq</th>
                        <th>Təsvir</th>
                        <th>Şəkil</th>
                        <th>Yenilə & Sil</th>
                      </tr>
                    </thead>
                  @endif
                @foreach ($Elan_all as $destekler)
                  <tbody>
                    <tr>
                  @if ($destekler->user_id == Auth::user()->id && $destekler->type_id == '1')
                    @php
                      $derc_status = 'Dərc olunmayıb';
                      $derc_icon = 'fa fa-times-circle-o fa-2x';
                      if ($destekler->status==1) {
                          $derc_status = " Dərc olunub";
                          $derc_icon = 'fa fa-check-circle-o fa-2x';
                        }
                    @endphp
                    <td class="profil-status" title="{{$derc_status}}"><i class="{{$derc_icon}}"></i></td>
                    <td>{{$destekler->deadline}}</td>
                    <td>{{$destekler->title}}</td>
                    <td class="profil-subText">{{substr($destekler->about,0,100)}}...</td>
                    <td class="profil-photo"><img src="{{url('/image/'.$destekler->shekiller[0]->imageName)}}" class="img-responsivse" alt="News image"></td>
                    <td class="profil-action">
                      <a href="{{url('/destek-edit/'.$destekler->id)}}" class="btn action-edit"><i class="fa fa-pencil-square"></i></a>
                      <a href="#" data-toggle="modal" data-target="#{{$destekler->id}}" class="btn action-delete"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                </tbody>
                {{-- For Delete Button Modal --}}
                <div id="{{$destekler->id}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                          <h4 class="modal-title text-center" id="myModalLabel">Əminsinizmi?</h4>
                        </div>
                        <div class="modal-body text-center">
                          <button class="btn btn-primary" type="button" class="close" data-dismiss="modal" aria-label="Close">Xeyir
                          </button>
                          <a href="{{url('/istek-delete/'.$destekler->id)}}" class="btn btn-danger">Bəli</a>
                        </div>
                      </div>
                  </div>
                </div>
                {{-- For Delete Button Modal END--}}
                  @endif
                @endforeach
              </table>
            {{-- </div> --}}
          </div>


          {{-- <================== DESTEK OLDUQLARIM PART ==================> --}}
{{-- {{dd($help[0]->type_id)}} --}}
          <div id="profil-destekolduqlarim" class="tab-pane fade in{{Request::is('Tekliflerim') ? " active" : ''}}">
          @if(count($help)!=0)
              @if($help[0]->type_id==2)
              <table class="table">
                <thead>
                  <tr>
                    <th>Qəbul olunub?</th>
                    <th>Başlıq</th>
                    <th>Təsvir</th>
                    <th>Şəkil</th>
                  </tr>
                </thead>

                  @foreach ($help as $helps)
                      <tbody>
                      @if ($helps->user_id == Auth::user()->id && $helps->type_id == 2)
                      <tr>
                          @php
                            $status = 'İmtina edilib';
                            $status_icon = 'fa fa-times-circle-o fa-2x';
                            if ($helps->data == 1)
                             {
                                $status = 'Qəbul olunub';
                                $status_icon = 'fa fa-check-circle-o fa-2x';
                              }
                          @endphp
                          <td class="profil-status" title="{{$status}}"><i class="{{$status_icon}}"></i></td>
                          <td>{{$helps->title}}</td>
                          <td class="profil-subText">{{substr($helps->description,0,100)}}...</td>
                          <td class="profil-photo"><img src="{{url('/image/'.$helps->imageName)}}" class="img-responsive" alt="News image"></td>

                      </tr>
                    </tbody>
                  @endif
               @endforeach
            </table>
              @else
              <h1>Heç bir istəyə dəstək olmamısız</h1>


            @endif
            @else
              <h1>Heç bir istəyə dəstək olmamısız</h1>

          @endif

            {{-- </div> --}}
          </div>


          {{-- <================== TEKLIFLERIM PART ==================> --}}

          <div id="profil-istekverdiklerim" class="tab-pane fade in{{Request::is('Tekliflerim') ? " active" : ''}}">
               {{-- {{dd($help[0]->type_id)}} --}}
          @if(count($help)!=0)
             @if($help[0]->type_id==1)
              <table class="table">
                <thead>
                  <tr>
                    <th>Qəbul olunub?</th>
                    <th>Başlıq</th>
                    <th>Təsvir</th>
                    <th>Şəkil</th>
                  </tr>
                </thead>
                    @foreach ($help as $helps)
                      <tbody>
                      @if ($helps->user_id == Auth::user()->id && $helps->type_id == 1)
                        <tr>
                            @php
                              $status = 'İmtina edilib';
                              $status_icon = 'fa fa-times-circle-o fa-2x';
                              if ($helps->data == 1)
                               {
                                  $status = 'Qəbul olunub';
                                  $status_icon = 'fa fa-check-circle-o fa-2x';
                                }
                            @endphp
                            <td class="profil-status" title="{{$status}}"><i class="{{$status_icon}}"></i></td>
                            <td>{{$helps->title}}</td>
                            <td class="profil-subText">{{substr($helps->description,0,100)}}...</td>
                            <td class="profil-photo"><img src="{{url('/image/'.$helps->imageName)}}" class="img-responsive" alt="News image"></td>

                        </tr>
                    </tbody>

                       @endif
                    @endforeach
              </table>
               @else
              <h1>Heç bir dəstəyə istək verməmisiniz</h1>


            @endif
            @else
              <h1>Heç bir dəstəyə istək verməmisiniz</h1>

          @endif

          </div>


          {{-- <================== NOTIFICATION PART==================> --}}

          <div id="profil-notification" class="tab-pane fade in {{Request::is('Bildirişlər') ? " active" : ''}}">
            {{-- @if (!isset($noti_message))
              <h1>Bildirişiniz yoxdur</h1>
            @else --}}
              @foreach($noti_message as $notification_message)
                @if($notification_message->elan_userid==Auth::user()->id)
                <div class="col-lg-12 padding0 notification-block">
                  <div class="col-lg-2">
                    <img src="{{url('/image/'.$notification_message->avatar)}}">
                  </div>
                  <div class="col-lg-9">
                    <h4 class="profil-notification-title">
                      @if($notification_message->type_id==2)
                        <span class="special-istek">{{$notification_message->name}}</span> adlı istifadəçi istəyinizə dəstək vermək istəyir !
                      @elseif($notification_message->type_id==1)
                        <span class="special-destek">{{$notification_message->name}}</span> adlı istifadəçi dəstəyinizdən yararlanmaq istəyir !
                      @endif
                    </h4>
                    <p class="profil-notification-desc">{{$notification_message->description}}</p>
                    <div class="col-lg-1 col-lg-offset-11 col-md-offset-10  col-sm-offset-9 col-xs-offset-4 padding0">
                      <p class="profil-notification-full"><a href="{{url('/Bildiriş/'.$notification_message->id)}}" class="btn zaa">Tam müraciətə bax<i class="fa fa-angle-double-right"></i></a></p>
                    </div>
                  </div>
                </div>
                @endif
              @endforeach

              @foreach($data_join as $data_joins)
                @if($data_joins->qars_userid==Auth::user()->id && $data_joins->data == 1)
              <div class="col-lg-12 padding0 notification-block">
                    <div class="col-lg-2">
                        <img src="{{url('/image/'.$data_joins->avatar)}}">
                    </div>
                    <div class="col-lg-9">
                      <h4 class="profil-notification-title">
                        @if($data_joins->type_id==2)
                          <span class="special-istek">{{$data_joins->name}}</span>  adlı istifadəçi dəstəyinizi qəbul etdi !
                        @endif
                        @if($data_joins->type_id==1)
                          <span class="special-destek">{{$data_joins->name}}</span>  adlı istifadəçi istəyinizi qəbul etdi !
                        @endif
                      </h4>
                      <p class="profil-notification-desc">{{$data_joins->description}}</p>
                      <p class="profil-notification-full pull-right"><a href="{{url('/message/'.$data_joins->id)}}" class="btn zaa">Tam müraciətə bax<i class="fa fa-angle-double-right"></i></a></p>
                    </div>
              </div>
            @endif
            @endforeach
          </div>
          {{-- <================== NOTIFICATION PART END ==================> --}}


          {{-- <================== MESSAGE PART  ==================> --}}
          {{-- <div id="profil-ismariclar" class="tab-pane fade in {{Request::is('Ismarıclar') ? " active" : ''}}">
          </div> --}}


          {{-- <================== TENZIMLEMELER PART==================> --}}
        <div id="profil-settings" class="tab-pane fade in {{Request::is('tenzimlemeler') ? " active" : ''}}">
        @if (Session::has('imageerror'))
          <div class="alert alert-danger" role="alert">{{Session::get('imageerror')}}</div>
        @endif
        @if (Session::has('added'))
          <div class="alert alert-success" role="alert">{{Session::get('added')}}</div>
        @endif
        @if ($errors->has('name') || $errors->has('phone') || $errors->has('avatar'))
        <span class="help-block">
            <div class="alert alert-danger"><p>Ulduz ilə işarəli xanaları boş saxlamayın.</p></div>
        </span>
        @endif
        <div id="ErrorImage" ></div>

          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding0 profil-avatar">
            <img src="{{url('/image/'.Auth::user()->avatar)}}" class="shoUploadedImg center-block" alt="Avatar">
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <form action="{{url('/tenzimlemeler')}}" method="post" enctype="multipart/form-data">
              {{csrf_field()}}
              <p>
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="email">Ad,Soyad<SPAN> *</SPAN></label>
                <input class="form-control" type="text" name="name" value="{{Auth::user()->name}}">
              </div>
            </p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <p>
              <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                <label for="email">Əlaqə nömrəsi<SPAN> *</SPAN></label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <input id="operator" type="hidden" name="operator" value="{{substr(Auth::user()->phone,4,2) == '55' ? '55' : substr(Auth::user()->phone,4,2) }}">
                        +994
                            <select id="operator-numbers" name="operator-numbers">
                              <option {{substr(Auth::user()->phone,4,2) == '55' ? 'selected' : '' }}>55</option>
                              <option {{substr(Auth::user()->phone,4,2) == '51' ? 'selected' : '' }}>51</option>
                              <option {{substr(Auth::user()->phone,4,2) == '50' ? 'selected' : '' }}>50</option>
                              <option {{substr(Auth::user()->phone,4,2) == '70' ? 'selected' : '' }}>70</option>
                              <option {{substr(Auth::user()->phone,4,2) == '77' ? 'selected' : '' }}>77</option>
                            </select>
                        </div>
                  <input type="text" class="form-control" name="phone" maxlength="7" value="{{substr(Auth::user()->phone,6)}}">
                </div>
              </div>
            </p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <p>
              <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">

                <label for="file">Şəkil:</label>
                <a class="forImg form-control btn btn-default">Şəkil Seç</a>
                <input class="imgInput form-control hidden" type="file" name="avatar">
              </div>
            </p>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <p>
              <div class="form-group">
                <label for="email">Ünvan:</label>
                <input type="text" id="city" class="hidden" name="city" value="{{Auth::user()->city}}">
                <select id="CitySelectOption" class="form-control" name="city">
                             <option value="{{Auth::user()->city}}">{{Auth::user()->city}}</option>
                             <option value="Bakı">Bakı</option>
                             <option value="Abşeron">Abşeron</option>
                             <option value="Ağdam">Ağdam</option>
                             <option value="Ağdaş">Ağdaş</option>
                             <option value="Ağcabədi">Ağcabədi</option>
                             <option value="Ağstafa">Ağstafa</option>
                             <option value="Ağsu">Ağsu</option>
                             <option value="Astara">Astara</option>
                             <option value="Babək">Babək</option>
                             <option value="Balakən">Balakən</option>
                             <option value="Bərdə">Bərdə</option>
                             <option value="Beyləqan">Beyləqan</option>
                             <option value="Biləsuvar">Biləsuvar</option>
                             <option value="Cəbrayıl">Cəbrayıl</option>
                             <option value="Cəlilabad">Cəlilabad</option>
                             <option value="Culfa">Culfa</option>
                             <option value="Daşkəsən">Daşkəsən</option>
                             <option value="Füzuli">Füzuli</option>
                             <option value="Gədəbəy">Gədəbəy</option>
                             <option value="Goranboy">Goranboy</option>
                             <option value="Göyçay">Göyçay</option>
                             <option value="Göygöl">Göygöl</option>
                             <option value="Hacıqabul">Hacıqabul</option>
                             <option value="Xaçmaz">Xaçmaz</option>
                             <option value="Xızı">Xızı</option>
                             <option value="Xocalı">Xocalı</option>
                             <option value="Xocavənd">Xocavənd</option>
                             <option value="İmişli">İmişli</option>
                             <option value="İsmayıllı">İsmayıllı</option>
                             <option value="Kəlbəcər">Kəlbəcər</option>
                             <option value="Kəngərli">Kəngərli</option>
                             <option value="Kürdəmir">Kürdəmir</option>
                             <option value="Qəbələ">Qəbələ</option>
                             <option value="Qax">Qax</option>
                             <option value="Qazax">Qazax</option>
                             <option value="Qobustan">Qobustan</option>
                             <option value="Quba">Quba</option>
                             <option value="Qubadlı">Qubadlı</option>
                             <option value="Qusar">Qusar</option>
                             <option value="Laçın">Laçın</option>
                             <option value="Lənkəran">Lənkəran</option>
                             <option value="Lerik">Lerik</option>
                             <option value="Masallı">Masallı</option>
                             <option value="Neftçala">Neftçala</option>
                             <option value="Oğuz">Oğuz</option>
                             <option value="Ordubad">Ordubad</option>
                             <option value="Sumqayıt">Sumqayıt</option>
                             <option value="Saatlı">Saatlı</option>
                             <option value="Sabirabad">Sabirabad</option>
                             <option value="Sədərək">Sədərək</option>
                             <option value="Salyan">Salyan</option>
                             <option value="Samux">Samux</option>
                             <option value="Şabran">Şabran</option>
                             <option value="Şahbuz">Şahbuz</option>
                             <option value="Şəki">Şəki</option>
                             <option value="Şamaxı">Şamaxı</option>
                             <option value="Şəmkir">Şəmkir</option>
                             <option value="Şərur">Şərur</option>
                             <option value="Şuşa">Şuşa</option>
                             <option value="Siyəzən">Siyəzən</option>
                             <option value="Tərtər">Tərtər</option>
                             <option value="Tovuz">Tovuz</option>
                             <option value="Ucar">Ucar</option>
                             <option value="Yardımlı">Yardımlı</option>
                             <option value="Yevlax">Yevlax</option>
                             <option value="Zəngilan">Zəngilan</option>
                             <option value="Zaqatala">Zaqatala</option>
                             <option value="Zərdab">Zərdab</option>
                       </select>
              </div>
            </p>
          </div>
          <div class="col-lg-12">
            <p>
              <div class="form-group pull-right">
                <input class="btn" type="submit" name="submit" value="Göndər">
              </div>
            </p>
            </form>
          </div>
        </div>
        {{-- <================== TENZIMLEMELER END==================> --}}

        </div>
      </div>
    </div>
  </div>
</section>
@endsection
