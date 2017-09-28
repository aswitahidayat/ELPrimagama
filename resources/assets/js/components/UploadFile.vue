<template>
    <div>
        <label for="company">File</label>
        <input type="file" v-on:change="onFileChange" class="form-control" v-if="!myFile.uploadFile">
        <div v-if="myFile.uploadFile">
            <img :src="myFile.uploadFile" class="form-control" style="width: 100px;" v-if="myFile.fileType == 'image/png' || myFile.fileType == 'image/jpg' || myFile.fileType == 'image/jpeg' || myFile.fileType == 'image/gif'" />
            <i class="fa fa-file-pdf-o" v-if="myFile.fileType == 'application/pdf'"></i>
            <button type="button" @click="myFile.uploadFile =''" class="btn btn-sm btn-primary">Hapus</button>
        </div>
    </div>
</template>

<script>
import modal from 'vue-strap/src/Modal'

export default {
    created() {
    },
    data() {
        return {
            image: '',
            ready: true,
        }
    },
    props: {
        myFile: {
            type: Object,
            default: function() {
                return { message: 'hello' }
            }
        },
        onFileChange: {
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
        showDialog() {
        },

    }
}
</script>