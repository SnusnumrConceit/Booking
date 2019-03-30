<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Отзывы</h2>
                    </div>
                    <!--<button class="btn btn-outline-success" @click="$router.push({ path: '/admin/reports/create' })">-->
                        <!--Добавить-->
                    <!--</button>-->
                    <div class="col-md-2 players__search">
                        <datepicker v-model="date"
                                    :language="ru"
                                    :monday-first="true"
                                    :required="true"
                                    :bootstrap-styling="true">
                        </datepicker>
                    </div>
                </div>
                <div class="table-responsive table--no-card m-b-40" v-if="reports.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                            <th @click="setFilter('description')" class="text-left">
                                Описание
                                <i class="fa fa-sort-amount-up" v-if="filter.name === 'description' && filter.type === 'DESC'"></i>
                                <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'description' && filter.type === 'ASC'"></i>
                            </th>
                            <th @click="setFilter('created_at')" class="text-left">
                                Дата публикации
                                <i class="fa fa-sort-amount-up" v-if="filter.name === 'created_at' && filter.type === 'DESC'"></i>
                                <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'created_at' && filter.type === 'ASC'"></i>
                            </th>
                            <th></th>
                        </thead>
                        <tbody v-for="(report, index) in reports" :key="report.id">
                            <td>{{ report.description }}</td>
                            <td>{{ report.created_at }}</td>
                            <td>
                                <i class="fa fa-cog text-success" @click="$router.push({path: '/admin/reports/' + report.id})"></i>
                                <i class="fa fa-trash text-danger" @click="remove(index, report.id)"></i>
                            </td>
                        </tbody>
                    </table>
                    <paginate v-model="pagination.page"
                              v-if="pagination.last_page"
                              :page-count="pagination.last_page"
                              :container-class="'pagination'"
                              :page-class="'page-item'"
                              :page-link-class="'page-link'"
                              :prev-text="'Пред.'"
                              :prev-class="'page-item'"
                              :prev-link-class="'page-link'"
                              :next-text="'След.'"
                              :next-class="'page-item'"
                              :next-link-class="'page-link'"
                              :click-handler="switchPage"></paginate>
                </div>
                <div class="alert alert-info" v-else>
                    Не найдено ни одного отзыва
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import {ru} from 'vuejs-datepicker/dist/locale';

  export default {
    name: "reports",
    components: {Datepicker},
    data() {
      return {
        reports: [],

        pagination: {
          page: 1,
          last_page: 1,
        },

        date: new Date().toString(),
        ru: ru,

        search: {
          isSearch: false,
          processing: false, //для дисабли кнопки
        },

        filter: {
          name: '',
          type: ''
        },
      }
    },


    computed: {
      isSearch() {
        return this.search.isSearch;
      }
    },

    methods: {
      switchPage(page) {
        this.pagination.page = page;
        if (!this.search.isSearch) {
          this.loadData();
        } else {
          this.searchData();
        }
        return true;
      },

      setFilter(name) {
        switch (this.filter.type) {
          case '':
            this.filter.type = 'DESC';
            break;
          case 'DESC':
            this.filter.type = 'ASC';
            break;
          case 'ASC':
            this.filter.type = 'DESC';
            break;
          default:
            break;
        }
        this.filter.name = name;
        this.search.isSearch = true;
        this.switchPage(1);
      },

      resetSearch() {
        this.search.isSearch = false;
        this.switchPage(1);
        return true;
      },

      searchWrap() {
        this.search.isSearch = true;
        this.search.processing = true;
        this.switchPage(1);
      },

      async remove(index, id) {
        const response = await axios.post('/reports/remove/' + id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.$swal('Успешно!', response.data.msg, 'success');
          this.reports.splice(index, 1);
        }
      },

      async searchData() {
        const response = await axios.get('/reports/search', { params: {
            date: this.date,
            filter: {...this.filter},
            page: this.pagination.page
          }});
        if (response.status !== 200) {
          this.$swal('Ошибка', response.data.msg, 'error');
          this.search.processing = false;
          return false;
        } else {
          this.search.processing = false;
          this.reports = response.data.reports.data;
          this.pagination.last_page = response.data.reports.last_page;
        }
      },

      async loadData() {
        const response = await axios.get('/reports', { params: { page: this.pagination.page} });
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.reports = response.data.reports.data;
          this.pagination.last_page = response.data.reports.last_page;
        }
      }
    },

    watch: {
      'date': function (after, before) {
        if (after === before) {
          return false;
        }
        if (!after.length) {
          this.resetSearch();
          return true;
        }
        this.searchWrap();
      },
    },

    created() {
      this.loadData();
    },
  }
</script>

<style scoped>

</style>