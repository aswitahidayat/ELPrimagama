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
                <button @click="fetchMititiList()" class="input-group-addon"><i class="fa fa-search"></i></button>
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
                  <button type="button" class="btn btn-primary" @click="popUpEditMititi(task.RecID)"><i class="fa fa-edit"></i></button>
                  <button @click="popUpDeleteMititi(task)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>

          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1" >
                <a class="page-link" href="javascript:;" @click="fetchMititiList(pagination.current_page - 1)">Prev</a>
              </li>
              <li v-for="(page, index) in pagination.last_page" :key="index" v-bind:class="[ page == pagination.current_page ? 'active' : '']">
                <a href="javascript:;" @click="fetchMititiList(page)">{{ page }}</a>
              </li>
              <li class="page-item" v-if="pagination.current_page <  pagination.last_page">
                <a class="page-link" href="javascript:;" @click="fetchMititiList(pagination.current_page + 1)">Next</a>
              </li>
            </ul>
          </nav>

        </div>
      </div>
    </div><!--/.col-->
    
    <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editMititi(dataForm.RecID)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">{{ dataForm.id ? "Edit Data" : "Tambah Data" }}</h4>
      </div>

        <div class="card-block">
          <div class="form-group">
            <label for="company">File</label>
            <input name="nama" type="text" class="form-control" v-validate="'required'" v-model="dataForm.nmfile" value="{ dataForm.nmfile }" placeholder="Masukan Nama File">
            <span v-show="errors.has('nama')" class="help-block">nama diperlukan</span>
          </div>

          <div class="form-group">
            <label for="company">Keterangan</label>
            <input name="keterangan" type="text" class="form-control" v-validate="'required'" v-model="dataForm.keterangan" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan">
            <span v-show="errors.has('keterangan')" class="help-block">keterangan diperlukan</span>
          </div>

        </div>

        <div slot="modal-footer" class="modal-footer">
          <button type="button" class="btn btn-default" @click="dataForm = {};primaryModal = false">Tutup</button> 
          <button type="submit" class="btn btn-primary" @click="vaidateForm(dataForm.RecID)">Simpan</button>
        </div>
    </modal>

    <modal title="Modal title" class="modal-danger" v-model="deleteModal" @ok="deleteMititi(dataForm.RecID)" effect="fade/zoom">
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
  import { input as bsInput, formValidator } from 'vue-strap'
    
    export default {
      name: 'modals',
      components: {
        modal,
        formValidator,
        bsInput,
      },
      data() {
        return {
          valid: false,
          keyword: '',
          primaryModal: false,
          deleteModal: false,
          list: [],
          dataForm: {
            id: '',
            nmfile: '',
            keterangan: ''
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
          this.fetchMititiList();
        },
        
        methods: {
          fetchMititiList(page) {
            axios.get('api/mititi?page=' + page + '&keyword=' + this.keyword)
              .then((res) => {
                this.list = res.data;
                this.pagination = res.data.pagination;
              })
              .catch((err) => console.error(err));
            },
            
            popUpEditMititi(id){
              axios.get('api/mititi/' + id)
                .then((res) => {
                  this.primaryModal = true;
                  this.dataForm = res.data;
                })
                .catch((err) => console.error(err));
            },

            vaidateForm(id){
              this.$validator.validateAll().then((result) => {
                if (result) {
                  this.editMititi(id);
                }
                alert('Correct them errors!');
              });
            },

            editMititi(id) {
              
              if(id && id !== ""){
                 axios.put('api/mititi/' + id, this.dataForm)
                .then((res) => {
                  this.primaryModal = false;
                  this.dataForm = {};
                  this.fetchMititiList()
                })
                .catch((err) => console.error(err));
              } else {
                axios.post('api/mititi', this.dataForm)
                .then((res) => {
                  this.primaryModal = false;
                  this.dataForm = {};
                  this.fetchMititiList()
                })
              .catch((err) => {
                console.log("err");
              }); 
              }
            },

            popUpDeleteMititi(task){
              this.dataForm = task;
              this.deleteModal = true;
            },
            
            deleteMititi(id) {
              axios.delete('api/mititi/' + id)
                .then((res) => {
                  this.dataForm = {};
                  this.deleteModal = false;
                  this.fetchMititiList()
                })
                .catch((err) => console.error(err));
            },

        }
    }
</script>