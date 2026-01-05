@props(['message'])

@if ($message)
    <p class="text-danger">{{ $message }}</p>
@endif
