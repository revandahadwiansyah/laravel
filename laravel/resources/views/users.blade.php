@extends('layouts.app')

@section('content')
<?php
//echo '<pre>'; print_r($users); echo '<br>';
//echo 'full:' .  url()->full() .'<br>';
//echo 'current:' .  url()->current() .'<br>';
//echo 'previous:' .  url()->previous() .'<br>';
//exit; 
?>
<link href="{{ asset('css/internal/app.css?v=' . time()) }}" rel="stylesheet">
<script src="{{ asset('js/modules/Users.js?v=' . time()) }}"></script>
<input type="hidden" id="publicUrl" value="{{url('/')}}">

<div class="container">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="panel-heading titles"><h2>{{strtoupper(Request::segment(1))}}</h2></div>
                    <div style="float: right;">
                        <button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add New Users" onclick="AddNew('member')">Add Users</button>
                    </div>

                    <!-- Search/Filter -->
                    <div class="container">
                        <form action="{{ url(Request::segment(1) . '/list') }}" method="POST" role="filters" id="filters" class="login100-form validate-form">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <div>Search:</div>

                                <input type="text" class="form-control filter-box" id="filterbox" name="filters" placeholder="Search/filter" value="{{$filters}}"> 
                            </div>
                        </form>
                    </div>
                    <!-- End Search/Filter -->

                    <div class="table-responsive">
                        <table class="table user-list">
                            <thead>
                                <tr>
                                    <th><span>ID</span></th>
                                    <th><span>Fullname</span></th>
                                    <th><span>Level</span></th>
                                    <th><span>Phone</span></th>
                                    <th><span>Register Date</span></th>
                                    <th class="text-center"><span>Status</span></th>
                                    <th class="text-center"><span>Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($users[0]) == true)
                                @foreach($users as $user)
                                <tr data="{{$user->id}}">
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->fname}} {{$user->lname}}</td>
<!--                                    <td>
                                        @if($user->files != '' && $user->files != null)
                                        <img src="{{$user->files}}" alt="avatar" class="round">
                                        @else
                                        <img src="{{asset('images/avatar-personal.png')}}" alt="">
                                        @endif
                                        <span>{{$user->displayName}}</span>
                                        <span class="user-subhead">{{ ($user->level == 1)? 'Host' : 'Member'}}</span>
                                    </td>-->
                                    <td><?php
                                        switch ($user->level) {
                                            case 1:
                                                echo 'Admin';
                                                break;
                                            case 2:
                                                echo 'Users';
                                                break;
                                            default:
                                                echo 'Ghost';
                                                break;
                                        }
                                        ?></td>
                                    <td>
                                        <span>{{$user->phone}}</span>
                                    </td>
                                    <td>{{$user->created}}</td>
                                    <td class="text-center">
                                        <?php
                                        switch ($user->status) {
                                            case 0:
                                                echo '<span class="label label-success">Deactive</span>';
                                                break;
                                            case 1:
                                                echo '<span class="label label-success">Active</span>';
                                                break;
                                        }
                                        ?>

                                    </td>
<!--                                    <td style="width: 20%;">
                                        <div class="navbar icons">
                                            <a class="active" href="#"><i class="fa fa-fw fa-home"></i></a> 
                                            <a href="#"><i class="fa fa-fw fa-search"></i></a> 
                                            <a href="#"><i class="fa fa-fw fa-envelope"></i></a> 
                                            <a href="#"><i class="fa fa-fw fa-user"></i></a>
                                        </div>
                                    </td>-->
                                    <td style="width: 13%;">
                                        <div class="navbar icons">
                                            <a id="details" class="active btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit Members" onclick="details({{$user->id}})"><i class="fa fa-fw far fa-edit"></i></a>
                                            <a id="details" class="active btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit Members" onclick="removed({{$user->id}})"><i class="fa fa-fw far fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="100%"><h5 align="center">Record Not Found!</h2></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Display Member List -->
<div class="modal fade chat-box row justify-content-center" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Member List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--<div class="modal-body">-->

            <div class="container p18">
                <div class="row">
                    <div class="col-sm-6 col-md-4">
                        <img src="https://www.signivis.com/img/custom/avatars/member-avatar-01.png" alt="" class="img-rounded img-responsive image-profile" id="image-profile" style="height: 45%; width: 125%">
                    </div>
                    <div class="col-sm-6 col-md-8">
                        <form id="member_update" class="login100-form validate-form" onSubmit="return false;">
                            @csrf
                            <input type="hidden" id="id" name="id">
                            <!-- fname -->
                            <div class="form-group row">
                                <label for="fname" class="col-md-4 col-form-label text-md-right">fname</label>

                                <div class="col-md-6">
                                    <input id="fname" type="text" class="form-control" name="fname" value="fname" required autocomplete="fname" autofocus>
                                </div>
                            </div>

                            <!-- lname -->
                            <div class="form-group row">
                                <label for="lname" class="col-md-4 col-form-label text-md-right">lname</label>

                                <div class="col-md-6">
                                    <input id="lname" type="text" class="form-control" name="lname" value="lname" required autocomplete="lname" autofocus>
                                </div>
                            </div>

                            <!-- email -->
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">email</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="email" required autocomplete="email" autofocus>
                                </div>
                            </div>

                            <!-- phone -->
                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">phone</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone" value="phone" required autocomplete="phone" autofocus>
                                </div>
                            </div>

                            <!-- address -->
                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">address</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control" name="address" value="address" required autocomplete="address" autofocus>
                                </div>
                            </div>

                            <!-- city -->
                            <div class="form-group row">
                                <label for="city" class="col-md-4 col-form-label text-md-right">city</label>

                                <div class="col-md-6">
                                    <input id="city" type="text" class="form-control" name="city" value="city" required autocomplete="address" autofocus>
                                </div>
                            </div>

                            <!-- state -->
                            <div class="form-group row">
                                <label for="state" class="col-md-4 col-form-label text-md-right">state</label>

                                <div class="col-md-6">
                                    <input id="state" type="text" class="form-control" name="state" value="{{ old('state') }}" required autocomplete="address" autofocus>
                                </div>
                            </div>
                            <div class="">
                                <label for="status">Status:</label>
                                <select id="status" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Deleted</option>
                                </select>
                            </div>
                            <div class="">
                                <label for="roles">Admin:</label>
                                <select id="roles" name="roles">
                                    <option value="1">Admin</option>
                                    <option value="2">Members</option>
                                </select>
                            </div>
                            <button class="btn btn-success update" onclick="updated('isAdmin')">Update</button>
                        </form>
                    </div>

                </div>
            </div>

            <!--</div>-->
        </div>
    </div>
</div>

<!-- Add Member/Admin -->
<div class="modal fade chat-box" id="exampleModalCenter3" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add New Members</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!--<div class="modal-body">-->

            <div class="container p18">
                <div class="row">
                    <div class="col-sm-6 col-md-8">
                        <form id="create_new_member" class="login100-form validate-form" onSubmit="return false;">
                            <input type="hidden" id="id" name="id">
                            <!-- fname -->
                            <div class="form-group row">
                                <label for="fname" class="col-md-4 col-form-label text-md-right">fname</label>

                                <div class="col-md-6">
                                    <input id="fname" type="text" class="form-control" name="fname" value="" required autocomplete="fname" autofocus>
                                </div>
                            </div>

                            <!-- lname -->
                            <div class="form-group row">
                                <label for="lname" class="col-md-4 col-form-label text-md-right">lname</label>

                                <div class="col-md-6">
                                    <input id="lname" type="text" class="form-control" name="lname" value="" required autocomplete="lname" autofocus>
                                </div>
                            </div>

                            <!-- email -->
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">email</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="" required autocomplete="email" autofocus>
                                </div>
                            </div>

                            <!-- phone -->
                            <div class="form-group row">
                                <label for="phone" class="col-md-4 col-form-label text-md-right">phone</label>

                                <div class="col-md-6">
                                    <input id="phone" type="text" class="form-control" name="phone" value="" required autocomplete="phone" autofocus>
                                </div>
                            </div>

                            <!-- address -->
                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">address</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control" name="address" value="" required autocomplete="address" autofocus>
                                </div>
                            </div>

                            <!-- city -->
                            <div class="form-group row">
                                <label for="city" class="col-md-4 col-form-label text-md-right">city</label>

                                <div class="col-md-6">
                                    <input id="city" type="text" class="form-control" name="city" value="" required autocomplete="address" autofocus>
                                </div>
                            </div>

                            <!-- state -->
                            <div class="form-group row">
                                <label for="state" class="col-md-4 col-form-label text-md-right">state</label>

                                <div class="col-md-6">
                                    <input id="state" type="text" class="form-control" name="state" value="" required autocomplete="address" autofocus>
                                </div>
                            </div>
                            
                            <!-- password -->
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" value="" required autocomplete="password" autofocus>
                                </div>
                            </div>
                            
                           <!-- admin/user -->
                            <div class="">
                                <label for="roles">Admin:</label>
                                <select id="roles" name="roles">
                                    <option value="2">Members</option>
                                    <option value="1">Admin</option>
                                </select>
                            </div>
                            <button class="btn btn-success" style="width: 100%;" onclick="createNew('member')">Add New!</button>
                        </form>
                    </div>

                </div>
            </div>

            <!--</div>-->
        </div>
    </div>
</div>
@endsection
