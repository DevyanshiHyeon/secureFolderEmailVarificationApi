<!DOCTYPE html>

<html>

<head>
    <title>{{ $details['title'] }}</title>
</head>
<body>
    {{-- <h1>{{ $details['title'] }}</h1> --}}
    <p><b>Dear user,</b></p>
    <p>Please fill in the following code in <b>Secure Folder</b> to complate the Verification.</p>
    <p><b>{{ $details['otp'] }}</b></p>
    <p>If you did not request a code, please ignore this message.</p>
    <p>Secure Folder Team</p>
</body>
</html>
