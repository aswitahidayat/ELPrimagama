<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Smartbook Table
        </div>
        <div class="card-block">
          <button type="button" class="btn btn-primary" @click="primaryModal = true"><i class="fa fa-plus"></i> Tambah</button>
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
                <td>{{ task.nama }}</td>
                <td>{{ task.keterangan }}</td>
                <td>{{ task.jenjang1data.NamaJenjang }}</td>
                <td>{{ task.jenjang2data.NamaJenjang }}</td>
                <td>{{ task.kurikulumdata.NamaKurikulum }}</td>
                <td>
                  <button type="button" class="btn btn-primary" @click="popUpEditSmartebook(task.id)"><i class="fa fa-edit"></i></button>
                  <button @click="deleteSmartebook(task.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
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
    
    <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editSmartebook(dataForm.id)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">{{ dataForm.id ? "Edit Data" : "Tambah Data" }}</h4>
      </div>

        <div class="card-block">
          <div class="form-group">
            <label for="company">File</label>
            <input type="text" class="form-control" v-model="dataForm.nama" value="{ dataForm.nama }" placeholder="Masukan Nama">
          </div>

          <div class="form-group">
            <label for="company">Keterangan</label>
            <input type="text" class="form-control" v-model="dataForm.keterangan" value="{ dataForm.keterangan }" placeholder="Masukan Keterangan">
          </div>

          <div class="form-group">
            <label for="company">Jenjang 1</label>
            <select class="form-control" v-model="dataForm.jenjang1 ">
                <option
                    v-for="cat in this.listJenjang" 
                    :key="cat.KodeJenjang"
                    :value="cat.KodeJenjang"
                    :selected="cat.KodeJenjang == dataForm.jenjang1">
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
            <select class="form-control" v-model="dataForm.kurikulum ">
                <option
                    v-for="cat in this.listKurikulum" 
                    :key="cat.KodeKurikulum"
                    :value="cat.KodeKurikulum"
                    :selected="cat.KodeKurikulum == dataForm.kurikulum">
                    {{ cat.NamaKurikulum }}
                </option>
            </select>    
          </div>

        </div>

    </modal>
  </div><!--/.row-->

</template>

<script>
  import modal from 'vue-strap/src/Modal'
    
    export default {
      name: 'modals',
      components: {
        modal
      },
      data() {
        return {
          primaryModal: false,
          list: [],
          dataForm: {
            id: '',
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
            axios.get('api/smartebook?page=' + page)
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
              .catch((err) => console.error(err));
            },
            
            popUpEditSmartebook(id){
              axios.get('api/smartebook/' + id)
                .then((res) => {
                  this.primaryModal = true;
                  this.dataForm = res.data;
                })
                .catch((err) => console.error(err));
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
                  this.fetchSmartebookList()
                })
                .catch((err) => console.error(err));
            },

            getJenjang(){
                axios.get('api/jenjang/')
                .then((res) => {
                  this.listJenjang = res.data;
                  console.log(this.listJenjang);
                })
                .catch((err) => console.error(err));
            },

            getKurikulum(){
                axios.get('api/kurikulum/')
                .then((res) => {
                  this.listKurikulum = res.data;
                  console.log(this.listKurikulum);
                })
                .catch((err) => console.error(err));
            },

            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;
            },

            changePage: function (page) {
                this.pagination.current_page = page;
                this.fetchItems(page);
            }
        }
    }
</script>