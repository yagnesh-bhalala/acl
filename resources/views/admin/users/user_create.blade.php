@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} Player
    </div>

    <div class="card-body">
        <form action="{{ route("admin.users.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
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
            </div>
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                <label for="username">{{ trans('cruds.user.fields.username') }}* <small>[will be use as playercode]</small></label>
                <input type="username" id="username" name="username" class="form-control" value="{{ old('username', isset($user) ? $user->username : '') }}" required>
                @if($errors->has('username'))
                    <em class="invalid-feedback">
                        {{ $errors->first('username') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>

            <input type="hidden" name="start_access_date" value="{{ $loginUser->start_access_date }}" >
            <input type="hidden" name="end_access_date" value="{{ $loginUser->end_access_date }}">

            <div class="form-group {{ $errors->has('opening_balance') ? 'has-error' : '' }}">
                <label for="opening_balance">Balance *</label>
                <input type="text" id="opening_balance" name="opening_balance" class="form-control" value="{{ old('opening_balance', isset($user) ? $user->opening_balance : '') }}" >
                @if($errors->has('opening_balance'))
                    <em class="invalid-feedback">
                        {{ $errors->first('opening_balance') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('player_commision_percentage') ? 'has-error' : '' }}">
                <label for="player_commision_percentage">Player Commission %</label>
                <select id="player_commision_percentage" name="player_commision_percentage" class="form-control" onchange="setPercentage(this.value)">                    
                    @for ($i=1; $i < 20.5;)
                        @if ($i==5)
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
                <label for="third_party_code">Third party commision</label>
                <select id="third_party_code" name="third_party_code" class="form-control">
                    <option value="">Select code</option>
                    @foreach ($player_detail as $player)
                      <option value="{{ $player['id'] }}">{{ $player['player_code'] }} ({{$player['player_name']}})</option>
                    @endforeach
                </select>
                
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group">
                <label for="third_party_percentage">{{ __('Third pary commission %') }}</label>
                <div id="secondperc">
                    <select id="third_party_percentage" name="third_party_percentage" class="form-control" >
                    <option value="{{ old('third_party_percentage') }}">Select percentage(Range 1 to n %)</option>

                    @for ($i=0.5; $i <= 5; $i)
                        <option value="{{ $i }}">{{ $i }} %</option>
                        @php
                            $i = $i+0.5
                        @endphp
                    @endfor
                    </select>
                </div>
            </div>

            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                <label for="phone">{{ trans('cruds.user.fields.phone') }}</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', isset($user) ? $user->phone : '') }}" >
                @if($errors->has('phone'))
                    <em class="invalid-feedback">
                        {{ $errors->first('phone') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>

            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">{{ trans('cruds.user.fields.password') }}*</label>
                <input type="password" id="password" name="password" class="form-control" required>
                @if($errors->has('password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>

            <!-- <div class="form-group {{ $errors->has('is_flat_commision') ? 'has-error' : '' }}"> 
                <label for="is_flat_commision" class="custom-control custom-switch">Is flex commision</label>
                <label class="switch">
                  <input type="checkbox" id="is_flat_commision" name="is_flat_commision"  />
                  <span class="slider round"></span>
                </label>
            </div> -->
            <div class="form-group {{ $errors->has('is_flat_commision') ? 'has-error' : '' }}"> 
                <label for="is_flat_commision" class="flexLabel">Is flex commision</label>
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="switch1" name="is_flat_commision">
                  <label class="custom-control-label" for="switch1">Don't care player win or loose</label>
                </div>
            </div>

            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}"> 
                <label for="status" class="statusLabel">{{ trans('cruds.user.fields.status') }}</label>
                <label class="switch">
                  <input type="checkbox" id="status" name="status"  checked>
                  <span class="slider round"></span>
                </label>
            </div>
            
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}"> 
            </div>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}</a>
            
        </form>


    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
function setPercentage(mainPerce) {
    var varloop='';
    for (var i = 0.5; i <= mainPerce; i=i+0.5) {
        varloop+="<option value="+i+">"+i+"%</option>";
    }
    var responseText = "<select name='third_party_percentage' class='form-control'><option value='' >Select percentage(Range 1 to " +mainPerce+ "%) </option>" + varloop;        
    responseText += "</select>";
    // console.log(responseText)
    $('#secondperc').html(responseText);
}
$(document).ready(function() {
    // Initialize select2
    // $("#third_party_code").select2();    

    $("button").click(function() {
        $('button').hide();
    });
});
</script>
@endsection

@section('styles')

@endsection
