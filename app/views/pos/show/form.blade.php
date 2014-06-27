		{{ Form::model($po, array('route' => 'pos.store')) }}
		<div class="row">
			<div class="col-lg-7">
				<div class="panel panel-default {{ set_panel_error('name', $errors) }}">
					<div class="panel-heading">
						<h3 class="panel-title">Name <small style="font-size:.8em">This Purchase Order</small></h3>
					</div>
					<div class="panel-body">
						{{ get_error_alert('name', $errors) }}
						{{ Form::text('name', $po->name, array('class' => 'form-control', 'placeholder' => 'e.g., New Office Desk', 'disabled')) }}
					</div>
				</div>
				<div class="panel panel-default {{ set_panel_error('vendor', $errors) }}">
					<div class="panel-heading">
						<h3 class="panel-title">Vendor <small style="font-size:.8em">Information</small></h3>
					</div>
					<div class="panel-body">
						{{ get_error_alert('vendor', $errors) }}
						{{ Form::textarea('vendor', $po->vendor, array('rows' => 10, 'style' => 'width:100%', 'disabled')) }}
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				<?php $divisionErrorsExist = keyMatchCounter('po\.divisions\.(\d+)+', $errors->getMessages());?>
				<div class="panel panel-default {{ $divisionErrorsExist ? 'panel-danger' : '' }}">
					<div class="panel-heading">
						<h3 class="panel-title">Divisions <small style="font-size:.8em">of WRS Group, Ltd.</small></h3>
					</div>
					<div class="panel-body">
						{{-- Division validation errors exist. --}}
						@if( keyMatchCounter('po\.divisions\.(\d+)+', $errors->getMessages()) )
							<div class="row">
								<div class="col-lg-12">
									<?php $divisionErrors = preg_grep("/po\.divisions\.(\d+)+/", array_keys($errors->getMessages()));?>
									@foreach( $divisionErrors as $divisionError )
										{{ get_error_alert($divisionError, $errors) }}
									@endforeach
								</div>
							</div>
						@endif
						<div class="row">
							<div class="col-lg-6 col-sm-6">
								@foreach( $divisions as $division )
									<div style="margin-bottom:0px">{{ Form::checkbox('po[divisions][]', $division->id, in_array($division->id, $selectedDivisions) , array('disabled')) }} <span style="position:relative;top:-2px">{{ $division->name }}</span></div>
								@endforeach
							</div>
							<div class="col-lg-6 hidden-xs">
								<address>
								  <strong>WRS Group, Ltd.</strong><br>
								  5045 Franklin Avenue<br>
								  P.O. Box 21207<br>
								  Waco, Tx 76702-1207<br>
								  <abbr title="Phone">P:</abbr> 254/776-6461</br>
								  <abbr title="Fax">F:</abbr> Fax 254/776-6321
								</address>
							</div>
						</div>
					</div>
				</div>

				<?php $locationErrorsExist = keyMatchCounter('po\.locations\.(\d+)+', $errors->getMessages());?>
				<div class="panel panel-default {{ $locationErrorsExist ? 'panel-danger' : '' }}">
					<div class="panel-heading">
						<h3 class="panel-title">Shipping <small style="font-size:.8em">Locations</small></h3>
					</div>
					<div class="panel-body" style="border-bottom:1px solid #DDDDDD">
						{{-- Location validation errors exist. --}}
						@if( keyMatchCounter('po\.locations\.(\d+)+', $errors->getMessages()) )
							<div class="row">
								<?php $locationErrors = preg_grep("/po\.locations\.(\d+)+/", array_keys($errors->getMessages()));?>
								@foreach( $locationErrors as $locationError )
									{{ get_error_alert($locationError, $errors) }}
								@endforeach
							</div>
						@endif
						@foreach( $locations as $location )
							<div>{{ Form::checkbox('po[locations][]', $location->id, in_array($location->id, $selectedLocations) , array('disabled') ) }} {{ $location->address }}</div>
						@endforeach
					</div>
					<div class="panel-group" id="accordion">
					  <div class="panel panel-default">
					    <div class="panel-heading">
					      <h4 class="panel-title">
					        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
					          Use Alternate Shipping Address
					        </a>
					      </h4>
					    </div>
					    <div id="collapseTwo" class="panel-collapse collapse in">
					      <div class="panel-body">
							{{ get_error('ship_to', $errors) }}
							{{ Form::textarea('ship_to', $po->ship_to, array('rows' => 5, 'style' => 'width:100%', 'disabled')) }}
					      </div>
					    </div>
					  </div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<hr/>
		</div>
		<div class="row">
			<div class="col-lg-12">
					<?php $itemErrorsExist = keyMatchCounter('po\.items\.(\d+)+', $errors->getMessages());?>
					<div class="panel panel-default {{ $itemErrorsExist ? 'panel-danger' : '' }}">
						<div class="panel-heading">
							<strong>Line Items</strong> 
						</div>
						<div class="panel-body">

						{{-- Item validation errors exist. --}}
						@if( keyMatchCounter('po\.items\.(\d+)+', $errors->getMessages()) )
							<div class="row">
								<div class="col-lg-12">
									<?php $itemErrors = preg_grep("/po\.items\.(\d+)+/", array_keys($errors->getMessages()));?>
									@foreach( $itemErrors as $itemError )
										{{ get_error_alert($itemError, $errors) }}
									@endforeach
								</div>
							</div>
						@endif

							<div class="alert alert-info">
								<strong><i class="icon-exclamation-sign"></i> Heads up!</strong> You will need to <strong>manually</strong> calculate all values for each line since we cannot predict what unit of measure formula you will be using.
							</div>

							<table class="table table-striped table-bordered poFormTable">
								<thead>
									<tr>
										<th style="width:75px">Line #</th>
										<th>Item / MFG. No. / Description</th>
										<th class="text-centered">Due Date</th>
										<th class="text-centered">QTY</th>
										<th class="text-centered">UM</th>
										<th class="text-centered">Unit Cost</th>
										<th class="text-centered">Um</th>
										<th class="text-centered">Tax</th>
										<th class="text-centered" style="width:120px">Total</th>
									</tr>
								</thead>
								<tbody>
										@for ($i = 0, $c = count( $po->items ); $i < $c; $i++)
											<tr class="new" row="po[items][{{ $i }}][row_id]">
												<td class="#">{{ $i }}</td>
												<td class="#">{{ Form::text("items[$i][description]"	 , $po->items[$i]['description'], array('class' => 'form-control', 'disabled')) }}</td>
												<td class="#">{{ Form::text('po[items]['.$i.'][due_date]', $po->items[$i]['due_date'], array('class' => 'form-control', 'disabled')) }}</td>
												<td class="#">{{ Form::text('po[items]['.$i.'][qty]'	 , $po->items[$i]['qty'], array('class' => 'form-control text-centered', 'disabled')) }}</td>
												<td class="#">{{ Form::text('po[items]['.$i.'][um]'		 , $po->items[$i]['um'], array('class' => 'form-control text-centered', 'disabled')) }}</td>
												<td class="#">{{ Form::text('po[items]['.$i.'][uc]'		 , $po->items[$i]['uc'], array('class' => 'form-control text-centered', 'disabled')) }}</td>
												<td class="#">{{ Form::text('po[items]['.$i.'][uc_um]'	 , $po->items[$i]['uc_um'], array('class' => 'form-control text-centered', 'disabled')) }}</td>
												<td class="#">{{ Form::text('po[items]['.$i.'][tax]'	 , $po->items[$i]['tax'], array('class' => 'form-control text-centered', 'disabled')) }}</td>
												<td class="#">{{ Form::text('po[items]['.$i.'][total]'	 , $po->items[$i]['total'], array('class' => 'form-control text-centered', 'disabled')) }}</td>
											</tr>
										@endfor
								</tbody>
							</table>
						</div>
					</div>
			</div>
		</div>
		{{ Form::close() }}


@section('script.embedded.footer')
	@parent
	$('.tooltips').tooltip();
	/**
	 * Add datpicker to due date input fields.
	 */
	$( document ).delegate( 'input:text' , 'focus' , function( e ){
		$(this).filter(function(){
			return this.name.match(/po\[items\]\[(\d+)\]\[due_date\]/);
		}).datepicker();
	});

	// Assign the errorContainer object to a variable
	//
	var errorContainer = $( '#errorContainer' );

	// Assign the table object to a variable
	// 
	var poFormTable = $( '.poFormTable' );

	// Set the counters default value so that it can properly increment
	// if the user is returned to the page.
	//
	var counter = getTbodyTrCount( poFormTable );

	/**
	 * Obtain the current number of tbody trs
	 * 
	 * @param  {object} element The table object
	 * @return {int}            The number of rows.
	 */
	function getTbodyTrCount(element)
	{
		return element.find( 'tbody tr' ).length - 1;		
	}

	/**
	 * Dynamically add new rows and form elements to the table.
	 */
	$( '#addNewRow' ).on( 'click' , function( e )
	{
		e.preventDefault();
		e.stopPropagation();

		// Increment the counter
		// 
		counter++;

		// Clone the very last table row inside of the table.
		// 
		poFormTable.find( 'tbody tr.new:last' ).clone().attr({

			// Dynamically modify the value of the custom "row" attribute
			// so that it reflects the new row
			// 
			row : function ( i , val ) 
			{
				return val.replace( /\[(\d+)\]/ , function( str , p1 )
				{
					return '[' + ( parseInt( p1 , 10 ) + 1 ) + ']';
				});
			}

		}).find( 'td, :input' ).each( function( )
		{
			// Since we are looking for both td and input elements we
			// will need to use a switch statement to handle each
			// specific element when encountered.
			// 
			switch( $(this).get(0).tagName )
			{
				//-----------------------------------------------------
				case 'TD':

					// Change the text value in the first td cell
					// 
					if( $( this ).index() == 0 )
					{
						$( this ).removeAttr('class');
						$( this ).text( counter );
					}

					// Remove any error-related classes used for error 
					// formatting with bootstrap.
					// 
					$(this).removeClass( 'has-error' );

				break;
				//-----------------------------------------------------
				case 'INPUT':

					// Dynamically modify the name of the form element
					// so that it reflects the new row
					// 
					this.name = this.name.replace( /\[(\d+)\]/ , function( str , p1 )
					{
						return '[' + ( parseInt( p1 , 10 ) + 1 ) + ']';
					});

					// Give the form element a blank value
					// 
					this.value = '';

				break;
				//-----------------------------------------------------
			}
		}).end().appendTo( poFormTable );
	});

	/**
	 * Detect keypress inside of a forms input element.
	 */
	$( '#newPoForm' ).on( 'keydown' , ':input' , function( e )
	{
		// Define which form elements will need to ignore this rule.
		// 
		var ignoreTags = [ 'TEXTAREA' ];

		// Check if the currently element is listed in our ignore list
		// 
		var inArray = $.inArray( $(this).get(0).tagName , ignoreTags ) == 0;

		// Prevent new rows being added when the enter key
		// is pressed while inside of a form element.
		//
	    if ( e.keyCode === 13 && ! inArray )
	    {
	        e.preventDefault();
	        e.stopPropagation();
	    }
	});

	/**
	 * Sometimes users may take a little longer than normal to submit 
	 * their forms. So, in order to prevent invalid CSRF token, we
	 * will generate a new one on they fly when the form is submitted.
	 */
	$( '#submitFormDraft' ).on( 'click' , function( e )
	{
        e.preventDefault();
        e.stopPropagation();

        var $this 		= $( this );
        var $form 		= $this.parent().parent( 'form' );
        var tokenField 	= $form.find( ':input[name="_token"]:first' );
        var tokenValue 	= tokenField.val();

        // Find the draft field and set it to one (1);
        //
        var draftField  = $form.find(':input[name="po[draft]"]').val(1);

        // Obtain a new csrf token in the event the current one has expired
        //
        $.getJSON('{{ route("token.index") }}', function(response)
        {
        	// Assign a new token to the form.
        	// 
        	tokenField.val( response._token );

        	// Submit the form
        	//
        	$form.submit();
    	});
	});

	/**
	 * Sometimes users may take a little longer than normal to submit 
	 * their forms. So, in order to prevent invalid CSRF token, we
	 * will generate a new one on they fly when the form is submitted.
	 */
	$( '#submitForm' ).on( 'click' , function( e )
	{
        e.preventDefault();
        e.stopPropagation();

        var $this 		= $( this );
        var $form 		= $this.parent().parent( 'form' );
        var tokenField 	= $form.find( ':input[name="_token"]:first' );
        var tokenValue 	= tokenField.val();

        // Find the draft field and set it to zer0 (0);
        //
        var draftField  = $form.find(':input[name="po[draft]"]').val(0);

        // Obtain a new csrf token in the event the current one has expired
        //
        $.getJSON('{{ route("token.index") }}', function(response)
        {
        	// Assign a new token to the form.
        	// 
        	tokenField.val( response._token );

        	// Submit the form
        	//
        	$form.submit();
    	});
	});

	/**
	 * Remove an entire row from the table.
	 */
	$( document ).delegate( '.removeRow' , 'click' , function( e )		
	{
		e.preventDefault();
		e.stopPropagation();

		// Count how many rows that are currently in the table.
		// 
		var rowCount = getTbodyTrCount( poFormTable ) + 1;

		// If there is only one row in the table we will need to simply 
		// reset the table...otherwise you would look foolish if you took away
		// the ablility for the user to add line items ;)
		// 
		if( rowCount == 1 )
		{
			// Reset the counter to zero
			// 
			counter = 0;

			// Clone the very last table row inside of the table.
			// 
			poFormTable.find( 'tbody tr' ).attr({

				// Dynamically modify the value of the custom "row" attribute
				// so that it reflects the new row
				// 
				row : function ( i , val ) 
				{
					return val.replace( /\[(\d+)\]/ , function( str , p1 )
					{
						return '[' + counter + ']';
					});
				}

			}).find( 'td, :input' ).each( function()
			{
				// Since we looking for both td and input elements we
				// will need to use a switch statement to handle each
				// specific element when encountered.
				// 
				switch( $( this ).get(0).tagName )
				{
					//-----------------------------------------------------
					case 'TD':

						// Change the text value in the first td cell
						// 
						if( $( this ).index() == 0 ) $( this ).text( counter );

						// Remove any error-related classes used for error 
						// formatting with bootstrap.
						// 
						$( this ).removeClass( 'has-error' );

					break;
					//-----------------------------------------------------
					case 'INPUT':

						// Dynamically modify the name of the form element
						// so that it reflects the new row
						// 
						this.name = this.name.replace( /\[(\d+)\]/ , function( str , p1 )
						{
							return '[' + counter + ']';
						});

						// Give the form element a blank value
						// 
						this.value = '';
						
						// Remove all existing row alert messages.
						//
						errorContainer.find( 'li[row="po[items][0][row_id]"]' ).remove();

						// Fire the errorContainer change event
						//
						errorContainer.change();

					break;
					//-----------------------------------------------------
				}
			}).end();
		} 

		// We are cleared to remove rows.
		else
		{
			// Update our row count
			// 
			rowCount = getTbodyTrCount( poFormTable ) + 1;

			// Asign the nearest table row object to a variable
			// 
			var closestRow = $( this ).closest( 'tr' );

			// Capture the row identity from the tr element
			// 
			var rowValue = closestRow.attr( 'row' );

			// Delete any existing error message that relate to the row
			// 
			errorContainer.find( 'li[row="' + rowValue + '"]' ).remove();

			// Remove the tr that is associate with the button.
			// 
			closestRow.remove();
		}
	});

	// Each time the errorContainer changes we need to check if there
	// are any remaining errors listed
	//
	$( document ).delegate( '#errorContainer' , 'change' , function()
	{
		// Count how many li are still present
		//
		var liCount = $( this ).find( 'ul' ).find( 'li' ).length;

		// If there are no more listed errors then remove the errorContainer
		// from the page.
		//
		if( liCount == 0 ) $( this ).remove();
	});

	$('.destroyPo').on('click', function(event)
	{
		event.preventDefault();

		$.ajax({
			'type'   : 'DELETE',
			'url'    : $(this).attr('href'),
			'success': function(data, textStatus, jqXHR)
			{	
				window.location = '{{ route("pos.index") }}';
			}
		});
	});
@stop