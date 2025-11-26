<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Report Available</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Arial', 'Helvetica', sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f3f4f6;">
        <tr>
            <td style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

                    <!-- Header with Gradient -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                📊 New Progress Report Available
                            </h1>
                            <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px;">
                                {{ $student->first_name }}'s Monthly Progress Update
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                Dear Parent,
                            </p>

                            <p style="margin: 0 0 20px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                We're pleased to share <strong>{{ $student->first_name }} {{ $student->last_name }}'s</strong> progress report for <strong>{{ date('F Y', strtotime($report->month . '-01')) }}</strong>. This report has been prepared by their tutor and approved by our director.
                            </p>

                            <!-- Student & Report Info Card -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0; background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #1e40af;">
                                            Report Overview
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
                                                    <strong>Tutor:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937;">
                                                    {{ $tutor->first_name }} {{ $tutor->last_name }}
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
                                                    <strong>Performance Rating:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937; font-weight: bold;">
                                                    {{ ucfirst($report->performance_rating ?? 'N/A') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; font-size: 14px; color: #6b7280;">
                                                    <strong>Attendance Score:</strong>
                                                </td>
                                                <td style="padding: 8px 0; font-size: 14px; color: #1f2937; font-weight: bold;">
                                                    {{ $report->attendance_score ?? 'N/A' }}@if($report->attendance_score)%@endif
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Progress Summary -->
                            @if($report->progress_summary)
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #f0fdf4; border-left: 4px solid #10b981; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #065f46;">
                                            Progress Summary:
                                        </h3>
                                        <p style="margin: 0; font-size: 14px; color: #1f2937; line-height: 1.6;">
                                            {{ $report->progress_summary }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Strengths -->
                            @if($report->strengths)
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #d97706;">
                                            ⭐ Strengths:
                                        </h3>
                                        <p style="margin: 0; font-size: 14px; color: #1f2937; line-height: 1.6;">
                                            {{ $report->strengths }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Areas for Improvement -->
                            @if($report->weaknesses)
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #dc2626;">
                                            📈 Areas for Growth:
                                        </h3>
                                        <p style="margin: 0; font-size: 14px; color: #1f2937; line-height: 1.6;">
                                            {{ $report->weaknesses }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <!-- Next Steps -->
                            @if($report->next_steps)
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 20px 0; background-color: #ede9fe; border-left: 4px solid #8b5cf6; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h3 style="margin: 0 0 10px 0; font-size: 16px; color: #5b21b6;">
                                            🎯 Next Steps:
                                        </h3>
                                        <p style="margin: 0; font-size: 14px; color: #1f2937; line-height: 1.6;">
                                            {{ $report->next_steps }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            @endif

                            <p style="margin: 30px 0 20px 0; font-size: 16px; color: #1f2937; line-height: 1.6;">
                                The complete progress report is attached to this email and is also available on your parent portal. You can view it online, download it, or print it for your records.
                            </p>

                            <!-- CTA Buttons -->
                            <table role="presentation" style="margin: 30px 0;">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ route('parent.reports.show', [$student->id, $report->id]) }}" style="display: inline-block; background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%); color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3); margin: 0 10px 10px 0;">
                                            📄 View on Portal
                                        </a>
                                        <a href="{{ route('parent.reports.pdf', [$student->id, $report->id]) }}" style="display: inline-block; background-color: #6b7280; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-size: 16px; font-weight: bold; box-shadow: 0 4px 6px rgba(107, 114, 128, 0.3); margin: 0 10px 10px 0;">
                                            📥 Download PDF
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 0 0; font-size: 14px; color: #6b7280; line-height: 1.6;">
                                We appreciate your continued support in {{ $student->first_name }}'s coding education. If you have any questions about this report, please don't hesitate to reach out to us.
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
                                This email was sent to you as a parent of a KidzTech student.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
