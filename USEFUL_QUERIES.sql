-- ============================================================================
-- LAPORPADANG - USEFUL SQL QUERIES
-- Kumpulan query SQL yang berguna untuk development dan testing
-- ============================================================================

-- ============================================================================
-- 1. QUERY UNTUK MELIHAT STRUKTUR DATABASE
-- ============================================================================

-- Lihat semua tabel
SHOW TABLES;

-- Lihat struktur tabel users
DESCRIBE users;

-- Lihat struktur tabel reports
DESCRIBE reports;

-- Lihat semua foreign key constraints
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    REFERENCED_TABLE_SCHEMA = 'laporpadang'
    AND REFERENCED_TABLE_NAME IS NOT NULL;


-- ============================================================================
-- 2. QUERY UNTUK TESTING DATA
-- ============================================================================

-- Insert user admin
INSERT INTO users (name, email, phone, role, password, created_at, updated_at) 
VALUES (
    'Admin LaporPadang',
    'admin@laporpadang.id',
    '081234567890',
    'admin',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    NOW(),
    NOW()
);

-- Insert user masyarakat
INSERT INTO users (name, email, phone, address, role, password, created_at, updated_at) 
VALUES (
    'Budi Santoso',
    'budi@example.com',
    '082345678901',
    'Jl. Sudirman No. 123, Padang',
    'user',
    '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: password
    NOW(),
    NOW()
);

-- Insert sample report
INSERT INTO reports (
    user_id, 
    category_id, 
    district_id, 
    title, 
    description, 
    latitude, 
    longitude, 
    address, 
    status, 
    created_at, 
    updated_at
) VALUES (
    2, -- user_id (Budi Santoso)
    1, -- category_id (Jalan Rusak)
    7, -- district_id (Padang Barat)
    'Jalan Berlubang di Jl. Sudirman',
    'Jalan berlubang cukup besar di depan Toko ABC, berbahaya untuk pengendara motor',
    -0.94924800,
    100.35427400,
    'Jl. Sudirman No. 45, Padang Barat, Kota Padang',
    'pending',
    NOW(),
    NOW()
);


-- ============================================================================
-- 3. QUERY UNTUK STATISTIK & DASHBOARD
-- ============================================================================

-- Total laporan per status
SELECT 
    status,
    COUNT(*) as total
FROM reports
WHERE deleted_at IS NULL
GROUP BY status
ORDER BY total DESC;

-- Total laporan per kategori
SELECT 
    c.name as kategori,
    COUNT(r.id) as total_laporan
FROM categories c
LEFT JOIN reports r ON c.id = r.category_id AND r.deleted_at IS NULL
GROUP BY c.id, c.name
ORDER BY total_laporan DESC;

-- Total laporan per kecamatan
SELECT 
    d.name as kecamatan,
    COUNT(r.id) as total_laporan
FROM districts d
LEFT JOIN reports r ON d.id = r.district_id AND r.deleted_at IS NULL
GROUP BY d.id, d.name
ORDER BY total_laporan DESC;

-- Laporan terbaru (10 terakhir)
SELECT 
    r.id,
    r.title,
    u.name as pelapor,
    c.name as kategori,
    d.name as kecamatan,
    r.status,
    r.created_at
FROM reports r
JOIN users u ON r.user_id = u.id
JOIN categories c ON r.category_id = c.id
JOIN districts d ON r.district_id = d.id
WHERE r.deleted_at IS NULL
ORDER BY r.created_at DESC
LIMIT 10;

-- User paling aktif (top 10 pelapor)
SELECT 
    u.name,
    u.email,
    COUNT(r.id) as total_laporan
FROM users u
LEFT JOIN reports r ON u.id = r.user_id AND r.deleted_at IS NULL
WHERE u.role = 'user'
GROUP BY u.id, u.name, u.email
ORDER BY total_laporan DESC
LIMIT 10;

-- Laporan yang belum ditangani (pending)
SELECT 
    r.id,
    r.title,
    u.name as pelapor,
    c.name as kategori,
    d.name as kecamatan,
    r.created_at,
    DATEDIFF(NOW(), r.created_at) as hari_pending
FROM reports r
JOIN users u ON r.user_id = u.id
JOIN categories c ON r.category_id = c.id
JOIN districts d ON r.district_id = d.id
WHERE r.status = 'pending' 
    AND r.deleted_at IS NULL
ORDER BY r.created_at ASC;

-- Rata-rata waktu penyelesaian laporan (dalam hari)
SELECT 
    AVG(DATEDIFF(updated_at, created_at)) as rata_rata_hari
FROM reports
WHERE status = 'done' 
    AND deleted_at IS NULL;


-- ============================================================================
-- 4. QUERY UNTUK DETAIL LAPORAN
-- ============================================================================

-- Detail laporan lengkap dengan relasi
SELECT 
    r.id,
    r.title,
    r.description,
    r.address,
    r.latitude,
    r.longitude,
    r.status,
    r.admin_note,
    u.name as pelapor,
    u.email as email_pelapor,
    u.phone as phone_pelapor,
    c.name as kategori,
    d.name as kecamatan,
    r.created_at,
    r.updated_at
FROM reports r
JOIN users u ON r.user_id = u.id
JOIN categories c ON r.category_id = c.id
JOIN districts d ON r.district_id = d.id
WHERE r.id = 1 -- ganti dengan ID laporan
    AND r.deleted_at IS NULL;

-- Foto-foto laporan
SELECT 
    ri.id,
    ri.image,
    ri.created_at
FROM report_images ri
WHERE ri.report_id = 1 -- ganti dengan ID laporan
ORDER BY ri.created_at ASC;

-- Komentar pada laporan
SELECT 
    c.id,
    c.comment,
    u.name as pemberi_komentar,
    u.role,
    c.created_at
FROM comments c
JOIN users u ON c.user_id = u.id
WHERE c.report_id = 1 -- ganti dengan ID laporan
ORDER BY c.created_at ASC;


-- ============================================================================
-- 5. QUERY UNTUK NOTIFIKASI
-- ============================================================================

-- Notifikasi belum dibaca per user
SELECT 
    n.id,
    n.title,
    n.message,
    n.created_at
FROM notifications n
WHERE n.user_id = 2 -- ganti dengan ID user
    AND n.is_read = 0
ORDER BY n.created_at DESC;

-- Total notifikasi belum dibaca
SELECT 
    COUNT(*) as total_unread
FROM notifications
WHERE user_id = 2 -- ganti dengan ID user
    AND is_read = 0;

-- Tandai notifikasi sebagai sudah dibaca
UPDATE notifications 
SET is_read = 1, updated_at = NOW()
WHERE id = 1; -- ganti dengan ID notifikasi

-- Tandai semua notifikasi user sebagai sudah dibaca
UPDATE notifications 
SET is_read = 1, updated_at = NOW()
WHERE user_id = 2 -- ganti dengan ID user
    AND is_read = 0;


-- ============================================================================
-- 6. QUERY UNTUK ACTIVITY LOGS
-- ============================================================================

-- Activity log terbaru
SELECT 
    al.id,
    u.name as user_name,
    u.role,
    al.activity,
    al.description,
    al.ip_address,
    al.created_at
FROM activity_logs al
LEFT JOIN users u ON al.user_id = u.id
ORDER BY al.created_at DESC
LIMIT 50;

-- Activity log per user
SELECT 
    al.activity,
    al.description,
    al.ip_address,
    al.created_at
FROM activity_logs al
WHERE al.user_id = 1 -- ganti dengan ID user
ORDER BY al.created_at DESC;

-- Activity log per jenis aktivitas
SELECT 
    activity,
    COUNT(*) as total
FROM activity_logs
GROUP BY activity
ORDER BY total DESC;


-- ============================================================================
-- 7. QUERY UNTUK UPDATE STATUS LAPORAN
-- ============================================================================

-- Update status laporan ke 'process'
UPDATE reports 
SET status = 'process', 
    updated_at = NOW()
WHERE id = 1; -- ganti dengan ID laporan

-- Update status laporan ke 'done' dengan admin note
UPDATE reports 
SET status = 'done', 
    admin_note = 'Laporan telah selesai ditangani. Jalan sudah diperbaiki.',
    updated_at = NOW()
WHERE id = 1; -- ganti dengan ID laporan

-- Update status laporan ke 'rejected' dengan alasan
UPDATE reports 
SET status = 'rejected', 
    admin_note = 'Laporan tidak valid. Lokasi tidak ditemukan.',
    updated_at = NOW()
WHERE id = 1; -- ganti dengan ID laporan


-- ============================================================================
-- 8. QUERY UNTUK SOFT DELETE
-- ============================================================================

-- Soft delete laporan
UPDATE reports 
SET deleted_at = NOW()
WHERE id = 1; -- ganti dengan ID laporan

-- Restore laporan yang di-soft delete
UPDATE reports 
SET deleted_at = NULL
WHERE id = 1; -- ganti dengan ID laporan

-- Lihat laporan yang sudah di-soft delete
SELECT 
    r.id,
    r.title,
    u.name as pelapor,
    r.deleted_at
FROM reports r
JOIN users u ON r.user_id = u.id
WHERE r.deleted_at IS NOT NULL
ORDER BY r.deleted_at DESC;

-- Permanent delete (hati-hati!)
DELETE FROM reports 
WHERE id = 1 -- ganti dengan ID laporan
    AND deleted_at IS NOT NULL;


-- ============================================================================
-- 9. QUERY UNTUK SEARCH & FILTER
-- ============================================================================

-- Search laporan berdasarkan keyword
SELECT 
    r.id,
    r.title,
    r.description,
    u.name as pelapor,
    c.name as kategori,
    r.status,
    r.created_at
FROM reports r
JOIN users u ON r.user_id = u.id
JOIN categories c ON r.category_id = c.id
WHERE r.deleted_at IS NULL
    AND (
        r.title LIKE '%jalan%' 
        OR r.description LIKE '%jalan%'
        OR r.address LIKE '%jalan%'
    )
ORDER BY r.created_at DESC;

-- Filter laporan berdasarkan kategori dan status
SELECT 
    r.id,
    r.title,
    u.name as pelapor,
    d.name as kecamatan,
    r.created_at
FROM reports r
JOIN users u ON r.user_id = u.id
JOIN districts d ON r.district_id = d.id
WHERE r.deleted_at IS NULL
    AND r.category_id = 1 -- Jalan Rusak
    AND r.status = 'pending'
ORDER BY r.created_at DESC;

-- Filter laporan berdasarkan tanggal
SELECT 
    r.id,
    r.title,
    u.name as pelapor,
    c.name as kategori,
    r.status,
    r.created_at
FROM reports r
JOIN users u ON r.user_id = u.id
JOIN categories c ON r.category_id = c.id
WHERE r.deleted_at IS NULL
    AND DATE(r.created_at) BETWEEN '2026-05-01' AND '2026-05-31'
ORDER BY r.created_at DESC;


-- ============================================================================
-- 10. QUERY UNTUK MAINTENANCE
-- ============================================================================

-- Cek ukuran database
SELECT 
    table_schema AS 'Database',
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
FROM information_schema.tables
WHERE table_schema = 'laporpadang'
GROUP BY table_schema;

-- Cek jumlah record per tabel
SELECT 
    table_name AS 'Table',
    table_rows AS 'Rows'
FROM information_schema.tables
WHERE table_schema = 'laporpadang'
ORDER BY table_rows DESC;

-- Optimize semua tabel
OPTIMIZE TABLE users, categories, districts, reports, report_images, comments, notifications, activity_logs;

-- Backup database (jalankan di terminal)
-- mysqldump -u root -p laporpadang > backup_laporpadang_2026_05_13.sql

-- Restore database (jalankan di terminal)
-- mysql -u root -p laporpadang < backup_laporpadang_2026_05_13.sql


-- ============================================================================
-- 11. QUERY UNTUK TESTING & DEVELOPMENT
-- ============================================================================

-- Hapus semua data (HATI-HATI!)
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE activity_logs;
TRUNCATE TABLE notifications;
TRUNCATE TABLE comments;
TRUNCATE TABLE report_images;
TRUNCATE TABLE reports;
TRUNCATE TABLE users;
-- Jangan truncate categories dan districts karena ada seeder
SET FOREIGN_KEY_CHECKS = 1;

-- Reset auto increment
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE reports AUTO_INCREMENT = 1;
ALTER TABLE report_images AUTO_INCREMENT = 1;
ALTER TABLE comments AUTO_INCREMENT = 1;
ALTER TABLE notifications AUTO_INCREMENT = 1;
ALTER TABLE activity_logs AUTO_INCREMENT = 1;


-- ============================================================================
-- 12. QUERY UNTUK LAPORAN BULANAN
-- ============================================================================

-- Laporan per bulan (tahun 2026)
SELECT 
    MONTH(created_at) as bulan,
    MONTHNAME(created_at) as nama_bulan,
    COUNT(*) as total_laporan
FROM reports
WHERE YEAR(created_at) = 2026
    AND deleted_at IS NULL
GROUP BY MONTH(created_at), MONTHNAME(created_at)
ORDER BY bulan;

-- Laporan per kategori per bulan
SELECT 
    c.name as kategori,
    MONTHNAME(r.created_at) as bulan,
    COUNT(r.id) as total
FROM categories c
LEFT JOIN reports r ON c.id = r.category_id 
    AND YEAR(r.created_at) = 2026
    AND r.deleted_at IS NULL
GROUP BY c.id, c.name, MONTH(r.created_at), MONTHNAME(r.created_at)
ORDER BY MONTH(r.created_at), total DESC;


-- ============================================================================
-- CATATAN PENTING
-- ============================================================================
-- 1. Selalu backup database sebelum menjalankan query UPDATE atau DELETE
-- 2. Gunakan WHERE clause dengan hati-hati
-- 3. Test query di development environment terlebih dahulu
-- 4. Gunakan LIMIT untuk query yang menghasilkan banyak data
-- 5. Perhatikan soft delete (deleted_at IS NULL) saat query reports
-- ============================================================================
