@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-12">

				@if( $requireAccountingApprovalCount && Auth::user()->isAdmin() )
					{{ HTML::alert('warning', 'There ' . Lang::choice('messages.is_are', $requireAccountingApprovalCount) . ' '.$requireAccountingApprovalCount.' ' . Lang::choice('messages.purchase_orders', $requireAccountingApprovalCount) . ' that needs the <a href="'.route('approvals.index').'" style="text-decoration:underline"><strong>account department\'s approval</strong></a>.', "Important!")}}
				@endif
				
				<h1><i class="fa fa-list"></i> My Purchase Orders</h1>
				<p>Listed below are the purchase orders you have submitted.</p>
				<hr/>
				<div class="row">
					<div class="col-lg-2">
						<a class="btn btn-success myTeamPos" href="{{ route('team-pos.index') }}"><strong><i class="fa fa-users"></i> My Team's POs</strong></a>
					</div>
					<div class="col-lg-2">
						<a class="btn btn-primary submitNewPo" href="{{ route('pos.create') }}"><strong><i class="fa fa-pencil"></i> Submit New PO</strong></a>
					</div>
					<div class="col-lg-5">
						{{ Form::open(array('method' => 'GET')) }}
			                <div class="input-group">
			                	{{ Form::text('q', Input::query('q') , array('class' => 'form-control lato', 'placeholder' => 'Search for...')) }}
			                    <span class="input-group-btn">
			                        <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-search"></i></button>
			                    </span>
			                </div>
			            {{ Form::close() }}
					</div>
				</div>
				<hr/>
				@if( Session::has('po_successful') )
					<div class="alert alert-success">{{ Session::get('po_successful') }}</div>
				@endif
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				@if($query)
					<p>{{ $pos->count() }} {{ Lang::choice('messages.records', $pos->count()) }} returned for "<span class="lato">{{ $query }}</span>"</p>
				@endif
				<table class="table poTable">
					<thead>
						<tr>
							<th>View</th>
							<th>PDF</th>
							<th>Draft</th>
							<th>Name</th>
							<th>Submitted</th>
							<th class="centered">Item Count</th>
							<th class="centered">Total Value</th>
							<th class="centered">Attachments</th>
							<th class="centered"><span class="tooltips" data-toggle="tooltip" title="Approved By Accounting">AA <i class="fa fa-question-circle"></i></span></th>
							<th class="centered"><span class="tooltips" data-toggle="tooltip" title="Approved By Management">MA <i class="fa fa-question-circle"></i></span></th>
						</tr>
					</thead>
					<tbody>
					@if( ! $pos->count() )
						<tr>
							<td colspan="10">{{ HTML::alert('info', 'There are no records to display.', 'Nothing Here!') }}</td>
						</tr>
					@endif
					@foreach( $pos as $po )
						<tr class="{{ $po->isApprovedComplete() ? 'success' : '#' }}">
							<td>{{ link_to_route('pos.show', $po->id, $po->id , array('class' => 'lato', 'style' => 'text-decoration:underline')) }}</td>
							<td>{{ HTML::decode(link_to_route('pos.pdf', '<i class="fa fa-file-o"></i>', $po->id , array('class' => 'lato', 'style' => 'text-decoration:underline'))) }}</td>
							<td>
								@if( $po->draft == 1 )
									<i class="fa fa-check-square-o"></i>
								@endif
							</td>
							<td>{{ $po->name }}</td>
							<td>{{ $po->created_at }}</td>
							<td class="centered">{{ $po->items->count() }}</td>
							<td class="centered"><i class="fa fa-usd"></i> {{ $po->items(true) }}</td>
							<td class="centered">
								<a  class="lato" style="text-decoration:underline" href="{{ route('pos.attachments.index', $po->id) }}">{{ $po->attachments->count() }}</a>
							</td>
							<td class="centered">
								@if( $po->accountant_approved_at )
									<span class="btn btn-info btn-xs tooltips" data-toggle="tooltip" title="{{ $po->accountant->display_name }}"><i class="fa fa-check"></i></span>
								 @endif
							</td>
							<td class="centered">
								@if( $po->manager_approved_at )
									<span class="btn btn-info btn-xs tooltips" data-toggle="tooltip" title="{{ $po->manager }}"><i class="fa fa-check"></i></span>
								 @endif
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
				{{ $pos->links() }}
			</div>
		</div>
	</div>
@stop

@section('script.embedded.footer')
	$('.tooltips').tooltip();
@stop