<script setup>

import { ref } from "vue";
const asset = (url) => {
    const app_url = "http://mobilekamm.ddns.net:8065/m_mlp"
    return `${app_url}/${url}`
}

const no_kontrak = ref("12345678")
const nama_konsumen = ref("Susi Susanti")
const nama_cabang = ref("Barat")
const nama_posko = ref("Jatimakmur")

const file_ttd = ref("ttd-placeholder.png")
const file_qrcode = ref("qrcode-placeholder.png")
const file_avatar = ref("avatar-placeholder.png")
</script>

<template>
    <div class="border border-gray-300 w-96 rounded-xl mx-auto my-2 relative overflow-hidden">
        <img :src="asset('storage/Kartu Anggota KAMM.png')" class="w-full">
        <div class="text-gray-800 absolute font-bold text-2xl top-20 left-32">{{ no_kontrak }}</div>
        <div class="text-gray-800 absolute text-xl inset-y-28 inset-x-32">{{ nama_konsumen }}</div>
        <div class="text-gray-800 absolute font-bold text-sm top-44 left-6">Cabang: {{ nama_cabang }}</div>
        <div class="text-gray-800 absolute font-bold text-sm inset-x-6 inset-y-48">Posko: {{ nama_posko }}</div>
        <img :src="asset(`storage/${file_qrcode}`)" class="w-20 border rounded-md absolute right-4 top-20">
        <img :src="asset(`storage/${file_ttd}`)" class="w-28 absolute right-4 top-44">
        <img :src="asset(`storage/${file_avatar}`)" class="w-20 absolute left-4 top-20">
    </div>
</template>