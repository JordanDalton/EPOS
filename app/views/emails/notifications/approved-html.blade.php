<body>
	<h3>Purchase Oder Approved</h3>
	The purchase order "<a href="{{ route('pos.show', $data['id']) }}">{{ $data['name'] }}</a>" has been approved by {{ $data['accountant']['display_name'] }}.
</body>