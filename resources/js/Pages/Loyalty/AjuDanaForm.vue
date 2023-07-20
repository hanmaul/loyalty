<script setup>
import BannerForm from '@/Components/Loyalty/BannerForm.vue';
import axios from 'axios';
import { ref, onMounted, watch, reactive, computed } from 'vue';

onMounted(() => {
    getProvinsi()
    getJaminan()
    // format()
})

// ------------------------------------  Dropdown Alamat ------------------------------------------------------------------------------------------------------------------------------------------
const model = reactive({
    no_referensi:'',
    nama_konsumen:'',
    alamat:'',
    provinsi: '',
    kabupaten: '',
    kecamatan: '',
    kelurahan: '',
    kode_pos: '',
    no_polisi:'',
    jaminan: '',
    merk: '',
    tipe: '',
    tahun: '',
    dana: '',
    tenor: '',
    cicilan: ''
})

const daftarProvinsi = ref(false)
const getProvinsi = () => {
    daftarProvinsi.value = false
    axios.post('http://mobilekamm.ddns.net:8065/fapi/provinsi/?key=d41d8cd98f00b204e9800998ecf8427e'
    ).then(res => {
        const data = res.data
        daftarProvinsi.value = data
    }).catch(err => {
        alert(`Gagal mengambil data Provinsi\n\nError: ${err}`)
    })
}

const daftarKabupaten = ref(false)
const getKabupaten = () => {
    daftarKabupaten.value = false
    const getBody = new FormData()
    getBody.append('provinsi', model.provinsi)
    axios.post('http://mobilekamm.ddns.net:8065/fapi/kabupaten?key=d41d8cd98f00b204e9800998ecf8427e', getBody).then(res => {
        const data = res.data
        daftarKabupaten.value = data
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
    axios.post('http://mobilekamm.ddns.net:8065/fapi/kecamatan?key=d41d8cd98f00b204e9800998ecf8427e', getBody).then(res => {
        const data = res.data
        daftarKecamatan.value = data
    }).catch(err => {
        alert(`Gagal mengambil data Kecamatan\n\nError: ${err}`)
    })
}
watch(() => model.kabupaten, () => {
    getKecamatan()
})

const daftarKelurahan = ref(false)
const getKelurahan = () => {
    daftarKelurahan.value = false
    const getBody = new FormData()
    getBody.append('kabupaten', model.kabupaten)
    getBody.append('kecamatan', model.kecamatan)
    axios.post('http://mobilekamm.ddns.net:8065/fapi/kelurahan?key=d41d8cd98f00b204e9800998ecf8427e', getBody).then(res => {
        const data = res.data
        daftarKelurahan.value = data
    }).catch(err => {
        alert(`Gagal mengambil data Kelurahan\n\nError: ${err}`)
    })
}
watch(() => [model.kabupaten, model.kecamatan], () => {
    getKelurahan()
})

const getKodePos = () => {
    const getBody = new FormData()
    getBody.append('kabupaten', model.kabupaten)
    getBody.append('kecamatan', model.kecamatan)
    getBody.append('kelurahan', model.kelurahan)
    axios.post('http://mobilekamm.ddns.net:8065/fapi/kodeposna?key=d41d8cd98f00b204e9800998ecf8427e', getBody).then(res => {
        const data = res.data
        model.kode_pos = data
    }).catch(err => {
        alert(`Gagal mengambil Kode Pos\n\nError: ${err}`)
    })
}
watch(() => [model.kabupaten, model.kecamatan, model.kelurahan], () => {
    getKodePos()
})

// ------------------------------------  Dropdown Kendaraan -------------------------------------------------------------------------------------------------------------

const daftarJaminan = ref(false)
const getJaminan = () => {
    daftarJaminan.value = false
    axios.post('http://mobilekamm.ddns.net:8065/fapi/jeniskendaraan/?key=d41d8cd98f00b204e9800998ecf8427e'
    ).then(res => {
        const data = res.data
        daftarJaminan.value = data
    }).catch(err => {
        alert(`Gagal mengambil Data Jaminan\n\nError: ${err}`)
    })
}

const daftarMerk = ref(false)
const getMerk = () => {
    daftarMerk.value = false
    const getBody = new FormData()
    getBody.append('kendaraan', model.jaminan)
    axios.post('http://mobilekamm.ddns.net:8065/fapi/tipekendaraan?key=d41d8cd98f00b204e9800998ecf8427e', getBody
    ).then(res => {
        const data = res.data
        daftarMerk.value = data
    }).catch(err => {
        alert(`Gagal mengambil Data Merk\n\nError: ${err}`)
    })
}
watch(() => model.jaminan, () => {
    getMerk()
})

const daftarTipe = ref(false)
const getTipe = () => {
    daftarTipe.value = false
    const getBody = new FormData()
    getBody.append('kendaraan', model.jaminan)
    getBody.append('merkmotor', model.merk)
    axios.post('http://mobilekamm.ddns.net:8065/fapi/tipemotor?key=d41d8cd98f00b204e9800998ecf8427e', getBody
    ).then(res => {
        const data = res.data
        daftarTipe.value = data
    }).catch(err => {
        alert(`Gagal mengambil Data Tipe\n\nError: ${err}`)
    })
}
watch(() => [model.jaminan, model.merk], () => {
    getTipe()
})

const getTahun = () => {
    model.tahun = model.tipe.substring(model.tipe.length - 4)
}
watch(() => model.tipe, () => {
    getTahun()
})

const daftarDana = ref(false)
const getDana = () => {
    daftarDana.value = false
    const getBody = new FormData()
    getBody.append('kendaraan', model.jaminan)
    getBody.append('merkmotor', model.merk)
    getBody.append('tipemotor', model.tipe)
    getBody.append('tahun', model.tahun)
    axios.post('http://mobilekamm.ddns.net:8065/fapi/danamotor?key=d41d8cd98f00b204e9800998ecf8427e', getBody
    ).then(res => {
        const data = res.data
        daftarDana.value = data
        console.log(daftarDana.value);
    }).catch(err => {
        alert(`Gagal mengambil Data Dana\n\nError:${err}`)
    })
}
watch(() => [model.jaminan, model.merk, model.tipe, model.tahun], () => {
    getDana()
})

const daftarTenor = ref(false)
const getTenor = () => {
    daftarTenor.value = false
    const getBody = new FormData()
    getBody.append('danamotor', model.dana)
    axios.post('http://mobilekamm.ddns.net:8065/fapi/tenorajuan?key=d41d8cd98f00b204e9800998ecf8427e', getBody
    ).then(res => {
        const data = res.data
        daftarTenor.value = data
    }).catch(err => {
        alert(`Gagal mengambil Data Tenor\n\nError:${err}`)
    })
}
watch(() => model.dana, () => {
    getTenor()
})

const getCicilan = () => {
    const getBody = new FormData()
    getBody.append('danamotor', model.dana)
    getBody.append('tenormotor', model.tenor)
    axios.post('http://mobilekamm.ddns.net:8065/fapi/cicilanajuan?key=d41d8cd98f00b204e9800998ecf8427e', getBody
    ).then(res => {
        const data = res.data
        model.cicilan = data
    }).catch(err => {
        alert(`Gagal mengambil Data Cicilan\n\nError:${err}`)
    })
}
watch(() => [model.dana, model.tenor], () => {
    getCicilan()
})

// const props = defineProps(['data.Angssuran'])
// const format = () => {
//     props.data.Angssuran.replace(/\B(?=(\d{3})+(?!\d))/g, ",").substring(0,data.Angssuran.length - 0) 
// }

// ------------------------------------  Simpan Data -------------------------------------------------------------------------------------------------------------
// const simpanData = () => {
//     const getBody = new FormData()
//     getBody.append('')
//     axios.post('',getBody
//     ).then(res=> {
//         const data = res.data
//         console.log(`Data berhasil disimpan`);
//     })
// }
</script>
<template>
    <BannerForm></BannerForm>
    <div>
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Isi Formulir</h2>
                <form action="#">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No.
                                Referensi</label>
                            <input type="text" name="name" id="name"
                                v-model="model.no_referensi"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="ketik no ref manual di sini" required="">
                        </div>
                        <div class="w-full">
                            <label for="brand" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                Konsumen</label>
                            <input type="text" name="brand" id="brand"
                                v-model="model.nama_konsumen"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Isi sesuai dengan KTP" required="">
                        </div>
                        <div class="w-full">
                            <label for="brand"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                            <input type="text" name="brand" id="brand"
                                v-model="model.alamat"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Isi sesuai domisili" required="">
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provinsi</label>
                            <select id="category" v-model="model.provinsi"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Provinsi</option>
                                <option :value="data.PROPINSI" v-for="(data, index) in daftarProvinsi" :key="index">
                                    {{ data.PROPINSI }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="category" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kota
                                /
                                Kabupaten</label>
                            <select id="category" v-model="model.kabupaten"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Kabupaten</option>
                                <option :value="data.KABUPATEN" v-for="(data, index) in daftarKabupaten" :key="index">
                                    {{ data.KABUPATEN }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kecamatan</label>
                            <select id="category" v-model="model.kecamatan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Kecamatan</option>
                                <option :value="data.KECAMATAN" v-for="(data, index) in daftarKecamatan" :key="index">
                                    {{ data.KECAMATAN }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelurahan</label>
                            <select id="category" v-model="model.kelurahan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Kelurahan</option>
                                <option :value="data.KELURAHAN" v-for="data in daftarKelurahan">
                                    {{ data.KELURAHAN }}
                                </option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode
                                Pos</label>
                            <template v-if="model.kelurahan === ''">
                                <input type="text" name="name" id="name"
                                    class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    disabled>
                            </template>
                            <template v-else>
                                <template v-for="data in model.kode_pos">
                                    <input type="text" name="name" id="name" v-bind:value="data.KODE_POS"
                                        class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        disabled>
                                </template>
                            </template>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No.
                                Polisi</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required="">
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jaminan</label>
                            <select id="category" v-model="model.jaminan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Jaminan</option>
                                <option :value="data.Keteranga" v-for="data in daftarJaminan">{{ data.Keteranga }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Merk</label>
                            <select id="category" v-model="model.merk"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Merk</option>
                                <option :value="data.Type" v-for="data in daftarMerk">{{ data.Type }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                            <select id="category" v-model="model.tipe"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Tipe</option>
                                <option :value="data.TYPE" v-for="data in daftarTipe">{{ data.TYPE }}</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                            <input type="text" name="name" id="name" :value="model.tahun"
                                class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                disabled>
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dana</label>
                            <select id="category" v-model="model.dana"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option disabled selected>Pilih Dana</option>
                                <option :value="data.Dana_cair" v-for="data in daftarDana">{{
                                    data.Dana_cair.replace(/\B(?=(\d{3})+(?!\d))/g, ",").substring(0,
                                        data.Dana_cair.length - 0) }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="category"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tenor</label>
                            <select id="category" v-model="model.tenor"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option disabled selected>Pilih Tenor</option>
                                <option :value="data.Tenor" v-for="data in daftarTenor">{{
                                    data.Tenor.substring(0, data.Tenor.length - 2) }}</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cicilan</label>
                            <template v-if="model.tenor === ''">
                                <input type="text" name="name" id="name"
                                    class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    disabled>
                            </template>
                            <template v-else>
                                <template v-for="data in model.cicilan">
                                    <input type="text" name="name" id="cicilan" v-bind:value="data.Angssuran"
                                        class="cursor-not-allowed bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        disabled>
                                </template>
                            </template>
                        </div>
                    </div>
                    <div class="pt-6 pb-14">
                        <button type="submit"
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">SIMPAN</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</template>