<?php

namespace Tests\Feature\Components;

use Tests\TestCase;

/**
 * Locks the public API of the display components — the props each one accepts
 * and the markup they guarantee. These render Blade directly (no HTTP), so a
 * failure points at the component rather than at a route.
 */
class DisplayComponentsTest extends TestCase
{
    // ---------------------------------------------------------------- alert

    public function test_alert_defaults_to_a_status_role(): void
    {
        $this->blade('<x-alert type="success">Saved</x-alert>')
            ->assertSee('role="status"', escape: false);
    }

    public function test_alert_uses_the_alert_role_for_warning_and_danger(): void
    {
        $this->blade('<x-alert type="warning">Careful</x-alert>')
            ->assertSee('role="alert"', escape: false);

        $this->blade('<x-alert type="danger">Broke</x-alert>')
            ->assertSee('role="alert"', escape: false);
    }

    public function test_alert_role_can_be_overridden(): void
    {
        $this->blade('<x-alert type="success" role="alert">Saved</x-alert>')
            ->assertSee('role="alert"', escape: false);
    }

    public function test_alert_picks_an_icon_from_its_type(): void
    {
        // 'check' is the icon mapped to type="success".
        $this->blade('<x-alert type="success">Saved</x-alert>')
            ->assertSee('alert-icon', escape: false)
            ->assertSee('M5 12l5 5l10 -10', escape: false);
    }

    public function test_alert_icon_can_be_overridden_or_disabled(): void
    {
        $this->blade('<x-alert type="success" icon="star">Saved</x-alert>')
            ->assertSee('M12 17.75l', escape: false);

        $this->blade('<x-alert type="success" :icon="false">Saved</x-alert>')
            ->assertDontSee('alert-icon', escape: false);
    }

    public function test_alert_variants_add_their_modifier_class(): void
    {
        $this->blade('<x-alert type="warning" variant="important">Careful</x-alert>')
            ->assertSee('alert-important', escape: false);

        $this->blade('<x-alert type="warning" variant="minor">Careful</x-alert>')
            ->assertSee('alert-minor', escape: false);
    }

    public function test_alert_with_a_title_wraps_the_body_in_a_description(): void
    {
        $this->blade('<x-alert type="success" title="Nice">Body copy</x-alert>')
            ->assertSee('alert-heading', escape: false)
            ->assertSee('alert-description', escape: false);
    }

    public function test_alert_dismiss_button_inverts_on_an_important_alert(): void
    {
        $this->blade('<x-alert type="info" dismissible>Hello</x-alert>')
            ->assertSee('btn-close', escape: false)
            ->assertDontSee('btn-close-white', escape: false);

        $this->blade('<x-alert type="warning" variant="important" dismissible>Careful</x-alert>')
            ->assertSee('btn-close btn-close-white', escape: false);
    }

    // ---------------------------------------------------------------- badge

    public function test_badge_defaults_to_a_plain_badge(): void
    {
        $this->blade('<x-badge>New</x-badge>')
            ->assertSee('<span', escape: false)
            ->assertSee('class="badge"', escape: false)
            ->assertSee('New');
    }

    public function test_badge_color_variants(): void
    {
        $this->blade('<x-badge color="purple">Solid</x-badge>')
            ->assertSee('bg-purple text-purple-fg', escape: false);

        $this->blade('<x-badge color="blue" variant="light">Light</x-badge>')
            ->assertSee('bg-blue-lt', escape: false);

        $this->blade('<x-badge color="blue" variant="outline">Outline</x-badge>')
            ->assertSee('badge-outline text-blue', escape: false);
    }

    public function test_badge_pill_and_size_modifiers(): void
    {
        $this->blade('<x-badge pill>Pill</x-badge>')
            ->assertSee('badge-pill', escape: false);

        $this->blade('<x-badge size="sm">Small</x-badge>')
            ->assertSee('badge-sm', escape: false);
    }

    public function test_a_dot_badge_carries_its_colour_on_the_dot_and_labels_itself(): void
    {
        $this->blade('<x-badge color="green" dot label="Online" />')
            ->assertSee('badge-dot bg-green', escape: false)
            ->assertSee('visually-hidden', escape: false)
            ->assertSee('Online');
    }

    public function test_badge_renders_as_a_link_when_given_an_href(): void
    {
        $this->blade('<x-badge href="/users">Users</x-badge>')
            ->assertSee('<a', escape: false)
            ->assertSee('href="/users"', escape: false);
    }

    public function test_an_icon_badge_with_no_slot_is_flagged_icon_only(): void
    {
        $this->blade('<x-badge icon="check" label="Done" />')
            ->assertSee('badge-icononly', escape: false);
    }

    // --------------------------------------------------------------- avatar

    public function test_avatar_renders_an_image_background(): void
    {
        $this->blade('<x-avatar src="/a.jpg" label="Jane Doe" />')
            ->assertSee('background-image: url(/a.jpg)', escape: false)
            ->assertSee('aria-label="Jane Doe"', escape: false);
    }

    public function test_avatar_falls_back_to_initials(): void
    {
        $this->blade('<x-avatar initials="AB" />')
            ->assertSee('AB');
    }

    public function test_avatar_modifiers(): void
    {
        $this->blade('<x-avatar initials="AB" size="lg" shape="square" color="blue" />')
            ->assertSee('avatar-lg', escape: false)
            ->assertSee('avatar-square', escape: false)
            ->assertSee('bg-blue-lt', escape: false);
    }

    public function test_avatar_status_maps_to_a_badge_colour(): void
    {
        $this->blade('<x-avatar initials="AB" status="online" />')
            ->assertSee('badge bg-success', escape: false);
    }

    // --------------------------------------------------------------- status

    public function test_status_with_a_label_wraps_the_dot(): void
    {
        $this->blade('<x-status color="green">Active</x-status>')
            ->assertSee('class="status status-green', escape: false)
            ->assertSee('Active');
    }

    public function test_a_status_with_no_label_is_a_standalone_dot(): void
    {
        // Colour has to move onto the dot itself, since there's no wrapper.
        $this->blade('<x-status color="green" />')
            ->assertSee('status-dot status-green', escape: false);
    }

    public function test_an_indicator_renders_three_circles(): void
    {
        $this->blade('<x-status color="azure" indicator animated />')
            ->assertSee('status-indicator status-azure status-indicator-animated', escape: false);
    }

    // ------------------------------------------------------------- progress

    public function test_progress_sets_width_and_aria_values(): void
    {
        $this->blade('<x-progress value="38" label="38% done" />')
            ->assertSee('width: 38%', escape: false)
            ->assertSee('aria-valuenow="38"', escape: false)
            ->assertSee('aria-valuemax="100"', escape: false);
    }

    public function test_progress_modifiers(): void
    {
        $this->blade('<x-progress value="60" color="primary" striped animated size="sm" />')
            ->assertSee('progress-sm', escape: false)
            ->assertSee('bg-primary', escape: false)
            ->assertSee('progress-bar-striped', escape: false)
            ->assertSee('progress-bar-animated', escape: false);
    }

    public function test_an_indeterminate_progress_bar_has_no_width(): void
    {
        $this->blade('<x-progress indeterminate />')
            ->assertSee('progress-bar-indeterminate', escape: false)
            ->assertDontSee('width:', escape: false);
    }

    // ---------------------------------------------------------------- empty

    public function test_empty_renders_its_title_subtitle_and_icon(): void
    {
        $this->blade('<x-empty icon="search" title="Nothing here" subtitle="Try again" />')
            ->assertSee('empty-icon', escape: false)
            ->assertSee('empty-title', escape: false)
            ->assertSee('Nothing here')
            ->assertSee('empty-subtitle', escape: false)
            ->assertSee('Try again');
    }
}
