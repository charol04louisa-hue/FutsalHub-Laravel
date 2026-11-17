
@props([
    'eyebrow' => 'FUTSALHUB MANAGEMENT',
    'title' => 'Dashboard',
    'description' => '',
    'greeting' => null,
])

<section {{ $attributes->class(['fh-dashboard-hero']) }}>
    <div class="container">
        <span class="fh-dashboard-eyebrow">
            {{ $eyebrow }}
        </span>

        <h1>
            {{ $greeting ?? $title }}
        </h1>

        @if ($description)
            <p>{{ $description }}</p>
        @endif
    </div>
</section>
