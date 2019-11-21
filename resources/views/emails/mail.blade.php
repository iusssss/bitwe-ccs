Hello <strong>{{ $name }}</strong>,<br>
{!! $body !!}
@if ($ticketStatus == 'Resolved')
	<?php
		$question = ($type == "Request") ? "Have I completed your request?" : "Have I solved your issue";
	?>
	<h3><strong>{{ $question }}</strong></h3>
	<a href="{{ URL::to('/csat/' . $ticketNo) }}">Yes</a>
	<br>
	<a href="{{ URL::to('/csat/' . $ticketNo) }}">No</a>
@endif
<br>
<br>
<strong>Ticket Details:</strong><br>
Ticket #: <strong>{{ $ticketNo }}</strong><br>
Ticket Status: <strong>{{ $ticketStatus }}</strong><br>
{{ $type }}: <strong>{{ $issue }}</strong><br>
Agent: <strong>{{ $agent }}</strong><br><br>
Actions Taken: <br>
@foreach ($updates as $update)
	{{ $update->created_at }}: <strong>{{ $update->action_taken }}</strong> <br>
@endforeach