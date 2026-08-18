@extends('layouts.admin')

@section('content')

<div class="p-4">
    <h2 class="text-xl font-bold mb-4">ប្រវត្តិសន្ទនា</h2>

    @foreach($conversations as $c)
    <a href="{{ route('admin.chat.show', $c->id) }}"
        class="block p-3 bg-white shadow mb-2">
        ប្រវត្តិសន្ទនា #{{ $c->id }}
    </a>
    @endforeach
</div>

@endsection