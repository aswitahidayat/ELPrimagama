<template>
  <div>
    <modal title="Modal title" class="modal-danger" v-model="show" @ok="okFunc(dataForm.RecID)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">Delete Data</h4>
      </div>

      <div class="card-block">
         <div class="form-group">
           <label for="company">Apakah kamu yakin? </label>
         </div>
      </div>
      <div slot="modal-footer" class="modal-footer">
           <button type="button" class="btn btn-default" >Tidak</button> 
           <button type="submit" class="btn btn-primary" >Ya</button>
          </div>
    </modal>
  </div>
</template>

<script>
  import modal from 'vue-strap/src/Modal'

  export default{
    created() {
    },
    data(){
      return {
        image: '',
        show: false,
      }
    },
    props: ['id', 'show'],
    components: {
        modal,
    },
    events: {
            	showDialogChild: function() {
                	this.showDialog();
                }
            },
    methods: {
      showDialog(){
        this.show = true;
      },
      okFunc(e) {
        let files = e.target.files || e.dataTransfer.files;
          if (!files.length)
              return;
          this.createImage(files[0]);
          this.$emit('fileChange', files[0])
      },
    }
  }
</script>