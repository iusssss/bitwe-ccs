<h3>Hi {{ $name }},</h3>
<p class="text-muted">You recently requested to reset your password. Click the link below to reset it.</p>
<a href="{{ URL::to('/password/reset/' . $token) }}" class="btn btn-success">Reset your password</a>
<p class="text-muted">This request was received from crm.test. If you did not request a password reset, please ignore this email.</p>
<p>Thanks,<br>CRM team</p>