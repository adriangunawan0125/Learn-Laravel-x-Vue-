
<script setup>
import { ref } from 'vue';

const nama = ref('Teman');
</script>

<template>
    <main style="font-family: Arial; padding: 40px;">
        <h1>Dashboard Inventaris 🚀</h1>
        <p>Selamat datang di aplikasi Laravel + Vue!</p>

        <input v-model="nama" placeholder="Nama kamu">

        <h2>Halo, {{ nama }}!</h2>
    </main>
</template>
