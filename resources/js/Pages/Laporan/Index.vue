
<script setup>
import { computed, reactive, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';

const props = defineProps({
    transaksi: {
        type: Array,
        default: () => [],
    },
    filters: {
        type: Object,
        default: () => ({
            tanggal_mulai: '',
            tanggal_akhir: '',
            jenis: '',
        }),
    },
    ringkasan: {
        type: Object,
        default: () => ({
            totalProduk: 0,
            totalStok: 0,
            totalMasuk: 0,
            totalKeluar: 0,
            jumlahTransaksi: 0,
            nilaiPersediaan: 0,
        }),
    },
});

const form = reactive({
    tanggal_mulai: props.filters.tanggal_mulai ?? '',
    tanggal_akhir: props.filters.tanggal_akhir ?? '',
    jenis: props.filters.jenis ?? '',
});

watch(
    () => props.filters,
    (filters) => {
        form.tanggal_mulai = filters.tanggal_mulai ?? '';
        form.tanggal_akhir = filters.tanggal_akhir ?? '';
        form.jenis = filters.jenis ?? '';
    },
    { deep: true },
);

// Satu query filter digunakan untuk PDF dan CSV.
const filterQuery = computed(() => {
    const params = new URLSearchParams();

    Object.entries(form).forEach(([key, value]) => {
        if (value) params.set(key, value);
    });

    return params.toString();
});

const exportUrl = computed(() =>
    '/laporan/export-csv' +
    (filterQuery.value ? `?${filterQuery.value}` : '')
);

const exportPdfUrl = computed(() =>
    '/laporan/export-pdf' +
    (filterQuery.value ? `?${filterQuery.value}` : '')
);

function terapkanFilter() {
    router.get('/laporan', { ...form }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function resetFilter() {
    form.tanggal_mulai = '';
    form.tanggal_akhir = '';
    form.jenis = '';

    router.get('/laporan', {}, {
        preserveState: true,
        preserveScroll: true,
    });
}

function rupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value) || 0);
}

function tanggalIndonesia(value) {
    if (!value) return '-';

    const tanggal = String(value).slice(0, 10);
    const [tahun, bulan, hari] = tanggal.split('-');

    return `${hari}/${bulan}/${tahun}`;
}
</script>

<template>
    <AppLayout>
        <div class="space-y-7">
            <!-- Judul halaman -->
            <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
                <div>
                    <p class="text-sm font-semibold text-blue-600">
                        Manajemen inventaris
                    </p>

                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                        Laporan Inventaris
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        Pantau persediaan dan riwayat transaksi barang masuk dan keluar.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2 print:hidden">
                    <a
                        :href="exportPdfUrl"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path d="M12 3v12m-5-5 5 5 5-5M5 17v4h14v-4" />
                        </svg>
                        Unduh PDF
                    </a>

                    <a
                        :href="exportUrl"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-4 w-4">
                            <path d="M12 3v12m-5-5 5 5 5-5M5 17v4h14v-4" />
                        </svg>
                        Ekspor Excel (csv)
                    </a>
                </div>
            </section>

            <!-- Ringkasan -->
            <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Total Produk</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">
                        {{ Number(props.ringkasan.totalProduk || 0).toLocaleString('id-ID') }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Jenis produk terdaftar</p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Total Unit Stok</p>
                    <p class="mt-3 text-2xl font-bold text-slate-900">
                        {{ Number(props.ringkasan.totalStok || 0).toLocaleString('id-ID') }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Persediaan saat ini</p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Barang Masuk</p>
                    <p class="mt-3 text-2xl font-bold text-emerald-600">
                        +{{ Number(props.ringkasan.totalMasuk || 0).toLocaleString('id-ID') }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Unit sesuai filter</p>
                </article>

                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Barang Keluar</p>
                    <p class="mt-3 text-2xl font-bold text-amber-600">
                        -{{ Number(props.ringkasan.totalKeluar || 0).toLocaleString('id-ID') }}
                    </p>
                    <p class="mt-1 text-xs text-slate-400">Unit sesuai filter</p>
                </article>
            </section>

            <!-- Nilai persediaan -->
            <section class="flex flex-col justify-between gap-3 rounded-2xl border border-blue-100 bg-blue-50/70 p-5 sm:flex-row sm:items-center">
                <div>
                    <p class="text-sm font-medium text-blue-800">
                        Estimasi Nilai Persediaan Saat Ini
                    </p>
                    <p class="mt-2 text-2xl font-bold text-slate-900">
                        {{ rupiah(props.ringkasan.nilaiPersediaan) }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">
                        Harga satuan dikalikan stok saat ini.
                    </p>
                </div>

                <div class="text-sm text-slate-600">
                    {{ Number(props.ringkasan.jumlahTransaksi || 0).toLocaleString('id-ID') }}
                    transaksi sesuai filter
                </div>
            </section>

            <!-- Filter laporan -->
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 print:hidden">
                <div class="mb-5">
                    <h2 class="font-bold text-slate-900">Filter Laporan</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Pilih rentang tanggal dan jenis transaksi.
                    </p>
                </div>

                <form
                    class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"
                    @submit.prevent="terapkanFilter"
                >
                    <div>
                        <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-slate-700">
                            Tanggal Mulai
                        </label>
                        <input
                            id="tanggal_mulai"
                            v-model="form.tanggal_mulai"
                            type="date"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        />
                    </div>

                    <div>
                        <label for="tanggal_akhir" class="mb-2 block text-sm font-medium text-slate-700">
                            Tanggal Akhir
                        </label>
                        <input
                            id="tanggal_akhir"
                            v-model="form.tanggal_akhir"
                            type="date"
                            :min="form.tanggal_mulai || undefined"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        />
                    </div>

                    <div>
                        <label for="jenis" class="mb-2 block text-sm font-medium text-slate-700">
                            Jenis Transaksi
                        </label>
                        <select
                            id="jenis"
                            v-model="form.jenis"
                            class="w-full rounded-xl border border-slate-200 bg-white px-3 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        >
                            <option value="">Semua Transaksi</option>
                            <option value="masuk">Barang Masuk</option>
                            <option value="keluar">Barang Keluar</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
                        >
                            Terapkan
                        </button>

                        <button
                            type="button"
                            @click="resetFilter"
                            class="rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50"
                        >
                            Reset
                        </button>
                    </div>
                </form>
            </section>

            <!-- Detail transaksi -->
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col justify-between gap-2 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:px-6">
                    <div>
                        <h2 class="font-bold text-slate-900">Detail Transaksi</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Riwayat barang masuk dan keluar sesuai filter.
                        </p>
                    </div>

                    <span class="text-sm text-slate-500">
                        {{ props.transaksi.length }} baris data
                    </span>
                </div>

                <div v-if="props.transaksi.length" class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-4 font-semibold">Tanggal</th>
                                <th class="px-5 py-4 font-semibold">Produk</th>
                                <th class="px-5 py-4 font-semibold">Kategori</th>
                                <th class="px-5 py-4 font-semibold">Jenis</th>
                                <th class="px-5 py-4 text-right font-semibold">Jumlah</th>
                                <th class="px-5 py-4 font-semibold">Keterangan</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="item in props.transaksi"
                                :key="item.id"
                                class="transition hover:bg-slate-50/70"
                            >
                                <td class="whitespace-nowrap px-5 py-4 text-slate-600">
                                    {{ tanggalIndonesia(item.tanggal) }}
                                </td>

                                <td class="px-5 py-4 font-semibold text-slate-800">
                                    {{ item.produk?.nama ?? 'Produk dihapus' }}
                                </td>

                                <td class="px-5 py-4 text-slate-500">
                                    {{ item.produk?.kategori ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="rounded-full px-3 py-1.5 text-xs font-semibold"
                                        :class="item.jenis === 'masuk'
                                            ? 'bg-emerald-50 text-emerald-700'
                                            : 'bg-amber-50 text-amber-700'"
                                    >
                                        {{ item.jenis === 'masuk' ? 'Barang Masuk' : 'Barang Keluar' }}
                                    </span>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right font-bold text-slate-800">
                                    {{ Number(item.jumlah).toLocaleString('id-ID') }} unit
                                </td>

                                <td class="max-w-64 px-5 py-4 text-slate-500">
                                    {{ item.keterangan || '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="px-6 py-14 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-6 w-6">
                            <path d="M7 3h7l5 5v13H7z" />
                            <path d="M14 3v6h5M10 14h6M10 18h6" />
                        </svg>
                    </div>

                    <h3 class="mt-4 font-semibold text-slate-800">
                        Belum ada transaksi
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Transaksi akan tampil setelah barang masuk atau keluar dicatat.
                    </p>

                    <Link
                        href="/riwayat-stok/create"
                        class="mt-5 inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 print:hidden"
                    >
                        Catat Transaksi
                    </Link>
                </div>
            </section>

            <p class="text-xs text-slate-400 print:hidden">
                Laporan ini menggunakan transaksi yang tersimpan di sistem inventaris.
            </p>
        </div>
    </AppLayout>
</template>
