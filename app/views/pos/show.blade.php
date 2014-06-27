@section('content')
	<div class="container">
		<div class="row">
			<ol class="breadcrumb">
			  <li><a href="{{ route('pos.index') }}"><i class="fa fa-list-alt"></i> My POs</a></li>
			  <li class="active">PO #{{ $po->id }}</li>
			</ol>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<div class="page-header">
					<h1>PO #{{ $po->id}} <small>Review</small></h1>
					<hr/>
					{{ HTML::alert('warning', 'You are currently in <strong>read-only</strong> mode.', 'Warning!')}}
					@include('pos._alerts')
					@if( count($errors->all()))
						<div class="alert alert-danger" style="margin-bottom:0">
							<strong>Oops!</strong> There were errors with your submisison. All areas that need attention will be highlighted in red.
						</div>
					@endif

					@if( is_null($po->manager_approved_at) OR is_null($po->manager_approved_at) )
					 <hr/>
					 <a class="btn btn-primary" href="{{ route('pos.edit', $po->id) }}"><i class="fa fa-pencil"></i> Edit PO</a>
					 <a class="btn btn-info" href="{{ route('pos.attachments.index', $po->id) }}"><i class="fa fa-paperclip"></i> View Attachments</a>
					 <a class="btn btn-danger pull-right destroyPo" href="{{ route('pos.destroy', $po->id) }}"><i class="fa fa-times"></i> Delete PO</a>
					@endif
				</div>
			</div>
		</div>
		{{ $form }}
	</div>
@stop