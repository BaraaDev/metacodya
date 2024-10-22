<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
<p>Hi {{ $user->name }},</p>
<p>You requested a password reset. Click the link below to reset your password:</p>
<a href="{{ url('password/reset', $token) }}">Reset Password</a>
<p>If you did not request this, please ignore this email.</p>
</body>
</html>
