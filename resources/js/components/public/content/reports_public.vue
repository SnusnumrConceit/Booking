<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="reports-container" v-if="reports.length">
                    <div class="card report-card" v-for="report in reports">
                        <div class="card-header">
                            {{ report.user.full_name }}
                        </div>
                        <div class="card-body">
                            {{ report.description }}
                        </div>
                        <div class="card-footer">
                            {{ report.created_at }}
                        </div>
                    </div>
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
                    <div class="alert alert-info" v-else>
                        Пока никто не оставлял отзывов :(
                    </div>
                </div>
                <div class="reports-form">
                    <button class="btn btn-outline-success" @click="">
                        Оставить отзыв
                    </button>
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

</style>