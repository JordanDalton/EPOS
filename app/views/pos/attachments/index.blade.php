@section('content')
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ol class="breadcrumb">
				  <li><a href="{{ route('pos.index') }}"><i class="fa fa-list"></i> My Purchase Orders</a></li>
				  <li><a href="{{ route('pos.show', $po->id) }}">PO# {{ $po->id }}</a></li>
				  <li class="active"><small><i class="fa fa-paperclip"></i> Attachments</li>
				</ol>
				<div class="page-header">
					<h1>PO# {{ $po->id }} <small><i class="fa fa-paperclip"></i> Attachments</small></h1>
					<p>Here you can attach supporting documents for your purchase order submission.</p>
				</div>

				@include('pos._alerts')
			</div>
		</div>
		<div class="row">
			<div class="col-lg-6">
				@if( Session::has('files_uploaded_successfully') )
					{{ HTML::alert('success', Session::get('files_uploaded_successfully'), 'Upload was successful!') }}
				@endif

				<h4>Upload Attachment(s)</h4>
				<hr/>
				{{ Form::open(array('files' => true, 'id' => 'uploadFile', 'class' => 'form-horizontal', 'role' => 'form')) }}
					@if( $errors )
						@foreach( $errors->all() as $error )
						{{ HTML::alert('danger', $error, 'Error:') }}</li>
						@endforeach
					@endif
					@for( $i = 1; $i < 5; $i++ )
					<div class='form-group {{ set_error("files.$i", $errors) }}'>
						<div class="row">
							<div class="col-lg-2 col-lg-offset-1">
								{{ Form::label("files[$i]", "File #$i", array('class' => 'control-label')) }}
							</div>
							<div class="col-lg-9">
								{{ Form::file("files[$i]") }}
							</div>
						</div>
					</div>
					@endfor
					<hr/>
					{{ Form::submit('Upload File', array('class' => 'btn btn-success')) }}
				{{ Form::close() }}
			</div>
			<div class="col-lg-6">
				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title">Current Attachments</h3>
					</div>
					<div class="well well-sm">
						Attachments are stored "in the cloud." If an attempt to download a file fails please wait a few more seconds before trying again.
					</div>
					<div class="panel-body">
						<table class="table">
							<thead>
								<tr>
									<td style="text-align:center;width:70px">Download</td>
									<td>Filename</td>
									<td>Delete</td>
								</tr>
							</thead>
							<tbody>
								@if( ! $po->attachments->count() )
									<tr>
										<td colspan="3"><div class="alert alert-info">There are currently no attachments to list.</div></td>
									</tr>
								@endif
								@foreach( $po->attachments as $attachment )
								<tr>
									<td style="text-align:center">
										<a href="{{ URL::route('pos.attachments.show', array($attachment->po_id, $attachment->id)) }}" target="_blank"><i class="fa fa-cloud-download fa-2x"></i></a>
									</td>
									<td>
										Local: {{ $attachment->local_filename }} | Cloud: {{ $attachment->cloud_filename }}
									</td>
									<td>
										<a class="btn btn-xs btn-danger destroyAttachment" attachment_id="{{ $attachment->id }}" po_id="{{ $po->id }}" href="{{ route('pos.attachments.destroy', array($po->id, $attachment->id)) }}"><i class="fa fa-times"></i> Delete</a>
									</td>
								</tr>
								@endforeach
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('script.embedded.footer')
	@parent

	$('#uploadFile').on('submit', function(event)
	{
		/**\/
		event.preventDefault();

		$.ajax({
			'type'   : 'POST',
			'url'    : '{{ route("pos.attachments.index", $po->po_number) }}',
			'data'   : $(this).serialize(),
			'success': function(data, textStatus, jqXHR){

			}
		});
		/**/
	});

	$('.destroyAttachment').on('click', function(event)
	{
		event.preventDefault();

		var $this         = $(this);
		var attachment_id = $(this).attr('attachment_id');
		var po_id 		  = $(this).attr('po_id');
		var link_target   = $(this).attr('href');

		$.ajax({
			'type'   : 'DELETE',
			'url'    : link_target,
			'success': function(data, textStatus, jqXHR)
			{	
				if( data == true )
				{
					$this.closest('tr').remove();
				}
			}
		});

		// console.log('destory attachment id#', attachment_id);
		// console.log('destory attachment po id#', po_id);
	});
@stop