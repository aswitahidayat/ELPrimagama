<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="icon-puzzle"></i> Tips N Trik
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
                                <button @click="fetchMititiList()" class="input-group-addon">
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
                                    <button type="button" class="btn btn-primary" @click="popUpEditMititi(task.RecID)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <delete-btn :okFunc="deleteMititi.bind(null, task.RecID)"> </delete-btn>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :pagination="pagination" :fetchFunc="fetchMititiList"> </Pagination>

                </div>
            </div>
        </div>
        <!--/.col-->

        <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editMititi(dataForm.RecID)" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">{{ dataForm.id ? "Edit Data" : "Tambah Data" }}</h4>
            </div>

            <div class="card-block">
                <div class="form-group">
                    <label>File</label>
                    <input name="nama" type="text" class="form-control" v-validate="'required'" v-model="dataForm.nmfile" value="{ dataForm.nmfile }" placeholder="Masukan Nama File" maxlength="50">
                    <span v-show="errors.has('nama')" class="help-block">nama diperlukan</span>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input name="keterangan" type="text" class="form-control" v-validate="'required'" v-model="dataForm.keterangan" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan" maxlength="50">
                    <span v-show="errors.has('keterangan')" class="help-block">keterangan diperlukan</span>
                </div>

                <div class="form-group">
                    <upload-file :myFile="dataForm.myFile" :onFileChange="onFileChange" />
                    <span v-if="invalidFile" class="help-block">file tidak valid</span>
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
import UploadFile from '../../../components/UploadFile'

export default {
    name: 'TipsNTrik',
    components: {
        modal,
        toastr,
        DeleteBtn,
        UploadFile,
    },
    data() {
        return {
            ready: true,
            keyword: '',
            primaryModal: false,
            deleteModal: false,
            invalidFile: false,
            list: [],
            dataForm: {
                id: '',
                nmfile: '',
                keterangan: '',
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
        this.fetchMititiList();
    },

    watch: {
        primaryModal: function(val) {
            if (!val) {
                this.dataForm = {
                    id: '',
                    nmfile: '',
                    keterangan: '',
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

        fetchMititiList(page) {
            this.ready = false;
            axios.get('/api/mititi?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        popUpEditMititi(id) {
            this.ready = false;
            axios.get('/api/mititi/' + id)
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
                    this.editMititi(id);
                } else {
                    toastr.error('Ada Field Yang Belum Diisi!');
                }
            });
        },

        editMititi(id) {
            this.ready = false;
            if (id && id !== "") {
                axios.put('/api/mititi/' + id, this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchMititiList();
                        toastr.success('Data Berhasil Di Ubah');
                        this.ready = true;
                    })
                    .catch((err) => console.error(err));
            } else {
                this.ready = false;
                axios.post('/api/mititi', this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchMititiList();
                        toastr.success('Data Berhasil Di Simpan');
                        this.ready = true;
                    })
                    .catch((err) => {
                        console.log("err");
                    });
            }
        },

        deleteMititi(id) {
            this.ready = false;
            axios.delete('/api/mititi/' + id)
                .then((res) => {
                    this.dataForm = {};
                    this.deleteModal = false;
                    this.fetchMititiList();
                    toastr.success('Data Berhasil Di Delete');
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

    }
}
</script>