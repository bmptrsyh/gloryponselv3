<!-- resources/views/admin/inbox.blade.php -->
<x-dashboard>
<style>
/* Styles untuk inbox admin */


.container {
    max-width: 1200px;
    margin: 0 auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.inbox-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.inbox-header h1 {
    font-size: 24px;
    color: #333;
}

.inbox-actions {
    display: flex;
    gap: 10px;
}

.btn-new-message, .btn-export {
    padding: 8px 15px;
    border-radius: 4px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.btn-new-message {
    background-color: #3b82f6;
    color: white;
    border: none;
}

.btn-export {
    background-color: white;
    border: 1px solid #ddd;
    color: #333;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-title {
    font-size: 14px;
    color: #666;
    margin-bottom: 5px;
}

.stat-value {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-change {
    font-size: 12px;
}

.stat-change.positive {
    color: #10b981;
}

.stat-change.negative {
    color: #ef4444;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.message-icon {
    background-color: #dbeafe;
    color: #3b82f6;
}

.alert-icon {
    background-color: #fee2e2;
    color: #ef4444;
}

.check-icon {
    background-color: #dcfce7;
    color: #10b981;
}

.inbox-table-container {
    overflow-x: auto;
}

.inbox-table {
    width: 100%;
    border-collapse: collapse;
}

.inbox-table th, .inbox-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.inbox-table th {
    background-color: #f9fafb;
    font-weight: 600;
    color: #666;
}

.inbox-table tr:hover {
    background-color: #f8fafc;
}

.inbox-table tr.unread {
    background-color: #f8fafc;
}

.select-all, .message-checkbox {
    width: 16px;
    height: 16px;
}

.sender-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.sender-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #dbeafe;
    color: #3b82f6;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.sender-name {
    font-weight: 500;
    display: block;
}

.sender-email {
    font-size: 12px;
    color: #666;
}

.message-subject {
    font-weight: 500;
    margin-bottom: 3px;
}

.message-preview {
    font-size: 13px;
    color: #666;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.answered {
    background-color: #dcfce7;
    color: #10b981;
}

.status-badge.pending {
    background-color: #fef3c7;
    color: #d97706;
}

.timestamp {
    font-size: 13px;
    color: #666;
}

.action-link {
    color: #3b82f6;
    text-decoration: none;
}

.pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.pagination-info {
    font-size: 14px;
    color: #666;
}

.pagination-controls {
    display: flex;
    gap: 5px;
}

.pagination-prev, .pagination-next, .pagination-page {
    padding: 6px 12px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    cursor: pointer;
}

.pagination-page.active {
    background-color: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.pagination-ellipsis {
    padding: 6px 12px;
}
</style>
    <div class="content">
        <div class="container">
            <div class="inbox-header">
                <h1>Inbox Admin</h1>
                <div class="inbox-actions">
                    <button class="btn-new-message">
                        <i class="icon-plus"></i> Pesan Baru
                    </button>
                    <button class="btn-export">
                        <i class="icon-download"></i> Export
                    </button>
                </div>
            </div>

            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-info">
                        <span class="stat-title">Total Pesan</span>
                        <span class="stat-value">1,248</span>
                        <span class="stat-change positive">↑ 12% dari bulan lalu</span>
                    </div>
                    <div class="stat-icon message-icon">
                        <i class="icon-message"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <span class="stat-title">Pesan Belum Dibaca</span>
                        <span class="stat-value">42</span>
                        <span class="stat-change negative">↓ 5% dari kemarin</span>
                    </div>
                    <div class="stat-icon alert-icon">
                        <i class="icon-alert"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <span class="stat-title">Pesan Terjawab</span>
                        <span class="stat-value">1,153</span>
                        <span class="stat-change positive">↑ 8% dari kemarin</span>
                    </div>
                    <div class="stat-icon check-icon">
                        <i class="icon-check"></i>
                    </div>
                </div>
            </div>

            <div class="inbox-table-container">
                <table class="inbox-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" class="select-all"></th>
                            <th>Pengirim</th>
                            <th>Subjek</th>
                            <th>Status</th>
                            <th>Terakhir Dibuka</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Pesan 1 -->
                        <tr>
                            <td><input type="checkbox" class="message-checkbox"></td>
                            <td>
                                <div class="sender-info">
                                    <div class="sender-avatar">AJ</div>
                                    <div class="sender-details">
                                        <span class="sender-name">Allen Jask</span>
                                        <span class="sender-email">allen@example.com</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="message-subject">Pertanyaan tentang iPhone 13 Pro</div>
                                <div class="message-preview">Saya ingin menanyakan ketersediaan warna untuk iPhone 13 Pro warna Graphite...</div>
                            </td>
                            <td><span class="status-badge answered">Terjawab</span></td>
                            <td class="timestamp">2 jam yang lalu</td>
                            <td><a href="{{ route('admin.inbox') }}" class="action-link">Lihat</a></td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination">
                    <div class="pagination-info">
                        Menampilkan 1 sampai 10 dari 1,153 hasil
                    </div>
                    <div class="pagination-controls">
                        <button class="pagination-prev">Sebelumnya</button>
                        <button class="pagination-page active">1</button>
                        <button class="pagination-page">2</button>
                        <button class="pagination-page">3</button>
                        <span class="pagination-ellipsis">...</span>
                        <button class="pagination-page">10</button>
                        <button class="pagination-next">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-dashboard>