<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Report;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Summary Stats
        $totalStudents = Student::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $totalReports = Report::count();
        
        // Calculate average attendance
        $totalAttendanceRecords = AttendanceRecord::count();
        $presentRecords = AttendanceRecord::where('status', 'present')->count();
        $avgAttendance = $totalAttendanceRecords > 0 
            ? round(($presentRecords / $totalAttendanceRecords) * 100, 1) 
            : 0;

        // Revenue by Month (Last 6 months) - FIXED
        $revenueByMonth = Payment::where('status', 'completed')
            ->where('payment_date', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('MONTH(payment_date) as month, YEAR(payment_date) as year, SUM(amount) as total')
            ->groupByRaw('YEAR(payment_date), MONTH(payment_date)')
            ->orderByRaw('YEAR(payment_date) ASC, MONTH(payment_date) ASC')
            ->get();

        $revenueMonths = [];
        $revenueData = [];
        
        if ($revenueByMonth->isEmpty()) {
            // Default data if no payments exist
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $revenueMonths[] = $date->format('M Y');
                $revenueData[] = 0;
            }
        } else {
            foreach ($revenueByMonth as $record) {
                $revenueMonths[] = Carbon::create($record->year, $record->month)->format('M Y');
                $revenueData[] = $record->total;
            }
        }

        // Attendance by Month (Last 6 months) - FIXED
        $attendanceByMonth = AttendanceRecord::where('attendance_date', '>=', Carbon::now()->subMonths(6))
            ->selectRaw('MONTH(attendance_date) as month, YEAR(attendance_date) as year, 
                         COUNT(*) as total,
                         SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present')
            ->groupByRaw('YEAR(attendance_date), MONTH(attendance_date)')
            ->orderByRaw('YEAR(attendance_date) ASC, MONTH(attendance_date) ASC')
            ->get();

        $attendanceMonths = [];
        $attendanceData = [];
        
        if ($attendanceByMonth->isEmpty()) {
            // Default data if no attendance exists
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $attendanceMonths[] = $date->format('M Y');
                $attendanceData[] = 0;
            }
        } else {
            foreach ($attendanceByMonth as $record) {
                $attendanceMonths[] = Carbon::create($record->year, $record->month)->format('M Y');
                $rate = $record->total > 0 ? round(($record->present / $record->total) * 100, 1) : 0;
                $attendanceData[] = $rate;
            }
        }

        // Payment Methods Distribution
        $paymentMethodsStats = Payment::where('status', 'completed')
            ->selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        if ($paymentMethodsStats->isEmpty()) {
            $paymentMethods = ['Cash', 'Bank Transfer', 'Card'];
            $paymentMethodsData = [0, 0, 0];
        } else {
            $paymentMethods = $paymentMethodsStats->pluck('payment_method')->toArray();
            $paymentMethodsData = $paymentMethodsStats->pluck('count')->toArray();
        }

        // Reports Status Distribution
        $reportsStats = Report::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        if ($reportsStats->isEmpty()) {
            $reportsStatusLabels = ['Draft', 'Submitted', 'Approved'];
            $reportsStatusData = [0, 0, 0];
        } else {
            $reportsStatusLabels = $reportsStats->pluck('status')->map(function($status) {
                return ucfirst($status);
            })->toArray();
            $reportsStatusData = $reportsStats->pluck('count')->toArray();
        }

        // Students by Location
        $locationStats = Student::whereNotNull('location')
            ->where('location', '!=', '')
            ->selectRaw('location, COUNT(*) as count')
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->get();

        if ($locationStats->isEmpty()) {
            $locations = ['Lekki', 'Victoria Island', 'Ikeja'];
            $locationsData = [0, 0, 0];
        } else {
            $locations = $locationStats->pluck('location')->toArray();
            $locationsData = $locationStats->pluck('count')->toArray();
        }

        return view('dashboards.analytics', compact(
            'totalStudents',
            'totalRevenue',
            'totalReports',
            'avgAttendance',
            'revenueMonths',
            'revenueData',
            'attendanceMonths',
            'attendanceData',
            'paymentMethods',
            'paymentMethodsData',
            'reportsStatusLabels',
            'reportsStatusData',
            'locations',
            'locationsData'
        ));
    }
}

