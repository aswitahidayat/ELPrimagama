<template>
    <div>
        <navbar>
            <button class="navbar-toggler mobile-sidebar-toggler hidden-lg-up" type="button" @click="mobileSidebarToggle">&#9776;</button>
            <a class="navbar-brand" href="#"></a>
            <ul class="nav navbar-nav hidden-md-down">
                <li class="nav-item">
                    <a class="nav-link navbar-toggler sidebar-toggler" href="#" @click="sidebarToggle">&#9776;</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item">
                    <dropdown size="nav" class="nav-item">
                        <span slot="button">
                            <i class="icon-bell"></i>
                            <span class="badge badge-pill badge-danger">{{jumlahPesan}}</span>
                        </span>
                        <div slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">

                            <div class="dropdown-header text-center">
                                <strong>Account</strong>
                            </div>

                            <router-link class="dropdown-item" :to="'/master/semuapesan'">
                                <i class="fa fa-bell-o"></i> Pesan
                                <span class="badge badge-info">{{jumlahPesan}}</span>
                            </router-link>

                        </div>
                    </dropdown>
                </li>
                <dropdown size="nav" class="nav-item">
                    <span slot="button">
                        <img src="/static/img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        <span class="hidden-md-down">admin</span>
                    </span>
                    <div slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>Settings</strong>
                        </div>

                        <a class="dropdown-item" href="#">
                            <i class="fa fa-user"></i> Profile</a>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-wrench"></i> Settings</a>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-usd"></i> Payments
                            <span class="badge badge-default">42</span>
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-file"></i> Projects
                            <span class="badge badge-primary">42</span>
                        </a>
                        <div class="divider"></div>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-shield"></i> Lock Account</a>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-lock"></i> Logout</a>
                    </div>
                </dropdown>
                <li class="nav-item hidden-md-down">&nbsp;</li>
            </ul>
        </navbar>
        <modal title="Modal title" class="modal-primary" v-model="pesanModal" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">Semua Pesan</h4>
            </div>
            <div class="card-block">
                <table class="table table-bordered table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Pesan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(task, index) in list.data" :key="index">
                            <td>
                                <strong>{{ task.judul }}</strong> <br/> {{ task.tanggal }} <br/>
                                <p>{{ task.keterangan }}</p>
                                <button type="button" class="btn btn-primary" @click="popUpEditKalpen(task.RecID)">
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <Pagination :pagination="pagination" :fetchFunc="fetchPesanList"> </Pagination>

            </div>

            <div slot="modal-footer" class="modal-footer">

            </div>

        </modal>
    </div>
</template>
<script>
import modal from 'vue-strap/src/Modal'
import navbar from './Navbar'
import { dropdown } from 'vue-strap'
import { EventBus } from './event-bus.js';

export default {
    name: 'header',
    components: {
        navbar,
        dropdown,
        EventBus,
        modal,
    },
    data() {
        return {
            jumlahPesan: 0,
            pesanModal: false,
            list: [],
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
        var vm = this;
        vm.setTheInterval();

        EventBus.$on('hitungUlangPesan', function() {
            debugger;
            vm.hitungPesan();
        }),
            vm.hitungPesan();
    },
    methods: {

        click() {
            // do nothing
        },
        sidebarToggle(e) {
            e.preventDefault()
            document.body.classList.toggle('sidebar-hidden')
        },
        mobileSidebarToggle(e) {
            e.preventDefault()
            document.body.classList.toggle('sidebar-mobile-show')
        },
        fetchPesanList(page) {
            axios.get('/api/semuagpesan')
                .then((res) => {
                    this.list = res.data;
                    this.pagination = res.data.pagination;
                })
                .catch((err) => console.error(err));
        },
        hitungPesan() {
            axios.get('/api/hitungpesan')
                .then((res) => {
                    this.jumlahPesan = res.data;
                })
                .catch((err) => console.error(err));
        },

        //         for (var i = 0; i < 10; i++) {
        //      setTheInterval(i);
        //  },

        setTheInterval() {
            setInterval(function() {
                this.hitungPesan();
            }.bind(this), 10000);
        }

        // asideToggle (e) {
        //   e.preventDefault()
        //   document.body.classList.toggle('aside-menu-hidden')
        // }
    },

    ready: function() {
        // this.hitungPesan();

        setInterval(function() {
            this.hitungPesan();
        }.bind(this), 3);

        setInterval(function() {
            console.log(i);
        }, 3000);
    }
}
</script>

<style lang="css">
.dropdown-toggle::after {
    /*display: none !important;*/
}
</style>
