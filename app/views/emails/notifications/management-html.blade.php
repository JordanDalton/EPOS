<body>
    <p>{{ $data['manager']['display_name'] }},
	<br/><br/>
    {{ $data['user']['display_name'] }} has submitted a new <a href="{{ route('pos.manager-approval.index', $data['id']) }}">purchase order</a> for your review and approval.</p>

    <p>Regards,<br/>
    EPOS Notification System</p>
</body>