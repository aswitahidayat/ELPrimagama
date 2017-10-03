<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Banner Table
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
                                <td>{{ task.judul }}</td>
                                <td>{{ task.keterangan }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" @click="popUpEditBanner(task.RecID)">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <delete-btn :okFunc="deleteBanner.bind(null, task.RecID)"> </delete-btn>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :pagination="pagination" :fetchFunc="fetchBannerList"> </Pagination>

                </div>
            </div>
        </div>
        <!--/.col-->

        <modal title="Modal title" class="modal-primary" v-model="primaryModal" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">{{ dataForm.id ? "Edit Data" : "Tambah Data" }}</h4>
            </div>

            <div class="card-block">
                <div class="form-group">
                    <label for="company">Judul</label>
                    <input name="judul" type="text" class="form-control" v-model="dataForm.judul" 
                    v-validate="'required'" value="{ dataForm.jdudul }" placeholder="Masukan Nama File" maxlength="50">
                    <span v-show="errors.has('judul')" class="help-block">judul diperlukan</span>
                </div>

                <div class="form-group">
                    <label for="company">Keterangan</label>
                    <input name="keterangan" type="text" class="form-control" v-model="dataForm.keterangan" 
                    v-validate="'required'" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan" maxlength="50">
                    <span v-show="errors.has('keterangan')" class="help-block">keterangan diperlukan</span>
                </div>

                <div class="form-group">
                    <upload-file :myFile="dataForm.myFile" :onFileChange="onFileChange" />
                    <span v-if="invalidFile" class="help-block">file tidak valid</span>
                </div>

            </div>

            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="primaryModal = false">Tutup</button>
                <button type="submit" class="btn btn-primary" @click="vaidateForm(dataForm.RecID)" v-if="ready">Simpan</button>
                <button type="submit" class="btn btn-primary disabled" v-if="!ready">
                    <i class="fa fa-spinner fa-spin" /> Loading</button>
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
    name: 'app',
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
                judul: '',
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
        }
    },

    created() {
        this.fetchBannerList();
    },

    watch: {
        primaryModal: function(val) {
            if (!val) {
                this.dataForm = {
                    id: '',
                    judul: '',
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
                if (e.target.files[0].type.match(/image.*/) ){
                    var vm = this;

                    vm.ready = false;
                    vm.invalidFile = false;
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    vm.fileName = files[0].name;
                    vm.myFile = files[0];

                    vm.dataForm.myFile.fileName = vm.fileName;
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
        fetchBannerList(page) {
            this.ready = false;
            axios.get('api/banner?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        popUpEditBanner(id) {
            this.ready = false;
            axios.get('api/banner/' + id)
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
                    this.editBanner(id);
                } else {
                    toastr.error('Ada Field Yang Belum Diisi!');
                }
            });
        },

        editBanner(id) {
            this.ready = false;
            if (id && id !== "") {
                axios.put('api/banner/' + id, this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchBannerList();
                        toastr.success('Data Berhasil Di Ubah');
                        this.ready = true;
                    })
                    .catch((err) => console.error(err));
            } else {
                axios.post('api/banner', this.dataForm)
                    .then((res) => {
                        this.primaryModal = false;
                        this.dataForm = {};
                        this.fetchBannerList();
                        toastr.success('Data Berhasil Di Simpan');
                        this.ready = true;
                    })
                    .catch((err) => {
                        console.log("err");
                    });
            }
        },

        deleteBanner(id) {
            this.ready = false;
            axios.delete('api/banner/' + id)
                .then((res) => {
                    this.dataForm = {};
                    this.deleteModal = false;
                    this.fetchBannerList();
                    toastr.success('Data Berhasil Di Delete');
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },


    },
}

</script>