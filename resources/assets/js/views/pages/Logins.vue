<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card-group mb-0">
                        <div class="card p-2">
                            <div class="card-block">
                                <h1>Login</h1>
                                <p class="text-muted">Sign In to your account</p>
                                <div class="input-group mb-1">
                                    <span class="input-group-addon">
                                        <i class="icon-user"></i>
                                    </span>
                                    <input type="text" v-model="dataForm.email" class="form-control" placeholder="Username">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-addon">
                                        <i class="icon-lock"></i>
                                    </span>
                                    <input type="password" v-model="dataForm.password" class="form-control" placeholder="Password">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <button type="button" @click="login" class="btn btn-primary px-2">Login</button>
                                    </div>
                                    <div class="col-6 text-right">
                                        <button type="button" class="btn btn-link px-0">Forgot password?</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-inverse card-primary py-3 hidden-md-down" style="width:44%">
                            <div class="card-block text-center">
                                <div>
                                    <h2>Sign up</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                    <button type="button" class="btn btn-primary active mt-1">Register Now!</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import slicknav from '../../components/jquery.slicknav.min'

export default {
    name: 'Logins',
    components: {
        slicknav
    },
    data() {
        return {
            dataForm: {}
        }
    },
    methods: {
        login() {
            this.ready = false;
            axios.post('/api/login', this.dataForm)
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

        },
    }
}
</script>
