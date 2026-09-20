<?php

namespace Tests\Feature\Components;

use Tests\TestCase;

class OffcanvasTest extends TestCase
{
    public function test_it_renders_a_dialog_with_the_expected_defaults(): void
    {
        $this->blade('<x-offcanvas id="panel" title="Filters">Body</x-offcanvas>')
            ->assertSee('id="panel"', escape: false)
            ->assertSee('role="dialog"', escape: false)
            ->assertSee('aria-modal="true"', escape: false)
            // end is the default placement
            ->assertSee('offcanvas offcanvas-end', escape: false)
            ->assertSee('offcanvas-body', escape: false);
    }

    public function test_the_title_labels_the_dialog(): void
    {
        $this->blade('<x-offcanvas id="panel" title="Filters">Body</x-offcanvas>')
            ->assertSee('aria-labelledby="panel-title"', escape: false)
            ->assertSee('id="panel-title"', escape: false)
            ->assertSee('Filters');
    }

    public function test_a_titleless_panel_still_renders_a_close_button(): void
    {
        // Without a title, .offcanvas-header's space-between would push the
        // close button hard left, so it needs ms-auto to stay on the right.
        $this->blade('<x-offcanvas id="panel">Body</x-offcanvas>')
            ->assertSee('data-bs-dismiss="offcanvas"', escape: false)
            ->assertSee('btn-close ms-auto', escape: false)
            ->assertDontSee('aria-labelledby', escape: false);
    }

    public function test_placement_is_configurable(): void
    {
        foreach (['start', 'top', 'bottom'] as $position) {
            $this->blade('<x-offcanvas id="panel" :position="$p">Body</x-offcanvas>', ['p' => $position])
                ->assertSee("offcanvas offcanvas-{$position}", escape: false);
        }
    }

    public function test_responsive_and_narrow_modifiers(): void
    {
        $this->blade('<x-offcanvas id="panel" responsive="lg" narrow>Body</x-offcanvas>')
            ->assertSee('offcanvas-lg', escape: false)
            ->assertSee('offcanvas-narrow', escape: false);
    }

    public function test_static_blocks_the_backdrop_dismissal(): void
    {
        $this->blade('<x-offcanvas id="panel" static>Body</x-offcanvas>')
            ->assertSee('data-bs-backdrop="static"', escape: false)
            ->assertSee('data-bs-keyboard="false"', escape: false);
    }

    public function test_the_footer_slot_is_optional_and_rendered_outside_the_body(): void
    {
        $without = $this->blade('<x-offcanvas id="panel">Body</x-offcanvas>');
        $without->assertDontSee('offcanvas-footer', escape: false);

        $with = $this->blade(
            '<x-offcanvas id="panel"><x-slot:footer>Actions</x-slot:footer></x-offcanvas>'
        );
        $with->assertSee('offcanvas-footer', escape: false);
        $with->assertSee('Actions');
    }

    public function test_arbitrary_attributes_land_on_the_root(): void
    {
        // This is what lets a caller attach Bootstrap's trigger attributes, or
        // target it from elsewhere on the page.
        $this->blade('<x-offcanvas id="panel" class="my-panel" data-foo="bar">Body</x-offcanvas>')
            ->assertSee('my-panel', escape: false)
            ->assertSee('data-foo="bar"', escape: false);
    }
}
