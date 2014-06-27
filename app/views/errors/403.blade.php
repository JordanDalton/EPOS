@extends('layouts.default')

@section('content')
	<div class="container">
		<div class="row">
			<div class="page-header">
				<h1><i class="fa fa-exclamation-circle"></i> Error {{ $code }}</h1>
				<p class="lead"><small>{{ $message }}</small></p>
			</div>
		</div>
	</div>
@stop