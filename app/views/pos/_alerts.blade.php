
					@if( $po->manager_approved_at )
						{{ HTML::alert('success', ' ' . $po->manager . ' on ' . $po->manager_approved_at, 'Management Approved! ')}}
					@endif
                    @if( is_null($po->manager_approved_at) )
                        {{ HTML::alert('info', '<a class="btn btn-xs btn-primary" href="' . route('pos.manager-approval.index', $po->id) . '"><strong>Assign</strong></a> manager approval if needed.', 'Manager Approval:')}}
                    @endif
					@if( is_null($po->accountant_approved_at) )
						{{ HTML::alert('info', 'This purchase order requires accountant approval. <a class="btn btn-xs btn-primary" href="' . route('pos.accountant-approval.index', $po->id) . '"><strong>Click Here</strong></a> to approve.', 'Accountant Approval Required:')}}
					@endif
					@if( $po->accountant_approved_at )
						{{ HTML::alert('success', ' ' . $po->accountant->display_name . ' @ ' . $po->accountant_approved_at, 'Accountant Approved!')}}
					@endif