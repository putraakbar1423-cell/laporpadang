@extends('layouts.admin')

@section('title', 'Pengaturan Umum')

@section('breadcrumb')
    <span>Admin</span> / <span>Pengaturan</span> / <span>Umum</span>
@endsection

@section('content')
<style>
    .settings-container {
        max-width: 1000px;
    }
    
    .page-header {
        margin-bottom: 30px;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 8px;
    }
    
    .page-subtitle {
        font-size: 15px;
        color: #666;
    }
    
    .settings-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
    }
    
    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    .form-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }
    
    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #2E7D32;
        box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
    }
    
    .form-group .help-text {
        font-size: 12px;
        color: #999;
        margin-top: 6px;
    }
    
    .btn-save {
        padding: 12px 32px;
        background: linear-gradient(135deg, #2E7D32, #00A86B);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
    }
    
    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
    }
    
    .alert {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
    }
    
    .alert-success {
        background: rgba(102, 187, 106, 0.15);
        color: #388E3C;
        border-left: 4px solid #388E3C;
    }
</style>

<div class="settings-container">
    <div class="page-header">
        <h1 class="page-title">⚙️ Pengaturan Umum</h1>
        <p class="page-subtitle">Konfigurasi umum sistem LaporPadang</p>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    
    <!-- Site Settings -->
    <div class="settings-card">
        <h3 class="card-title">🌐 Pengaturan Situs</h3>
        
        <form action="{{ route('admin.settings.general.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="site_name">Nama Situs</label>
                <input type="text" id="site_name" name="site_name" value="Sistem KKM" required>
                <div class="help-text">Nama aplikasi yang ditampilkan di header dan email</div>
            </div>
            
            <div class="form-group">
                <label for="site_description">Deskripsi Situs</label>
                <textarea id="site_description" name="site_description" rows="3" required>Sistem Komunikasi dan Keluhan Masyarakat - Kota Padang</textarea>
                <div class="help-text">Deskripsi singkat tentang aplikasi</div>
            </div>
            
            <div class="form-group">
                <label for="contact_email">Email Kontak</label>
                <input type="email" id="contact_email" name="contact_email" value="admin@laporpadang.id" required>
                <div class="help-text">Email untuk kontak dan notifikasi sistem</div>
            </div>
            
            <div class="form-group">
                <label for="contact_phone">Nomor Telepon</label>
                <input type="tel" id="contact_phone" name="contact_phone" value="+62 751 1234567" required>
                <div class="help-text">Nomor telepon layanan pengaduan</div>
            </div>
            
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
    
    <!-- Report Settings -->
    <div class="settings-card">
        <h3 class="card-title">📝 Pengaturan Laporan</h3>
        
        <form action="{{ route('admin.settings.general.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="max_images">Maksimal Foto per Laporan</label>
                <input type="number" id="max_images" name="max_images" value="5" min="1" max="10" required>
                <div class="help-text">Jumlah maksimal foto yang bisa diupload per laporan (1-10)</div>
            </div>
            
            <div class="form-group">
                <label for="auto_approve">Auto Approve Laporan</label>
                <select id="auto_approve" name="auto_approve">
                    <option value="0">Tidak (Perlu Review Admin)</option>
                    <option value="1">Ya (Langsung Publish)</option>
                </select>
                <div class="help-text">Apakah laporan baru langsung dipublish tanpa review?</div>
            </div>
            
            <div class="form-group">
                <label for="report_expiry_days">Masa Aktif Laporan (Hari)</label>
                <input type="number" id="report_expiry_days" name="report_expiry_days" value="90" min="7" max="365" required>
                <div class="help-text">Laporan akan di-archive otomatis setelah masa ini (7-365 hari)</div>
            </div>
            
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
    
    <!-- Notification Settings -->
    <div class="settings-card">
        <h3 class="card-title">🔔 Pengaturan Notifikasi</h3>
        
        <form action="{{ route('admin.settings.general.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="email_notifications" value="1" checked>
                    Kirim Email Notifikasi
                </label>
                <div class="help-text">Kirim email saat ada laporan baru atau update status</div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="push_notifications" value="1" checked>
                    Kirim Push Notification
                </label>
                <div class="help-text">Kirim notifikasi push ke aplikasi mobile</div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="sms_notifications" value="1">
                    Kirim SMS Notifikasi
                </label>
                <div class="help-text">Kirim SMS untuk laporan urgent (memerlukan konfigurasi tambahan)</div>
            </div>
            
            <button type="submit" class="btn-save">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
    
    <!-- System Info -->
    <div class="settings-card">
        <h3 class="card-title">ℹ️ Informasi Sistem</h3>
        
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 12px 0; font-weight: 600; color: #666;">Laravel Version</td>
                <td style="padding: 12px 0;">{{ app()->version() }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 12px 0; font-weight: 600; color: #666;">PHP Version</td>
                <td style="padding: 12px 0;">{{ phpversion() }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 12px 0; font-weight: 600; color: #666;">Environment</td>
                <td style="padding: 12px 0;">{{ config('app.env') }}</td>
            </tr>
            <tr style="border-bottom: 1px solid #f0f0f0;">
                <td style="padding: 12px 0; font-weight: 600; color: #666;">Debug Mode</td>
                <td style="padding: 12px 0;">
                    @if(config('app.debug'))
                        <span style="color: #EF5350; font-weight: 600;">Enabled ⚠️</span>
                    @else
                        <span style="color: #66BB6A; font-weight: 600;">Disabled ✓</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td style="padding: 12px 0; font-weight: 600; color: #666;">Database</td>
                <td style="padding: 12px 0;">{{ config('database.default') }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
