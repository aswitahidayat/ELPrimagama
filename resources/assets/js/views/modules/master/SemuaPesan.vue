<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Tips N Trik Table
                </div>
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-group" style="padding-bottom:10px;">
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
                                <th>Pesan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(task, index) in list.data" :key="index">
                                <td v-on:click="popupPesan(task)" style="cursor:pointer">
                                    <div class="row">
                                        <div class="col-sm-9">
                                            <strong>{{ task.judul }}</strong> <br/> {{ task.tanggal }} <br/>
                                            <p>{{ task.keterangan }}</p>
                                        </div>
                                        <div class="col-sm-3">
                                            <strong>
                                                <span v-if="!task.id_berita">New</span>
                                            </strong>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <Pagination :pagination="pagination" :fetchFunc="fetchPesanList"> </Pagination>

                </div>
            </div>
        </div>
        <!--/.col-->

        <modal title="Modal title" class="modal-primary" v-model="pesanModal" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <span>
                    <h4 class="modal-title">{{ dataForm.judul }}</h4>
                    <p>{{ dataForm.tanggal }}</p>
                </span>
            </div>

            <div class="card-block">
                <div class="form-group">
                    <p>{{ dataForm.keterangan }}</p>
                </div>
            </div>

            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="pesanModal = false">Tutup</button>
                <button type="submit" class="btn btn-primary" @click="vaidateForm(dataForm.RecID)">Delete</button>
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
            pesanModal: false,
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
        pesanModal: function(val) {
            if (!val) {
                this.dataForm = {
                    id: '',
                    judul: '',
                    keterangan: '',
                    Tanggal: '',
                };
                this.fetchPesanList();
            }

        }
    },

    methods: {

        fetchPesanList(page) {
            this.ready = false;
            axios.get('/api/semuagpesan?page=' + page + '&keyword=' + this.keyword)
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                    this.ready = true;
                })
                .catch((err) => console.error(err));
        },

        popupPesan(task) {
            if (!task.id_berita) {
                this.ready = false;
                axios.post('/api/semuagpesan', task)
                    .then((res) => {
                        this.ready = true;
                    })
                    .catch((err) => console.error(err));
            }
            this.dataForm = task;
            this.pesanModal = true;
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