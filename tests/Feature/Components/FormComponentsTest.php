<?php

namespace Tests\Feature\Components;

use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class FormComponentsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Components read validation state from the shared $errors bag, which
        // normally arrives via middleware — share one so they render standalone.
        view()->share('errors', new ViewErrorBag);
    }

    /**
     * @param  array<string, list<string>>  $messages
     */
    private function withErrors(array $messages, string $bag = 'default'): void
    {
        $errors = new ViewErrorBag;
        $errors->put($bag, new MessageBag($messages));

        view()->share('errors', $errors);
    }

    // ---------------------------------------------------------------- input

    public function test_input_wires_its_label_to_the_control(): void
    {
        $this->blade('<x-input name="email" label="Email address" />')
            ->assertSee('for="email"', escape: false)
            ->assertSee('id="email"', escape: false)
            ->assertSee('name="email"', escape: false);
    }

    public function test_a_required_input_marks_both_the_label_and_the_control(): void
    {
        $this->blade('<x-input name="email" label="Email" required />')
            ->assertSee('form-label required', escape: false)
            ->assertSee('required', escape: false);
    }

    public function test_a_password_input_never_repopulates_its_value(): void
    {
        $this->blade('<x-input type="password" name="password" value="secret" />')
            ->assertSee('value=""', escape: false)
            ->assertDontSee('secret', escape: false);
    }

    public function test_input_renders_a_textarea_when_asked(): void
    {
        $this->blade('<x-input type="textarea" name="bio">Hello</x-input>')
            ->assertSee('<textarea', escape: false)
            ->assertDontSee('<input', escape: false);
    }

    public function test_an_icon_input_wraps_the_control_in_an_addon(): void
    {
        $this->blade('<x-input name="q" icon="search" />')
            ->assertSee('input-icon', escape: false)
            ->assertSee('input-icon-addon', escape: false);
    }

    public function test_extra_attributes_land_on_the_control_not_the_wrapper(): void
    {
        $this->blade('<x-input name="email" autocomplete="email" />')
            ->assertSee('autocomplete="email"', escape: false);
    }

    public function test_a_validation_error_marks_the_control_and_shows_the_message(): void
    {
        $this->withErrors(['email' => ['The email field is required.']]);

        $this->blade('<x-input name="email" label="Email" />')
            ->assertSee('is-invalid', escape: false)
            ->assertSee('invalid-feedback', escape: false)
            ->assertSee('The email field is required.');
    }

    public function test_help_text_shows_only_when_there_is_no_error(): void
    {
        $this->blade('<x-input name="email" help="We never share it." />')
            ->assertSee('form-hint', escape: false)
            ->assertSee('We never share it.');

        $this->withErrors(['email' => ['Required.']]);

        // Error wins — showing both would be contradictory.
        $this->blade('<x-input name="email" help="We never share it." />')
            ->assertDontSee('form-hint', escape: false)
            ->assertSee('invalid-feedback', escape: false);
    }

    public function test_an_input_can_read_from_a_named_error_bag(): void
    {
        // Fortify reports profile and password failures in their own bags.
        $this->withErrors(['name' => ['Name is required.']], bag: 'updateProfileInformation');

        $this->blade('<x-input name="name" error-bag="updateProfileInformation" />')
            ->assertSee('is-invalid', escape: false)
            ->assertSee('Name is required.');

        // The same field is clean in the default bag.
        $this->blade('<x-input name="name" />')
            ->assertDontSee('is-invalid', escape: false);
    }

    // --------------------------------------------------------------- select

    public function test_select_renders_its_options(): void
    {
        $this->blade('<x-select name="role" :options="[\'admin\' => \'Admin\', \'editor\' => \'Editor\']" />')
            ->assertSee('value="admin"', escape: false)
            ->assertSee('Admin')
            ->assertSee('value="editor"', escape: false)
            ->assertSee('Editor');
    }

    public function test_select_marks_the_current_value_as_selected(): void
    {
        $this->blade('<x-select name="role" value="editor" :options="[\'admin\' => \'Admin\', \'editor\' => \'Editor\']" />')
            ->assertSee('value="editor"', escape: false)
            ->assertSee('selected', escape: false);
    }

    public function test_a_multiple_select_submits_as_an_array(): void
    {
        $this->blade('<x-select name="tags" multiple :options="[\'php\' => \'PHP\']" />')
            ->assertSee('name="tags[]"', escape: false)
            ->assertSee('multiple', escape: false);
    }

    public function test_a_plain_select_offers_a_placeholder_option(): void
    {
        $this->blade('<x-select name="role" placeholder="Choose one" :options="[\'admin\' => \'Admin\']" />')
            ->assertSee('Choose one')
            ->assertDontSee('data-advanced-select', escape: false);
    }

    public function test_an_advanced_select_uses_ghost_placeholder_text_instead(): void
    {
        // Tom Select renders its own placeholder, so an extra <option> row
        // would be redundant.
        $this->blade('<x-select name="role" advanced placeholder="Choose one" :options="[\'admin\' => \'Admin\']" />')
            ->assertSee('data-advanced-select', escape: false)
            ->assertSee('data-placeholder="Choose one"', escape: false)
            ->assertDontSee('<option value=""', escape: false);
    }

    public function test_rich_option_markup_is_emitted_only_for_advanced_selects(): void
    {
        $options = "['1' => ['label' => 'Jane', 'html' => '<span>Jane</span>']]";

        $this->blade('<x-select name="assignee" advanced :options="'.$options.'" />')
            ->assertSee('data-custom-properties', escape: false);

        $this->blade('<x-select name="assignee" :options="'.$options.'" />')
            ->assertSee('Jane')
            ->assertDontSee('data-custom-properties', escape: false);
    }

    // ------------------------------------------------------------- checkbox

    public function test_checkbox_renders_a_labelled_input(): void
    {
        $this->blade('<x-checkbox name="remember">Remember me</x-checkbox>')
            ->assertSee('form-check-input', escape: false)
            ->assertSee('form-check-label', escape: false)
            ->assertSee('Remember me');
    }

    public function test_a_switch_adds_the_switch_class(): void
    {
        $this->blade('<x-checkbox name="dark" switch>Dark mode</x-checkbox>')
            ->assertSee('form-check form-switch', escape: false);
    }

    public function test_checkbox_checked_disabled_and_value(): void
    {
        $this->blade('<x-checkbox name="terms" value="yes" checked disabled>Agree</x-checkbox>')
            ->assertSee('checked', escape: false)
            ->assertSee('disabled', escape: false)
            ->assertSee('value="yes"', escape: false);
    }

    // ---------------------------------------------------------------- radio

    public function test_radio_renders_a_labelled_input(): void
    {
        $this->blade('<x-radio name="status" value="online">Online</x-radio>')
            ->assertSee('type="radio"', escape: false)
            ->assertSee('form-check-input', escape: false)
            ->assertSee('form-check-label', escape: false)
            ->assertSee('Online');
    }

    public function test_radio_checked_disabled_and_value(): void
    {
        $this->blade('<x-radio name="status" value="offline" checked disabled>Offline</x-radio>')
            ->assertSee('checked', escape: false)
            ->assertSee('disabled', escape: false)
            ->assertSee('value="offline"', escape: false);
    }

    public function test_a_radio_group_shares_one_name_with_different_values(): void
    {
        $html = $this->blade(
            '<x-radio name="status" value="online" checked>Online</x-radio>'
            .'<x-radio name="status" value="under_maintenance">Under Maintenance</x-radio>',
        );

        $html->assertSeeInOrder([
            'name="status"', 'value="online"', 'checked',
            'name="status"', 'value="under_maintenance"',
        ], escape: false);
    }

    // --------------------------------------------------------------- button

    public function test_a_button_defaults_to_type_button(): void
    {
        $this->blade('<x-button>Save</x-button>')
            ->assertSee('<button', escape: false)
            ->assertSee('type="button"', escape: false);
    }

    public function test_button_colour_variants(): void
    {
        $this->blade('<x-button color="primary">A</x-button>')
            ->assertSee('btn btn-primary', escape: false);

        $this->blade('<x-button color="primary" variant="outline">B</x-button>')
            ->assertSee('btn-outline-primary', escape: false);

        $this->blade('<x-button color="primary" variant="ghost">C</x-button>')
            ->assertSee('btn-ghost-primary', escape: false);
    }

    public function test_a_button_with_an_href_becomes_a_link(): void
    {
        $this->blade('<x-button href="/export">Export</x-button>')
            ->assertSee('<a', escape: false)
            ->assertSee('href="/export"', escape: false)
            ->assertSee('role="button"', escape: false);
    }

    public function test_a_disabled_link_is_marked_not_just_styled(): void
    {
        // <a> has no disabled attribute, so it needs aria + tabindex instead.
        $this->blade('<x-button href="/export" disabled>Export</x-button>')
            ->assertSee('aria-disabled="true"', escape: false)
            ->assertSee('tabindex="-1"', escape: false);
    }

    public function test_a_disabled_button_uses_the_real_attribute(): void
    {
        $this->blade('<x-button disabled>Save</x-button>')
            ->assertSee('disabled', escape: false)
            ->assertDontSee('aria-disabled', escape: false);
    }

    public function test_a_loading_button_is_busy_and_disabled(): void
    {
        $this->blade('<x-button loading>Save</x-button>')
            ->assertSee('aria-busy="true"', escape: false)
            ->assertSee('btn-loading', escape: false)
            ->assertSee('disabled', escape: false);
    }

    public function test_an_icon_only_button_gets_a_class_and_an_accessible_name(): void
    {
        $this->blade('<x-button icon="plus" label="Add user" />')
            ->assertSee('btn-icon', escape: false)
            ->assertSee('aria-label="Add user"', escape: false);
    }

    public function test_an_icon_button_with_a_label_keeps_the_text(): void
    {
        $this->blade('<x-button icon="plus">Add user</x-button>')
            ->assertDontSee('btn-icon', escape: false)
            ->assertSee('Add user');
    }

    public function test_the_icon_can_be_positioned_at_the_end(): void
    {
        $this->blade('<x-button icon="chevron-right" icon-position="end">Next</x-button>')
            ->assertSee('icon-end', escape: false);
    }

    public function test_a_block_button_spans_the_width(): void
    {
        $this->blade('<x-button block>Sign in</x-button>')
            ->assertSee('w-100', escape: false);
    }
}
