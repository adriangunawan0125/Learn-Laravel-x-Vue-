
<script setup>
import { reactive } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';

const props = defineProps({
    riwayat: { type: Object, required: true },
    filters: { type: Object, default: () => ({ jenis: '', search: '' }) },
    ringkasan: {
        type: Object,
        default: () => ({ totalMasuk: 0, totalKeluar: 0, jumlahTransaksi: 0 }),
    },
});

const page = usePage();

const filterForm = reactive({
    jenis: props.filters.jenis || '',
    search: props.filters.search || '',
});

function cari() {
    router.get('/riwayat-stok', {
        jenis: filterForm.jenis || undefined,
        search: filterForm.search || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}

function formatTanggal(tanggal) {
    if (!tanggal) return '-';

    const date = new Date(`${tanggal}T00:00:00`);

    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

function formatAngka(value) {
    return Number(value || 0).toLocaleString('id-ID');
}
</script>

<template>
    <AppLayout>
        <section class="mb-8 flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold text-blue-600">Kontrol persediaan</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Riwayat Barang
                </h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Pantau seluruh transaksi barang masuk dan keluar.
                </p>
            </div>

            <Link
                href="/riwayat-stok/create"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/15 transition hover:bg-blue-700"
            >
                <span class="text-lg">+</span>
                Catat Transaksi
            </Link>
        </section>

        <div
            v-if="page.props.flash?.success"
            class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
        >
            {{ page.props.flash.success }}
        </div>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Total Barang Masuk</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-xl font-bold text-emerald-600">↓</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">
                    {{ formatAngka(ringkasan.totalMasuk) }}
                    <span class="text-sm font-medium text-slate-400">unit</span>
                </p>
                <p class="mt-2 text-xs text-slate-400">Akumulasi semua transaksi masuk</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Total Barang Keluar</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-xl font-bold text-amber-600">↑</span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">
                    {{ formatAngka(ringkasan.totalKeluar) }}
                    <span class="text-sm font-medium text-slate-400">unit</span>
                </p>
                <p class="mt-2 text-xs text-slate-400">Akumulasi semua transaksi keluar</p>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Jumlah Transaksi</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
                            <path d="M8 4h11v16H5V7z" />
                            <path d="M8 4v4H5m4 4h7m-7 4h7" />
                        </svg>
                    </span>
                </div>
                <p class="mt-4 text-3xl font-bold text-slate-900">
                    {{ formatAngka(ringkasan.jumlahTransaksi) }}
                </p>
                <p class="mt-2 text-xs text-slate-400">Semua transaksi yang tercatat</p>
            </article>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 p-5 sm:p-6">
                <h2 class="font-bold text-slate-900">Daftar Transaksi</h2>
                <p class="mt-1 text-sm text-slate-500">Cari dan filter catatan pergerakan stok.</p>

                <form @submit.prevent="cari" class="mt-5 grid gap-3 sm:grid-cols-[1fr_190px_auto]">
                    <input
                        v-model="filterForm.search"
                        type="search"
                        placeholder="Cari nama produk..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    />

                    <select
                        v-model="filterForm.jenis"
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                    >
                        <option value="">Semua transaksi</option>
                        <option value="masuk">Barang masuk</option>
                        <option value="keluar">Barang keluar</option>
                    </select>

                    <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold sm:px-6">Tanggal</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Produk</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Jenis</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Jumlah</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Keterangan</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="item in riwayat.data" :key="item.id" class="transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500 sm:px-6">
                                {{ formatTanggal(item.tanggal) }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                <p class="font-semibold text-slate-800">{{ item.produk?.nama || 'Produk tidak tersedia' }}</p>
                                <p class="mt-1 text-xs text-slate-400">{{ item.produk?.kategori || '-' }}</p>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                                    :class="item.jenis === 'masuk' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'"
                                >
                                    <span>{{ item.jenis === 'masuk' ? '↓' : '↑' }}</span>
                                    {{ item.jenis === 'masuk' ? 'Barang masuk' : 'Barang keluar' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 font-bold text-slate-800 sm:px-6">
                                {{ formatAngka(item.jumlah) }} unit
                            </td>
                            <td class="min-w-48 px-5 py-4 text-slate-500 sm:px-6">
                                {{ item.keterangan || '—' }}
                            </td>
                        </tr>

                        <tr v-if="!riwayat.data.length">
                            <td colspan="5" class="px-6 py-16 text-center">
                                <p class="font-semibold text-slate-800">Belum ada riwayat transaksi</p>
                                <p class="mt-1 text-sm text-slate-500">Catat transaksi pertama untuk mulai melacak pergerakan stok.</p>
                                <Link href="/riwayat-stok/create" class="mt-4 inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                                    Catat Transaksi
                                </Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <p class="text-xs text-slate-500">
                    Menampilkan {{ riwayat.from ?? 0 }}–{{ riwayat.to ?? 0 }} dari {{ riwayat.total }} transaksi
                </p>

                <div class="flex flex-wrap gap-2">
                    <template v-for="(link, index) in riwayat.links" :key="index">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            preserve-scroll
                            class="rounded-lg border px-3 py-2 text-xs font-semibold transition"
                            :class="link.active ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                        >
                            <span v-if="index === 0">‹</span>
                            <span v-else-if="index === riwayat.links.length - 1">›</span>
                            <span v-else v-html="link.label"></span>
                        </Link>
                        <span
                            v-else
                            class="rounded-lg border border-slate-100 px-3 py-2 text-xs text-slate-300"
                        >
                            <span v-if="index === 0">‹</span>
                            <span v-else-if="index === riwayat.links.length - 1">›</span>
                            <span v-else v-html="link.label"></span>
                        </span>
                    </template>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
