<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    produk: Object,
});

const form = useForm({
    nama: props.produk.nama,
    kategori: props.produk.kategori,
    harga: props.produk.harga,
    stok: props.produk.stok,
});

const submit = () => {
    form.put(`/produk/${props.produk.id}`);
};
</script>

<template>
    <main class="container">
        <h1>Edit Produk</h1>

        <form @submit.prevent="submit">
            <div class="form-group">
                <label>Nama Produk</label>
                <input v-model="form.nama" type="text">
                <small v-if="form.errors.nama">{{ form.errors.nama }}</small>
            </div>

            <div class="form-group">
                <label>Kategori</label>
                <input v-model="form.kategori" type="text">
                <small v-if="form.errors.kategori">{{ form.errors.kategori }}</small>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input v-model="form.harga" type="number">
                <small v-if="form.errors.harga">{{ form.errors.harga }}</small>
            </div>

            <div class="form-group">
                <label>Stok</label>
                <input v-model="form.stok" type="number">
                <small v-if="form.errors.stok">{{ form.errors.stok }}</small>
            </div>

            <button type="submit" :disabled="form.processing">
                {{ form.processing ? 'Menyimpan...' : 'Update Produk' }}
            </button>
        </form>
    </main>
</template>

<style scoped>
.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 0 24px;
    font-family: Arial, sans-serif;
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 6px;
}

input {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
}

small {
    display: block;
    color: red;
    margin-top: 5px;
}

button {
    padding: 10px 18px;
    cursor: pointer;
}
</style>