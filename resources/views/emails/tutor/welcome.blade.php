<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to KidzTech</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Arial', 'Helvetica', sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f3f4f6;">
        <tr>
            <td style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #423A8E 0%, #00CCCD 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                Welcome to KidzTech!
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px;">
                                Your Tutor Account Has Been Created
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                Dear {{ $tutor->first_name }} {{ $tutor->last_name }},
                            </p>

                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                Welcome to the KidzTech team! Your tutor account has been successfully created. You can now access the Tutor Portal to manage your classes, submit attendance, and create student progress reports.
                            </p>

                            <!-- Login Credentials Box -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0; background-color: #f0f9ff; border-left: 4px solid #00CCCD; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #423A8E;">
                                            Your Login Credentials
                                        </h2>
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280; width: 30%;">
                                                    <strong>Login URL:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    <a href="{{ $loginUrl }}" style="color: #00CCCD; text-decoration: none;">{{ $loginUrl }}</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280; width: 30%;">
                                                    <strong>Email:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    {{ $user->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280; width: 30%;">
                                                    <strong>Password:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    {{ $password }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Notice -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0; font-size: 14px; color: #92400e; line-height: 1.5;">
                                            <strong>Important:</strong> For security purposes, you will be prompted to change your password upon your first login. Please keep your credentials safe and do not share them with anyone.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Action Button -->
                            <table role="presentation" style="margin: 30px 0; width: 100%;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $loginUrl }}" style="display: inline-block; background: linear-gradient(135deg, #423A8E 0%, #00CCCD 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(66, 58, 142, 0.3);">
                                            Login to Tutor Portal
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #6b7280; line-height: 1.6;">
                                Through the Tutor Portal, you'll be able to:
                            </p>
                            <ul style="margin: 10px 0 20px 0; padding-left: 20px; font-size: 14px; color: #6b7280; line-height: 1.8;">
                                <li>View your assigned students</li>
                                <li>Submit class attendance records</li>
                                <li>Create monthly progress reports</li>
                                <li>Manage your availability calendar</li>
                                <li>Access teaching resources</li>
                            </ul>

                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #6b7280; line-height: 1.6;">
                                If you have any questions or need assistance, please contact the admin team.
                            </p>

                            <p style="margin: 20px 0 0 0; font-size: 14px; color: #6b7280; line-height: 1.6;">
                                Best regards,<br>
                                <strong style="color: #1f2937;">The KidzTech Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 10px 0; font-size: 12px; color: #6b7280;">
                                &copy; {{ date('Y') }} KidzTech - Empowering Young Minds Through Technology
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                This is an automated message. Please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
