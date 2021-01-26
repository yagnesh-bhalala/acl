@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Manage events
    </div>

    <div class="card-body">
        <form action="{{ route("admin.player.try-manual.store") }}" method="POST" >
            @csrf           

            <div class="form-group {{ $errors->has('winner_code') ? 'has-error' : '' }}">
                <label for="winner_code">Winner code*</label>
                <select id="winner_code" name="winner_code" class="form-control">
                    <option value="">Select code</option>
                    @foreach ($player_dd as $player)
                      <option value="{{ $player['id'] }}">{{ $player['player_code'] }} ({{$player['player_name']}})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group {{ $errors->has('loser_code') ? 'has-error' : '' }}">
                <label for="loser_code">Loser code*</label>
                <select id="loser_code" name="loser_code" class="form-control">
                    <option value="">Select code</option>
                    @foreach ($player_dd as $player)
                      <option value="{{ $player['id'] }}">{{ $player['player_code'] }} ({{$player['player_name']}})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                <label for="amount">Amount*</label>
                <input type="text" id="amount" name="amount" class="form-control" value="{{ old('amount', isset($user) ? $user->amount : '') }}" >
                @if($errors->has('amount'))
                    <em class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>

            
            <div class="form-group {{ $errors->has('date_time') ? 'has-error' : '' }}">
                <label for="date_time">Date time*</label>
                <input type="text" id="date_time" name="date_time" class="form-control datetime" value="" required>
                @if($errors->has('date_time'))
                    <em class="invalid-feedback">
                        {{ $errors->first('date_time') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }} 
                </p>
            </div>

            <div class="form-group {{ $errors->has('comments') ? 'has-error' : '' }}">
                <label for="comments">Note</label>
                <input type="text" id="comments" name="comments" class="form-control" value="">
                @if($errors->has('comments'))
                    <em class="invalid-feedback">
                        {{ $errors->first('comments') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
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
$(document).ready(function() {
    // Initialize select2
    $("#winner_code").select2();    
    // $("#third_party_code").select2();    

    $("button").click(function() {
        $('button').hide();
    });
});
</script>
@endsection

@section('styles')

@endsection
