
@extends('partial.invoice.main')

@section('content')
<div class="page-heading">
    <h3>File PDF Delivery Order</h3>
    <p class="text-subtitle text-muted">
        DO Number: {{ $do->do_no }}
    </p>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar File ({{ count($files) }})</h5>

            <a href="/billing/dock-DO" class="btn btn-secondary">
                Kembali
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (count($files) > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $index => $file)
                                @php
                                    $filename = basename($file);
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $filename }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ asset('storage/' . $file) }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="btn btn-info btn-sm">
                                                Lihat
                                            </a>

                                            <form
                                                action="{{ route('doFiles.delete', $do->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus file ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <input type="hidden"
                                                       name="filename"
                                                       value="{{ $filename }}">

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    Belum ada file PDF untuk DO ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
