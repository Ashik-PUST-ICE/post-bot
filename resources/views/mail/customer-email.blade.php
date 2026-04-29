<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }}</title>
    <style>
        body { margin:0; padding:0; background:#f4f4f4; font-family: Arial, sans-serif; }
        .wrapper { max-width:600px; margin:30px auto; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.07); }
        .header { background:#6366f1; padding:28px 32px; }
        .header h1 { color:#fff; margin:0; font-size:20px; font-weight:700; }
        .body { padding:32px; color:#374151; font-size:15px; line-height:1.7; }
        .footer { background:#f9fafb; padding:20px 32px; text-align:center; font-size:12px; color:#9ca3af; border-top:1px solid #e5e7eb; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>{{ $appName }}</h1>
        </div>
        <div class="body">
            {!! nl2br(e($body)) !!}
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $appName }} &mdash; <a href="{{ $appUrl }}" style="color:#6366f1;">{{ $appUrl }}</a>
        </div>
    </div>
</body>
</html>
