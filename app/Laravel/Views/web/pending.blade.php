<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Payment Unpaid</title>
    <style>
        :root {
            --bg: #f6f9fc;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #6b7280;
            --warn: #d97706;
            --warn-weak: #fef3c7;
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
            background: linear-gradient(135deg, var(--warn-weak), #fff7ed);
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
            background: #f59e0b14;
            border: 2px solid #f59e0b40;
            margin-bottom: 12px;
        }
        .icon { color: var(--warn); }
        h1 { margin: 0 0 6px; font-size: 1.5rem; line-height: 1.2; }
        .subtitle { margin: 0; color: var(--muted); font-size: 0.95rem; }
        .content { padding: 20px 24px 24px; }
        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 16px;
            margin: 16px 0 24px;
            padding: 16px;
            border: 1px dashed var(--border);
            border-radius: 12px;
            background: #fafafa;
        }
        .details .label { color: var(--muted); font-size: 0.85rem; }
        .details .value { font-weight: 600; word-break: break-word; }
        .badge {
            display: inline-block;
            font-size: 0.8rem;
            color: var(--warn);
            background: var(--warn-weak);
            border: 1px solid #fcd34d;
            padding: 4px 8px;
            border-radius: 999px;
            margin-top: 8px;
        }
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
                <path d="M12 7v5m0 5h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </div>
        <h1 id="title">Payment unpaid</h1>
        <p class="subtitle">Your order was created but remains unpaid. Complete the payment to proceed.</p>
        <span class="badge" aria-label="status">Status: Unpaid</span>
    </div>

    <div class="content">
        <section class="details" aria-label="Order details">
            <div>
                <div class="label">Reference Number</div>
                <div class="value">{{$transaction->reference_number}}</div>
            </div>
            <div>
                <div class="label">Amount due</div>
                <div class="value">$ {{money_format($transaction->total)}}</div>
            </div>
            <div>
                <div class="label">Created at</div>
                <div class="value">{{$transaction->created_at->format("m/d/Y h:i A")}}</div>
            </div>
            <div>
                <div class="label">Expires at</div>
                <div class="value">{{$transaction->expired_at->format("m/d/Y h:i A")}}</div>
            </div>
        </section>

        <div class="actions">
            <a class="btn" href="{{$transaction->checkout_url}}" aria-label="Resume payment">Resume payment</a>
        </div>

        <p class="note">If you need help completing your payment, contact support at <a href="mailto:support@velu.com">velu@example.com</a>.</p>
    </div>
</main>
</body>
</html>
