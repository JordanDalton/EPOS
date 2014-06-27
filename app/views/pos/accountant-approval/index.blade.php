@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
				  <li><a href="{{ route('approvals.index') }}"><i class="fa fa-usd"></i> Accounting</a></li>
				  <li><a href="{{ route('pos.show', $po->id) }}">PO# {{ $po->id }}</a></li>
				  <li class="active"><small><i class="fa fa-check"></i> Accountant Approval</li>
				</ol>
				<div class="page-header">
					<h1>PO# {{ $po->id }} <small><i class="fa fa-check"></i> Accountant Approval</small></h1>
					<p>{{ Auth::user()->first_name }}, here is where you can approve/deny-approval of a purhcase order.</p>
				</div>
				<p><a class="btn btn-info" href="{{ route('pos.show', $po->id ) }}" target="_blank">View Po</a> <a class="btn btn-info" href="{{ route('pos.attachments.index', $po->id ) }}" target="_blank">View Attachments</a></p>
				<hr/>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<p class="lead">{{ Auth::user()->first_name }}, if you approve please enter your workstation password below.</p>
				<div class="row">
					<div class="col-lg-5">

						@if( Session::has('accountant_approval_successful'))
							{{ HTML::alert('success', Session::get('accountant_approval_successful')) }}
						@endif

						@if( is_null( $po->accountant_approved_at ) )

							{{ HTML::alert('info', "Only members of accounting can approve this purchase order.", 'Heads Up!')}}

							@if( $errors->has('accountant_approval_failed') )
								{{ HTML::alert('danger', $errors->first('accountant_approval_failed'), 'Oops!') }}
							@endif

							@if( $po->draft )
								{{ HTML::alert('warning', 'PO is still marked as a draft.', 'Accountant Approval Disabled!')}}
							@else
								{{ Form::open(array('class' => 'form-horizontal' , 'role' => 'form')) }}
								@if( $errors->has('password'))
									{{ HTML::alert('danger', get_error('password', $errors))}}
								@endif
									{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Workstation Password', '')) }}
									{{ Form::submit('Approve Purchase Order', array('class' => 'btn btn-success btn-sm', 'style' => 'margin-top:10px;')) }}
								{{ Form::close() }}
							@endif
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