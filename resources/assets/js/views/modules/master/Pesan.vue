<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="icon-envelope"></i> Master Pesan
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
                                <button @click="fetchPesanList()" class="input-group-addon">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(task, index) in list.data" :key="index">
                                <td>{{ index + list.pagination.from }}</td>
                                <td>{{ task.Judul }}</td>
                                <td>{{ task.Keterangan }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" @click="popUpEditPesan(task.RecID)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <delete-btn :okFunc="deletePesan.bind(null, task.RecID)"> </delete-btn>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :pagination="pagination" :fetchFunc="fetchPesanList"> </Pagination>

                </div>
            </div>
        </div>
        <!--/.col-->

        <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editPesan(dataForm.RecID)" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">{{ dataForm.id ? "Edit Data" : "Tambah Data" }}</h4>
            </div>

            <div class="card-block">
                <div class="form-group">
                    <label>Judul</label>
                    <input name="judul" type="text" class="form-control" v-validate="'required'" v-model="dataForm.Judul" value="{ dataForm.judul }" placeholder="Masukan Judul" maxlength="50">
                    <span v-show="errors.has('judul')" class="help-block">judul diperlukan</span>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input name="keterangan" type="text" class="form-control" v-validate="'required'" v-model="dataForm.Keterangan" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan" maxlength="50">
                    <span v-show="errors.has('keterangan')" class="help-block">keterangan diperlukan</span>
                </div>

                <div class="form-group">
                    <label for="company">Tanggal</label>
                    <datepicker name="tanggal" v-model="dataForm.Tanggal" v-validate="'required'" value="{ dataForm.Tanggal || '' }" :label="'File'" :format="'yyyy-MM-dd'" :placeholder="'Masukan Tanggal'"></datepicker>
                    <span v-show="errors.has('tanggal')" class="help-block">tanggal diperlukan</span>
                </div>

                <div class="form-group">
                    <label for="company">Otori</label>
                    <select name="otori" class="form-control" v-model="dataForm.otori" v-validate.initial="'required'">
                        <option v-for="cat in this.listUser" 
                        :key="cat.id_user" :value="cat.id_user" :selected="cat.id_user == dataForm.otori">
                            {{ cat.nama }}
                        </option>
                    </select>
                    <span v-show="errors.has('otori')" class="help-block">otori diperlukan</span>
                </div>

            </div>

            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="primaryModal = false">Tutup</button>
                <button type="submit" class="btn btn-primary" @click="vaidateForm(dataForm.RecID)">Simpan</button>
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
import { EventBus } from '../../../components/event-bus.js';
import { datepicker } from 'vue-strap'

export default {
    name: 'TipsNTrik',
    components: {
        modal,
        toastr,
        DeleteBtn,
        datepicker,
        EventBus,
    },
    data() {
        return {
            ready: true,
            keyword: '',
            primaryModal: false,
            deleteModal: false,
            invalidFile: false,
            list: [],
            listUser: [],
            dataForm: {
                id: '',
                judul: '',
                keterangan: '',
                Tanggal: '',
                myFile: {
                    uploadFile: '',
                    fileName: '',
                    fileType: '',
                },
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
        this.fetchPesanList();
        this.getUser();
    },

    watch: {
        primaryModal: function(val) {
            if (!val) {
                this.dataForm = {
                    id: '',
                    judul: '',
                    keterangan: '',
                    Tanggal: '',
                    myFile: {
                        uploadFile: '',
                        fileName: '',
                        fileType: '',
                    },
                };
            }
            this.errors.clear();
            this.invalidFile = false;
        }
    },

    methods: {
        onFileChange(e) {
            if (e) {
                if (e.target.files[0].type == 'application/pdf' || e.target.files[0].type == 'application/x-shockwave-flash') {
                    var vm = this;

                    vm.invalidFile = false;
                    vm.ready = false;
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    vm.fileName = files[0].name;
                    vm.myFile = files[0];

                    vm.dataForm.myFile.fileName = vm.myFile.name;
                    vm.dataForm.myFile.fileType = vm.myFile.type;

                    var reader = new FileReader();
                    reader.onloadend = function(event) {
                        vm.dataForm.myFile.uploadFile = event.target.result;
                        vm.ready = true;
                    };

                    reader.readAsDataURL(vm.myFile);
                } else {
                    this.invalidFile = true;
                }

            }
        },

        fetchPesanList(page) {
            this.ready = false;
            axios.get('/api/pesan?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        popUpEditPesan(id) {
            this.ready = false;
            axios.get('/api/pesan/' + id)
                .then((res) => {
                    this.primaryModal = true;
                    this.dataForm = res.data;
                    if (!this.dataForm.Tanggal) {
                        this.dataForm.Tanggal ='';
                    }
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        vaidateForm(id) {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.editPesan(id);
                } else {
                    toastr.error('Ada Field Yang Belum Diisi!');
                }
            });
        },

        editPesan(id) {
            this.ready = false;
            if (id && id !== "") {
                axios.put('/api/pesan/' + id, this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.fetchPesanList();
                        toastr.success('Data Berhasil Di Ubah');
                        this.ready = true;
                        this.fetchPesanList();
                        EventBus.$emit('hitungUlangPesan');
                    })
                    .catch((err) => console.error(err));
            } else {
                this.ready = false;
                axios.post('/api/pesan', this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.fetchPesanList();
                        toastr.success('Data Berhasil Di Simpan');
                        this.ready = true;
                        this.fetchPesanList();
                        EventBus.$emit('hitungUlangPesan');
                    })
                    .catch((err) => {
                        console.log("err");
                    });
            }
        },

        deletePesan(id) {
            this.ready = false;
            axios.delete('/api/pesan/' + id)
                .then((res) => {
                    this.deleteModal = false;
                    this.fetchPesanList();
                    toastr.success('Data Berhasil Di Delete');
                    this.ready = true;
                    EventBus.$emit('hitungUlangPesan');
                })
                .catch((err) => console.error(err));
        },

        getUser() {
            this.ready = false;
            axios.get('/api/user/')
                .then((res) => {
                    this.ready = true;
                    this.listUser = res.data;
                })
                .catch((err) => console.error(err));
        },

    }
}
</script>