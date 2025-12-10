<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Arial', 'Helvetica', sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f3f4f6;">
        <tr>
            <td style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                {{ $title }}
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px;">
                                KidzTech System Notification
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <div style="margin: 0 0 30px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                {!! nl2br(e($body)) !!}
                            </div>

                            <!-- Metadata (if provided) -->
                            @if(!empty($metadata))
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0; background-color: #f9fafb; border-left: 4px solid #6366f1; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #4f46e5;">
                                            Details
                                        </h2>
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            @foreach($metadata as $key => $value)
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280; width: 40%;">
                                                    <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    {{ $value }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Action Button (if provided) -->
                            @if($actionText && $actionUrl)
                            <table role="presentation" style="margin: 30px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ $actionUrl }}" style="display: inline-block; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(99, 102, 241, 0.3);">
                                            {{ $actionText }}
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <p style="margin: 30px 0 0 0; font-size: 14px; color: #6b7280; line-height: 1.6;">
                                If you have any questions or concerns, please contact our support team.
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
                                This is an automated system notification. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
