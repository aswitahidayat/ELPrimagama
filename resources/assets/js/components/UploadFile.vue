<template>
    <div>
        <label for="company">File</label>
        <input type="file" v-on:change="onFileChange" class="form-control" v-if="!myFile.uploadFile">
        <div v-if="myFile.uploadFile">
            <img :src="myFile.uploadFile" class="form-control" style="width: 100px;" @click="fileModal =true" v-if="myFile.fileType == 'image/png' || myFile.fileType == 'image/jpg' || myFile.fileType == 'image/jpeg' || myFile.fileType == 'image/gif'" />
            <div v-else-if="myFile.fileType == 'application/pdf'">
                <img src="/static/icon-pdf.png"  class="form-control" style="width: 100px;" @click="fileModal =true">
                {{ myFile.fileName }}
            </div>
            <div v-else>
                <img src="/static/icon-file.png"  class="form-control" style="width: 100px;" @click="fileModal =true">
                {{ myFile.fileName }}
            </div>
            <button type="button" @click="myFile.uploadFile =''" class="btn btn-sm btn-primary">Hapus</button>
        </div>
        <modal title="Modal title" large class="modal-primary" v-model="fileModal" effect="fade/zoom">
            <div slot="modal-header" class="">
            </div>

            <div class="">
                <div class="form-group">
                    <img :src="myFile.uploadFile" class="form-control" v-if="myFile.fileType == 'image/png' || myFile.fileType == 'image/jpg' || myFile.fileType == 'image/jpeg' || myFile.fileType == 'image/gif'" />
                    <iframe :src="myFile.uploadFile" alt="pdf" @click="fileModal =true" v-if="myFile.fileType == 'application/pdf'" style="width: 100%; height: 400px" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html" />
                </div>
            </div>
            <div slot="modal-footer" class="">
            </div>
        </modal>
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
            fileModal: false,
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