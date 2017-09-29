<template>
    <span>
        <button @click="deleteModal = true" class="btn btn-danger btn-xs">
            <i class="fa fa-trash"></i>
        </button>
        <modal title="Modal title" class="modal-danger" v-model="deleteModal" effect="fade/zoom">
            <div slot="modal-header" class="modal-header">
                <h4 class="modal-title">Delete Data</h4>
            </div>

            <div class="card-block">
                <div class="form-group">
                    <label for="company">Apakah kamu yakin? </label>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="deleteModal = false">Tidak</button>
                <button type="submit" class="btn btn-primary" @click="callFunc">Ya</button>
            </div>
        </modal>
    </span>
</template>

<script>
import modal from 'vue-strap/src/Modal'

export default {
    created() {
    },
    data() {
        return {
            image: '',
            deleteModal: false,
        }
    },
    props: {
        okFunc: {
            type: Function,
            required: true
        },
    },
    components: {
        modal,
    },
    events: {
        showDialogChild: function() {
            this.showDialog();
        }
    },
    methods: {
        callFunc() {
            this.deleteModal = false;
            this.okFunc();
        },
    }
}
</script>