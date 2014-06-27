<?php $poId = $data['conversation']['po']['po_number'] ? $data['conversation']['po']['po_number'] : $data['conversation']['po']['id'];?>

    {{ $data['sender']['display_name'] }} has submitted a new <a href="{{ route('conversations.show', $data['conversation_id']) }}">message</a> about purchase order #<a href="{{ route('pos.show', $poId) }}">{{ $poId }}</a>.

    Here's the message:
    {{ $data['message'] }}

    <hr/>
    Regards,<br/>
    EPOS Notification System</p>
