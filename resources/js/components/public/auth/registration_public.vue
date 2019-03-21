<template>
    <div>
        <div class="modal-header">
            <h2>Регистрация</h2>
            <button class="close"
                    @click="$root.$emit('hide', 'registration')">
                &times;
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="">Email</label>
                <input class="form-control" v-model="registration.email">
            </div>
            <div class="form-group">
                <label for="">Фамилия</label>
                <input class="form-control" v-model="registration.last_name">
            </div>
            <div class="form-group">
                <label for="">Имя</label>
                <input class="form-control" v-model="registration.first_name">
            </div>
            <div class="form-group">
                <label for="">Отчество</label>
                <input class="form-control" v-model="registration.middle_name">
            </div>
            <div class="form-group">
                <label for="">Пароль</label>
                <input class="form-control" v-model="registration.password">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline-primary" @click="registrate">
                Зарегистрироваться
            </button>
        </div>
    </div>
</template>

<script>
  export default {
    name: "registration_public",
    data() {
      return {
        registration: {
          email: '',
          last_name: '',
          first_name: '',
          middle_name: '',
          birthdate: '',
          password: '',
        }
      }
    },
    methods: {
      async registrate() {
        const response = await axios.post('/registration');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.fillStorage(response.data);
        }
      },

      fillStorage(data) {
        localStorage.addItem('user', data.user);
        localStorage.addItem('token', data.token);
        localStorage.addItem('csrf_token', data.user.csrf_token);
      },
    }
  }
</script>

<style scoped>

</style>