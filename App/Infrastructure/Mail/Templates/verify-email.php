<?php
$baseUrl = 'http://localhost/GrapeCultivationAdvisoryChatSystem/public';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        /* Responsive overrides for modern email clients */
        @media screen and (max-width: 600px) {
            .email-container { width: 100% !important; padding: 20px !important; }
            .fluid-button { display: block !important; width: 100% !important; text-align: center !important; padding-left: 0 !important; padding-right: 0 !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; width: 100%; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f8fafc; padding: 40px 16px;">
        <tr>
            <td align="center">
                
                <table class="email-container" role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 500px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05); border: 1px solid #f1f5f9;">
                    
                    <tr>
                        <td height="6" style="background-color: #029664; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 32px; text-align: left;">
                            
                            <div style="margin-bottom: 24px; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #029664;">
                                Grape Cultivation Advisory
                            </div>

                            <h2 style="margin: 0 0 16px 0; font-size: 24px; font-weight: 700; color: #1e293b; tracking-tight: -0.02em;">
                                Hello <?= htmlspecialchars(isset($username) ? $username : 'User') ?>,
                            </h2>

                            <p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #475569;">
                                Welcome to the <strong>Grape Cultivation Advisory Chat System</strong>. We're thrilled to help you optimize your vineyard's health and yields.
                            </p>

                            <p style="margin: 0 0 32px 0; font-size: 15px; line-height: 1.6; color: #475569;">
                                Please verify your email address by clicking the button below to fully activate your account.
                            </p>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 32px; width: 100%;">
                                <tr>
                                    <td align="left">
                                        <a class="fluid-button" href="<?= htmlspecialchars(isset($verificationLink) ? $verificationLink : '#') ?>" target="_blank" style="background-color: #029664; color: #ffffff; display: inline-block; padding: 14px 28px; font-size: 15px; font-weight: 600; text-decoration: none; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(2, 150, 100, 0.2), 0 2px 4px -1px rgba(2, 150, 100, 0.1); transition: background-color 0.2s ease;">
                                            Verify Email Address
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #ecfdf5; border-radius: 8px; border: 1px solid #d1fae5;">
                                <tr>
                                    <td style="padding: 14px 16px; font-size: 13px; color: #065f46; line-height: 1.5;">
                                        <strong style="color: #047857;">Note:</strong> This verification link is temporary and will expire in <span style="font-weight: 700; color: #047857;">24 hours</span> for security reasons.
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                    
                    <tr>
                        <td style="padding: 0 32px 32px 32px; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8; line-height: 1.5;">
                                If you did not create an account, you can safely ignore this email.<br>
                                &copy; 2026 Grape Cultivation Advisory Chat System.
                            </p>
                        </td>
                    </tr>

                </table>
                
            </td>
        </tr>
    </table>

</body>
</html>