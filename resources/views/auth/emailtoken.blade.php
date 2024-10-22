<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h1 style="color: #333;">Thank you for signing up {{ $user->name }}!</h1>
    <p>To finalize your registration, please use the following code:</p>
    <p style="font-size: 1.5em; font-weight: bold; background-color: #f4f4f4; padding: 10px; border-radius: 5px;">{{ $token }}</p>
    <p>If you didn't sign up for this account, please ignore this email.</p>
</body>
</html>
