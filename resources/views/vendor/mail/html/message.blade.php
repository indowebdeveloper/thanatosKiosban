@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => '#'])
         <img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path($logo)))}}" style="width:150px;">
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}
    {{-- Subcopy --}}
    @if (isset($subcopy))
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endif

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            &copy; {{ date('Y') }} {{ getSettings('general-settings','application_name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
