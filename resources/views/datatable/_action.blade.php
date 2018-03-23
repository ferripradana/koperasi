{!! Form::model($model, ['url' => $form_url, 'method' => 'delete', 'class' => 'form-inline js-confirm', 'data-confirm' => $confirm_message]) !!}
	@if (!Request::is('admin/transaction/penarikan'))
		<a class="btn btn-info" id="ubahbutton" href="{{ $edit_url }}">Ubah</a>
	@endif
	@if (Request::is('admin/transaction/penarikan'))
		<a class="btn btn-info" id="ubahbutton" href="{{ $show_url }}">Show</a>
	@endif
    {!! Form::submit('Hapus', ['class' => 'btn btn-danger']) !!}
{!! Form::close() !!}
