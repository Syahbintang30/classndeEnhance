<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Guitarclassbynde</title>
</head>
<body style="margin: 0; padding: 0; background-color: #08080a; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #e4e4e7;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #08080a; padding: 40px 10px;">
        <tr>
            <td align="center">
                <!-- Outer Glassmorphic Card Container -->
                <table role="presentation" width="100%" max-width="600" border="0" cellspacing="0" cellpadding="0" style="max-width: 600px; background-color: #121217; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px; overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);">
                    
                    <!-- Top Glowing Accent Bar -->
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
                                            <img src="{{ asset('compro/img/logo_styled.png') }}" alt="Guitarclassbynde Logo" style="height: 42px; width: auto; max-height: 42px; display: block; border: 0;" />
                                        </a>
                                        <div style="font-size: 11px; color: #71717a; margin-top: 4px;">
                                            Official Payment Receipt
                                        </div>
                                    </td>
                                    <td align="right" valign="middle">
                                        <div style="display: inline-block; padding: 6px 14px; background-color: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 9999px; color: #34d399; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">
                                            ✓ PAID &amp; CONFIRMED
                                        </div>
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

                    <!-- Thank You Greeting & Invoice Metadata -->
                    <tr>
                        <td style="padding: 24px 40px 10px 40px;">
                            <h2 style="margin: 0 0 8px 0; font-size: 22px; font-weight: 800; color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px;">
                                Thank You For Your <span style="color: #60a5fa;">Order!</span>
                            </h2>
                            <p style="margin: 0; font-size: 13px; color: #a1a1aa; line-height: 1.6;">
                                Hi <strong style="color: #ffffff;">{{ $user->name ?? 'Student' }}</strong>, your payment has been successfully processed. Your course package and coaching access have been unlocked in your student portal.
                            </p>
                        </td>
                    </tr>

                    <!-- Invoice Info Box (Billed To & Order Meta) -->
                    <tr>
                        <td style="padding: 16px 40px 24px 40px;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 16px; padding: 20px;">
                                <tr>
                                    <td width="50%" align="left" valign="top" style="padding-right: 10px;">
                                        <div style="font-size: 10px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Billed To</div>
                                        <div style="font-size: 13px; font-weight: 700; color: #ffffff;">{{ $user->name ?? 'Student' }}</div>
                                        <div style="font-size: 12px; color: #a1a1aa; margin-top: 2px;">{{ $user->email ?? '' }}</div>
                                    </td>
                                    <td width="50%" align="right" valign="top" style="padding-left: 10px;">
                                        <div style="font-size: 10px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Invoice Details</div>
                                        <div style="font-size: 12px; color: #a1a1aa;">Order ID: <strong style="color: #3b82f6;">#{{ $transaction->order_id ?? $orderId }}</strong></div>
                                        <div style="font-size: 12px; color: #a1a1aa; margin-top: 2px;">Date: <strong style="color: #ffffff;">{{ date('d M Y, H:i') }} WIB</strong></div>
                                        <div style="font-size: 12px; color: #a1a1aa; margin-top: 2px;">Method: <strong style="color: #ffffff;">{{ strtoupper($transaction->method ?? 'Midtrans') }}</strong></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Order Item Breakdown Table -->
                    <tr>
                        <td style="padding: 0 40px 24px 40px;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
                                <thead>
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                        <th align="left" style="padding: 10px 0; font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 1px;">Item Description</th>
                                        <th align="center" style="padding: 10px 0; font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 1px;">Qty</th>
                                        <th align="right" style="padding: 10px 0; font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 1px;">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                        <td align="left" style="padding: 16px 0; font-size: 13px; font-weight: 700; color: #ffffff;">
                                            {{ $package->name ?? 'Guitarclassbynde Course Package' }}
                                            @if($package && $package->description)
                                                <div style="font-size: 11px; font-weight: 400; color: #71717a; margin-top: 2px;">{{ Str::limit($package->description, 60) }}</div>
                                            @endif
                                        </td>
                                        <td align="center" style="padding: 16px 0; font-size: 13px; color: #a1a1aa;">1</td>
                                        <td align="right" style="padding: 16px 0; font-size: 13px; font-weight: 700; color: #ffffff;">
                                            Rp {{ number_format($transaction->original_amount ?? $transaction->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                    @if(isset($transaction->original_amount) && $transaction->original_amount > $transaction->amount)
                                        <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
                                            <td colspan="2" align="left" style="padding: 12px 0; font-size: 12px; color: #34d399;">
                                                Discount / Voucher Savings
                                            </td>
                                            <td align="right" style="padding: 12px 0; font-size: 12px; font-weight: 700; color: #34d399;">
                                                - Rp {{ number_format($transaction->original_amount - $transaction->amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Grand Total Row -->
                                    <tr>
                                        <td colspan="2" align="left" style="padding: 18px 0 0 0; font-size: 14px; font-weight: 800; color: #ffffff; text-transform: uppercase;">
                                            Total Amount Paid
                                        </td>
                                        <td align="right" style="padding: 18px 0 0 0; font-size: 18px; font-weight: 800; color: #60a5fa;">
                                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>

                    <!-- CTA Button -->
                    <tr>
                        <td style="padding: 10px 40px 32px 40px;" align="center">
                            <a href="{{ route('lms.dashboard') }}" target="_blank" style="display: inline-block; width: 80%; padding: 14px 28px; background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); color: #ffffff; font-size: 13px; font-weight: 800; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; border-radius: 12px; text-align: center; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);">
                                ACCESS STUDENT PORTAL NOW →
                            </a>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="border-top: 1px solid rgba(255, 255, 255, 0.08);"></div>
                        </td>
                    </tr>

                    <!-- Footer Section -->
                    <tr>
                        <td style="padding: 24px 40px 32px 40px; text-align: center; font-size: 11px; color: #71717a; line-height: 1.6;">
                            <p style="margin: 0 0 4px 0;">Need help with your order? Contact us at <a href="mailto:support@guitarclassbynde.id" style="color: #3b82f6; text-decoration: none;">support@guitarclassbynde.id</a></p>
                            <p style="margin: 0; color: #52525b;">© {{ date('Y') }} Guitarclassbynde. All rights reserved. Master Guitar with Nde.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
