@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-lg-offset-4">

				@if( Session::has('logout_successful') )
					<div class="alert alert-success">{{ Session::get('logout_successful') }}</div>
				@endif

				<h3><i class="fa fa-sign-in"></i> Please Log In</h3>
				<hr/>
				{{ $form }}
			</div>
		</div>
	</div>
@stop