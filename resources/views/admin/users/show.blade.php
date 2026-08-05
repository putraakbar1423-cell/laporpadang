@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('content')
<div class="page-header">
    <h1>👤 Detail Pengguna</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <h3>{{ $user->name }}</h3>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>No. HP:</strong> {{ $user->phone ?? '-' }}</p>
        <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
        <p><strong>Bergabung:</strong> {{ $user->created_at->format('d F Y') }}</p>
        
        <hr>
        
        <h4>Laporan Pengguna ({{ $user->reports->count() }})</h4>
        
        @if($user->reports->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->reports as $report)
                    <tr>
                        <td>#{{ $report->id }}</td>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->category->name ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $report->status }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td>{{ $report->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>Belum ada laporan dari pengguna ini.</p>
        @endif
    </div>
</div>

<style>
.badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}
.badge-pending { background: #FFA726; color: white; }
.badge-process { background: #42A5F5; color: white; }
.badge-done { background: #66BB6A; color: white; }
.badge-rejected { background: #EF5350; color: white; }
</style>
@endsection
