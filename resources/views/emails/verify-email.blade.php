<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email Address - Guitarclassbynde</title>
</head>
<body style="margin: 0; padding: 0; background-color: #08080a; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #e4e4e7;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #08080a; padding: 40px 10px;">
        <tr>
            <td align="center">
                <!-- Outer Glassmorphic Card Container -->
                <table role="presentation" width="100%" max-width="560" border="0" cellspacing="0" cellpadding="0" style="max-width: 560px; background-color: #121217; border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px; overflow: hidden; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);">
                    
                    <!-- Header Accent Bar -->
                    <tr>
                        <td style="height: 4px; background: linear-gradient(90deg, #2563eb, #6366f1, #3b82f6);"></td>
                    </tr>

                    <!-- Header Section -->
                    <tr>
                        <td style="padding: 36px 40px 20px 40px; text-align: center;">
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/') }}" target="_blank" style="text-decoration: none; display: inline-block; margin-bottom: 20px;">
                                            <div style="font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: 1.5px; text-transform: uppercase; line-height: 1;">
                                                GUITARCLASS<span style="color: #3b82f6;">BYNDE</span>
                                            </div>
                                        </a>
                                        <h1 style="margin: 0; font-size: 26px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; text-transform: uppercase;">
                                            Verify Your <span style="color: #60a5fa;">Email</span>
                                        </h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content Section -->
                    <tr>
                        <td style="padding: 10px 40px 30px 40px; text-align: center;">
                            <p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #a1a1aa;">
                                Hello, <strong style="color: #ffffff;">{{ $user->name ?? 'Student' }}</strong>! 👋
                            </p>
                            <p style="margin: 0 0 28px 0; font-size: 14px; line-height: 1.6; color: #a1a1aa;">
                                Thank you for registering at <strong style="color: #ffffff;">Guitarclassbynde</strong>. Please click the button below to verify your email address and activate your student portal account.
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 14px 36px; background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%); color: #ffffff; font-size: 13px; font-weight: 700; text-decoration: none; text-transform: uppercase; letter-spacing: 1px; border-radius: 12px; box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);">
                                            VERIFY EMAIL ADDRESS
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #71717a;">
                                If you did not create an account at Guitarclassbynde, no further action is required.
                            </p>
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
                            <p style="margin: 0 0 6px 0;">If you're having trouble clicking the button, copy and paste the URL below into your web browser:</p>
                            <p style="margin: 0 0 16px 0; word-break: break-all;"><a href="{{ $url }}" style="color: #3b82f6; text-decoration: none;">{{ $url }}</a></p>
                            <p style="margin: 0; color: #52525b;">© {{ date('Y') }} Guitarclassbynde. All rights reserved. Master Guitar with Nde.</p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
