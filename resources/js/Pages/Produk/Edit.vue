
<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';

const props = defineProps({
    produk: {
        type: Object,
        required: true,
    },
});


const form = useForm({
    nama: props.produk.nama ?? '',
    kategori: props.produk.kategori ?? '',
    harga: props.produk.harga ?? '',
    stok: props.produk.stok ?? 0,
    stok_minimum: props.produk.stok_minimum ?? 5,
});


function submit() {
    form.put(`/produk/${props.produk.id}`);
}
</script>

<template>
    <AppLayout>
        <section class="mb-8">
            <Link href="/produk" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-blue-600">
                <span>←</span> Kembali ke produk
            </Link>
            <div class="mt-4">
                <p class="text-sm font-semibold text-blue-600">Manajemen produk</p>
                <h1 class="mt-2 text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                    Edit Produk
                </h1>
                <p class="mt-2 text-sm leading-6 text-slate-500">
                    Perbarui informasi produk #{{ produk.id }}.
                </p>
            </div>
        </section>

        <form @submit.prevent="submit" class="max-w-4xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-4 border-b border-slate-100 px-5 py-5 sm:px-8">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-6 w-6">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z" />
                    </svg>
                </div>
                <div>
                    <h2 class="font-bold text-slate-900">Informasi Produk</h2>
                    <p class="mt-1 text-xs text-slate-500">Ubah data yang ingin diperbarui.</p>
                </div>
            </div>

            <div class="grid gap-x-6 gap-y-6 p-5 sm:grid-cols-2 sm:p-8">
                <div class="sm:col-span-2">
                    <label for="nama" class="mb-2 block text-sm font-semibold text-slate-700">Nama Produk *</label>
                    <input
                        id="nama"
                        v-model="form.nama"
                        type="text"
                        required
                        maxlength="255"
                        class="w-full rounded-xl border bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        :class="form.errors.nama ? 'border-red-300' : 'border-slate-200'"
                    />
                    <p v-if="form.errors.nama" class="mt-2 text-sm text-red-600">{{ form.errors.nama }}</p>
                </div>

                <div>
                    <label for="kategori" class="mb-2 block text-sm font-semibold text-slate-700">Kategori *</label>
                    <input
                        id="kategori"
                        v-model="form.kategori"
                        type="text"
                        required
                        maxlength="255"
                        class="w-full rounded-xl border bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        :class="form.errors.kategori ? 'border-red-300' : 'border-slate-200'"
                    />
                    <p v-if="form.errors.kategori" class="mt-2 text-sm text-red-600">{{ form.errors.kategori }}</p>
                </div>

                <div>
                    <label for="harga" class="mb-2 block text-sm font-semibold text-slate-700">Harga Satuan *</label>
                    <div class="flex overflow-hidden rounded-xl border transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10" :class="form.errors.harga ? 'border-red-300' : 'border-slate-200'">
                        <span class="flex items-center border-r border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500">Rp</span>
                        <input
                            id="harga"
                            v-model="form.harga"
                            type="number"
                            min="0"
                            step="1"
                            required
                            class="min-w-0 flex-1 bg-white px-4 py-3 text-sm outline-none"
                        />
                    </div>
                    <p v-if="form.errors.harga" class="mt-2 text-sm text-red-600">{{ form.errors.harga }}</p>
                </div>

                <div class="sm:col-span-2">
                    <label for="stok" class="mb-2 block text-sm font-semibold text-slate-700">Jumlah Stok *</label>
                    <input
                        id="stok"
                        v-model="form.stok"
                        type="number"
                        min="0"
                        step="1"
                        required
                        class="w-full rounded-xl border bg-white px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
                        :class="form.errors.stok ? 'border-red-300' : 'border-slate-200'"
                    />
                    <p v-if="form.errors.stok" class="mt-2 text-sm text-red-600">{{ form.errors.stok }}</p>
                </div>
                
<div>
    <label class="mb-2 block text-sm font-semibold text-slate-700">
        Batas Minimum Stok
    </label>

    <input
        v-model.number="form.stok_minimum"
        type="number"
        min="0"
        max="1000000"
        required
        class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10"
        placeholder="Contoh: 5"
    />

    <p class="mt-1 text-xs text-slate-500">
        Peringatan akan muncul ketika stok mencapai batas ini.
    </p>

    <p
        v-if="form.errors.stok_minimum"
        class="mt-2 text-sm text-red-600"
    >
        {{ form.errors.stok_minimum }}
    </p>
</div>

            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-5 py-5 sm:flex-row sm:justify-end sm:px-8">
                <Link href="/produk" class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                    Batal
                </Link>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-600/15 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <svg v-if="form.processing" viewBox="0 0 24 24" fill="none" class="h-4 w-4 animate-spin">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3" />
                        <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                    </svg>
                    {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </button>
            </div>
        </form>
    </AppLayout>
</template>
