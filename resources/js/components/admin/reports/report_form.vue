<template>
    <div class="row">
        <div class="col-6">
            <form action="">
                <div class="form-group">
                    <label for="">Описание</label>
                    <textarea class="form-control"
                              v-model="report.description">
                    </textarea>
                    <!--<span class="invalid-" v-if="errors.description.status">-->
                        <!--{{ errors.description.msg }}-->
                    <!--</span>-->
                </div>
                <div class="form-group">
                    <button class="btn btn-outline-success" v-if="$route.params.id" @click.prevent="save()">
                        Сохранить
                    </button>
                    <button class="btn btn-outline-success" v-else @click.prevent="save()">
                        Добавить
                    </button>
                    <button class="btn btn-outline-secondary" @click="$router.push({name: 'reports'})">
                        Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
  export default {
    name: "report_form",
    data() {
      return {
        report: {
          description: '',
          user_id: 4
        },

        // errors: {
        //   description: {
        //     status: false,
        //     msg: ''
        //   }
        // },

        errors: [],

        swal: {
          errors: [],
          message: ``
        },
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/reports/update/' + this.$route.params.id, this.report);
          if (response.status !== 200 || response.data.status === 'error') {
            this.swal.errors = (response.data.errors !== undefined) ? response.data.errors : {};
            this.swal.message = this.getSwalMessage();
            this.$swal({
              title: 'Ошибка!',
              html: response.data.msg + this.swal.message,
              type: 'error'
            });
            return false;
          } else {
            this.$swal('Успешно!', response.data.msg, 'success');
            this.$router.push({ name: 'reports'});
            return true;
          }
        } else {
          const response = await axios.post('/reports/create', this.report);
          if (response.status !== 200 || response.data.status === 'error') {
            this.swal.errors = (response.data.errors !== undefined) ? response.data.errors : {};
            this.swal.message = this.getSwalMessage();
            this.$swal({
              title: 'Ошибка!',
              html: response.data.msg + this.swal.message,
              type: 'error'
            });
            return false;
          } else {
            this.$swal('Успешно!', response.data.msg, 'success');
            this.$router.push({ name: 'reports'});
            return true;
          }
        }
      },

      async loadData() {
        const response = await axios.get('/reports/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.report = response.data.report;
          return true;
        }
      },

      getSwalMessage() {
        return (Object.keys(this.swal.errors).length) ?
            `<div class="alert alert-danger m-t-20">
                        <ul class="p-l-20 p-r-20">
                            ${Object.values(this.swal.errors).map(err => `<li class="text-danger">${err[0]}</li>`)}
                        </ul>
                </div>`
            : '';
      }
    },
    watch: {
      'report.description': function () {
        if (! this.report.description.length) {
          this.errors.status = true;
          this.errors.msg = 'Наименование является обязательным полем';
        }
      }
    },

    created() {
      if (this.$route.params.id) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>

</style>