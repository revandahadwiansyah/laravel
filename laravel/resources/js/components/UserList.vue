<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="main-box clearfix">
                    <div class="panel-heading titles"><h2>{{strtoupper(Request::segment(1))}}</h2></div>
                    <div style="float: right;">
                        <button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add New Admin" onclick="AddNew('member')">Add Members</button>
                        <button class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Add New" onclick="AddNew('ghost')">Add Ghost</button>
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
                                    <th><span>User</span></th>
                                    <th><span>Level</span></th>
                                    <th><span>Register Date</span></th>
                                    <th><span>Phone</span></th>
                                    <!--{!! ($env != 'production')? '<th><span>Ghost</span></th>': ''; !!}-->
                                    <th class="text-center"><span>Status</span></th>
                                    <th class="text-center"><span>Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($users[0]) == true)
                                @foreach($users as $user)
                                <tr data="{{$user->id}}">
                                    <td>
                                        @if($user->profileImageKey != '' && $user->profileImageKey != null)
                                        <img src="{{$user->profileImageKey}}" alt="avatar" class="round">
                                        @else
                                        <img src="{{asset('images/avatar-personal.png')}}" alt="">
                                        @endif
                                        <span>{{$user->displayName}}</span>
                                        <span class="user-subhead">{{ ($user->level == 1)? 'Host' : 'Member'}}</span>
                                    </td>
                                    <td><?php
                                        switch ($user->level) {
                                            case 1:
                                                echo 'T1';
                                                break;
                                            default:
                                                echo 'Member';
                                                break;
                                        }
                                        ?></td>
                                    <td>{{$user->created}}</td>
                                    <td>
                                        <span>{{$user->phone}}</span>
                                    </td>
                                    <?php if ($env != 'production') { ?>
                                                                                    <!--<td>
                                                                                        <span>{{($user->isGhost === true || $user->isGhost === 1)? 'yes':'no'}}</span>
                                                                                    </td>
                                        -->
                                    <?php } ?>
                                    <td class="text-center">
                                        <?php
                                        if ($user->suspended_id != null && $user->suspended_id != '') {
                                            if ($user->suspended_status == 1 || $user->suspended_status === 1) {
                                                echo '<span class="label label-success">Suspended</span>';
                                            } else {
                                                echo '<span class="label label-success">Active</span>';
                                            }
                                        } else {
                                            switch ($user->status) {
                                                case 1:
                                                    echo '<span class="label label-success">Active</span>';
                                                    break;
                                                case 2:
                                                    echo '<span class="label label-success">Kick</span>';
                                                    break;
                                                case 3:
                                                    echo '<span class="label label-success">Deactive</span>';
                                                    break;
                                            }
                                        }
                                        ?>

                                    </td>
                                    <td style="width: 13%;">
                                        <div class="navbar icons">
                                            <a id="details" class="active btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edit Members" onclick="details({{$user->id}})"><i class="fa fa-fw far fa-edit"></i></a>
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
                    @if(isset($users[0]) == true)
                    <ul class="pagination pull-right">
                        {!! $users->render() !!}
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
