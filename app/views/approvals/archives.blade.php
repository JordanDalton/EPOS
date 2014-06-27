@section('content')
    <div class="container">
        <div class="row">
            <ol class="breadcrumb">
              <li><a href="{{ route('approvals.index') }}"><i class="fa fa-usd"></i> Accounting</a></li>
              <li class="active"><small><i class="fa fa-check"></i> Approved PO Archives</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h1><i class="fa fa-check"></i> Approved PO Archives</h1>
                <p>Purchase order that have been previously approved by accounting.</p>
                <hr/>
                <div class="row">
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
                <table class="table poTable">
                    <thead>
                        <tr>
                            <th>View</th>
                            <th>Name</th>
                            <th class="centered">Submitted</th>
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
                            <td colspan="9">{{ HTML::alert('info', 'There are no records to display.', 'Nothing Here!') }}</td>
                        </tr>
                    @endif
                    @foreach( $pos as $po )
                        <tr class="{{ $po->isApprovedComplete() ? 'success' : '#' }}">
                            <td>{{ link_to_route('pos.show', $po->id, $po->id , array('class' => 'lato', 'style' => 'text-decoration:underline')) }}</td>
                            <td>{{ $po->name }}</td>
                            <td class="centered">{{ $po->created_at }}</td>
                            <td class="centered">{{ $po->items->count() }}</td>
                            <td class="centered"><i class="fa fa-usd"></i> {{ $po->items(true) }}</td>
                            <td class="centered">
                                <a  class="lato" style="text-decoration:underline" href="{{ route('pos.attachments.index', $po->id) }}">{{ $po->attachments->count() }}</a>
                            </td>
                            <td class="centered">
                                @if( $po->accountant_approved_at() )
                                    <span class="btn btn-info btn-xs tooltips" data-toggle="tooltip" title="{{ $po->accountant->display_name }}"><i class="fa fa-check"></i></span>
                                     @else
                                    <a href="{{ route('pos.accountant-approval.index', $po->id) }}"><i class="fa fa-plus"></i></a>
                                 @endif
                            </td>
                            <td class="centered">
                                @if( $po->manager_approved_at )
                                    <span class="btn btn-info btn-xs tooltips" data-toggle="tooltip" title="{{ $po->manager }}"><i class="fa fa-check"></i></span>
                                 @else
                                    <a href="{{ route('pos.manager-approval.index', $po->id) }}"><i class="fa fa-plus"></i></a>
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