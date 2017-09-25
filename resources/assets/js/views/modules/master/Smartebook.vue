<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Smart Ebook Table
        </div>
        <div class="card-block">
          <div class="row">
            <div class="col-sm-3">
              <button type="button" class="btn btn-primary" @click="primaryModal = true"><i class="fa fa-plus"></i> Tambah</button>   
            </div>
            <div class="col-sm-9">
              <div class="input-group">
                <input type="text" id="username2" name="username2" v-model="keyword" placeholder="Cari" class="form-control">
                <button @click="fetchSmartebookList()" class="input-group-addon"><i class="fa fa-search"></i></button>
              </div>
            </div>
          </div>

          <table class="table table-bordered table-striped table-condensed">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Keterangan</th>
                <th>Jenjang 1</th>
                <th>Jenjang 2</th>
                <th>Kurikulum</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(task, index) in list.data" :key="index">
                <td>{{ index + list.pagination.from }}</td>
                <td>{{ task.nmfile }}</td>
                <td>{{ task.keterangan }}</td>
                <td>{{ task.jenjangdata.NamaJenjang }}</td>
                <td>{{ task.jenjang2data.NamaJenjang }}</td>
                <td>{{ task.kurikulum }}</td>
                <td>
                  <button type="button" class="btn btn-primary" @click="popUpEditSmartebook(task.idsb)"><i class="fa fa-edit"></i></button>
                  <button @click="popUpDeleteSmartebook(task)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
                </td>
              </tr>
            </tbody>
          </table>

          <nav>
            <ul class="pagination">
              <li class="page-item" v-if="pagination.current_page > 1" >
                <a class="page-link" href="javascript:;" @click="fetchSmartebookList(pagination.current_page - 1)">Prev</a>
              </li>
              <li v-for="(page, index) in pagination.last_page" :key="page.index" v-bind:class="[ page == pagination.current_page ? 'active' : '']">
                <a href="javascript:;" @click="fetchSmartebookList(page)">{{ page }}</a>
              </li>
              <li class="page-item" v-if="pagination.current_page <  pagination.last_page">
                <a class="page-link" href="javascript:;" @click="fetchSmartebookList(pagination.current_page + 1)">Next</a>
              </li>
            </ul>
          </nav>

        </div>
      </div>
    </div><!--/.col-->
    
    <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editSmartebook(dataForm.idsb)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">{{ dataForm.idsb ? "Edit Data" : "Tambah Data" }}</h4>
      </div>

        <div class="card-block">
          <form>
            <div class="form-group has-error">
              <label for="company">File</label>
              <input type="text" id="firstname" name="firstname" class="form-control" v-model="dataForm.nmfile" value="{ dataForm.nmfile }" placeholder="Masukan Nama" required >
            </div>

            <div class="form-group">
              <label for="company">Keterangan</label>
              <input type="text" class="form-control" v-model="dataForm.keterangan" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan">
            </div>

            <div class="form-group">
              <label for="company">Jenjang 1</label>
              <select class="form-control" v-model="dataForm.jenjang ">
                  <option
                      v-for="cat in this.listJenjang" 
                      :key="cat.KodeJenjang"
                      :value="cat.KodeJenjang"
                      :selected="cat.index == dataForm.jenjang">
                      {{ cat.NamaJenjang }}
                  </option>
              </select>
            </div>

            <div class="form-group">
              <label for="company">Jenjang 2</label>
              <select class="form-control" v-model="dataForm.jenjang2 ">
                  <option
                      v-for="cat in this.listJenjang" 
                      :key="cat.KodeJenjang"
                      :value="cat.KodeJenjang"
                      :selected="cat.KodeJenjang == dataForm.jenjang2">
                      {{ cat.NamaJenjang }}
                  </option>
              </select>
            </div>

            <div class="form-group">
              <label for="company">Kurikulum</label>
              <input type="text" class="form-control" v-model="dataForm.kurikulum" value="{ dataForm.kurikulum }" placeholder="Masukan Kurikulum">
            </div>
          </form>

        </div>

    </modal>

    <modal title="Modal title" class="modal-danger" v-model="deleteModal" @ok="deleteSmartebook(dataForm.idsb)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header"><h4 class="modal-title">Delete Data</h4></div>
      <div class="card-block"><div class="form-group"> <label for="company">Apakah kamu yakin? </label></div></div>
    </modal>
  </div><!--/.row-->

</template>

<script>
  import modal from 'vue-strap/src/Modal'
  import { input as bsInput, formValidator } from 'vue-strap'
  import vueToast from 'vue-toast'
    
    export default {
      name: 'modals',
      components: {
        modal,
        formValidator,
        bsInput,
        vueToast,
      },
      data() {
        return {
          valid: false,
          keyword: '',
          primaryModal: false,
          deleteModal: false,
          list: [],
          dataForm: {
            idsb: '',
            file: '',
            keterangan: ''
          },
          listJenjang: [],
          listKurikulum: [],
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
          this.fetchSmartebookList();
          this.getJenjang();
          this.getKurikulum();
        },
        
        methods: {
          fetchSmartebookList(page) {
            axios.get('api/smartebook?page=' + page + '&keyword=' + this.keyword)
              .then((res) => {
                this.list = res.data;
                this.pagination = res.data.pagination;
              })
              .catch((err) => console.error(err));
            },
          
          createSmartebook() {
            axios.post('api/smartebook', this.dataForm)
              .then((res) => {
                this.dataForm = {};
                this.fetchSmartebookList();
              })
              .catch((err) => {
                debugger;
                console.log("err");
              });
            },
            
            popUpEditSmartebook(id){
              axios.get('api/smartebook/' + id)
                .then((res) => {
                  this.primaryModal = true;
                  this.dataForm = res.data;
                })
                .catch((err) => console.error(err));
            },

            popUpDeleteSmartebook(task){
              this.dataForm = task;
              this.deleteModal = true;
            },

            editSmartebook(id) {
              if(id && id !== ""){
                 axios.put('api/smartebook/' + id, this.dataForm)
                .then((res) => {
                  this.primaryModal = false;
                  this.dataForm = {};
                  this.fetchSmartebookList()
                })
                .catch((err) => console.error(err));
              } else {
                axios.post('api/smartebook', this.dataForm)
                .then((res) => {
                  this.primaryModal = false;
                  this.dataForm = {};
                  this.fetchSmartebookList()
                })
              .catch((err) => console.error(err));
              }
            },

            deleteSmartebook(id) {
              axios.delete('api/smartebook/' + id)
                .then((res) => {
                  this.deleteModal = false;
                  this.dataForm = {};
                  this.fetchSmartebookList()
                })
                .catch((err) => console.error(err));
            },

            getJenjang(){
                axios.get('api/jenjang/')
                .then((res) => {
                  this.listJenjang = res.data;
                })
                .catch((err) => console.error(err));
            },

            getKurikulum(){
                axios.get('api/kurikulum/')
                .then((res) => {
                  this.listKurikulum = res.data;
                })
                .catch((err) => console.error(err));
            },

        }
    }
</script>