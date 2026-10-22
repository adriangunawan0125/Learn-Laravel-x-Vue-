```vue
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    produks: {
        type: Array,
        default: () => [],
    },
});

const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(angka);
};
</script>

<template>
    <main class="container">
        <header class="header">
            <div>
                <h1>Data Produk</h1>
                <p>Kelola inventaris produk kamu.</p>
            </div>

            <div class="header-actions">
                <span class="count">
                    {{ produks.length }} produk
                </span>

                <Link href="/produk/create" class="btn-add">
                    + Tambah Produk
                </Link>
            </div>
        </header>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(produk, index) in produks"
                        :key="produk.id"
                    >
                        <td>{{ index + 1 }}</td>
                        <td class="nama-produk">{{ produk.nama }}</td>
                        <td>{{ produk.kategori }}</td>
                        <td class="harga">
                            {{ formatRupiah(produk.harga) }}
                        </td>
                        <td>
                            <span
                                class="stok"
                                :class="{ 'stok-habis': produk.stok === 0 }"
                            >
                                {{ produk.stok }}
                            </span>
                        </td>
                        <td>
                            <div class="aksi">
                                <Link
                                    :href="`/produk/${produk.id}/edit`"
                                    class="btn-edit"
                                >
                                    Edit
                                </Link>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="produks.length === 0">
                        <td colspan="6" class="empty">
                            <div class="empty-icon">📦</div>
                            <h3>Belum Ada Produk</h3>
                            <p>Mulai tambahkan produk ke inventaris kamu.</p>

                            <Link href="/produk/create" class="btn-add">
                                + Tambah Produk
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <footer class="footer">
            Menampilkan {{ produks.length }} produk
        </footer>
    </main>
</template>

<style scoped>
.container {
    max-width: 1100px;
    margin: 50px auto;
    padding: 0 24px;
    font-family: Arial, sans-serif;
    color: #1e293b;
}

.header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 28px;
}

h1 {
    margin: 0 0 8px;
    font-size: 30px;
    font-weight: 700;
}

p {
    margin: 0;
    color: #64748b;
    line-height: 1.6;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.count {
    padding: 10px 14px;
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
}

.btn-add,
.btn-edit {
    display: inline-block;
    padding: 10px 15px;
    color: white;
    text-decoration: none;
    border-radius: 7px;
    font-size: 14px;
    font-weight: 600;
    transition: background-color 0.2s;
    white-space: nowrap;
}

.btn-add {
    background: #2563eb;
}

.btn-add:hover {
    background: #1d4ed8;
}

.btn-edit {
    background: #0d9488;
}

.btn-edit:hover {
    background: #0f766e;
}

.table-wrapper {
    overflow-x: auto;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
}

table {
    width: 100%;
    border-collapse: collapse;
    text-align: left;
}

th,
td {
    padding: 16px;
    border-bottom: 1px solid #e2e8f0;
}

th {
    background: #f8fafc;
    color: #475569;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    white-space: nowrap;
}

td {
    font-size: 14px;
}

tbody tr:hover {
    background: #f8fafc;
}

tbody tr:last-child td {
    border-bottom: none;
}

.nama-produk {
    font-weight: 600;
    color: #0f172a;
}

.harga {
    font-weight: 600;
    white-space: nowrap;
}

.stok {
    display: inline-block;
    min-width: 32px;
    padding: 5px 9px;
    text-align: center;
    border-radius: 6px;
    background: #dcfce7;
    color: #166534;
    font-weight: 600;
}

.stok-habis {
    background: #fee2e2;
    color: #b91c1c;
}

.aksi {
    display: flex;
    align-items: center;
    gap: 8px;
}

.empty {
    padding: 55px 20px;
    text-align: center;
    color: #64748b;
}

.empty-icon {
    font-size: 42px;
    margin-bottom: 12px;
}

.empty h3 {
    margin: 0 0 8px;
    color: #1e293b;
    font-size: 20px;
}

.empty p {
    margin-bottom: 20px;
}

.footer {
    margin-top: 16px;
    color: #64748b;
    font-size: 13px;
}

@media (max-width: 640px) {
    .container {
        margin: 28px auto;
        padding: 0 14px;
    }

    .header {
        align-items: flex-start;
        flex-direction: column;
    }

    h1 {
        font-size: 25px;
    }

    th,
    td {
        padding: 12px;
    }
}
</style>
```