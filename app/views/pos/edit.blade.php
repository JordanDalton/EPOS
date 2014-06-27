@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
				  <li><a href="{{ route('pos.index') }}"><i class="fa fa-list-alt"></i> My POs</a></li>
				  <li><a href="{{ route('pos.show', $po->id) }}">PO #{{ $po->id }}</a></li>
				  <li class="active">Edit</li>
				</ol>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-warning"><strong>Heads Up!</strong> You're currently in edit mode</div>
				<div class="page-header">
					<h1><i class="fa fa-pencil"></i> Edit <small>PO #{{ $po->id }}</small></h1>
					  @if( count($errors->all()))
						  <div class="alert alert-danger" style="margin-bottom:0">
						  	<strong>Oops!</strong> There were errors with your submisison. All areas that need attention will be highlighted in red.
						  </div>
					  @endif
				</div>
			</div>
		</div>
		{{ $form }}
	</div>
@stop