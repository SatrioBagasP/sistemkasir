@extends('main')
@push('styles')
    <style>
        .kasir {
            transition: transform 0.2s ease-in-out;
        }

        .data {
            transition: transform 0.2s ease-in-out;
        }

        .kasir:hover {
            transform: scale(1.1);
            cursor: pointer;
        }

        .data:hover {
            transform: scale(1.1);
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <div class="d-flex justify-content-center align-items-center gap-2" style="height: 100vh;">

        <a href="{{ route('home.kasir') }}">
            <div id='click-kasir' class="kasir rounded bg-primary d-flex justify-content-center align-items-center"
                style="height:100px; width:100px;">
                <div class="">
                    <i class="bi bi-cash-coin fs-1 text-light"></i><br>
                    <center>
                        <span class="text-light">Kasir</span>
                    </center>
                </div>

            </div>
        </a>

        <a href="{{ route('home.data') }}">
            <div id='data' class="data rounded bg-primary d-flex justify-content-center align-items-center"
                style="height:100px; width:100px;">
                <div class="">
                    <i class="bi bi-database-fill fs-1 text-light"></i><br>
                    <center>
                        <span class="text-light">Data</span>
                    </center>
                </div>
            </div>
        </a>

    </div>
@endsection
@push('js')
    {{-- <script>
        $('#click-kasir').click(function(e) {
            e.preventDefault();
            alert('test');
        });
    </script> --}}
@endpush
