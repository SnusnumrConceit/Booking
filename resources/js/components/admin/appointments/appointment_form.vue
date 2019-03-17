<template>
    <div class="row">
        <div class="col-6">
            <form action="">
                <div class="form-group">
                    <label for="">Наименование</label>
                    <input type="text" class="form-control" v-model="appointment.name">
                    <span class="invalid-" v-if="errors.name.status">
                        {{ errors.name.msg }}
                    </span>
                    <button class="btn btn-outline-success" v-if="$route.params.id" @click.prevent="save()">
                        Сохранить
                    </button>
                    <button class="btn btn-outline-success" v-else @click.prevent="save()">
                        Добавить
                    </button>
                    <button class="btn btn-outline-secondary" @click="$router.push({name: 'appointments'})">
                        Отмена
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
  export default {
    name: "appointment_form",
    data() {
      return {
        appointment: {
          name: ''
        },

        errors: {
          name: {
            status: false,
            msg: ''
          }
        }
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/appointments/update/' + this.$route.params.id, this.appointment);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
          } else {
            this.$swal('Успешно!', response.data.msg, 'success');
            this.$router.push({ name: 'appointments'});
            return true;
          }
        } else {
          const response = await axios.post('/appointments/create', this.appointment);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          } else {
            this.$swal('Успешно!', response.data.msg, 'success');
            this.$router.push({ name: 'appointments'});
            return true;
          }
        }
      },

      async loadData() {
        const response = await axios.get('/appointments/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.appointment = response.data.appointment;
          return true;
        }
      }
    },
    watch: {
      'appointment.name': function () {
        if (! this.appointment.name.length) {
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