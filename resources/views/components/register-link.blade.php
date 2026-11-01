@if (config('registration.open'))
    <a href="{{ route('registration.create') }}" {{ $attributes }}>{{ $slot }}</a>
@else
    <span role="link" aria-disabled="true"{{ $attributes->merge(['class' => 'opacity-50 pointer-events-none select-none']) }}>{{ $slot }}</span>
@endif
