<script setup>
import BannerForm from '@/Components/Loyalty/BannerForm.vue';
import axios from 'axios';
axios.defaults.baseURL = 'http://mobilekamm.ddns.net:8065/';
import { ref, onMounted, watch, reactive, watchEffect, onUpdated, onBeforeUpdate, onBeforeMount, vModelSelect } from 'vue';
import Swal from 'sweetalert2';

onMounted(() => {
    getCust()
    getProvinsi()
    getJaminan()
})

//v-model
const model = reactive({
    no_referensi: '',
    nama_konsumen: '',
    alamat: '',
    provinsi: '',
    kabupaten: '',
    kecamatan: '',
    kelurahan: '',
    kode_pos: '',
    no_polisi: '',
    jaminan: '',
    merk: '',
    tipe: '',
    tahun: '',
    dana: '',
    tenor: '',
    cicilan: ''
})

// ------------------------------------  Get Data ------------------------------------------------------------------------------------------------------------------------------------------

const params = defineProps(
    ['token', 'custid']
)

const dataCust = ref({})
const getCust = () => {
    dataCust.value = {}
    const getBody = new FormData()
    getBody.append('custid', params.custid)
    axios.post(`fapi_uat/getajuann?key=${params.token}`, getBody
    ).then(res => {
        dataCust.value = res.data
        showCust()
    }).catch(err => {
        alert(`Gagal mengambil data customer!\n\nError: ${err}`)
    })
}

const showCust = () => {
    dataCust.value.map((m) => {
        model.no_referensi = m.Ref_code,
            model.nama_konsumen = m.nama_lengkap,
            model.alamat = m.alamat_lengkap,
            model.provinsi = m.provinsi,
            model.kabupaten = m.kabupaten,
            model.kecamatan = m.kecamatan,
            model.kelurahan = m.kelurahan,
            model.kode_pos = m.kode_pos,
            model.no_polisi = m.no_polisi,
            model.jaminan = m.Kendaraan,
            model.merk = m.Merk,
            model.tipe = m.Type,
            model.tenor = m.Tenor
    })
    dataCust.value.map((m) => {
        if (m.Dana == null) {
            model.dana = m.Dana
        } else if (m.Dana.includes(".0")) {
            model.dana = m.Dana.replace(/\B(?=(\d{3})+(?!\d))/g, ",").slice(0, - 2)
        } else {
            model.dana = m.Dana.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }
    })
}

// watchEffect(() => {
//     if (model.dana.includes(".0")) {
//         model.dana = model.dana.slice(0, - 2)
//     }
// })

// ------------------------------------  Dropdown Alamat ------------------------------------------------------------------------------------------------------------------------------------------

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
watch(() => model.kabupaten, () => {
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

// ------------------------------------  Dropdown Kendaraan -------------------------------------------------------------------------------------------------------------

const daftarJaminan = ref(false)
const getJaminan = () => {
    daftarJaminan.value = false
    axios.post(`fapi/jeniskendaraan/?key=${params.token}`
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
    axios.post(`fapi/tipekendaraan?key=${params.token}`, getBody
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
    axios.post(`fapi/tipemotor?key=${params.token}`, getBody
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
    if (model.tipe == null) {
        model.tahun = null
    } else {
        model.tahun = model.tipe.substring(model.tipe.length - 4)
    }
}
watch(() => model.tipe, () => {
    getTahun()
})

const temp3 = ref(false)
const daftarDana = ref(false)
const getDana = () => {
    daftarDana.value = false
    const getBody = new FormData()
    getBody.append('kendaraan', model.jaminan)
    getBody.append('merkmotor', model.merk)
    getBody.append('tipemotor', model.tipe)
    getBody.append('tahun', model.tahun)
    axios.post(`fapi/danamotor?key=${params.token}`, getBody
    ).then(res => {
        temp3.value = res.data
        daftarDana.value = temp3.value.map((m) =>
            //m.Dana_cair.slice(0, -2)
            m.Dana_cair.replace(/\B(?=(\d{3})+(?!\d))/g, ",").slice(0, - 2)
        )
    }).catch(err => {
        if (model.tahun == null) {
            //console.log('field tipe kosong')
        } else {
            console.log(`Gagal mengambil Data Dana\n\nError:${err}`)
        }
    })
}
watch(() => [model.jaminan, model.merk, model.tipe, model.tahun], () => {
    getDana()
})

const temp4 = ref(false)
const daftarTenor = ref(false)
const getTenor = () => {
    daftarTenor.value = false
    const getBody = new FormData()
    if (model.dana === '' || model.dana === null) {
        getBody.append('danamotor', model.dana)
    } else {
        getBody.append('danamotor', model.dana.split(',').join(''))
    }
    axios.post(`fapi/tenorajuan?key=${params.token}`, getBody
    ).then(res => {
        temp4.value = res.data
        daftarTenor.value = temp4.value.map((m) =>
            m.Tenor.slice(0, - 2)
        )
    }).catch(err => {
        if (model.dana == null) {
            //console.log('field dana kosong')
        } else {
            console.log(`Gagal mengambil Data Tenor\n\nError:${err}`)
        }
    })
}
watch(() => model.dana, () => {
    getTenor()
})

const temp2 = ref(false)
const getCicilan = () => {
    const getBody = new FormData()
    if (model.dana === '' || model.dana === null) {
        getBody.append('danamotor', model.dana)
    } else {
        getBody.append('danamotor', model.dana.split(',').join(''))
    }
    getBody.append('tenormotor', model.tenor)
    axios.post(`fapi/cicilanajuan?key=${params.token}`, getBody
    ).then(res => {
        temp2.value = res.data
        model.cicilan = temp2.value.map((m) =>
            m.Angssuran.replace(/\B(?=(\d{3})+(?!\d))/g, ",").slice(0, - 2)
            //m.Angssuran.slice(0, -2)
        )
    }).catch(err => {
        if (model.tenor == null) {
            //console.log('field tenor kosong')
        } else {
            console.log(`Gagal mengambil Data Cicilan\n\nError:${err}`)
        }
    })
}
watch(() => [model.dana, model.tenor], () => {
    getCicilan()
})

// ------------------------------------  Simpan Data -------------------------------------------------------------------------------------------------------------

const saveCust = () => {
    const formCust = new FormData()
    formCust.append('referal', model.no_referensi)
    formCust.append('no_polisi', model.no_polisi)
    formCust.append('custid', params.custid)
    formCust.append('kendaraan', model.jaminan)
    formCust.append('merk', model.merk)
    formCust.append('type', model.tipe)
    formCust.append('dana', model.dana.split(',').join(''))
    formCust.append('tenor', model.tenor)
    formCust.append('provinsi', model.provinsi)
    formCust.append('kabupaten', model.kabupaten)
    formCust.append('tenor', model.tenor)
    formCust.append('provinsi', model.provinsi)
    formCust.append('kabupaten', model.kabupaten)
    formCust.append('alamat_dom', model.alamat)
    formCust.append('nm_lengkap', model.nama_konsumen)
    formCust.append('kecamatan', model.kecamatan)
    formCust.append('kelurahan', model.kelurahan)
    formCust.append('kodepos', model.kode_pos)
    axios.post(`fapi_uat/newajuannn1?key=${params.token}`, formCust
    ).then(res => {
        const data = res.data
        console.log(data)
        alert(`Berhasil menyimpan data`)
        getCust()
    }).catch(err => {
        alert(`Gagal menyimpan data\n\nError:${err}`)
    })
}

const updateCust = () => {
    const formCust = new FormData()
    formCust.append('referal', model.no_referensi)
    formCust.append('no_polisi', model.no_polisi)
    formCust.append('custid', params.custid)
    formCust.append('kendaraan', model.jaminan)
    formCust.append('merk', model.merk)
    formCust.append('type', model.tipe)
    formCust.append('dana', model.dana.split(',').join(''))
    formCust.append('tenor', model.tenor)
    formCust.append('provinsi', model.provinsi)
    formCust.append('kabupaten', model.kabupaten)
    formCust.append('tenor', model.tenor)
    formCust.append('provinsi', model.provinsi)
    formCust.append('kabupaten', model.kabupaten)
    formCust.append('alamat_dom', model.alamat)
    formCust.append('nm_lengkap', model.nama_konsumen)
    formCust.append('kecamatan', model.kecamatan)
    formCust.append('kelurahan', model.kelurahan)
    formCust.append('kodepos', model.kode_pos)
    axios.post(`fapi_uat/upnewajuannn1?key=${params.token}`, formCust
    ).then(res => {
        const data = res.data
        console.log(data)
        Swal.fire({
            icon: 'success',
            title: 'Pengajuan Sukses!',
            showConfirmButton: false,
            timer: 1500
        })
        // Back to profile Form
        nextJaminan()
    }).catch(err => {
        alert(`Gagal mengubah data\n\nError:${err}`)
    })
}

// ---------------------------------- Change Form -------------------------------------------------
const form = reactive({
    profil: true, //default true
    alamat: false,   //default false
    jaminan: false, //default false
})

const nextProfil = () => {
    form.profil = false,
        form.alamat = true
}

const nextAlamat = () => {
    form.alamat = false,
        form.jaminan = true
}

const nextJaminan = () => {
    form.jaminan = false,
        form.profil = true
}
const trans = reactive({
    awal: 'transition duration-700 translate-x-0 opacity-100',
    kiri: 'transition duration-700 -translate-x-full opacity-0'
})
</script>

<template>
    <BannerForm></BannerForm>
    <div>
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-full">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Isi Formulir</h2>

                <!-- BreadCrumb-->
                <nav class="flex pb-3" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li>
                            <div class="flex items-center">
                                <div v-if="form.profil == true">
                                    <a href="#"
                                        class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Profil</a>
                                </div>
                                <div v-else>
                                    <span @click="[form.profil=true,form.alamat=false,form.jaminan=false]" class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                                        Profil
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <div v-if="form.alamat == true">
                                    <a href="#"
                                        class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Alamat</a>
                                </div>
                                <div v-else>
                                    <span @click="[form.profil=false,form.alamat=true,form.jaminan=false]" class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                                        Alamat
                                    </span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <div v-if="form.jaminan == true">
                                    <a href="#" 
                                        class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Jaminan</a>
                                </div>
                                <div v-else>
                                    <span @click="[form.profil=false,form.alamat=false,form.jaminan=true]" class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">
                                        Jaminan
                                    </span>
                                </div>
                            </div>
                        </li>
                    </ol>
                </nav>

                <form action="#">
                    <div class="relative">
                        <div class="absolute inset-x-0" :class="form.profil ? trans.awal : trans.kiri">
                            <div class=" bg-white border pt-8 pb-6 px-6 border-gray-200 rounded-lg">
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="no_ref" id="no_ref" v-model="model.no_referensi"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " required />
                                    <label for="no_ref"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">No.
                                        Referensi</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="no_ref" id="no_ref" v-model="model.nama_konsumen"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " required />
                                    <label for="no_ref"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama
                                        Konsumen</label>
                                </div>
                                <button type="submit" @click.prevent="nextProfil()"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    BERIKUTNYA
                                </button>
                            </div>
                        </div>
                        <div class="absolute inset-x-0"  :class="form.alamat ? trans.awal : trans.kiri">
                            <div class=" bg-white border pt-8 pb-6 px-6 border-gray-200 rounded-lg">
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="alamat" id="alamat" v-model="model.alamat"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " required />
                                    <label for="alamat"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Alamat</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select
                                        @change="[model.kabupaten = null, model.kecamatan = null, model.kelurahan = null]"
                                        id="provinsi" v-model="model.provinsi"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Provinsi</option>
                                        <option :value="data.PROPINSI" v-for="(data, index) in daftarProvinsi" :key="index">
                                            {{ data.PROPINSI }}
                                        </option>
                                    </select>
                                    <label for="provinsi"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Provinsi</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select @change="[model.kecamatan = null, model.kelurahan = null]" id="kabupaten"
                                        v-model="model.kabupaten"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Kabupaten</option>
                                        <option :value="data.KABUPATEN" v-for="(data, index) in daftarKabupaten"
                                            :key="index">
                                            {{ data.KABUPATEN }}
                                        </option>
                                    </select>
                                    <label for="kabupaten"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Kabupaten</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select @change="model.kelurahan = null" id="kecamatan" v-model="model.kecamatan"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Kecamatan</option>
                                        <option :value="data.KECAMATAN" v-for="(data, index) in daftarKecamatan"
                                            :key="index">
                                            {{ data.KECAMATAN }}
                                        </option>
                                    </select>
                                    <label for="kecamatan"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Kecamatan</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select id="kelurahan" v-model="model.kelurahan"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Kelurahan</option>
                                        <option :value="data.KELURAHAN" v-for="data in daftarKelurahan">
                                            {{ data.KELURAHAN }}
                                        </option>
                                    </select>
                                    <label for="kelurahan"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Kelurahan</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="kode_pos" id="kode_pos" v-model="model.kode_pos"
                                        class="cursor-not-allowed block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " required disabled />
                                    <label for="kode_pos"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Kode
                                        Pos</label>
                                </div>
                                <button type="submit" @click.prevent="nextAlamat()"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    BERIKUTNYA
                                </button>
                            </div>
                        </div>
                        <div :class="form.jaminan ? trans.awal : trans.kiri">
                            <div class="bg-white border pt-8 pb-6 px-6 border-gray-200 rounded-lg">
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="no_polisi" id="no_polisi" v-model="model.no_polisi"
                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " required />
                                    <label for="no_polisi"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">No.
                                        Polisi</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select @change="model.merk = null" id="jaminan" v-model="model.jaminan"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Jaminan</option>
                                        <option :value="data.Keteranga" v-for="data in daftarJaminan">{{ data.Keteranga }}
                                        </option>
                                    </select>
                                    <label for="jaminan"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Jaminan</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select
                                        @change="[model.tipe = null, model.dana = null, model.tenor = null, model.cicilan = null]"
                                        id="merk" v-model="model.merk"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Merk</option>
                                        <option :value="data.Type" v-for="data in daftarMerk">{{ data.Type }}</option>
                                    </select>
                                    <label for="merk"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Merk</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select @change="[model.dana = null, model.tenor = null, model.cicilan = null]"
                                        id="tipe" v-model="model.tipe"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Tipe</option>
                                        <option :value="data.TYPE" v-for="data in daftarTipe">{{ data.TYPE }}</option>
                                    </select>
                                    <label for="tipe"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Tipe</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="tahun" id="tahun" v-model="model.tahun"
                                        class="cursor-not-allowed block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " required disabled />
                                    <label for="tahun"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Tahun</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select @change="[model.tenor = null, model.cicilan = null]" id="dana"
                                        v-model="model.dana"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Dana</option>
                                        <option :value="dataDana" v-for="dataDana in daftarDana">
                                            {{ dataDana }}</option>
                                    </select>
                                    <label for="dana"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Dana</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <select @change="model.cicilan = null" id="tenor" v-model="model.tenor"
                                        class=" block bg-transparent border-0 border-b-2 border-gray-300 appearance-none text-gray-900 text-sm w-full py-2.5 px-0 focus:ring-primary-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                        <option value="" disabled selected>Pilih Tenor</option>
                                        <option :value="dataTenor" v-for="dataTenor in daftarTenor">{{ dataTenor }}</option>
                                    </select>
                                    <label for="tenor"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Tenor</label>
                                </div>
                                <div class="relative z-0 w-full mb-6 group">
                                    <input type="text" name="cicilan" id="cicilan" v-model="model.cicilan"
                                        class="cursor-not-allowed block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " required disabled />
                                    <label for="cicilan"
                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Cicilan</label>
                                </div>
                                <div class="">
                                    <template v-if="dataCust && dataCust.length <= 0">
                                        <button type="submit" @click.prevent="saveCust()"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded- text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            SIMPAN
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="submit" @click.prevent="updateCust()"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            SIMPAN
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
    </section>
</div></template>