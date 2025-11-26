<?php

namespace Tests\Feature\ParentStudent;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\ParentNotification;
use Illuminate\Support\Facades\Hash;

class ParentNotificationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating notifications for a parent
     */
    public function test_can_create_notifications_for_parent(): void
    {
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $notification = ParentNotification::create([
            'parent_id' => $parent->id,
            'type' => 'report_ready',
            'data' => [
                'title' => 'New Report Available',
                'message' => 'A new progress report is ready',
                'student_id' => 1,
            ],
        ]);

        $this->assertDatabaseHas('parent_notifications', [
            'parent_id' => $parent->id,
            'type' => 'report_ready',
        ]);
    }

    /**
     * Test fetching unread notification count
     */
    public function test_can_fetch_unread_notification_count(): void
    {
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        // Create 3 unread notifications
        for ($i = 1; $i <= 3; $i++) {
            ParentNotification::create([
                'parent_id' => $parent->id,
                'type' => 'test_notification',
                'data' => ['message' => "Notification {$i}"],
            ]);
        }

        // Create 2 read notifications
        for ($i = 1; $i <= 2; $i++) {
            ParentNotification::create([
                'parent_id' => $parent->id,
                'type' => 'test_notification',
                'data' => ['message' => "Read Notification {$i}"],
                'read_at' => now(),
            ]);
        }

        $unreadCount = ParentNotification::where('parent_id', $parent->id)
            ->whereNull('read_at')
            ->count();

        $this->assertEquals(3, $unreadCount);
    }

    /**
     * Test marking notification as read
     */
    public function test_can_mark_notification_as_read(): void
    {
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $notification = ParentNotification::create([
            'parent_id' => $parent->id,
            'type' => 'milestone_completed',
            'data' => [
                'title' => 'Milestone Achievement',
                'message' => 'Student completed a milestone',
            ],
        ]);

        $this->assertNull($notification->read_at);
        $this->assertTrue($notification->isUnread());

        // Mark as read
        $notification->markAsRead();

        $this->assertNotNull($notification->fresh()->read_at);
        $this->assertFalse($notification->fresh()->isUnread());
    }

    /**
     * Test notification data casting
     */
    public function test_notification_data_is_properly_cast(): void
    {
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $data = [
            'title' => 'Test Notification',
            'message' => 'This is a test',
            'student_id' => 123,
            'extra_data' => ['key' => 'value'],
        ];

        $notification = ParentNotification::create([
            'parent_id' => $parent->id,
            'type' => 'test',
            'data' => $data,
        ]);

        $this->assertIsArray($notification->data);
        $this->assertEquals($data, $notification->data);
        $this->assertEquals('Test Notification', $notification->data['title']);
    }

    /**
     * Test querying notifications by type
     */
    public function test_can_query_notifications_by_type(): void
    {
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        // Create different types of notifications
        ParentNotification::create([
            'parent_id' => $parent->id,
            'type' => 'report_ready',
            'data' => ['message' => 'Report notification'],
        ]);

        ParentNotification::create([
            'parent_id' => $parent->id,
            'type' => 'attendance_alert',
            'data' => ['message' => 'Attendance notification'],
        ]);

        ParentNotification::create([
            'parent_id' => $parent->id,
            'type' => 'attendance_alert',
            'data' => ['message' => 'Another attendance notification'],
        ]);

        $reportNotifications = ParentNotification::where('parent_id', $parent->id)
            ->where('type', 'report_ready')
            ->count();

        $attendanceNotifications = ParentNotification::where('parent_id', $parent->id)
            ->where('type', 'attendance_alert')
            ->count();

        $this->assertEquals(1, $reportNotifications);
        $this->assertEquals(2, $attendanceNotifications);
    }

    /**
     * Test parent relationship
     */
    public function test_notification_parent_relationship(): void
    {
        $parent = User::create([
            'name' => 'Test Parent',
            'email' => 'parent@test.com',
            'password' => Hash::make('password'),
            'status' => 'active',
        ]);

        $notification = ParentNotification::create([
            'parent_id' => $parent->id,
            'type' => 'test',
            'data' => ['message' => 'Test'],
        ]);

        $this->assertInstanceOf(User::class, $notification->parent);
        $this->assertEquals($parent->id, $notification->parent->id);
        $this->assertEquals('Test Parent', $notification->parent->name);
    }
}
