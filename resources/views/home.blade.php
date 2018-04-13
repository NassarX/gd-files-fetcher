@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form method="POST" action="{{ route('gd.files.fetch') }}">
                        @csrf
                        <button type="submit" class="btn-info"> Fetch Files </button>
                    </form>
                </div>

                <div class="card-body row">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Mime Type</th>
                            <th>File Size</th>
                            <th>Download Url</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($userFiles))
                            @foreach($userFiles->items() as $file)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $file['title'] }}</td>
                                    <td>{{ $file['mime_type'] }}</td>
                                    <td>{{ $file['size'] }} {{ $file['size']? '(KB)' : '---' }}</td>
                                    <td> <a href="{{ $file['download_url'] }}">download url</a></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
                @if(isset($userFiles))
                    {{ $userFiles->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
