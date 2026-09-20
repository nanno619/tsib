# Carousel

`<x-carousel>` wraps Tabler's [Carousel component](https://docs.tabler.io/ui/components/carousel) (Bootstrap's carousel) — a slides array in, full markup out.

Source: `resources/views/components/carousel.blade.php`

## Basic usage

```blade
<x-carousel id="hero-carousel" :slides="[
    ['image' => asset('images/slide-1.jpg'), 'alt' => 'Slide 1'],
    ['image' => asset('images/slide-2.jpg'), 'alt' => 'Slide 2'],
]" />
```

## Props

| Prop         | Type         | Default    | Description |
|--------------|--------------|------------|--------------|
| `id`         | string       | —          | Required. Used to wire indicators/controls to this specific carousel — must be unique on the page. |
| `slides`     | array        | `[]`       | See [slide shape](#slide-shape). |
| `indicators` | string\|false | `numbered` | `numbered` (default dots-as-buttons), `dot`, `thumb` (each indicator shows the slide's own image), or `false` to omit indicators entirely. |
| `vertical`   | bool         | `false`    | Moves indicators to the side (`carousel-indicators-vertical`). |
| `controls`   | bool         | `true`     | Shows the prev/next arrow controls. |
| `fade`       | bool         | `false`    | Crossfade instead of slide transition (`carousel-fade`) — commonly paired with `dot`/`thumb` indicators. |
| `ride`       | string\|false | `carousel` | `data-bs-ride` — `carousel` autoplays from page load, `false` only advances via controls/indicators. |

Any other attribute (e.g. `class="mb-3"`) is merged onto the outer `.carousel`.

## Slide shape

| Key       | Description |
|-----------|--------------|
| `image`   | Required. The slide image URL. |
| `alt`     | Alt text for the image. |
| `title`   | Optional caption heading (`carousel-caption`). Adding either `title` or `caption` also renders `carousel-caption-background`, a dark gradient so light text stays readable over the photo. |
| `caption` | Optional caption body text. |

## Variants

### Dot indicators with fade

```blade
<x-carousel id="carousel-dot" indicators="dot" fade :slides="$slides" />
```

### Thumbnail indicators

Each indicator shows the corresponding slide's image, cropped to a 4:3 tile:

```blade
<x-carousel id="carousel-thumb" indicators="thumb" fade :slides="$slides" />
```

### Vertical indicators

```blade
<x-carousel id="carousel-vertical" indicators="dot" vertical fade :slides="$slides" />
```

### With captions

```blade
<x-carousel id="carousel-captions" :slides="[
    ['image' => $url, 'title' => 'Slide label', 'caption' => 'Supporting text for this slide.'],
]" />
```

### No autoplay, no indicators

```blade
<x-carousel id="carousel-manual" :indicators="false" :ride="false" :slides="$slides" />
```

## See it live

The Starter Kit page's "Carousel" card.
