<template>
    <div class="container-fluid">
        <div class="container-content">
            <header class="row">
                <h2 class="col-2">
                    Booking.ru
                </h2>
                <div class="offset-7" v-if="! user">
                    <button class="btn btn-link" @click="showModal('registration')">
                        Регистрация
                    </button>
                    <button class="btn btn-link" @click="showModal('login')">
                        Войти
                    </button>
                </div>
                <div class="offset-7" v-else>
                    <button class="btn btn-link" @click="showModal('cabinet')">
                        {{ user.full_name }}
                    </button>
                    <button class="btn btn-link" @click="logout">
                        Выйти
                    </button>
                </div>
            </header>
            <main>
                <div class="">
                    <ul class="nav justify-content-center row nav-tabs">
                        <li :class="(tabs.main) ? 'active' : ''"
                            class="nav-link col-3 tab"
                            @click="activate('main')">
                            <router-link :to="'/main'">
                                Главная
                            </router-link>
                        </li>
                        <li :class="(tabs.public_rooms) ? 'active' : ''"
                            class="nav-link col-3 tab"
                            @click="activate('public_rooms')">
                            <router-link :to="'/rooms'">
                                Номера
                            </router-link>
                        </li>
                        <li :class="(tabs.reports) ? 'active' : ''"
                            class="nav-link col-3 tab"
                            @click="activate('public_reports')">
                            <router-link :to="'/reports'">
                                Отзывы
                            </router-link>
                        </li>
                        <li :class="(tabs.contacts) ? 'active' : ''"
                            class="nav-link col-3 tab"
                            @click="activate('contacts')">
                            <router-link :to="'/contacts'">
                                Контакты
                            </router-link>
                        </li>
                    </ul>
                </div>
                <div class="content">
                    <router-view></router-view>
                </div>
            </main>
        </div>
        <footer>
            <div class="text-center">
                Разработано в 2019 году
            </div>
        </footer>
        <modal name="login" height="auto">
            <login></login>
        </modal>
        <modal name="registration" height="auto">
            <registration></registration>
        </modal>
        <modal name="cabinet">
            <cabinet></cabinet>
        </modal>
    </div>
</template>

<script>
  import Login from '../public/auth/login_public';
  import Registration from '../public/auth/registration_public';
  import Cabinet from '../public/content/cabinet';

  import { mapGetters, mapActions } from 'vuex';

  export default {
    name: "public",
    components: { Login, Registration, Cabinet },
    data() {
      return {

        tabs: {},
      }
    },
    computed: {
        ...mapGetters('Auth', {
          'user': 'getUser'
        })
    },
    methods: {
      showModal(modal) {
        this.$modal.show(modal);
      },

      hideModal(modal) {
        this.$modal.hide(modal);
      },

      clearStorage() {
        localStorage.removeItem('user');
        this.setUser('');
        localStorage.removeItem('token');
        localStorage.removeItem('csrf_token');
      },

      activate(tab) {
        this.tab = {};
        this.tab[tab] = true;
        this.$router.push({ name: tab});
      },

        ...mapActions('Auth', {
          'setUser': 'setUser'
        }),

      async logout() {
        const response = await axios.post('/logout');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.clearStorage();
          this.user = {};
        }
      },
    },
    mounted() {
      this.$root.$on('hide', (modal) => {
        this.hideModal(modal);
      });
    }
  }
</script>

<style scoped lang="scss">
    .nav-tabs {
        .tab {
            text-align: center;
            padding: 20px 0px;
            font-size: 2em;
        }
        
        .tab:hover {
            background: #2b6fb9;
            cursor: pointer;
            a {
                color: #fff;
            }
        }
    }
    
    a {
        color: #2b6fb9;
    }
    .container-content {
        height: 97vh;
    }

    .btn-link {
        color: #d05d06;
        font-size: 1.3em;
    }
</style>