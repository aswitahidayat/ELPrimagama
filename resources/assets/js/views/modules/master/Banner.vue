<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Tips N Trik Table
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-sm-3">
              <button type="button" class="btn btn-primary" @click="primaryModal = true"><i class="fa fa-plus"></i> Tambah</button>   
            </div>
            <div class="col-sm-9">
              <div class="input-group">
                <input type="text" id="username2" name="username2" v-model="keyword" placeholder="Cari" class="form-control">
                <button @click="fetchBakmiList()" class="input-group-addon"><i class="fa fa-search"></i></button>
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
                  <button type="button" class="btn btn-primary" @click="popUpEditBakmi(task.RecID)"><i class="fa fa-edit"></i></button>
                  <button @click="popUpDeleteBakmi(task)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>

          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1" >
                <a class="page-link" href="javascript:;" @click="fetchBakmiList(pagination.current_page - 1)">Prev</a>
              </li>
              <li v-for="(page, index) in pagination.last_page" :key="index" v-bind:class="[ page == pagination.current_page ? 'active' : '']">
                <a href="javascript:;" @click="fetchBakmiList(page)">{{ page }}</a>
              </li>
              <li class="page-item" v-if="pagination.current_page <  pagination.last_page">
                <a class="page-link" href="javascript:;" @click="fetchBakmiList(pagination.current_page + 1)">Next</a>
              </li>
            </ul>
          </nav>

        </div>
      </div>
    </div><!--/.col-->
    
    <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editBakmi(dataForm.RecID)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">{{ dataForm.id ? "Edit Data" : "Tambah Data" }}</h4>
      </div>

        <div class="card-block">
          <div class="form-group">
            <label for="company">File</label>
            <input type="text" class="form-control" v-model="dataForm.nmfile" value="{ dataForm.nmfile }" placeholder="Masukan Nama File">
          </div>

          <div class="form-group">
            <label for="company">Keterangan</label>
            <input type="text" class="form-control" v-model="dataForm.keterangan" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan">
          </div>

          <div class="form-group">
            <label for="company">Keterangan</label>
            <file-upload @fileChange="onClickChild"></file-upload>
          </div>

        </div>

        <div slot="modal-footer" class="modal-footer">
          <button type="button" class="btn btn-default" @click="dataForm = {};primaryModal = false">Tutup</button> 
          <button type="submit" class="btn btn-primary" @click="editBakmi(dataForm.RecID)">Simpan</button>
        </div>
    </modal>

    <modal title="Modal title" class="modal-danger" v-model="deleteModal" @ok="deleteBakmi(dataForm.RecID)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">Delete Data</h4>
      </div>

      <div class="card-block">
         <div class="form-group">
              <label for="company">Apakah kamu yakin? </label>
         </div>
      </div>
    </modal>
  </div><!--/.row-->

</template>

<script>
  import modal from 'vue-strap/src/Modal'
  export default {
    name: 'app',
    components: {
        modal,
      },
    data() {
      return {
        keyword: '',
        myPhoto: '',
      }
    },
   
    methods: {
      onClickChild (value) {
        console.log(value);
        this.myPhoto = value;
      },

      onFileChange(e) {
        let files = e.target.files || e.dataTransfer.files;
        if (!files.length)
            return;
        this.myPhoto = files[0]
      },
      upload(){
        let formData = new FormData()
        formData.append('img', this.myPhoto)
        axios.post('api/banner', formData)
              .then((res) => {
                //
              })
              .catch((err) => console.error(err));
      }
    },
  }

</script>