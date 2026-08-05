@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('breadcrumb')
    <span>Dashboard</span> / <span>Pengguna</span>
@endsection

@section('content')
<div class="page-header">
    <h1>👥 Kelola Pengguna</h1>
    <p>Daftar pengguna aplikasi LaporPadang</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Total Laporan</th>
                        <th>Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->reports()->count() }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center;">Belum ada pengguna</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 20px;">
            {{ $users->links() }}
        </div>
    </div>
</div>

<style>
.table {
    width: 100%;
    border-collapse: collapse;
}
.table th {
    background: #f5f5f5;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #ddd;
}
.table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}
.btn-sm {
    padding: 6px 12px;
    font-size: 13px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}
.btn-primary {
    background: #2E7D32;
    color: white;
}
.btn-primary:hover {
    background: #1B5E20;
}
</style>
@endsection
