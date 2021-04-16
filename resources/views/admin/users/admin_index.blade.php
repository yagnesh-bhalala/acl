@extends('layouts.admin')
@section('content')

@if(Auth::user()->rl == '1' || Auth::user()->rl == '2' || Auth::user()->rl == '3' )
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.create") }}">
                {{ trans('global.add') }} Admin
            </a>
        </div>
    </div>
@endif
<div class="card">
    <div class="card-header">
        {{ trans('cruds.user.title_singular') }} Admin
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Sr.
                        </th>
                        <th>
                            {{ trans('cruds.user.fields.name') }}
                        </th>
                        <th>
                            Username
                        </th>
                        <th>
                            Start date
                        </th>
                        <th>
                            End date
                        </th>
                        <th>
                            Status
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}">
                            <td>

                            </td>
                            <td>
                                {{ ++$key }}
                            </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->username ?? '' }}
                            </td>                            
                            <td>
                                {{ $user->start_access_date ?? '' }}
                            </td>
                            <td>
                                {{ $user->end_access_date ?? '' }}
                            </td>
                            <td>
                                <!-- <label class="switch">
                                  <input type="checkbox" id="status" name="status"  <?php //echo ($user->status == 1 ? "checked" : ""); ?> disabled />
                                  <span class="slider round"></span>
                                </label> -->
                                <span class="font-weight-bold text<?php echo ($user->status == 1 ? "-success" : "-danger"); ?>"><?php echo ($user->status == 1 ? "Active" : "Inactive"); ?></span>
                            </td>

                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.show', $user->id) }}">
                                    {{ trans('global.view') }}
                                </a>

                                <a class="btn btn-xs btn-info" href="{{ route('admin.edit', $user->id) }}">
                                    {{ trans('global.edit') }}
                                </a>

                                <form action="{{ route('admin.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                </form>

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.users.mass_destroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection