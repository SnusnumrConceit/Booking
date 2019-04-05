<template>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="">Фамилия</label>
                <input type="text" class="form-control" v-model.trim="user.last_name">
            </div>
            <div class="form-group">
                <label for="">Имя</label>
                <input type="text" class="form-control" v-model.trim="user.first_name">
            </div>
            <div class="form-group">
                <label for="">Отчество</label>
                <input type="text" class="form-control" v-model.trim="user.middle_name">
            </div>
            <div class="form-group">
                <label for="">Дата рождения</label>
                <datepicker v-model="user.birthday"
                            :language="ru"
                            :monday-first="true"
                            :required="true"
                            :bootstrap-styling="true">
                </datepicker>
            </div>
            <div class="form-group">
                <label for="">Роль</label>
                <select name="" id="" class="form-control" v-model="user.role">
                    <option :value="role.id" v-for="role in roles">
                        {{ role.name }}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="">email</label>
                <input type="text" class="form-control" v-model.trim="user.email">
            </div>
            <div class="form-group">
                <label for="">Пароль</label>
                <input type="password" class="form-control" v-model.trim="user.password">
            </div>
            <div class="form-group">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click.prevent="save()">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" v-else @click.prevent="save()">
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({name: 'users'})">
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
    name: "user_form",
    components: { Datepicker },
    data() {
      return {
        user: {
          birthday: new Date().toString(),
          role: ''
        },

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
        // },

        errors: [],
        swal: {
          errors: [],
          message: ``,
        },

        roles: []
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/users/update/' + this.$route.params.id, this.user);
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
            this.$router.push({name: 'users'});
            return true;
          }
        } else {
          const response = await axios.post('/users/create', this.user);
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
            this.$router.push({name: 'users'});
            return true;
          }
        }
      },

      async loadData() {
        const response = await axios.get('/users/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.user = response.data.user;
          return true;
        }
      },

      async loadExtendData() {
        const response = await axios.get('/users/form_info');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.roles = response.data.roles;
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
      this.loadExtendData();
    }
  }
</script>

<style scoped>

</style>