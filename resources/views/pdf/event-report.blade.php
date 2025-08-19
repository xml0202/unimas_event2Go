<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; margin: 2em; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        .content { white-space: pre-line; margin-top: 1em; }
    </style>
</head>
<body>
    <h1>Report for {{ $event->title }}</h1>

    <p><strong>Report Date:</strong> {{ $event->report_updated_at?->format('Y-m-d H:i') ?? '-' }}</p>

    <hr>

    <div class="content">
        {{ $event->report }}
    </div>
</body>
</html>
