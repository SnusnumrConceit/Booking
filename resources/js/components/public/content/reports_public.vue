<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="reports-container row" v-if="reports.length">
                    <div class="card report-card col-4" v-for="report in reports">
                        <!--<div class="card-header">-->
                            <!--{{ report.user.full_name }}-->
                        <!--</div>-->
                        <div class="card-body">
                            {{ report.description }}
                        </div>
                        <div class="card-footer">
                            {{ report.created_at }}
                        </div>
                    </div>
                </div>
                <div class="alert alert-info" v-else>
                    Пока никто не оставлял отзывов :(
                </div>
                <paginate v-model="pagination.page"
                          v-if="pagination.last_page && reports.length"
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
                <div class="reports-form">
                    <button class="btn btn-outline-success" @click="toggleForm" >
                        Оставить отзыв
                    </button>
                    <transition name="fade">
                        <div class="form-group m-t-20" v-if="show">
                            <textarea cols="30" rows="10" v-model="report.description" class="form-control col-12 m-b-20"></textarea>
                            <button class="btn btn-outline-primary" @click="publish()">
                                Опубликовать
                            </button>
                        </div>
                    </transition>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "reports_public",
    data() {
      return {
        reports: [],

        report: {
          description: '',
          user_id: 4,
        },

        show: false,

        pagination: {
          page: 1,
          last_page: 1,
        },
      }
    },

    methods: {
      switchPage(page) {
        this.pagination.page = page;
        this.loadData();
        return true;
      },

      toggleForm() {
        this.show = !this.show;
      },

      async publish() {
        const response = await axios.post('/reports/create', this.report);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.$swal('Успешно!', response.data.msg, 'success');
          console.log(response.data.report);
          this.reports.unshift(Object.assign({}, response.data.report));
          this.report = {};
          this.toggleForm();
          return true;
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

    created() {
      this.loadData();
    }
  }
</script>

<style scoped>
    .reports-container {
        display: flex;
    }
    .card {
        padding: 0px;
        border-radius: 14px;        
    }
</style>