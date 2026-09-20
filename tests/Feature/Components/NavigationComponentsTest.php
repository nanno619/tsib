<?php

namespace Tests\Feature\Components;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Tests\TestCase;

class NavigationComponentsTest extends TestCase
{
    /**
     * @return LengthAwarePaginator<int, int>
     */
    private function paginator(int $total, int $perPage, int $current, int $items = 5): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            items: range(1, $items),
            total: $total,
            perPage: $perPage,
            currentPage: $current,
            options: ['path' => '/users'],
        );
    }

    // ----------------------------------------------------------- breadcrumb

    public function test_the_last_breadcrumb_is_never_a_link(): void
    {
        $this->blade('<x-breadcrumb :items="[
            [\'label\' => \'Home\', \'url\' => \'/home\'],
            [\'label\' => \'Current\', \'url\' => \'/current\'],
        ]" />')
            ->assertSee('aria-current="page"', escape: false)
            ->assertDontSee('href="/current"', escape: false);
    }

    public function test_a_middle_item_with_a_url_is_a_link(): void
    {
        $this->blade('<x-breadcrumb :items="[
            [\'label\' => \'Home\', \'url\' => \'/home\'],
            [\'label\' => \'Last\'],
        ]" />')
            ->assertSee('href="/home"', escape: false);
    }

    public function test_a_middle_item_without_a_url_is_plain_text(): void
    {
        // e.g. a "Settings" section that is only a dropdown, not a page.
        $this->blade('<x-breadcrumb :items="[
            [\'label\' => \'Settings\'],
            [\'label\' => \'Profile\'],
        ]" />')
            ->assertSee('Settings')
            ->assertDontSee('href', escape: false);
    }

    public function test_breadcrumb_variants(): void
    {
        $this->blade('<x-breadcrumb variant="arrows" muted :items="[[\'label\' => \'A\']]" />')
            ->assertSee('breadcrumb-arrows breadcrumb-muted', escape: false);
    }

    // ---------------------------------------------------- segmented control

    public function test_a_tab_list_marks_the_selected_segment(): void
    {
        $this->blade('<x-segmented-control selected="week" :items="[
            [\'label\' => \'Day\', \'value\' => \'day\', \'target\' => \'#pane-day\'],
            [\'label\' => \'Week\', \'value\' => \'week\', \'target\' => \'#pane-week\'],
        ]" />')
            ->assertSee('role="tablist"', escape: false)
            ->assertSee('aria-selected="true"', escape: false)
            ->assertSee('aria-current="page"', escape: false)
            // Unselected tabs are removed from the tab order.
            ->assertSee('tabindex="-1"', escape: false);
    }

    public function test_a_navigation_segment_gets_no_tab_semantics(): void
    {
        // Regression: Bootstrap's tab data-API calls preventDefault() on <a>,
        // so data-bs-toggle="tab" on a navigation link stopped it navigating —
        // and the roving tabindex="-1" left the inactive links unreachable by
        // keyboard.
        $this->blade('<x-segmented-control selected="week" :items="[
            [\'label\' => \'Day\', \'value\' => \'day\', \'href\' => \'/day\'],
            [\'label\' => \'Week\', \'value\' => \'week\', \'href\' => \'/week\'],
        ]" />')
            ->assertSee('href="/week"', escape: false)
            ->assertSee('aria-current="page"', escape: false)
            ->assertDontSee('data-bs-toggle="tab"', escape: false)
            ->assertDontSee('role="tablist"', escape: false)
            ->assertDontSee('tabindex="-1"', escape: false);
    }

    public function test_a_disabled_segment_downgrades_from_a_link_to_a_button(): void
    {
        $this->blade('<x-segmented-control :items="[
            [\'label\' => \'Year\', \'value\' => \'year\', \'href\' => \'/year\', \'disabled\' => true],
        ]" />')
            ->assertDontSee('href="/year"', escape: false)
            ->assertSee('type="button"', escape: false)
            ->assertSee('disabled', escape: false);
    }

    public function test_a_segment_with_an_href_renders_a_link(): void
    {
        $this->blade('<x-segmented-control :items="[
            [\'label\' => \'Home\', \'value\' => \'home\', \'href\' => \'/home\'],
        ]" />')
            ->assertSee('href="/home"', escape: false);
    }

    public function test_a_segment_can_target_a_tab_pane(): void
    {
        $this->blade('<x-segmented-control :items="[
            [\'label\' => \'Home\', \'value\' => \'home\', \'target\' => \'#tab-home\'],
        ]" />')
            ->assertSee('data-bs-target="#tab-home"', escape: false);
    }

    // ----------------------------------------------------------- pagination

    public function test_pagination_window_includes_gaps_on_both_sides(): void
    {
        // 50 items / 5 per page = 10 pages, sitting on page 5 with one either side.
        $html = $this->blade('<x-pagination :paginator="$p" />', [
            'p' => $this->paginator(total: 50, perPage: 5, current: 5),
        ]);

        $html->assertSee('>1</a>', escape: false)
            ->assertSee('>4</a>', escape: false)
            ->assertSee('>5</a>', escape: false)
            ->assertSee('>6</a>', escape: false)
            ->assertSee('>10</a>', escape: false)
            ->assertSee('&hellip;', escape: false);
    }

    public function test_a_short_range_has_no_gaps(): void
    {
        // 3 pages, on page 1 — nothing to elide.
        $html = $this->blade('<x-pagination :paginator="$p" />', [
            'p' => $this->paginator(total: 15, perPage: 5, current: 1),
        ]);

        $html->assertSee('>1</a>', escape: false)
            ->assertSee('>2</a>', escape: false)
            ->assertSee('>3</a>', escape: false)
            ->assertDontSee('&hellip;', escape: false);
    }

    public function test_previous_is_disabled_on_the_first_page(): void
    {
        $html = $this->blade('<x-pagination :paginator="$p" />', [
            'p' => $this->paginator(total: 15, perPage: 5, current: 1),
        ]);

        $html->assertSee('page-item disabled', escape: false)
            ->assertDontSee('rel="prev"', escape: false)
            ->assertSee('rel="next"', escape: false);
    }

    public function test_next_is_disabled_on_the_last_page(): void
    {
        $html = $this->blade('<x-pagination :paginator="$p" />', [
            'p' => $this->paginator(total: 15, perPage: 5, current: 3),
        ]);

        $html->assertSee('rel="prev"', escape: false)
            ->assertDontSee('rel="next"', escape: false);
    }

    public function test_a_single_page_paginator_renders_only_the_controls(): void
    {
        $html = $this->blade('<x-pagination :paginator="$p" />', [
            'p' => $this->paginator(total: 3, perPage: 5, current: 1, items: 3),
        ]);

        $html->assertDontSee('&hellip;', escape: false)
            ->assertDontSee('>1</a>', escape: false);
    }

    public function test_pagination_variants_and_labels(): void
    {
        $this->blade('<x-pagination :paginator="$p" variant="circle-outline" with-labels />', [
            'p' => $this->paginator(total: 15, perPage: 5, current: 1),
        ])
            ->assertSee('pagination-outline', escape: false)
            ->assertSee('pagination-circle', escape: false)
            ->assertSee('Previous', escape: false)
            ->assertSee('Next', escape: false);
    }

    public function test_caller_attributes_land_on_the_root_nav(): void
    {
        // Regression: attributes used to merge onto the inner <ul>, which is
        // not the flex child of a card footer — so `ms-auto` silently did
        // nothing and every pagination rendered left-aligned.
        $this->blade('<x-pagination :paginator="$p" class="ms-auto" />', [
            'p' => $this->paginator(total: 15, perPage: 5, current: 1),
        ])
            ->assertSee('<nav aria-label="Pagination" class="ms-auto">', escape: false)
            // The computed pagination classes stay on the <ul>.
            ->assertSee('<ul class="pagination">', escape: false);
    }

    public function test_a_simple_paginator_falls_back_to_prev_next_only(): void
    {
        // No lastPage(): the windowing can't run, so only prev/next render.
        // More than perPage items, otherwise there is no next page either.
        $paginator = new Paginator(range(1, 10), 5, 2, ['path' => '/users']);

        $this->blade('<x-pagination :paginator="$p" />', ['p' => $paginator])
            ->assertSee('rel="prev"', escape: false)
            ->assertSee('rel="next"', escape: false)
            ->assertDontSee('page-link">1<', escape: false);
    }
}
