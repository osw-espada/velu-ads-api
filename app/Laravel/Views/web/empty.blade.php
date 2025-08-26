<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Transaction Not Found</title>
    <style>
        :root {
            --bg: #f6f9fc;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #6b7280;
            --neutral: #64748b;
            --neutral-weak: #e2e8f0;
            --border: #e5e7eb;
            --btn: #111827;
            --btn-text: #ffffff;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
            background: var(--bg);
            color: var(--text);
            display: grid;
            place-items: center;
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 560px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.06);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, var(--neutral-weak), #f8fafc);
            padding: 32px 24px 16px;
            text-align: center;
        }
        .icon-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: #94a3b814;
            border: 2px solid #94a3b840;
            margin-bottom: 12px;
        }
        .icon { color: var(--neutral); }
        h1 { margin: 0 0 6px; font-size: 1.5rem; line-height: 1.2; }
        .subtitle { margin: 0; color: var(--muted); font-size: 0.95rem; }
        .content { padding: 20px 24px 24px; }
        .details {
            margin: 16px 0 24px;
            padding: 16px;
            border: 1px dashed var(--border);
            border-radius: 12px;
            background: #fafafa;
        }
        .details .label { color: var(--muted); font-size: 0.85rem; }
        .details .value { font-weight: 600; word-break: break-word; margin-top: 4px; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .btn {
            appearance: none;
            border: 1px solid var(--border);
            background: var(--btn);
            color: var(--btn-text);
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 0.06s ease, box-shadow 0.2s ease, opacity 0.2s ease;
            box-shadow: 0 6px 14px rgba(0,0,0,0.10);
        }
        .btn:hover { transform: translateY(-1px); }
        .btn.secondary { background: #ffffff; color: #111827; }
        .note { margin-top: 14px; color: var(--muted); font-size: 0.9rem; }
    </style>
</head>
<body>
<main class="card" role="main" aria-labelledby="title">
    <div class="header">
        <div class="icon-wrap" aria-hidden="true">
            <svg class="icon" width="36" height="36" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" opacity="0.25"></circle>
                <path d="M11 7h2v6h-2zM11 15h2v2h-2z" fill="currentColor"></path>
            </svg>
        </div>
        <h1 id="title">Transaction not found</h1>
        <p class="subtitle">We couldn’t locate a transaction with the details provided.</p>
    </div>

    <div class="content">
        <section class="details" aria-label="Lookup details">
            <div class="label">Searched for</div>
            <div class="value">Code: {{$transaction_code}}</div>
        </section>


        <p class="note">Check the code for typos or contact support at <a href="mailto:support@velu.com">support@velu.com</a>.</p>
    </div>
</main>
</body>
</html>
