<?php

namespace Tests\Feature\Settings;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AvatarUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Avatar files land on the 'public' disk (config/media-library.php);
        // fake it so tests never touch real storage.
        Storage::fake('public');
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->put('/settings/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ])->assertRedirect('/login');
    }

    public function test_a_user_can_upload_an_avatar(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/settings/profile')
            ->put('/settings/avatar', [
                'avatar' => UploadedFile::fake()->image('avatar.jpg', 200, 200),
            ]);

        $response->assertRedirect('/settings/profile');
        $response->assertSessionHas('status', 'avatar-updated');

        $this->assertSame(1, $user->getMedia('avatar')->count());

        $media = $user->getFirstMedia('avatar');
        $this->assertNotNull($media);
        $this->assertSame('avatar.jpg', $media->file_name);
    }

    public function test_the_uploaded_avatar_is_rendered_on_the_profile_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/settings/avatar', [
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response = $this->actingAs($user->refresh())->get('/settings/profile');

        $response->assertOk();
        $response->assertSee($user->getFirstMediaUrl('avatar'), escape: false);
    }

    public function test_the_file_input_carries_an_accessible_name(): void
    {
        // It deliberately has no visible <label> — the card-title above already
        // reads "Avatar", so a second one directly over the input would read as
        // two labels for one field. That leaves aria-label as the only thing
        // naming the control, and aria-describedby as the only thing tying the
        // format and size limits to it.
        $user = User::factory()->create();

        $html = $this->actingAs($user)->get('/settings/profile')->getContent();
        $this->assertIsString($html);

        $matched = preg_match('/<input[^>]*type="file"[^>]*>/', $html, $matches);

        if ($matched !== 1) {
            $this->fail('No file input rendered on the profile page.');
        }

        $tag = $matches[0];

        $this->assertStringContainsString('name="avatar"', $tag);
        $this->assertStringContainsString('aria-label="Avatar image"', $tag);
        $this->assertStringContainsString('aria-describedby="avatar-hint"', $tag);

        // The hint it points at has to be on the page, or the reference dangles.
        $this->assertStringContainsString('id="avatar-hint"', $html);
    }

    public function test_a_non_image_file_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/settings/avatar', [
            'avatar' => UploadedFile::fake()->create('document.pdf', 100),
        ]);

        $response->assertSessionHasErrors('avatar', errorBag: 'avatar');
        $this->assertSame(0, $user->getMedia('avatar')->count());
    }

    public function test_an_oversized_image_is_rejected(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/settings/avatar', [
            'avatar' => UploadedFile::fake()->image('huge.jpg')->size(2049),
        ]);

        $response->assertSessionHasErrors('avatar', errorBag: 'avatar');
        $this->assertSame(0, $user->getMedia('avatar')->count());
    }

    public function test_the_avatar_is_required(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put('/settings/avatar', [])
            ->assertSessionHasErrors('avatar', errorBag: 'avatar');
    }

    public function test_uploading_again_replaces_the_previous_avatar(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put('/settings/avatar', [
            'avatar' => UploadedFile::fake()->image('first.jpg'),
        ]);

        $this->actingAs($user)->put('/settings/avatar', [
            'avatar' => UploadedFile::fake()->image('second.jpg'),
        ]);

        // The collection is registered with singleFile(), so the new upload
        // replaces rather than accumulating.
        $this->assertSame(1, $user->refresh()->getMedia('avatar')->count());

        $media = $user->getFirstMedia('avatar');
        $this->assertNotNull($media);
        $this->assertSame('second.jpg', $media->file_name);
    }
}
