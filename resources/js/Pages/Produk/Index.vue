
<script setup>
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';

const props = defineProps({
    produks: {
        type: Array,
        default: () => [],
    },
});

const search = ref('');
const page = usePage();

const filteredProduks = computed(() => {
    const keyword = search.value.trim().toLowerCase();

    if (!keyword) return props.produks;

    return props.produks.filter((produk) =>
        [produk.nama, produk.kategori, String(produk.id)]
            .some((value) => String(value ?? '').toLowerCase().includes(keyword))
    );
});

const totalStok = computed(() =>
    props.produks.reduce((sum, produk) => sum + Number(produk.stok || 0), 0)
);

const stokMenipis = computed(() =>
    props.produks.filter(
        (produk) =>
            Number(produk.stok) > 0 &&
            Number(produk.stok) <= Number(produk.stok_minimum ?? 5)
    ).length
);
function rupiah(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(Number(value) || 0);
}

function hapusProduk(produk) {
    if (!confirm(`Yakin ingin menghapus produk "${produk.nama}"?`)) return;

    router.delete(`/produk/${produk.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <AppLayout>
        <section class="mb-8 flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold text-blue-600">Manajemen inventaris</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Data Produk
                </h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Kelola seluruh produk, harga, kategori, dan ketersediaan stok.
                </p>
            </div>

            <Link href="/produk/create" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/15 transition hover:-translate-y-0.5 hover:bg-blue-700">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah Produk
            </Link>
        </section>

        <!-- Summary -->
        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Jenis Produk</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">
                    {{ props.produks.length.toLocaleString('id-ID') }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Produk terdaftar</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Total Unit Stok</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">
                    {{ totalStok.toLocaleString('id-ID') }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Akumulasi seluruh produk</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Stok Menipis</p>
                <p class="mt-3 text-2xl font-bold text-amber-600">
                    {{ stokMenipis.toLocaleString('id-ID') }}
                </p>
                <p class="mt-1 text-xs text-slate-400">Stok 5 unit atau kurang</p>
            </div>
        </section>

        <section class="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div v-if="page.props.flash?.success" class="mx-5 mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 sm:mx-6">
                {{ page.props.flash.success }}
            </div>

            <div class="flex flex-col gap-4 border-b border-slate-100 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <h2 class="font-bold text-slate-900">Semua Produk</h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Menampilkan {{ filteredProduks.length }} dari {{ props.produks.length }} produk
                    </p>
                </div>

                <div class="relative w-full sm:max-w-sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m16 16 4 4" />
                    </svg>
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Cari nama, kategori, atau ID..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                    />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50/80 text-[11px] uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-semibold sm:px-6">Produk</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Kategori</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Harga</th>
                            <th class="px-5 py-4 font-semibold sm:px-6">Stok</th>
                            <th class="px-5 py-4 text-right font-semibold sm:px-6">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="produk in filteredProduks" :key="produk.id" class="transition hover:bg-slate-50/70">
                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 font-bold text-blue-700">
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
    <div class="flex flex-col items-start gap-1.5">
        <span
            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
            :class="
                Number(produk.stok) === 0
                    ? 'bg-red-50 text-red-700'
                    : Number(produk.stok) <= Number(produk.stok_minimum ?? 5)
                        ? 'bg-amber-50 text-amber-700'
                        : 'bg-emerald-50 text-emerald-700'
            "
        >
            <span
                class="h-1.5 w-1.5 rounded-full"
                :class="
                    Number(produk.stok) === 0
                        ? 'bg-red-500'
                        : Number(produk.stok) <= Number(produk.stok_minimum ?? 5)
                            ? 'bg-amber-500'
                            : 'bg-emerald-500'
                "
            ></span>

            {{ produk.stok }} unit
        </span>

        <span class="text-xs text-slate-400">
            Minimum {{ produk.stok_minimum ?? 5 }} unit
        </span>

        <span
            v-if="Number(produk.stok) === 0"
            class="text-xs font-semibold text-red-600"
        >
            Stok Habis
        </span>

        <span
            v-else-if="Number(produk.stok) <= Number(produk.stok_minimum ?? 5)"
            class="text-xs font-semibold text-amber-600"
        >
            Stok Menipis
        </span>

        <span
            v-else
            class="text-xs font-semibold text-emerald-600"
        >
            Aman
        </span>
    </div>
</td>


                            <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                <div class="flex justify-end gap-2">
                                    <Link
                                        :href="`/produk/${produk.id}/edit`"
                                        class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        type="button"
                                        @click="hapusProduk(produk)"
                                        class="rounded-lg border border-red-100 px-3 py-2 text-xs font-semibold text-red-600 transition hover:border-red-200 hover:bg-red-50"
                                    >
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="!filteredProduks.length">
                            <td colspan="5" class="px-6 py-16 text-center">
                                <p class="font-semibold text-slate-700">
                                    {{ search ? 'Produk tidak ditemukan' : 'Belum ada produk' }}
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ search ? 'Coba kata kunci yang berbeda.' : 'Tambahkan produk pertama ke inventaris.' }}
                                </p>
                                <button v-if="search" type="button" @click="search = ''" class="mt-3 text-sm font-semibold text-blue-600 hover:text-blue-700">
                                    Hapus pencarian
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t border-slate-100 px-5 py-4 text-xs text-slate-400 sm:px-6">
                <span>{{ filteredProduks.length }} produk ditampilkan</span>
                <span>Inventaris</span>
            </div>
        </section>
    </AppLayout>
</template>
