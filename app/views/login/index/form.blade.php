{{ Form::open(array('route' => 'login.index')) }}
	@if( $errors->has('login_failed') )
		{{ HTML::alert('danger', get_error('login_failed', $errors), 'Oops, it appears something went wrong.') }}
	@endif
	<div class="form-group {{ set_error('username', $errors) }}">
		{{ Form::label('username', 'Username', array('class' => 'control-label')) }}
		{{ Form::text('username', Input::old('username'), array('class' => 'form-control', 'required')) }}
		{{ get_error('username', $errors) }}
	</div>
	<div class="form-group {{ set_error('password', $errors) }}">
		{{ Form::label('password', 'Password', array('class' => 'control-label')) }}
		{{ Form::password('password', array('class' => 'form-control', 'required')) }}
		{{ get_error('password', $errors) }}
	</div>
	<div class="form-group">
		{{ Form::submit('Log in', array('class' => 'btn btn-primary btn-lg btn-block')) }}
	</div>
{{ Form::close() }}

@section('script.embedded.footer')
	@parent

	$('#username').focus();
	
@stop