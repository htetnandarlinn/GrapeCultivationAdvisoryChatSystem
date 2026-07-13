<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        /* Mobile responsive adjustments for modern email clients */
        @media screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                padding: 16px !important;
            }
            .btn-wrapper {
                width: 100% !important;
                display: block !important;
            }
            .btn {
                display: block !important;
                text-align: center !important;
                padding: 14px 20px !important;
            }
        }
        /* Smooth micro-interaction transitions for clients supporting inline animations */
        .btn {
            transition: all 0.2s ease-in-out !important;
        }
        .btn:hover {
            background-color: #166534 !important; /* Slightly darker shade on hover */
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25) !important;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #ffffff; -webkit-font-smoothing: antialiased;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; padding: 32px 16px;">
        <tr>
            <td align="center">
                
                <table class="email-container" role="presentation" width="100%" max-width="540" style="max-width: 540px; background-color: #ffffff; border: 1px solid #f1f5f9; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05); overflow: hidden; text-align: left;" cellspacing="0" cellpadding="0" border="0">
                    
                    <tr>
                        <td height="6" style="background-color: #15803D; line-height: 6px; font-size: 1px;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="padding: 40px;">
                            
                            <h2 style="margin: 0 0 24px 0; color: #15803D; font-size: 24px; font-weight: 700; tracking: -0.025em;">
                                Reset Your Password
                            </h2>
                            
                            <p style="margin: 0 0 16px 0; font-size: 16px; line-height: 24px; color: #334155;">
                                Hello <strong style="color: #0f172a;"><?= htmlspecialchars($username ?? 'User') ?></strong>,
                            </p>
                            
                            <p style="margin: 0 0 32px 0; font-size: 15px; line-height: 24px; color: #475569;">
                                We received a request to reset your password for your account. Click the action button below to set up a new credentials profile. This temporary safety link will safely expire in <span style="font-weight: 600; color: #0f172a;">1 hour</span>.
                            </p>
                            
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="btn-wrapper" style="margin: 0 auto 32px auto;">
                                <tr>
                                    <td align="center" style="border-radius: 12px;" bgcolor="#15803D">
                                        <a href="<?= htmlspecialchars($resetLink ?? '#') ?>" class="btn" target="_blank" style="font-size: 15px; font-weight: 600; color: #ffffff; text-decoration: none; padding: 14px 32px; display: inline-block; border-radius: 12px; background-color: #15803D;">
                                            Reset Password
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 32px 0; font-size: 14px; line-height: 22px; color: #64748b; background-color: #fafafa; padding: 16px; border-radius: 12px; border-left: 4px solid #e2e8f0;">
                                If you didn't trigger this password change operation, you can safely ignore or delete this email. Your current security settings remain completely untouched.
                            </p>
                            
                            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 0 0 24px 0;">

                            <p style="margin: 0; font-size: 14px; line-height: 22px; color: #64748b;">
                                Thanks,<br>
                                <span style="font-weight: 600; color: #334155;">Grape Cultivation Advisory Team</span>
                            </p>
                            
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>