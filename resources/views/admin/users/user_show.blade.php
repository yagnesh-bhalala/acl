@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} Player
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>Player name </th>
                        <td> {{ $user->name }} </td>
                    </tr>
                    <tr>
                        <th>Username </th>
                        <td> {{ $user->username }} </td>
                    </tr>
                    <tr>
                        <th>Player code </th>
                        <td> {{ strtoupper($user->username) }} </td>
                    </tr>
                    <tr>
                        <th>Phone </th>
                        <td> {{ $user->phone }} </td>
                    </tr>
                    <tr>
                        <th>Password </th>
                        <td> {{ $user->pwd }} </td>
                    </tr>
                    <tr>
                        <th>Balance </th>
                        <td> {{ $player->opening_balance }} </td>
                    </tr>
                    <tr>
                        <th>Status </th>
                        <td> {{ ($user->status == 1) ? 'Active' : 'Inactive'  }} </td>
                    </tr>


                    <tr>
                        <th>Created At </th>
                        <td> {{ date("d-m-Y h:i a", strtotime($user->created_at)) }} </td>
                    </tr>
                    
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>


    </div>
</div>
@endsection