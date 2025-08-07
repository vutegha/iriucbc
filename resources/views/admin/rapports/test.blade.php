@extends('layouts.admin')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold">TEST PAGE</h1>
    <p>This is a test page to verify the layout is working.</p>
    
    <div class="mt-4">
        <h2>Categories Test:</h2>
        @if(isset($categories))
            <p>Categories variable exists with {{ $categories->count() }} items</p>
            @foreach($categories as $category)
                <li>{{ $category->nom ?? 'No name' }}</li>
            @endforeach
        @else
            <p>Categories variable is not set</p>
        @endif
    </div>
</div>
@endsection
