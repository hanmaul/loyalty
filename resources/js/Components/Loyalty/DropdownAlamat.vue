<script setup>

import axios from 'axios';
axios.defaults.baseURL = 'http://mobilekamm.ddns.net:8065/';
import { ref, onMounted, watch, reactive, watchEffect, onUpdated, onBeforeUpdate, onBeforeMount } from 'vue';

onMounted(()=>{
    getProvinsi()
})

const model = reactive({
    provinsi: '',
    kabupaten: '',
    kecamatan: '',
    kelurahan: '',
    kode_pos: ''
})

const params = defineProps(['token', 'custid'])

const daftarProvinsi = ref(false)
const getProvinsi = () => {
    daftarProvinsi.value = false
    axios.post(`fapi/provinsi/?key=${params.token}`
    ).then(res => {
        daftarProvinsi.value = res.data
    }).catch(err => {
        alert(`Gagal mengambil data Provinsi\n\nError: ${err}`)
    })
}

const daftarKabupaten = ref(false)
const getKabupaten = () => {
    daftarKabupaten.value = false
    const getBody = new FormData()
    getBody.append('provinsi', model.provinsi)
    axios.post(`fapi/kabupaten?key=${params.token}`, getBody).then(res => {
        daftarKabupaten.value = res.data
    }).catch(err => {
        alert(`Gagal mengambil data Kabupaten\n\nError: ${err}`)
    })
}
watch(() => model.provinsi, () => {
    getKabupaten()
})

const daftarKecamatan = ref(false)
const getKecamatan = () => {
    daftarKecamatan.value = false
    const getBody = new FormData()
    getBody.append('kabupaten', model.kabupaten)
    axios.post(`fapi/kecamatan?key=${params.token}`, getBody).then(res => {
        daftarKecamatan.value = res.data
    }).catch(err => {
        alert(`Gagal mengambil data Kecamatan\n\nError: ${err}`)
    })
}
watch(() => [model.provinsi, model.kabupaten], () => {
    getKecamatan()
})

const daftarKelurahan = ref(false)
const getKelurahan = () => {
    daftarKelurahan.value = false
    const getBody = new FormData()
    getBody.append('kabupaten', model.kabupaten)
    getBody.append('kecamatan', model.kecamatan)
    axios.post(`fapi/kelurahan?key=${params.token}`, getBody).then(res => {
        daftarKelurahan.value = res.data
    }).catch(err => {
        alert(`Gagal mengambil data Kelurahan\n\nError: ${err}`)
    })
}
watch(() => [model.kabupaten, model.kecamatan], () => {
    getKelurahan()
})
const temp = ref(false)
const getKodePos = () => {
    const getBody = new FormData()
    getBody.append('kabupaten', model.kabupaten)
    getBody.append('kecamatan', model.kecamatan)
    getBody.append('kelurahan', model.kelurahan)
    axios.post(`fapi/kodeposna?key=${params.token}`, getBody).then(res => {
        temp.value = res.data
        model.kode_pos = temp.value.map((m) => m.KODE_POS)
    }).catch(err => {
        alert(`Gagal mengambil Kode Pos\n\nError: ${err}`)
    })
}
watch(() => [model.kabupaten, model.kecamatan, model.kelurahan], () => {
    getKodePos()
})

</script>
<template>
    <div>
        <label for="provinsi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provinsi</label>
        <select id="provinsi" v-model="model.provinsi"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option value="" disabled selected>Pilih Provinsi</option>
            <option :value="data.PROPINSI" v-for="(data, index) in daftarProvinsi" :key="index">
                {{ data.PROPINSI }}
            </option>
        </select>
    </div>
    <div>
        <label for="kabupaten" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kota
            /
            Kabupaten</label>
        <select id="kabupaten" v-model="model.kabupaten"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option value="" disabled selected>Pilih Kabupaten</option>
            <option :value="data.KABUPATEN" v-for="(data, index) in daftarKabupaten" :key="index">
                {{ data.KABUPATEN }}
            </option>
        </select>
    </div>
    <div>
        <label for="kecamatan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kecamatan</label>
        <select id="kecamatan" v-model="model.kecamatan"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option value="" disabled selected>Pilih Kecamatan</option>
            <option :value="data.KECAMATAN" v-for="(data, index) in daftarKecamatan" :key="index">
                {{ data.KECAMATAN }}
            </option>
        </select>
    </div>
    <div>
        <label for="kelurahan" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelurahan</label>
        <select id="kelurahan" v-model="model.kelurahan"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            <option value="" disabled selected>Pilih Kelurahan</option>
            <option :value="data.KELURAHAN" v-for="data in daftarKelurahan">
                {{ data.KELURAHAN }}
            </option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label for="kode_pos" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
            Pos</label>
        <input type="text" name="name" id="kode_pos" v-model="model.kode_pos"
            class="cursor-not-allowed border bg-gray-100 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            disabled>
    </div>
</template>