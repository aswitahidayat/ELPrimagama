<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Asal Sekolah Table
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-primary" @click="primaryModal = true">
                                <i class="fa fa-plus"></i> Tambah</button>
                        </div>
                        <div class="col-sm-9">
                            <div class="input-group">
                                <input type="text" id="username2" name="username2" v-model="keyword" placeholder="Cari" class="form-control">
                                <button @click="fetchSekolahList()" class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Asal Sekolah</th>
                                <th>Provinsi</th>
                                <th>Kota / Kab</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(task, index) in list.data" :key="index">
                                <td>{{ index + list.pagination.from }}</td>
                                <td>{{ task.asal_sekolah }}</td>
                                <td>{{ task.propinsiData.NamaPropinsi }}</td>
                                <td>{{ task.kotaData.NamaKotaKab }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" @click="popUpEditSekolah(task.RecID)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <delete-btn :okFunc="deleteSekolah.bind(null, task.RecID)"> </delete-btn>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :pagination="pagination" :fetchFunc="fetchSekolahList"> </Pagination>

                </div>
            </div>
        </div>
        <!--/.col-->

        <modal title="Modal title" class="modal-primary" v-model="primaryModal" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">{{ dataForm.RecID ? "Edit Data" : "Tambah Data" }}</h4>
            </div>

            <div class="card-block">
                <form>
                    <div class="form-group has-error">
                        <label for="company">Asal Sekolah</label>
                        <input name="asal sekolah" type="text" class="form-control" v-validate="'required'" 
                            v-model="dataForm.asal_sekolah" value="{ dataForm.asal_sekolah }" placeholder="Asal Sekolah" maxlength="50">
                        <span v-show="errors.has('asal sekolah')" class="help-block">asal sekolah diperlukan</span>
                    </div>

                    <div class="form-group">
                        <label for="company">Propinsi</label>
                        <select name="propinsi" class="form-control" v-model="dataForm.propinsi" v-on:change="getKota()" v-validate.initial="'required'">
                            <option v-for="cat in this.listPropinsi" :key="cat.RecID" :value="cat.RecID" :selected="cat.RecID == dataForm.propinsi">
                                {{ cat.NamaPropinsi }}
                            </option>
                        </select>
                        <span v-show="errors.has('propinsi')" class="help-block">propinsi diperlukan</span>
                    </div>

                    <div class="form-group">
                        <label for="company">Kota</label>
                        <select name="kota" class="form-control" v-model="dataForm.kota" :disabled="!dataForm.propinsi" v-validate.initial="'required'">
                            <option v-for="cat in this.listKota" :key="cat.RecID" :value="cat.RecID" :selected="cat.RecID == dataForm.kota">
                                {{ cat.NamaKotaKab }}
                            </option>
                        </select>
                        <span v-show="errors.has('kota')" class="help-block">kota diperlukan</span>
                    </div>
                </form>

            </div>
            
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="primaryModal = false">Tutup</button>
                <button type="submit" class="btn btn-primary" @click="vaidateForm(dataForm.RecID)" >Simpan</button>
            </div>
        </modal>
        <loading-bar :show="!ready"> </loading-bar>

    </div>
    <!--/.row-->
</template>

<script>
import modal from 'vue-strap/src/Modal'
import toastr from 'toastr'
import DeleteBtn from '../../../components/DeleteBtn'

export default {
    name: 'modals',
    components: {
        modal,
        toastr,
        DeleteBtn,
    },
    data() {
        return {
            ready: true,
            keyword: '',
            primaryModal: false,
            deleteModal: false,
            list: [],
            dataForm: {
                RecID: '',
                propinsi: '',
                kota: '',
                asal_sekolah: '',
            },
            listPropinsi: [],
            listKota: [],
            pagination: {
                total: 0,
                per_page: 10,
                from: 1,
                to: 0,
                current_page: 1
            },
        };
    },

    created() {
        this.fetchSekolahList();
        this.getPropinsi();
        this.getKota();
    },

    watch: {
        primaryModal: function (val) {
            if (!val){
                this.dataForm={};
            }
            this.errors.clear();
        }
    },
    
    methods: {
        fetchSekolahList(page) {
            this.ready = false;
            axios.get('api/sekolah?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        popUpEditSekolah(id) {
            this.ready = false;
            axios.get('api/sekolah/' + id)
                .then((res) => {
                    this.primaryModal = true;
                    this.dataForm = res.data;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        vaidateForm(id) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.editSekolah(id);
                } else {
                    toastr.error('Ada Field Yang Belum Diisi!');
                }
            });
        },

        editSekolah(id) {
            this.ready = false;
            if (id && id !== "") {
                axios.put('api/sekolah/' + id, this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchSekolahList();
                        toastr.success('Data Berhasil Di Ubah');
                        this.ready = true;
                    })
                    .catch((err) => console.error(err));
            } else {
                axios.post('api/sekolah', this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchSekolahList();
                        toastr.success('Data Berhasil Di Tambah');
                        this.ready = true;
                    })
                    .catch((err) => console.error(err));
            }
        },

        deleteSekolah(id) {
            this.ready = false;
            axios.delete('api/sekolah/' + id)
                .then((res) => {
                    this.deleteModal = false;
                    this.dataForm = {};
                    this.fetchSekolahList();
                    toastr.success('Data Berhasil Di Hapus');
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        getPropinsi() {
            this.ready = false;
            axios.get('api/propinsi/')
                .then((res) => {
                    this.listPropinsi = res.data;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        getKota() {
            this.ready = false;
            axios.get('api/kota?propinsi=' + this.dataForm.propinsi)
                .then((res) => {
                    this.listKota = res.data;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

    }
}
</script>