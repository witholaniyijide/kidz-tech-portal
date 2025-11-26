<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Report Approved</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Arial', 'Helvetica', sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f3f4f6;">
        <tr>
            <td style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #9333ea 0%, #ec4899 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                🎉 Progress Report Approved!
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px;">
                                KidzTech Student Progress Report
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                Hello <strong>{{ $tutor->first_name }} {{ $tutor->last_name }}</strong>,
                            </p>

                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                Great news! The progress report for <strong>{{ $student->fullName() }}</strong> for the month of <strong>{{ date('F Y', strtotime($report->month . '-01')) }}</strong> has been approved by the Director.
                            </p>

                            <!-- Report Summary Card -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0; background-color: #f9fafb; border-left: 4px solid #9333ea; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #9333ea;">
                                            Report Summary
                                        </h2>
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280; width: 40%;">
                                                    <strong>Student:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    {{ $student->fullName() }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">
                                                    <strong>Report Period:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    {{ date('F Y', strtotime($report->month . '-01')) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">
                                                    <strong>Attendance Score:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    {{ $report->attendance_score ?? 'N/A' }}@if($report->attendance_score)%@endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">
                                                    <strong>Performance Rating:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    {{ $report->performance_rating ?? $report->rating ?? 'N/A' }}/10
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">
                                                    <strong>Status:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #059669; font-weight: bold;">
                                                    ✅ Approved by Director
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Director Comment (if exists) -->
                            @if($report->director_comment)
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #ede9fe; border-left: 4px solid #8b5cf6; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #5b21b6;">
                                            Director's Comment:
                                        </h3>
                                        <p style="margin: 0; font-size: 14px; color: #1f2937; line-height: 1.6; font-style: italic;">
                                            "{{ $report->director_comment }}"
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Manager Comment (if exists) -->
                            @if($report->manager_comment)
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #f0fdf4; border-left: 4px solid #10b981; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #065f46;">
                                            Manager's Feedback:
                                        </h3>
                                        <p style="margin: 0; font-size: 14px; color: #1f2937; line-height: 1.6; font-style: italic;">
                                            "{{ $report->manager_comment }}"
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <p style="margin: 30px 0 20px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                The complete progress report is attached to this email as a PDF. You can also view it online by clicking the button below:
                            </p>

                            <!-- CTA Button -->
                            <table role="presentation" style="margin: 30px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ url('/tutor/reports/' . $report->id) }}" style="display: inline-block; background: linear-gradient(135deg, #9333ea 0%, #ec4899 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(147, 51, 234, 0.3);">
                                            📄 View Report Online
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0 0; font-size: 14px; color: #6b7280; line-height: 1.6;">
                                Thank you for your dedication to helping our students learn and grow. Keep up the excellent work!
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
                                This email was sent to you as a KidzTech tutor. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
