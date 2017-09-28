<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Tips N Trik Table
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
                                <button @click="fetchBakmiList()" class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>File</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(task, index) in list.data" :key="index">
                                <td>{{ index + list.pagination.from }}</td>
                                <td>{{ task.nmfile }}</td>
                                <td>{{ task.keterangan }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" @click="popUpEditBakmi(task.RecID)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button @click="popUpDeleteBakmi(task)" class="btn btn-danger btn-xs">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="javascript:;" @click="fetchBakmiList(pagination.current_page - 1)">Prev</a>
                            </li>
                            <li v-for="(page, index) in pagination.last_page" :key="index" v-bind:class="[ page == pagination.current_page ? 'active' : '']">
                                <a href="javascript:;" @click="fetchBakmiList(page)">{{ page }}</a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page <  pagination.last_page">
                                <a class="page-link" href="javascript:;" @click="fetchBakmiList(pagination.current_page + 1)">Next</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
        <!--/.col-->

        <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editBakmi(dataForm.RecID)" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">{{ dataForm.id ? "Edit Data" : "Tambah Data" }}</h4>
            </div>

            <div class="card-block">
                <div class="form-group">
                    <label for="company">File</label>
                    <input type="text" class="form-control" v-model="dataForm.nmfile" value="{ dataForm.nmfile }" placeholder="Masukan Nama File">
                </div>

                <div class="form-group">
                    <label for="company">Keterangan</label>
                    <input type="text" class="form-control" v-model="dataForm.keterangan" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan">
                </div>

            </div>

            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="dataForm = {};primaryModal = false">Tutup</button>
                <button type="submit" class="btn btn-primary" @click="editBakmi(dataForm.RecID)">Simpan</button>
            </div>
        </modal>

        <modal title="Modal title" class="modal-danger" v-model="deleteModal" @ok="deleteBakmi(dataForm.RecID)" effect="fade/zoom">
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
            keyword: '',
            primaryModal: false,
            deleteModal: false,
            list: [],
            dataForm: {
                id: '',
                nmfile: '',
                keterangan: ''
            },
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
        this.fetchBakmiList();
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
        fetchBakmiList(page) {
            axios.get('api/bakmi?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                })
                .catch((err) => console.error(err));
        },

        createBakmi() {
            axios.post('api/bakmi', this.dataForm)
                .then((res) => {
                    this.dataForm = {};
                    this.fetchBakmiList();
                })
                .catch((err) => console.error(err));
        },

        popUpEditBakmi(id) {
            axios.get('api/bakmi/' + id)
                .then((res) => {
                    this.primaryModal = true;
                    this.dataForm = res.data;
                })
                .catch((err) => console.error(err));
        },

        editBakmi(id) {
            if (id && id !== "") {
                axios.put('api/bakmi/' + id, this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchBakmiList()
                    })
                    .catch((err) => console.error(err));
            } else {
                axios.post('api/bakmi', this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchBakmiList()
                    })
                    .catch((err) => console.error(err));
            }
        },

        popUpDeleteBakmi(task) {
            this.dataForm = task;
            this.deleteModal = true;
        },

        deleteBakmi(id) {
            axios.delete('api/bakmi/' + id)
                .then((res) => {
                    this.dataForm = {};
                    this.deleteModal = false;
                    this.fetchBakmiList()
                })
                .catch((err) => console.error(err));
        },

    }
}
</script>