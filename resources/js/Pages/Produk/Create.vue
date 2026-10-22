
<script setup>
import { Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    nama: '',
    kategori: '',
    harga: '',
    stok: 0,
});

const submit = () => {
    form.post('/produk');
};
</script>

<template>
    <main class="container">
        <header class="header">
            <div>
                <h1>Tambah Produk</h1>
                <p>Masukkan informasi produk inventaris kamu.</p>
            </div>

            <Link href="/produk" class="btn-back">
                Kembali
            </Link>
        </header>

        <form class="form-card" @submit.prevent="submit">
            <div class="field">
                <label for="nama">Nama Produk</label>
                <input
                    id="nama"
                    v-model="form.nama"
                    type="text"
                    placeholder="Contoh: Keyboard Logitech"
                >
                <small v-if="form.errors.nama" class="error">
                    {{ form.errors.nama }}
                </small>
            </div>

            <div class="field">
                <label for="kategori">Kategori</label>
                <input
                    id="kategori"
                    v-model="form.kategori"
                    type="text"
                    placeholder="Contoh: Elektronik"
                >
                <small v-if="form.errors.kategori" class="error">
                    {{ form.errors.kategori }}
                </small>
            </div>

            <div class="field">
                <label for="harga">Harga (Rp)</label>
                <input
                    id="harga"
                    v-model="form.harga"
                    type="number"
                    min="0"
                    placeholder="Contoh: 250000"
                >
                <small v-if="form.errors.harga" class="error">
                    {{ form.errors.harga }}
                </small>
            </div>

            <div class="field">
                <label for="stok">Stok</label>
                <input
                    id="stok"
                    v-model="form.stok"
                    type="number"
                    min="0"
                    placeholder="Contoh: 10"
                >
                <small v-if="form.errors.stok" class="error">
                    {{ form.errors.stok }}
                </small>
            </div>

            <div class="actions">
                <button
                    type="submit"
                    class="btn-submit"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Produk' }}
                </button>

                <Link href="/produk" class="btn-cancel">
                    Batal
                </Link>
            </div>
        </form>
    </main>
</template>

<style scoped>
.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 0 24px;
    font-family: Arial, sans-serif;
    color: #1e293b;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    margin-bottom: 28px;
}

h1 {
    margin: 0 0 8px;
    font-size: 30px;
}

p {
    margin: 0;
    color: #64748b;
    line-height: 1.5;
}

.form-card {
    padding: 28px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: white;
    box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
}

.field {
    margin-bottom: 22px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-size: 14px;
    font-weight: 600;
}

input {
    box-sizing: border-box;
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 7px;
    font-size: 14px;
    outline: none;
}

input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px #dbeafe;
}

.error {
    display: block;
    margin-top: 6px;
    color: #dc2626;
}

.actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 28px;
}

.btn-submit,
.btn-back,
.btn-cancel {
    display: inline-block;
    padding: 11px 16px;
    border: none;
    border-radius: 7px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
}

.btn-submit {
    background: #2563eb;
    color: white;
}

.btn-submit:hover {
    background: #1d4ed8;
}

.btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-back,
.btn-cancel {
    background: #f1f5f9;
    color: #334155;
}

@media (max-width: 600px) {
    .container {
        margin: 28px auto;
        padding: 0 14px;
    }

    .header {
        align-items: flex-start;
    }

    .form-card {
        padding: 20px;
    }
}
</style>
