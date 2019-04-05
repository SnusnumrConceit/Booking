<template>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="">Фамилия</label>
                <input type="text" class="form-control" v-model.trim="employee.last_name">
            </div>
            <div class="form-group">
                <label for="">Имя</label>
                <input type="text" class="form-control" v-model.trim="employee.first_name">
            </div>
            <div class="form-group">
                <label for="">Отчество</label>
                <input type="text" class="form-control" v-model.trim="employee.middle_name">
            </div>
            <div class="form-group">
                <label for="">Дата рождения</label>
                <datepicker v-model="employee.birthday"
                            :language="ru"
                            :monday-first="true"
                            :required="true"
                            :bootstrap-styling="true">
                </datepicker>
            </div>
            <div class="form-group" v-if="appointments.length">
                <label for="">Должность</label>
                <select type="text" class="form-control" v-model.trim="employee.appointment_id">
                    <option :value="appointment.id"
                            v-for="appointment in appointments"
                            :key="appointment.id">
                        {{ appointment.name }}
                    </option>
                </select>
            </div>
            <div class="alert alert-info" v-else>
                Добавьте <router-link :to="'/admin/appointments/create'">должности</router-link>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click.prevent="save()">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" v-else @click.prevent="save()">
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({name: 'employees'})">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import {ru} from 'vuejs-datepicker/dist/locale';

  export default {
    name: "employee_form",
    components: { Datepicker },
    data() {
      return {
        employee: {
            birthday: new Date().toString()
        },

        appointments: [],

        ru: ru,

        // errors: {
        //   name: {
        //     status: false,
        //     msg: ''
        //   },
        //   price: {
        //     status: false,
        //     msg: ''
        //   },
        //   description: {
        //     status: false,
        //     msg: ''
        //   }
        // }

        errors: [],
        
        swal: {
          errors: [],
          message: ``,
        }
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/employees/update/' + this.$route.params.id, this.employee);
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
            this.$router.push({ name: 'employees' });
            return true;
          }
        } else {
          const response = await axios.post('/employees/create', this.employee);
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
            this.$router.push({ name: 'employees' });
            return true;
          }
        }
      },

      async loadData() {
        const response = await axios.get('/employees/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.employee = response.data.employee;
          return true;
        }
      },

      async loadExtends() {
        const response = await axios.get('/employees/form_info');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.appointments = response.data.appointments;
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

    created() {
      if (this.$route.params.id) {
        this.loadData();
      }
      this.loadExtends();
    }
  }
</script>

<style scoped>

</style>