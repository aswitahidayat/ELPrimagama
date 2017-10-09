<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="icon-question"></i> Pikse
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
                                <button @click="fetchPikseList()" class="input-group-addon">
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
                                    <button type="button" class="btn btn-primary" @click="popUpEditPikse(task.idpikse)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <delete-btn :okFunc="deletePikse.bind(null, task.idpikse)"> </delete-btn>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :pagination="pagination" :fetchFunc="fetchPikseList"> </Pagination>

                </div>
            </div>
        </div>
        <!--/.col-->

        <modal title="Modal title" class="modal-primary" v-model="primaryModal" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">{{ dataForm.idpikse ? "Edit Data" : "Tambah Data" }}</h4>
            </div>

            <div class="card-block">
                <form>
                    <div class="form-group has-error">
                        <label for="company">File</label>
                        <input name="file" type="text" class="form-control" v-model="dataForm.nmfile" v-validate="'required'"
                            value="{ dataForm.nmfile }" placeholder="Masukan File" maxlength="50">
                        <span v-show="errors.has('file')" class="help-block">file diperlukan</span>
                    </div>

                    <div class="form-group">
                        <label for="company">Keterangan</label>
                        <input name="keterangan" type="text" class="form-control" v-model="dataForm.keterangan" v-validate="'required'"
                            value="{ dataForm.keterangan }" placeholder="Masukan Keterangan" maxlength="50">
                        <span v-show="errors.has('keterangan')" class="help-block">keterangan diperlukan</span>
                    </div>

                    <div class="form-group">
                        <label for="company">Jenjang 1</label>
                        <select name="jenjang" class="form-control" v-model="dataForm.jenjang" v-validate.initial="'required'">
                            <option v-for="cat in this.listJenjang" :key="cat.KodeJenjang" :value="cat.KodeJenjang" :selected="cat.index == 0">
                                {{ cat.NamaJenjang }}
                            </option>
                        </select>
                        <span v-show="errors.has('jenjang')" class="help-block">jenjang diperlukan</span>
                    </div>

                    <div class="form-group">
                        <label for="company">Jenjang 2</label>
                        <select class="form-control" v-model="dataForm.jenjang2 ">
                            <option v-for="cat in this.listJenjang" :key="cat.KodeJenjang" :value="cat.KodeJenjang" :selected="cat.KodeJenjang == dataForm.jenjang2">
                                {{ cat.NamaJenjang }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="company">Kurikulum</label>
                        <input type="text" class="form-control" v-model="dataForm.kurikulum" value="{ dataForm.kurikulum }" 
                        placeholder="Masukan Kurikulum" maxlength="50">
                    </div>

                    <div class="form-group">
                        <upload-file :myFile="dataForm.myFile" :onFileChange="onFileChange" />
                        <span v-if="invalidFile" class="help-block">file tidak valid</span>
                    </div>
                </form>

            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="primaryModal = false">Tutup</button>
                <button type="submit" class="btn btn-primary" @click="vaidateForm(dataForm.idpikse)">Simpan</button>
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
    name: 'modals',
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
            invalidFile: false,
            list: [],
            dataForm: {
                idpikse: '',
                file: '',
                keterangan: '',
                myFile: {
                    uploadFile: '',
                    fileName: '',
                    fileType: '',
                },
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
        this.fetchPikseList();
        this.getJenjang();
        this.getKurikulum();
    },

    watch: {
        primaryModal: function (val) {
            if (!val){
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

        fetchPikseList(page) {
            this.ready = false;
            axios.get('/api/pikse?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        popUpEditPikse(id) {
            this.ready = false;
            axios.get('/api/pikse/' + id)
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
                    this.editPikse(id);
                } else {
                    toastr.error('Ada Field Yang Belum Diisi!');
                }
            });
        },

        editPikse(id) {
            this.ready = false;
            if (id && id !== "") {
                axios.put('/api/pikse/' + id, this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchPikseList();
                        this.ready = true;
                        toastr.success('Data Berhasil Di Tambah');
                    })
                    .catch((err) => console.error(err));
            } else {
                axios.post('/api/pikse', this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchPikseList();
                        this.ready = true;
                        toastr.success('Data Berhasil Di Ubah');
                    })
                    .catch((err) => console.error(err));
            }
        },

        deletePikse(id) {
            this.ready = false;
            axios.delete('/api/pikse/' + id)
                .then((res) => {
                    this.deleteModal = false;
                    this.dataForm = {};
                    this.fetchPikseList();
                    this.ready = true;
                    toastr.success('Data Berhasil Di Delete');
                })
                .catch((err) => console.error(err));
        },

        getJenjang() {
            this.ready = false;
            axios.get('/api/jenjang/')
                .then((res) => {
                    this.listJenjang = res.data;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        getKurikulum() {
            this.ready = false;
            axios.get('/api/kurikulum/')
                .then((res) => {
                    this.listKurikulum = res.data;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

    }
}
</script>