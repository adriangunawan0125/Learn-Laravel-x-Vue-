
<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../layouts/AppLayout.vue';

defineProps({
    stats: {
        type: Object,
        default: () => ({
            totalProduk: 0,
            totalStok: 0,
            stokMenipis: 0,
        }),
    },
    produkTerbaru: {
        type: Array,
        default: () => [],
    },
});

function rupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value) || 0);
}
</script>

<template>
    <AppLayout>
        <!-- Page heading -->
        <section class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                    Ringkasan inventaris
                </div>
                <h1 class="mt-4 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Dashboard
                </h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Selamat datang! Pantau kondisi produk dan persediaanmu di sini.
                </p>
            </div>

            <Link
                href="/produk/create"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/15 transition hover:-translate-y-0.5 hover:bg-blue-700"
            >
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah Produk
            </Link>
        </section>

        <!-- Statistics -->
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Produk</p>
                        <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">
                            {{ Number(stats.totalProduk || 0).toLocaleString('id-ID') }}
                        </p>
                        <p class="mt-2 text-xs text-slate-400">Jenis produk terdaftar</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-6 w-6">
                            <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5z" />
                            <path d="m4.5 7.5 7.5 4.3 7.5-4.3M12 12v9" />
                        </svg>
                    </div>
                </div>
                <div class="mt-5 border-t border-slate-100 pt-4">
                    <Link href="/produk" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        Lihat data produk →
                    </Link>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Stok</p>
                        <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">
                            {{ Number(stats.totalStok || 0).toLocaleString('id-ID') }}
                        </p>
                        <p class="mt-2 text-xs text-slate-400">Unit dari seluruh produk</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-6 w-6">
                            <path d="M4 19V5m0 14h16" />
                            <rect x="7" y="11" width="3" height="5" rx="1" />
                            <rect x="12" y="7" width="3" height="9" rx="1" />
                            <rect x="17" y="4" width="3" height="12" rx="1" />
                        </svg>
                    </div>
                </div>
                <div class="mt-5 border-t border-slate-100 pt-4">
                    <span class="text-xs font-medium text-slate-400">Akumulasi jumlah persediaan</span>
                </div>
            </article>

            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md sm:p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Stok Menipis</p>
                        <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">
                            {{ Number(stats.stokMenipis || 0).toLocaleString('id-ID') }}
                        </p>
                        <p class="mt-2 text-xs text-slate-400">Produk dengan stok maksimal 5</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="h-6 w-6">
                            <path d="M12 3 2.8 20h18.4L12 3Z" />
                            <path d="M12 9v5m0 3h.01" />
                        </svg>
                    </div>
                </div>
                <div class="mt-5 border-t border-slate-100 pt-4">
                    <span class="text-xs font-medium" :class="Number(stats.stokMenipis) > 0 ? 'text-amber-600' : 'text-emerald-600'">
                        {{ Number(stats.stokMenipis) > 0 ? 'Perlu pemeriksaan stok' : 'Semua stok dalam batas aman' }}
                    </span>
                </div>
            </article>
        </section>

        <!-- Latest products -->
        <section class="mt-7 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <h2 class="font-bold text-slate-900">Produk Terbaru</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Daftar produk yang terakhir ditambahkan.
                    </p>
                </div>
                <Link href="/produk" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                    Lihat semua →
                </Link>
            </div>

            <div v-if="produkTerbaru.length" class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-[11px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold sm:px-6">Nama Produk</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Kategori</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Harga</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="produk in produkTerbaru" :key="produk.id" class="transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 font-bold text-blue-700">
                                        {{ produk.nama?.charAt(0)?.toUpperCase() || 'P' }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ produk.nama }}</p>
                                        <p class="mt-1 text-xs text-slate-400">ID #{{ produk.id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500 sm:px-6">
                                {{ produk.kategori }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-700 sm:px-6">
                                {{ rupiah(produk.harga) }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                                    :class="Number(produk.stok) <= 5 ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700'"
                                >
                                    {{ produk.stok }} unit
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else class="px-6 py-16 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="h-7 w-7">
                        <path d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5z" />
                        <path d="m4.5 7.5 7.5 4.3 7.5-4.3M12 12v9" />
                    </svg>
                </div>
                <h3 class="mt-4 font-bold text-slate-800">Belum ada produk</h3>
                <p class="mt-1 text-sm text-slate-500">Tambahkan produk pertama untuk mengisi inventaris.</p>
                <Link href="/produk/create" class="mt-5 inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                    Tambah Produk
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
