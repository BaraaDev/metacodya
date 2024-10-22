<!DOCTYPE html>
<html>
<head>
    <title>Your OTP Code</title>
</head>
<body>
<p>Hello {{ $user->name }},</p>
<p>Your verification code is: <strong>{{ $otp }}</strong></p>
<p>This code is valid for 10 minutes.</p>
<p>If you did not request this code, please ignore this email.</p>
<p>Thank you!</p>
</body>
</html>
