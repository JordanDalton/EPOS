<?php $poId = $data['conversation']['po']['po_number'] ? $data['conversation']['po']['po_number'] : $data['conversation']['po']['id'];?>
<body>
    <p>{{ $data['sender']['display_name'] }} has submitted a new <a href="{{ route('conversations.show', $data['conversation_id']) }}">message</a> about purchase order #<a href="{{ route('pos.show', $poId) }}">{{ $poId }}</a>.</p>

    <p>Here's the message:<br/>
    <blockquote>{{ $data['message'] }}</blockquote></p>

    <hr/>
    <p>Regards,<br/>
    EPOS Notification System</p>
</body>