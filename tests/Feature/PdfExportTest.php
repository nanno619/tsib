<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/starter-kit/users.pdf')->assertRedirect('/login');
    }

    public function test_the_users_table_can_be_exported_as_a_pdf(): void
    {
        User::factory()->count(3)->create();
        $viewer = User::factory()->create();

        $response = $this->actingAs($viewer)->get('/starter-kit/users.pdf');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');

        // A real PDF, not an error page with the right content type.
        $content = $response->getContent();
        $this->assertIsString($content);
        $this->assertStringStartsWith('%PDF-', $content);
    }
}
