@extends('layouts.app') {{-- Assuming you have a layout --}}

@section('content')
@if (count($files) > 0)

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Filename</th>
                    <th>MIME Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $file)
                    <tr>
                        <td>{{ $file->id }}</td>
                        <td>{{ $file->original_filename }}</td>
                        <td>{{ $file->mime_type }}</td>
                        <td>
                            <form action="{{ route('files.delete', ['id' => $file->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No files uploaded yet.</p>
    @endif
        

@include('files.upload')

@endsection