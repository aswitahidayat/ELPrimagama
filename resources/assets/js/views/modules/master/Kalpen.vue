<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <i class="fa fa-align-justify"></i> Tips N Trik Table
        </div>
        <div class="card-block">
          <button type="button" class="btn btn-primary" @click="primaryModal = true"><i class="fa fa-plus"></i> Tambah</button>
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
                  <button type="button" class="btn btn-primary" @click="popUpEditKalpen(task.id)"><i class="fa fa-edit"></i></button>
                  <button @click="deleteKalpen(task.id)" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>
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
    
    <modal title="Modal title" class="modal-primary" v-model="primaryModal" @ok="editKalpen(dataForm.id)" effect="fade/zoom">
      <div slot="modal-header" class="modal-header">
        <h4 class="modal-title">{{ dataForm.id ? "Edit Data" : "Tambah Data" }}</h4>
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
            <datepicker v-model="dataForm.tanggal" value="{ dataForm.tanggal }" placeholder="Masukan Tanggal"></datepicker>
          </div>

        </div>

    </modal>
  </div><!--/.row-->

</template>

<script>
  import modal from 'vue-strap/src/Modal'
  import datepicker from 'vue-date'
    
    export default {
      name: 'modals',
      components: {
        modal,
        datepicker
      },
      data() {
        return {
          primaryModal: false,
          list: [],
          dataForm: {
            id: '',
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
          fetchKalpenList(page) {
            axios.get('api/kalpen?page=' + page)
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
            
            popUpEditKalpen(id){
              axios.get('api/kalpen/' + id)
                .then((res) => {
                  this.primaryModal = true;
                  this.dataForm = res.data;
                })
                .catch((err) => console.error(err));
            },

            editKalpen(id) {
              if(id && id !== ""){
                 axios.put('api/kalpen/' + id, this.dataForm)
                .then((res) => {
                  this.primaryModal = false;
                  this.dataForm = {};
                  this.fetchKalpenList()
                })
                .catch((err) => console.error(err));
              } else {
                axios.post('api/kalpen', this.dataForm)
                .then((res) => {
                  this.primaryModal = false;
                  this.dataForm = {};
                  this.fetchKalpenList()
                })
              .catch((err) => console.error(err));
              }
            },

            deleteKalpen(id) {
              axios.delete('api/kalpen/' + id)
                .then((res) => {
                  this.fetchKalpenList()
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