<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Smart Ebook Table
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
                                    <button @click="popUpDeleteSekolah(task)" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="javascript:;" @click="fetchSekolahList(pagination.current_page - 1)">Prev</a>
                            </li>
                            <li v-for="(page, index) in pagination.last_page" :key="page.index" v-bind:class="[ page == pagination.current_page ? 'active' : '']">
                                <a href="javascript:;" @click="fetchSekolahList(page)" v-if="index < pagination.current_page + 5 && index > pagination.current_page - 5">{{ page }}</a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page <  pagination.last_page">
                                <a class="page-link" href="javascript:;" @click="fetchSekolahList(pagination.current_page + 1)">Next</a>
                            </li>
                        </ul>
                    </nav>

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
                        <input type="text" id="firstname" name="firstname" class="form-control" v-model="dataForm.asal_sekolah" value="{ dataForm.asal_sekolah }" placeholder="Asal Sekolah" required>
                    </div>

                    <div class="form-group">
                        <label for="company">Propinsi</label>
                        <select class="form-control" v-model="dataForm.propinsi" v-on:change="getKota()">
                            <option v-for="cat in this.listPropinsi" :key="cat.RecID" :value="cat.RecID" :selected="cat.index == dataForm.propinsi">
                                {{ cat.NamaPropinsi }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="company">Kota</label>
                        <select class="form-control" v-model="dataForm.kota" :disabled="!dataForm.propinsi">
                            <option v-for="cat in this.listKota" :key="cat.RecID" :value="cat.RecID" :selected="cat.KodeJenjang == dataForm.kota">
                                {{ cat.NamaKotaKab }}
                            </option>
                        </select>
                    </div>
                </form>

            </div>
            
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="primaryModal = false">Tutup</button>
                <button type="submit" class="btn btn-primary" @click="editSekolah(dataForm.RecID)" >Simpan</button>
            </div>

        </modal>

        <modal title="Modal title" class="modal-danger" v-model="deleteModal" @ok="deleteSekolah(dataForm.RecID)" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">Delete Data</h4>
            </div>
            <div class="card-block">
                <div class="form-group">
                    <label for="company">Apakah kamu yakin? </label>
                </div>
            </div>
        </modal>
    </div>
    <!--/.row-->
</template>

<script>
import modal from 'vue-strap/src/Modal'
import { input as bsInput, formValidator } from 'vue-strap'

export default {
    name: 'modals',
    components: {
        modal,
        formValidator,
        bsInput,
    },
    data() {
        return {
            valid: false,
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
            if (!val)
                this.dataForm={};
        }
    },
    methods: {
        fetchSekolahList(page) {
            axios.get('api/sekolah?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                })
                .catch((err) => console.error(err));
        },

        createSekolah() {
            axios.post('api/sekolah', this.dataForm)
                .then((res) => {
                    this.dataForm = {};
                    this.fetchSekolahList();
                })
                .catch((err) => console.error(err));
        },

        popUpEditSekolah(id) {
            axios.get('api/sekolah/' + id)
                .then((res) => {
                    this.primaryModal = true;
                    this.dataForm = res.data;
                })
                .catch((err) => console.error(err));
        },

        popUpDeleteSekolah(task) {
            this.dataForm = task;
            this.deleteModal = true;
        },

        editSekolah(id) {
            if (id && id !== "") {
                axios.put('api/sekolah/' + id, this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchSekolahList()
                    })
                    .catch((err) => console.error(err));
            } else {
                axios.post('api/sekolah', this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchSekolahList()
                    })
                    .catch((err) => console.error(err));
            }
        },

        deleteSekolah(id) {
            axios.delete('api/sekolah/' + id)
                .then((res) => {
                    this.deleteModal = false;
                    this.dataForm = {};
                    this.fetchSekolahList()
                })
                .catch((err) => console.error(err));
        },

        getPropinsi() {
            axios.get('api/propinsi/')
                .then((res) => {
                    this.listPropinsi = res.data;
                })
                .catch((err) => console.error(err));
        },

        getKota() {
            axios.get('api/kota?propinsi=' + this.dataForm.propinsi)
                .then((res) => {
                    this.listKota = res.data;
                })
                .catch((err) => console.error(err));
        },

    }
}
</script>