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
                <button @click="fetchKalpenList()" class="input-group-addon"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>

          <table class="table table-bordered table-striped table-condensed">
            <thead>
              <tr>
                <th>No</th>
                <th>Judul</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(task, index) in list.data" :key="index">
                <td>{{ index + list.pagination.from }}</td>
                <td>{{ task.judul }}</td>
                <td>{{ task.keterangan }}</td>
                <td>{{ task.tanggal }}</td>
                <td>
                  <button type="button" class="btn btn-primary" @click="popUpEditKalpen(task.RecID)"><i class="fa fa-edit"></i></button>
                  <button @click="popUpDeleteKalpen(task)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>

          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1" >
                <a class="page-link" href="javascript:;" @click="fetchKalpenList(pagination.current_page - 1)">Prev</a>
              </li>
              <li v-for="(page, index) in pagination.last_page" :key="index" v-bind:class="[ page == pagination.current_page ? 'active' : '']">
                <a href="javascript:;" @click="fetchKalpenList(page)">{{ page }}</a>
              </li>
              <li class="page-item" v-if="pagination.current_page <  pagination.last_page">
                <a class="page-link" href="javascript:;" @click="fetchKalpenList(pagination.current_page + 1)">Next</a>
              </li>
            </ul>
          </nav>

        </div>
      </div>
    </div><!--/.col-->
    
    <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editKalpen(dataForm.RecID)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">{{ dataForm.RecID ? "Edit Data" : "Tambah Data" }}</h4>
      </div>
        <div class="card-block">
          <div class="form-group">
            <label for="company">File</label>
            <input type="text" class="form-control" v-model="dataForm.judul" value="{ dataForm.judul }" 
                placeholder="Masukan Nama File">
          </div>

          <div class="form-group">
            <label for="company">Keterangan</label>
            <input type="text" class="form-control" v-model="dataForm.keterangan" value="{ dataForm.keterangan }" 
                placeholder="Masukan Keterangan" required>
          </div>

          <div class="form-group">
            <label for="company">Tanggal</label>
            <datepicker v-model="dataForm.tanggal" value="{ dataForm.tanggal }" :label="'File'" :format="'yyyy-MM-dd'" :placeholder="'Masukan Tanggal'"  ></datepicker>
          </div>
        </div>

        <div slot="modal-footer" class="modal-footer">
          <button type="button" class="btn btn-default" @click="resetDataFrom();primaryModal = false">Tutup</button> 
          <button type="submit" class="btn btn-primary" @click="editKalpen(dataForm.RecID)">Simpan</button>
        </div>
    </modal>

    <modal title="Modal title" class="modal-danger" v-model="deleteModal" @ok="deleteKalpen(dataForm.RecID)" effect="fade/zoom">
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
  import { input as bsInput, formValidator, datepicker } from 'vue-strap'
    
    export default {
      name: 'modals',
      components: {
        modal,
        formValidator,
        bsInput,
        datepicker,
      },
      data() {
        return {
          valid: false,
          keyword: '',
          primaryModal: false,
          deleteModal: false,
          list: [],
          dataForm: {
            RecID: '',
            judul: '',
            keterangan: '',
            tanggal: ''
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
          this.fetchKalpenList();
        },
        
        methods: {
          resetDataFrom(){
            this.dataForm = {
              RecID: '',
              judul: '',
              keterangan: '',
              tanggal: ''
            };
          },

          fetchKalpenList(page) {
            axios.get('api/kalpen?page=' + page + '&keyword=' + this.keyword)
              .then((res) => {
                this.list = res.data;
                this.pagination = res.data.pagination;
              })
              .catch((err) => console.error(err));
            },
          
          createKalpen() {
            axios.post('api/kalpen', this.dataForm)
              .then((res) => {
                this.dataForm = {};
                this.fetchKalpenList();
              })
              .catch((err) => console.error(err));
            },
            
            popUpEditKalpen(RecID){
              axios.get('api/kalpen/' + RecID)
                .then((res) => {
                  this.primaryModal = true;
                  this.dataForm = res.data;
                })
                .catch((err) => console.error(err));
            },

            editKalpen(RecID) {
              if(RecID && RecID !== ""){
                 axios.put('api/kalpen/' + RecID, this.dataForm)
                .then((res) => {
                  this.primaryModal = false;
                  this.dataForm = {};
                  this.fetchKalpenList();
                })
                .catch((err) => console.error(err));
              } else {
                axios.post('api/kalpen', this.dataForm)
                .then((res) => {
                  this.primaryModal = false;
                  this.dataForm = {};
                  this.fetchKalpenList();
                })
              .catch((err) => console.error(err));
              }
            },

            popUpDeleteKalpen(task){
              this.dataForm = task;
              this.deleteModal = true;
            },

            deleteKalpen(RecID) {
              axios.delete('api/kalpen/' + RecID)
                .then((res) => {
                  this.dataForm = {};
                  this.deleteModal = false;
                  this.fetchKalpenList();
                })
                .catch((err) => console.error(err));
            },

        }
    }
</script>