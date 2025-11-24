<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Notice;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagerNoticeboardTest extends TestCase
{
    use RefreshDatabase;

    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a manager user
        $this->manager = User::factory()->create([
            'role' => 'manager',
            'email' => 'manager@example.com',
        ]);
    }

    /** @test */
    public function manager_can_view_notice_board()
    {
        $this->actingAs($this->manager);

        $response = $this->get(route('manager.notices.index'));

        $response->assertStatus(200);
        $response->assertViewIs('noticeboard.index');
    }

    /** @test */
    public function manager_can_create_notice()
    {
        $this->actingAs($this->manager);

        $noticeData = [
            'title' => 'Test Notice',
            'content' => 'This is a test notice content.',
            'priority' => 'normal',
            'visible_to' => ['manager', 'tutor'],
            'status' => 'published',
        ];

        $response = $this->post(route('manager.notices.store'), $noticeData);

        $response->assertRedirect(route('manager.notices.index'));
        $response->assertSessionHas('success');

        // Check notice was created
        $this->assertDatabaseHas('notice_board', [
            'title' => 'Test Notice',
            'content' => 'This is a test notice content.',
            'priority' => 'normal',
            'status' => 'published',
            'posted_by' => $this->manager->id,
        ]);
    }

    /** @test */
    public function manager_cannot_create_director_only_notice()
    {
        $this->actingAs($this->manager);

        $noticeData = [
            'title' => 'Director Only Notice',
            'content' => 'This should not be created.',
            'priority' => 'high',
            'visible_to' => ['director'],
            'status' => 'published',
        ];

        $response = $this->post(route('manager.notices.store'), $noticeData);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Check notice was NOT created
        $this->assertDatabaseMissing('notice_board', [
            'title' => 'Director Only Notice',
        ]);
    }

    /** @test */
    public function manager_can_edit_own_notice()
    {
        $this->actingAs($this->manager);

        $notice = Notice::factory()->create([
            'posted_by' => $this->manager->id,
            'title' => 'Original Title',
            'content' => 'Original content',
            'priority' => 'normal',
            'visible_to' => ['manager'],
            'status' => 'draft',
        ]);

        $updatedData = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
            'priority' => 'high',
            'visible_to' => ['manager', 'tutor'],
            'status' => 'published',
        ];

        $response = $this->put(route('manager.notices.update', $notice), $updatedData);

        $response->assertRedirect(route('manager.notices.index'));
        $response->assertSessionHas('success');

        // Check notice was updated
        $notice->refresh();
        $this->assertEquals('Updated Title', $notice->title);
        $this->assertEquals('Updated content', $notice->content);
        $this->assertEquals('high', $notice->priority);
        $this->assertEquals('published', $notice->status);
    }

    /** @test */
    public function manager_cannot_edit_others_notice()
    {
        $otherUser = User::factory()->create(['role' => 'admin']);

        $notice = Notice::factory()->create([
            'posted_by' => $otherUser->id,
            'title' => 'Other User Notice',
        ]);

        $this->actingAs($this->manager);

        $response = $this->put(route('manager.notices.update', $notice), [
            'title' => 'Hacked Title',
            'content' => 'Hacked content',
            'priority' => 'urgent',
            'visible_to' => ['manager'],
            'status' => 'published',
        ]);

        $response->assertRedirect(route('manager.notices.index'));
        $response->assertSessionHas('error');

        // Check notice was NOT updated
        $notice->refresh();
        $this->assertEquals('Other User Notice', $notice->title);
    }

    /** @test */
    public function manager_can_delete_own_notice()
    {
        $this->actingAs($this->manager);

        $notice = Notice::factory()->create([
            'posted_by' => $this->manager->id,
        ]);

        $response = $this->delete(route('manager.notices.destroy', $notice));

        $response->assertRedirect(route('manager.notices.index'));
        $response->assertSessionHas('success');

        // Check notice was deleted
        $this->assertDatabaseMissing('notice_board', [
            'id' => $notice->id,
        ]);
    }

    /** @test */
    public function manager_cannot_delete_others_notice()
    {
        $otherUser = User::factory()->create(['role' => 'admin']);

        $notice = Notice::factory()->create([
            'posted_by' => $otherUser->id,
        ]);

        $this->actingAs($this->manager);

        $response = $this->delete(route('manager.notices.destroy', $notice));

        $response->assertRedirect(route('manager.notices.index'));
        $response->assertSessionHas('error');

        // Check notice still exists
        $this->assertDatabaseHas('notice_board', [
            'id' => $notice->id,
        ]);
    }

    /** @test */
    public function non_manager_cannot_access_manager_notice_routes()
    {
        $regularUser = User::factory()->create(['role' => 'tutor']);
        $this->actingAs($regularUser);

        $response = $this->get(route('manager.notices.index'));
        $response->assertStatus(403);

        $response = $this->get(route('manager.notices.create'));
        $response->assertStatus(403);
    }

    /** @test */
    public function guests_cannot_access_notice_board()
    {
        $response = $this->get(route('manager.notices.index'));
        $response->assertRedirect(route('login'));
    }
}
