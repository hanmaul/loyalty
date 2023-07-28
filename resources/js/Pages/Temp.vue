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
const params = defineProps(['token', 'custid'])

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
    }).catch(err => {
        alert(`Gagal mengubah data\n\nError:${err}`)
    })
}

</script>

<template>
    <BannerForm></BannerForm>
    <div>
        <section class="bg-white dark:bg-gray-900">
            <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Isi Formulir</h2>
                <form action="#">
                    <div class="max-w-sm bg-white border p-6 border-gray-200 rounded-lg">
                        <div>
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
                        </div>
                        <div class="w-full">
                            <label for="alamat"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat</label>
                            <input type="text" name="brand" id="alamat" v-model="model.alamat"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Isi sesuai domisili" required="">
                        </div>
                        <div>
                            <label for="provinsi"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provinsi</label>
                            <select @change="[model.kabupaten = null, model.kecamatan = null, model.kelurahan = null]"
                                id="provinsi" v-model="model.provinsi"
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
                            <select @change="[model.kecamatan = null, model.kelurahan = null]" id="kabupaten"
                                v-model="model.kabupaten"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Kabupaten</option>
                                <option :value="data.KABUPATEN" v-for="(data, index) in daftarKabupaten" :key="index">
                                    {{ data.KABUPATEN }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="kecamatan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kecamatan</label>
                            <select @change="model.kelurahan = null" id="kecamatan" v-model="model.kecamatan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Kecamatan</option>
                                <option :value="data.KECAMATAN" v-for="(data, index) in daftarKecamatan" :key="index">
                                    {{ data.KECAMATAN }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <label for="kelurahan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kelurahan</label>
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
                        <div class="sm:col-span-2">
                            <label for="no_polisi" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">No.
                                Polisi</label>
                            <input type="text" name="name" id="no_polisi" v-model="model.no_polisi"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                        </div>
                        <div>
                            <label for="jaminan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jaminan</label>
                            <select @change="model.merk = null" id="jaminan" v-model="model.jaminan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Jaminan</option>
                                <option :value="data.Keteranga" v-for="data in daftarJaminan">{{ data.Keteranga }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="merk"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Merk</label>
                            <select
                                @change="[model.tipe = null, model.dana = null, model.tenor = null, model.cicilan = null]"
                                id="merk" v-model="model.merk"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Merk</option>
                                <option :value="data.Type" v-for="data in daftarMerk">{{ data.Type }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="tipe"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                            <select @change="[model.dana = null, model.tenor = null, model.cicilan = null]" id="tipe"
                                v-model="model.tipe"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Tipe</option>
                                <option :value="data.TYPE" v-for="data in daftarTipe">{{ data.TYPE }}</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="tahun"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tahun</label>
                            <input type="text" name="name" id="tahun" :value="model.tahun"
                                class="cursor-not-allowed bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                disabled>
                        </div>
                        <div>
                            <label for="dana"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dana</label>
                            <select @change="[model.tenor = null, model.cicilan = null]" id="dana" v-model="model.dana"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Dana</option>
                                <option :value="dataDana" v-for="dataDana in daftarDana">
                                    {{ dataDana }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="tenor"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tenor</label>
                            <select @change="model.cicilan = null" id="tenor" v-model="model.tenor"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" disabled selected>Pilih Tenor</option>
                                <option :value="dataTenor" v-for="dataTenor in daftarTenor">{{ dataTenor }}</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="cicilan"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cicilan</label>
                            <input type="text" name="name" id="cicilan" v-model="model.cicilan"
                                class="cursor-not-allowed bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                disabled>
                        </div>

                        <div class="">
                            <template v-if="dataCust && dataCust.length <= 0">
                                <button type="submit" @click="saveCust()"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded- text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    SIMPAN
                                </button>
                            </template>
                            <template v-else>
                                <button type="submit" @click="updateCust()"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    SIMPAN
                                </button>
                            </template>
                        </div>

                    </div>
                </form>
            </div>
        </section>
    </div>
</template>