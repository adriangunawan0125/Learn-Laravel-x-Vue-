
<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const mobileMenuOpen = ref(false);

const menus = [
    { label: 'Dashboard', href: '/', icon: 'dashboard' },
    { label: 'Data Produk', href: '/produk', icon: 'products' },
    { label: 'Riwayat Stok', href: '/riwayat-stok', icon: 'history' },
    { label: 'Laporan', href: '/laporan', icon: 'history' },
];

const currentTitle = computed(() => {
    if (page.url.startsWith('/riwayat-stok')) {
        return 'Riwayat Stok';
    }

    if (page.url.startsWith('/produk/create')) {
        return 'Tambah Produk';
    }

    if (page.url.includes('/edit')) {
        return 'Edit Produk';
    }

    if (page.url.startsWith('/produk')) {
        return 'Data Produk';
    }

    return 'Dashboard';
});

function isActive(href) {
    if (href === '/') {
        return page.url === '/';
    }

    return page.url.startsWith(href);
}

function closeMobileMenu() {
    mobileMenuOpen.value = false;
}
</script>

<template>
    <div class="min-h-screen bg-slate-50 text-slate-800">
        <!-- Overlay untuk sidebar mobile -->
        <div
            v-if="mobileMenuOpen"
            class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm lg:hidden"
            @click="closeMobileMenu"
        ></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col border-r border-slate-200 bg-white transition-transform duration-200 ease-in-out lg:translate-x-0"
            :class="
                mobileMenuOpen
                    ? 'translate-x-0'
                    : '-translate-x-full lg:translate-x-0'
            "
        >
            <!-- Brand -->
            <div
                class="flex h-[76px] shrink-0 items-center justify-between border-b border-slate-100 px-6"
            >
                <Link
                    href="/"
                    class="flex items-center gap-3"
                    @click="closeMobileMenu"
                >
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-xl font-black text-white shadow-lg shadow-blue-600/20"
                    >
                        I
                    </div>

                    <div>
                        <p class="text-base font-bold tracking-tight text-slate-900">
                            Inventaris
                        </p>
                        <p
                            class="mt-0.5 text-[11px] font-medium tracking-wide text-slate-400"
                        >
                            MANAGEMENT SYSTEM
                        </p>
                    </div>
                </Link>

                <button
                    type="button"
                    class="rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 lg:hidden"
                    aria-label="Tutup menu"
                    @click="closeMobileMenu"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        class="h-5 w-5"
                    >
                        <path d="m18 6-12 12M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <div class="px-4 pt-7">
                <p
                    class="mb-3 px-3 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400"
                >
                    Menu utama
                </p>

                <nav class="space-y-1.5">
                    <Link
                        v-for="menu in menus"
                        :key="menu.href"
                        :href="menu.href"
                        @click="closeMobileMenu"
                        class="group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-semibold transition duration-150"
                        :class="
                            isActive(menu.href)
                                ? 'bg-blue-50 text-blue-700'
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'
                        "
                    >
                        <!-- Icon container -->
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg transition"
                            :class="
                                isActive(menu.href)
                                    ? 'bg-blue-100 text-blue-700'
                                    : 'bg-slate-100 text-slate-500 group-hover:bg-slate-200'
                            "
                        >
                            <!-- Dashboard icon -->
                            <svg
                                v-if="menu.icon === 'dashboard'"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-5 w-5"
                            >
                                <rect x="3" y="3" width="8" height="8" rx="2" />
                                <rect x="13" y="3" width="8" height="5" rx="2" />
                                <rect x="13" y="10" width="8" height="11" rx="2" />
                                <rect x="3" y="13" width="8" height="8" rx="2" />
                            </svg>

                            <!-- Products icon -->
                            <svg
                                v-else-if="menu.icon === 'products'"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-5 w-5"
                            >
                                <path
                                    d="M4 7.5 12 3l8 4.5v9L12 21l-8-4.5z"
                                />
                                <path
                                    d="m4.5 7.5 7.5 4.3 7.5-4.3M12 12v9"
                                />
                            </svg>

                            <!-- Stock history icon -->
                            <svg
                                v-else
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="h-5 w-5"
                            >
                                <path
                                    d="M3 12a9 9 0 1 0 3-6.7L3 8"
                                />
                                <path d="M3 3v5h5" />
                                <path d="M12 7v5l3 2" />
                            </svg>
                        </span>

                        <span>{{ menu.label }}</span>

                        <span
                            v-if="isActive(menu.href)"
                            class="ml-auto h-2 w-2 shrink-0 rounded-full bg-blue-600"
                        ></span>
                    </Link>
                </nav>
            </div>

            <!-- Sidebar bottom -->
            <div class="mt-auto p-4">
                <div
                    class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50 to-white p-4"
                >
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-blue-600 shadow-sm"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="h-5 w-5"
                        >
                            <path
                                d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2L12 17.3l-5.6 2.9 1.1-6.2L3 9.6l6.2-.9L12 3Z"
                            />
                        </svg>
                    </div>

                    <p class="mt-3 text-sm font-bold text-slate-800">
                        Kelola lebih mudah
                    </p>

                    <p class="mt-1 text-xs leading-5 text-slate-500">
                        Pantau data dan stok produk melalui satu dashboard.
                    </p>
                </div>

                <p class="mt-4 text-center text-[11px] text-slate-400">
                    Inventaris System · v1.0
                </p>
            </div>
        </aside>

        <!-- Main content -->
        <div class="min-h-screen lg:pl-72">
            <!-- Header -->
            <header
                class="sticky top-0 z-30 flex h-[76px] items-center justify-between border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-xl sm:px-8"
            >
                <div class="flex min-w-0 items-center gap-3">
                    <!-- Mobile menu button -->
                    <button
                        type="button"
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-50 lg:hidden"
                        aria-label="Buka menu"
                        @click="mobileMenuOpen = true"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            class="h-5 w-5"
                        >
                            <path d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <p
                            class="hidden text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400 sm:block"
                        >
                            WORKSPACE / INVENTARIS
                        </p>

                        <p
                            class="truncate text-sm font-bold text-slate-800 sm:mt-1"
                        >
                            {{ currentTitle }}
                        </p>
                    </div>
                </div>

                <!-- Administrator profile -->
                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-bold text-slate-800">
                            Administrator
                        </p>
                        <p class="mt-0.5 text-xs text-slate-400">
                            Pengelola inventaris
                        </p>
                    </div>

                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-white bg-blue-100 text-sm font-bold text-blue-700 shadow-sm ring-1 ring-blue-100"
                    >
                        A
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main
                class="mx-auto w-full max-w-[1600px] p-4 sm:p-6 lg:p-8"
            >
                <slot />
            </main>

            <!-- Footer -->
            <footer class="px-6 pb-6 text-center text-xs text-slate-400">
                © {{ new Date().getFullYear() }} Inventaris Management System
            </footer>
        </div>
    </div>
</template>
