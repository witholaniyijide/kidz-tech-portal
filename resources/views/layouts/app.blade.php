@auth
    @if(Auth::user()->hasRole('director'))
        {{-- Director uses sidebar layout --}}
        @include('layouts.director-content')
    @else
        {{-- Other roles use standard top nav layout --}}
        @include('layouts.standard-content')
    @endif
@else
    {{-- Guest users --}}
    @include('layouts.standard-content')
@endauth
