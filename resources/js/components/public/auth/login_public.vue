<template>
    <div>
        <div class="modal-header">
            <h2>Авторизация</h2>
            <button class="close"
                    @click="$root.$emit('hide', 'login')">
                &times;
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="">Email</label>
                <input class="form-control" v-model="login.email">
            </div>
            <div class="form-group">
                <label for="">Пароль</label>
                <input class="form-control" v-model="login.password">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-outline-primary" @click="authorize">
                Войти
            </button>
        </div>
    </div>
</template>

<script>
  export default {
    name: "login_public",
    data() {
      return {
        login: {
          email: '',
          password: ''
        },
      }
    },

    methods: {
      async authorize() {
        const response = await axios.post('/login');
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