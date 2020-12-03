@extends('public.layouts.app')

@section('content')
<div class="grid sm:grid-cols-1 md:grid-cols-3 gap-4 py-10">
    <div class="md:col-span-2 sm:col-span-3">

        @foreach (range(0, 10) as $item)
        @include('public.post.post')
        @endforeach

    </div>

    <div class="md:col-span-1 sm:col-span-3">
        <div class="">
            <p>a</p>
        </div>
    </div>
</div>
@endsection