<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt & Invoice - Guitarclassbynde</title>
</head>
<body style="margin: 0; padding: 0; background-color: #08080a; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #e4e4e7;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #08080a; padding: 40px 10px;">
        <tr>
            <td align="center">
                <!-- Outer Glassmorphic Card Container -->
                <table role="presentation" width="100%" max-width="600" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #121217; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px; overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);">
                    
                    <!-- Header Accent Bar -->
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #10b981, #3b82f6, #6366f1);"></td>
                    </tr>

                    <!-- Header Section -->
                    <tr>
                        <td style="padding: 36px 40px 24px 40px;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="left" valign="middle">
                                        <a href="{{ url('/') }}" target="_blank" style="text-decoration: none; display: inline-block;">
                                            <div style="font-size: 24px; font-weight: 900; color: #ffffff; letter-spacing: 1.5px; text-transform: uppercase; line-height: 1;">
                                                GUITARCLASS<span style="color: #3b82f6;">BYNDE</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td align="right" valign="middle">
                                        <span style="display: inline-block; padding: 6px 14px; background-color: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 20px; color: #34d399; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                            PAID & VERIFIED
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="border-top: 1px solid rgba(255, 255, 255, 0.08);"></div>
                        </td>
                    </tr>

                    <!-- Main Title Section -->
                    <tr>
                        <td style="padding: 28px 40px 10px 40px;">
                            <h1 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; text-transform: uppercase;">
                                Official <span style="color: #60a5fa;">Payment Invoice</span>
                            </h1>
                            <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #a1a1aa;">
                                Hi <strong style="color: #ffffff;">{{ $transaction->user->name ?? 'Student' }}</strong>, thank you for your payment! Here is your official receipt details.
                            </p>
                        </td>
                    </tr>

                    <!-- Perfectly Aligned Billed To & Invoice Details Card -->
                    <tr>
                        <td style="padding: 20px 40px;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px;">
                                <tr>
                                    <!-- Billed To Column -->
                                    <td width="46%" valign="top" style="padding: 20px;">
                                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #71717a; letter-spacing: 1px; margin-bottom: 10px;">
                                            BILLED TO
                                        </div>
                                        <div style="font-size: 15px; font-weight: 700; color: #ffffff; margin-bottom: 4px;">
                                            {{ $transaction->user->name ?? 'Student' }}
                                        </div>
                                        <div style="font-size: 12px; color: #3b82f6; word-break: break-all; text-decoration: none;">
                                            {{ $transaction->user->email ?? '' }}
                                        </div>
                                    </td>

                                    <!-- Vertical Divider -->
                                    <td width="2%" align="center" valign="middle" style="padding: 10px 0;">
                                        <div style="border-left: 1px solid rgba(255, 255, 255, 0.08); height: 75px;"></div>
                                    </td>

                                    <!-- Invoice Details Column (Key-Value Aligned) -->
                                    <td width="52%" valign="top" style="padding: 20px;">
                                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #71717a; letter-spacing: 1px; margin-bottom: 10px;">
                                            INVOICE DETAILS
                                        </div>
                                        <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="70" style="font-size: 12px; color: #71717a; padding-bottom: 6px;" valign="top">Order ID</td>
                                                <td width="15" style="font-size: 12px; color: #71717a; padding-bottom: 6px;" valign="top">:</td>
                                                <td style="font-size: 13px; font-weight: 700; color: #60a5fa; font-family: monospace; padding-bottom: 6px;" valign="top">
                                                    {{ $transaction->order_id }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #71717a; padding-bottom: 6px;" valign="top">Date</td>
                                                <td style="font-size: 12px; color: #71717a; padding-bottom: 6px;" valign="top">:</td>
                                                <td style="font-size: 12px; font-weight: 600; color: #ffffff; padding-bottom: 6px;" valign="top">
                                                    {{ $transaction->updated_at ? $transaction->updated_at->format('d M Y, H:i') : date('d M Y, H:i') }} WIB
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 12px; color: #71717a;" valign="top">Method</td>
                                                <td style="font-size: 12px; color: #71717a;" valign="top">:</td>
                                                <td style="font-size: 12px; font-weight: 600; color: #e4e4e7;" valign="top">
                                                    {{ strtoupper(str_replace('_', ' ', $transaction->payment_type ?? $transaction->method ?? 'Midtrans Guarantee')) }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Order Details Table -->
                    <tr>
                        <td style="padding: 10px 40px 20px 40px;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                        <th align="left" style="padding: 12px 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #71717a; letter-spacing: 1px;">Item Description</th>
                                        <th align="right" style="padding: 12px 0; font-size: 11px; font-weight: 700; text-transform: uppercase; color: #71717a; letter-spacing: 1px;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                        <td align="left" style="padding: 18px 0;">
                                            <div style="font-size: 15px; font-weight: 700; color: #ffffff;">
                                                {{ $transaction->package_name ?? 'Guitar Course Package' }}
                                            </div>
                                            <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">
                                                Full Lifetime Access • All Video Lessons & Materials
                                            </div>
                                        </td>
                                        <td align="right" style="padding: 18px 0; font-size: 15px; font-weight: 700; color: #ffffff; font-family: monospace;">
                                            Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td align="left" style="padding-top: 20px; font-size: 14px; font-weight: 700; color: #a1a1aa; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Total Amount Paid
                                        </td>
                                        <td align="right" style="padding-top: 20px; font-size: 20px; font-weight: 900; color: #34d399; font-family: monospace;">
                                            Rp {{ number_format($transaction->gross_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>

                    <!-- CTA Access Button Section -->
                    <tr>
                        <td style="padding: 10px 40px 30px 40px; text-align: center;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: rgba(37, 99, 235, 0.08); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 16px; padding: 24px;">
                                <tr>
                                    <td align="center">
                                        <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: 700; color: #ffffff;">Ready to start learning?</h3>
                                        <p style="margin: 0 0 20px 0; font-size: 13px; color: #9ca3af;">Your course access has been activated on your student dashboard.</p>
                                        <a href="{{ route('kelas') }}" target="_blank" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); color: #ffffff; font-size: 12px; font-weight: 700; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; border-radius: 12px; box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);">
                                            ACCESS YOUR COURSES NOW →
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="border-top: 1px solid rgba(255, 255, 255, 0.08);"></div>
                        </td>
                    </tr>

                    <!-- Footer Section -->
                    <tr>
                        <td style="padding: 24px 40px 32px 40px; text-align: center; font-size: 11px; color: #71717a; line-height: 1.6;">
                            <p style="margin: 0 0 6px 0;">This email serves as your official receipt for tax and record-keeping purposes.</p>
                            <p style="margin: 0; color: #52525b;">© {{ date('Y') }} Guitarclassbynde. All rights reserved. Master Guitar with Nde.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
