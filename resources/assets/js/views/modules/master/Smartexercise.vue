<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Smart Exercise Table
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
                                <button @click="fetchSmartexerciseList()" class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Jenjang 1</th>
                                <th>Jenjang 2</th>
                                <th>Kurikulum</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(task, index) in list.data" :key="index">
                                <td>{{ index + list.pagination.from }}</td>
                                <td>{{ task.nmfile }}</td>
                                <td>{{ task.keterangan }}</td>
                                <td>{{ task.jenjangdata.NamaJenjang }}</td>
                                <td>{{ task.jenjang2data.NamaJenjang }}</td>
                                <td>{{ task.kurikulum }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" @click="popUpEditSmartexercise(task.idse)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <delete-btn :okFunc="deleteSmartexercise.bind(null, task.idse)"> </delete-btn>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <Pagination :pagination="pagination" :fetchFunc="fetchMititiList"> </Pagination>
                </div>
            </div>
        </div>
        <!--/.col-->

        <modal title="Modal title" class="modal-primary" v-model="primaryModal" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">{{ dataForm.idse ? "Edit Data" : "Tambah Data" }}</h4>
            </div>

            <div class="card-block">
                <form>
                    <div class="form-group has-error">
                        <label for="company">File</label>
                        <input name="file" type="text" class="form-control" v-model="dataForm.nmfile" v-validate="'required'" value="{ dataForm.nmfile }" placeholder="Masukan Nama" required>
                        <span v-show="errors.has('file')" class="help-block">file diperlukan</span>
                    </div>

                    <div class="form-group">
                        <label for="company">Keterangan</label>
                        <input name="keterangan" type="text" class="form-control" v-model="dataForm.keterangan" v-validate="'required'" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan">
                        <span v-show="errors.has('keterangan')" class="help-block">keterangan diperlukan</span>
                    </div>

                    <div class="form-group">
                        <label for="company">Jenjang 1</label>
                        <select name="jenjang" class="form-control" v-model="dataForm.jenjang " v-validate="'required'">
                            <option v-for="cat in this.listJenjang" :key="cat.KodeJenjang" :value="cat.KodeJenjang" :selected="cat.index == 0">
                                {{ cat.NamaJenjang }}
                            </option>
                        </select>
                        <span v-show="errors.has('jenjang')" class="help-block">jenjang diperlukan</span>
                    </div>

                    <div class="form-group">
                        <label for="company">Jenjang 2</label>
                        <select name="jenjang2" class="form-control" v-model="dataForm.jenjang2 " >
                            <option v-for="cat in this.listJenjang" :key="cat.KodeJenjang" :value="cat.KodeJenjang" :selected="cat.KodeJenjang == dataForm.jenjang2">
                                {{ cat.NamaJenjang }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="company">Kurikulum</label>
                        <input type="text" class="form-control" v-model="dataForm.kurikulum" value="{ dataForm.kurikulum }" placeholder="Masukan Kurikulum">
                    </div>
                </form>

            </div>

            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="primaryModal = false">Tutup</button>
                <button type="submit" class="btn btn-primary" @click="vaidateForm(dataForm.idse)">Simpan</button>
            </div>

        </modal>
    </div>
    <!--/.row-->
</template>

<script>
import modal from 'vue-strap/src/Modal'
import toastr from 'toastr'
import DeleteBtn from '../../../components/DeleteBtn'
import Pagination from '../../../components/Pagination'

export default {
    name: 'Smartexercise',
    components: {
        modal,
        toastr,
        DeleteBtn,
        Pagination,
    },
    data() {
        return {
            valid: false,
            keyword: '',
            primaryModal: false,
            deleteModal: false,
            list: [],
            dataForm: {
                idse: '',
                file: '',
                keterangan: ''
            },
            listJenjang: [],
            listKurikulum: [],
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
        this.fetchSmartexerciseList();
        this.getJenjang();
        this.getKurikulum();
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
        fetchSmartexerciseList(page) {
            axios.get('api/smartexercise?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                })
                .catch((err) => console.error(err));
        },

        popUpEditSmartexercise(id) {
            axios.get('api/smartexercise/' + id)
                .then((res) => {
                    this.primaryModal = true;
                    this.dataForm = res.data;
                })
                .catch((err) => console.error(err));
        },

        vaidateForm(id) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.editSmartexercise(id);
                } else {
                    toastr.error('Ada Field Yang Belum Diisi!');
                }
            });
        },

        editSmartexercise(id) {
            if (id && id !== "") {
                axios.put('api/smartexercise/' + id, this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchSmartexerciseList();
                        toastr.success('Data Berhasil Di Ubah');
                    })
                    .catch((err) => console.error(err));
            } else {
                axios.post('api/smartexercise', this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchSmartexerciseList()
                        toastr.success('Data Berhasil Di Simpan');
                    })
                    .catch((err) => console.error(err));
            }
        },

        deleteSmartexercise(id) {
            axios.delete('api/smartexercise/' + id)
                .then((res) => {
                    this.deleteModal = false;
                    this.dataForm = {};
                    this.fetchSmartexerciseList();
                    toastr.success('Data Berhasil Di Delete');
                })
                .catch((err) => console.error(err));
        },

        getJenjang() {
            axios.get('api/jenjang/')
                .then((res) => {
                    this.listJenjang = res.data;
                })
                .catch((err) => console.error(err));
        },

        getKurikulum() {
            axios.get('api/kurikulum/')
                .then((res) => {
                    this.listKurikulum = res.data;
                })
                .catch((err) => console.error(err));
        },

    }
}
</script>