@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
				  <li><a href="{{ route('approvals.index') }}"><i class="fa fa-usd"></i> Accounting</a></li>
				  <li><a href="{{ route('pos.show', $po->id) }}">PO# {{ $po->id }}</a></li>
				  <li class="active"><small><i class="fa fa-check"></i> Manager Approval</li>
				</ol>
				<div class="page-header">
					<h1>PO# {{ $po->id }} <small><i class="fa fa-check"></i> Manager Approval</small></h1>
					<p>{{ Auth::user()->first_name }}, here is where you can approve/deny-approval of a purhcase order. When the PO is approved we will automatically notify the Accounting Department.</p>
				</div>
				<p><a class="btn btn-info" href="{{ route('pos.show', $po->id ) }}" target="_blank">View Po</a> <a class="btn btn-info" href="{{ route('pos.attachments.index', $po->id ) }}" target="_blank">View Attachments</a></p>
				<hr/>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<p class="lead">Please enter the name of the manager that approved this purchase order.</p>
				<div class="row">
					<div class="col-lg-5">
						
						{{-- Flash Message. --}}
						@if( Session::has('manager_approval_successful'))
							{{ HTML::alert('success', Session::get('manager_approval_successful')) }}
						@endif

						{{-- PO has NOT been approved by a manger. --}}
						@if( is_null( $po->manager_approved_at ) )
	
							{{-- Flash Message --}}
							@if( $errors->has('manager_approval_failed') )
								{{ HTML::alert('danger', $errors->first('manager_approval_failed'), 'Oops!') }}
							@endif
							
							{{-- PO is still in draft mode (prevent any approvals). --}}
							@if( $po->draft )
								{{ HTML::alert('warning', 'PO is still marked as a draft.', 'Manager Approval Disabled!')}}
							
							{{-- PO is no longer a draft (allow approval by management and accounting.) --}}
							@else
								{{ Form::open(array('class' => 'form-horizontal' , 'role' => 'form')) }}
									@if( $errors->has('manager'))
										{{ HTML::alert('danger', get_error('manager', $errors))}}
									@endif
									{{ Form::text('manager', Input::old('manager', $po->manager_name), array('class' => 'form-control', 'placeholder' => 'John Smith', ''))}}
									{{ Form::submit('Assign Manager Approval', array('class' => 'btn btn-success btn-sm', 'style' => 'margin-top:10px;')) }}
								{{ Form::close() }}
							@endif
						{{-- PO has been approved by a manger. --}}
						@else
							{{ HTML::alert('info', 'This purchase order has already been approved.', "Heads Up!") }}
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop