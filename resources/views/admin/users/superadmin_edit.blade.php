@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}*</label>
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
            </div>
            <div class="form-group {{ $errors->has('start_access_date') ? 'has-error' : '' }}">
                <label for="start_access_date">Start date*</label>
                <input type="text" id="start_access_date" name="start_access_date" class="form-control date" value="{{ old('start_access_date', isset($user) ? $user->start_access_date : '') }}" required>
                @if($errors->has('start_access_date'))
                    <em class="invalid-feedback">
                        {{ $errors->first('start_access_date') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('end_access_date') ? 'has-error' : '' }}">
                <label for="end_access_date">End date*</label>
                <input type="text" id="end_access_date" name="end_access_date" class="form-control date" value="{{ old('end_access_date', isset($user) ? $user->end_access_date : '') }}" required>
                @if($errors->has('end_access_date'))
                    <em class="invalid-feedback">
                        {{ $errors->first('end_access_date') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            
            
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

            <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                <label for="phone">{{ trans('cruds.user.fields.phone') }}</label>
                <input type="text" id="phone" name="phone" class="form-control">
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

                  <input type="checkbox" id="status" name="status"  <?php echo ($user->status == 1 ? "checked" : ""); ?> />
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