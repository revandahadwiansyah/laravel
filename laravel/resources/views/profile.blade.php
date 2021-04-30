@extends('layouts.app')

@section('content')
<script src="{{ asset('js/modules/Home.js?v=' . time()) }}"></script>

<div class="container">
    <div class="row justify-content-center">
        <div class="container bootstrap snippet">
            <div class="row">
                <div class="col-sm-10 panel-heading"><h1>{{strtoupper(Request::segment(1)) .' '. strtoupper(Request::segment(2))}}</h1></div>
            </div>
            <div class="row">
                <div class="col-sm-3"><!--left col-->


                    <div class="text-center">
                        <img src="https://www.signivis.com/img/custom/avatars/member-avatar-01.png" alt="" class="img-rounded img-responsive image-profile" id="image-profile" style="height: 45%; width: 125%">
                    </div></hr><br>

<!--                    <ul class="list-group" style="height: 50vh; overflow: auto;">
                        <li class="list-group-item text-muted">Last Login <i class="fa fa-dashboard fa-1x"></i></li>                        
                        @if(isset($watchdogs[0]) == true)
                        @foreach($watchdogs as $logs)
                        <li class="list-group-item text-right"><span class="pull-left"><strong>{{$logs->created}}</strong></span></li>
                        @endforeach
                        @endif
                    </ul> -->

                </div><!--/col-3-->
                <div class="col-sm-9">
                    <div class="tab-content">
                        <div class="tab-pane active" id="home">
                            <hr>
                            <form class="form" method="POST" action="{{ URL('/profile/updateProfile') }}" id="updateForm">
                                @csrf
                                <input type="hidden" class="form-control" name="id" id="id" readonly="readonly" value="{{ (is_object($profileDetails) === true)? $profileDetails->id : ''}}">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label for="fullname"><h4>Name</h4></label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="name" title="enter your name if any." readonly="readonly" value="{{ (is_object($profileDetails) === true)? $profileDetails->fname . ' '. $profileDetails->lname : ''}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label for="phone"><h4>pHONE</h4></label>
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="phone" title="enter your username." readonly="readonly" value="{{ (is_object($profileDetails) === true)? $profileDetails->phone : ''}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label for="email"><h4>Email</h4></label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email." readonly="readonly" value="{{ (is_object($profileDetails) === true)? $profileDetails->email : ''}}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label for="password"><h4>Previous Password</h4></label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-6">
                                        @if($errors->any())
                                        <h4>{{$errors->first('msg')}}</h4>
                                        @endif
                                        <label for="password2"><h4>New Password</h4></label>
                                        <input type="password" class="form-control" name="new_pasword" id="new_pasword" placeholder="new password" title="enter your new.">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <br>
                                        <button class="btn btn-lg btn-success" onclick="updated({{(is_object($profileDetails) === true)? $profileDetails->id : '0'}})"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                        <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                                    </div>
                                </div>
                            </form>
                        </div><!--/tab-pane-->
                    </div><!--/tab-pane-->
                </div><!--/tab-content-->

            </div><!--/col-9-->
        </div>
    </div>
</div>
@endsection
