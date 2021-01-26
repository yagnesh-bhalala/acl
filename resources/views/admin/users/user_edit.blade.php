@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} Player
    </div>

    <div class="card-body">
        <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">Player name*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div><div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                <label for="username">{{ trans('cruds.user.fields.username') }}*</label>
                <input type="username" id="username" name="username" class="form-control" value="{{ old('username', isset($user) ? $user->username : '') }}" required>
                @if($errors->has('username'))
                    <em class="invalid-feedback">
                        {{ $errors->first('username') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input type="password" id="password" name="password" class="form-control">
                @if($errors->has('password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>
            </div>
            <input type="hidden" name="start_access_date" value="{{ $loginUser->start_access_date }}" >
            <input type="hidden" name="end_access_date" value="{{ $loginUser->end_access_date }}">
            
            <div class="form-group {{ $errors->has('player_commision_percentage') ? 'has-error' : '' }}">
                <label for="player_commision_percentage">Player Commission %</label>
                <select id="player_commision_percentage" name="player_commision_percentage" class="form-control" onchange="setPercentage(this.value)">                    
                    @for ($i=1; $i < 20.5;)
                        @if ($user->player_model->player_commision_percentage == $i)
                        <option selected="selected" value="{{ $i }}">{{ $i }}%</option>
                        @else
                        <option value="{{ $i }}">{{ $i }}%</option>
                        @endif
                        @php
                            $i = $i+0.5
                        @endphp
                    @endfor
                </select>
                
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('third_party_code') ? 'has-error' : '' }}">
                <label for="third_party_code">Ref. Commision</label>
                <select id="third_party_code" name="third_party_code" class="form-control">
                    <option value="">Select code</option>
                    @foreach ($player_detail as $player)
                        @if ($user->player_model->id == $player['id'])
                            <option value="{{ $player['id'] }}" selected="selected">{{ $player['player_code'] }} ({{$player['player_name']}})</option>
                        @else
                            <option value="{{ $player['id'] }}">{{ $player['player_code'] }} ({{$player['player_name']}})</option>
                        @endif
                    @endforeach
                </select>
                
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group">
                <label for="third_party_percentage">{{ __('Ref. Commission %') }}</label>
                <div id="secondperc">
                    <select id="third_party_percentage" name="third_party_percentage" class="form-control" >
                    <option value="{{ old('third_party_percentage') }}">Select percentage(Range 1 to n %)</option>

                    @for ($i=0.5; $i <= 5; $i)
                        @if (($user->player_model->third_party_percentage) == $i)
                            <option selected="selected" value="{{ $i }}">{{ $i }} %</option>
                        @else
                            <option value="{{ $i }}">{{ $i }} %</option>
                        @endif

                        @php
                            $i = $i+0.5
                        @endphp
                    @endfor
                    </select>
                </div>
            </div>

            <div class="form-group {{ $errors->has('is_flat_commision') ? 'has-error' : '' }}"> 
                <label for="is_flat_commision" class="flexLabel">Flat Commision(Player win or loose)</label>
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="switch1" name="is_flat_commision" <?php echo ($user->player_model->is_flat_commision == 1 ? "checked" : ""); ?>>
                  <label class="custom-control-label" for="switch1"></label>
                </div>
            </div>

            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                <label for="phone">{{ trans('cruds.user.fields.phone') }}</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ $user->phone }}">
                @if($errors->has('phone'))
                    <em class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>

            

            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}"> 
                <label for="status" class="statusLabel">{{ trans('cruds.user.fields.status') }}</label>
                <label class="switch">

                  <input type="checkbox" id="status" name="status" <?php echo ($user->status == 1 ? "checked" : ""); ?> />
                  <span class="slider round"></span>
                </label>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.update') }}">
            </div>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}</a>
        </form>


    </div>
</div>
@endsection