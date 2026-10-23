
<script setup>
import { reactive, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';

const props = defineProps({
    produks: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    produk_id: '',
    jenis: 'masuk',
    jumlah: 1,
    tanggal: new Date().toLocaleDateString('en-CA'),
    keterangan: '',
});

const produkTerpilih = computed(() =>
    props.produks.find(
        (produk) => Number(produk.id) === Number(form.produk_id)
    )
);

function submit() {
    form.post('/riwayat-stok', {
        onSuccess: () => form.reset('produk_id', 'jumlah', 'keterangan'),
    });
}
</script>

<template>
    <Head title="Catat Transaksi Stok" />

    <AppLayout>
        <div class="mx-auto max-w-3xl space-y-6">
            <div>
                <Link
                    href="/riwayat-stok"
                    class="text-sm font-medium text-blue-600 hover:text-blue-700"
                >
                    ← Kembali ke Riwayat Stok
                </Link>

                <h1 class="mt-4 text-2xl font-bold text-slate-900">
                    Catat Transaksi Stok
                </h1>

                <p class="mt-2 text-sm text-slate-500">
                    Masukkan detail barang masuk atau barang keluar.
                </p>
            </div>

            <form
                @submit.prevent="submit"
                class="space-y-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8"
            >
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Jenis Transaksi
                    </label>

                    <div class="grid grid-cols-2 gap-3">
                        <button
                            type="button"
                            @click="form.jenis = 'masuk'"
                            :class="form.jenis === 'masuk'
                                ? 'border-emerald-500 bg-emerald-50 text-emerald-700 ring-2 ring-emerald-100'
                                : 'border-slate-200 text-slate-500 hover:bg-slate-50'"
                            class="rounded-xl border p-4 text-left transition"
                        >
                            <span class="block text-lg font-bold">↓ Barang Masuk</span>
                            <span class="mt-1 block text-xs">Menambah stok produk</span>
                        </button>

                        <button
                            type="button"
                            @click="form.jenis = 'keluar'"
                            :class="form.jenis === 'keluar'
                                ? 'border-amber-500 bg-amber-50 text-amber-700 ring-2 ring-amber-100'
                                : 'border-slate-200 text-slate-500 hover:bg-slate-50'"
                            class="rounded-xl border p-4 text-left transition"
                        >
                            <span class="block text-lg font-bold">↑ Barang Keluar</span>
                            <span class="mt-1 block text-xs">Mengurangi stok produk</span>
                        </button>
                    </div>

                    <p v-if="form.errors.jenis" class="mt-2 text-sm text-red-600">
                        {{ form.errors.jenis }}
                    </p>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Pilih Produk
                    </label>

                    <select
                        v-model="form.produk_id"
                        required
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="" disabled>-- Pilih produk --</option>

                        <option
                            v-for="produk in produks"
                            :key="produk.id"
                            :value="produk.id"
                        >
                            {{ produk.nama }} — Stok: {{ produk.stok }}
                        </option>
                    </select>

                    <p v-if="form.errors.produk_id" class="mt-2 text-sm text-red-600">
                        {{ form.errors.produk_id }}
                    </p>

                    <div
                        v-if="produkTerpilih"
                        class="mt-3 rounded-xl bg-slate-50 p-4 text-sm"
                    >
                        <p class="text-slate-500">
                            Kategori: {{ produkTerpilih.kategori || '-' }}
                        </p>

                        <p class="mt-1 font-semibold text-slate-800">
                            Stok saat ini: {{ produkTerpilih.stok }} unit
                        </p>
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Jumlah Barang
                        </label>

                        <input
                            v-model.number="form.jumlah"
                            type="number"
                            min="1"
                            max="1000000"
                            required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                            placeholder="Contoh: 10"
                        />

                        <p v-if="form.errors.jumlah" class="mt-2 text-sm text-red-600">
                            {{ form.errors.jumlah }}
                        </p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">
                            Tanggal Transaksi
                        </label>

                        <input
                            v-model="form.tanggal"
                            type="date"
                            required
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        />

                        <p v-if="form.errors.tanggal" class="mt-2 text-sm text-red-600">
                            {{ form.errors.tanggal }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                        Keterangan
                        <span class="font-normal text-slate-400">(Opsional)</span>
                    </label>

                    <textarea
                        v-model="form.keterangan"
                        rows="3"
                        maxlength="1000"
                        placeholder="Contoh: Pembelian dari supplier atau penjualan kepada pelanggan"
                        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                    />

                    <p v-if="form.errors.keterangan" class="mt-2 text-sm text-red-600">
                        {{ form.errors.keterangan }}
                    </p>
                </div>

                <div class="flex flex-col-reverse justify-end gap-3 border-t border-slate-100 pt-5 sm:flex-row">
                    <Link
                        href="/riwayat-stok"
                        class="rounded-xl border border-slate-200 px-5 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing || !produks.length"
                        class="rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Transaksi' }}
                    </button>
                </div>

                <p v-if="!produks.length" class="text-sm text-amber-700">
                    Belum ada produk. Tambahkan produk terlebih dahulu sebelum mencatat transaksi.
                </p>
            </form>
        </div>
    </AppLayout>
</template>
