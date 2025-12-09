<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bevestig je e-mailadres</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <tr>
                        <td style="background: linear-gradient(to right, #2563eb, #9333ea); padding: 32px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Oxxen.nl</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 32px;">
                            <h2 style="margin: 0 0 16px 0; color: #111827; font-size: 24px;">Welkom bij Oxxen.nl!</h2>
                            <p style="margin: 0 0 24px 0; color: #4b5563; font-size: 16px; line-height: 1.6;">
                                Hallo{{ $user->first_name ? ' ' . $user->first_name : '' }},<br><br>
                                Bedankt voor je registratie bij Oxxen.nl. Klik op de onderstaande knop om je e-mailadres te bevestigen en je account te activeren.
                            </p>
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" style="padding: 16px 0;">
                                        <a href="{{ $verificationUrl }}" style="display: inline-block; background: linear-gradient(to right, #2563eb, #9333ea); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 12px; font-weight: 600; font-size: 16px;">
                                            E-mailadres bevestigen
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin: 24px 0 0 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                                Deze link is 60 minuten geldig. Als je geen account hebt aangemaakt bij Oxxen.nl, kun je deze e-mail negeren.
                            </p>
                            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 32px 0;">
                            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                                Als de knop niet werkt, kopieer dan deze link naar je browser:<br>
                                <a href="{{ $verificationUrl }}" style="color: #2563eb; word-break: break-all;">
                                    {{ $verificationUrl }}
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px 32px; text-align: center;">
                            <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                &copy; {{ date('Y') }} Oxxen.nl - Alle rechten voorbehouden
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
