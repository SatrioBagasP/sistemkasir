@extends('main')
@push('styles')
@endpush
@section('content')
    <li>
        <a href="{{ route('data.menu') }}">List Menu</a>
    </li>
    <li>
        <a href="{{ route('data.nota') }}">List Nota</a>
    </li>
@endsection
@push('js')
@endpush
