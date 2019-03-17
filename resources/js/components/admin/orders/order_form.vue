<template>
    <div class="row">
        <div class="col-4">
            <div class="form-group" v-if="customers.length">
                <label for="">Клиент</label>
                <select class="form-control" v-model="order.user_id">
                    <option :value="customer.id" v-for="customer in customers" :key="customer.id">
                        {{ customer.last_name }} {{ customer.first_name }} {{ customer.middle_name }}
                    </option>
                </select>
            </div>
            <div class="alert alert-info" v-else>
                Добавьте <router-link :to="'/admin/users/create'">пользователей</router-link>
            </div>
            <div class="form-group" v-if="rooms.length">
                <label for="">Комната</label>
                <select class="form-control" v-model="order.room_id">
                    <option :value="room.id" v-for="room in rooms" :key="room.id">
                        {{ room.number }}
                    </option>
                </select>
            </div>
            <div class="alert alert-info" v-else>
                Добавьте <router-link :to="'/admin/rooms/create'">комнаты</router-link>
            </div>
            <div class="form-group">
                <label for="">Статус</label>
                <select class="form-control" v-model="order.status">
                    <option value="0">Отказано</option>
                    <option value="1">Забронировано</option>
                    <option value="2">Оплачено</option>
                </select>
            </div>
            <div class="form-group">
                <label for="">Дата и время заселения</label>
                <datepicker v-model="order.note_date"
                            :language="ru"
                            :monday-first="true"
                            :required="true"
                            :bootstrap-styling="true">
                </datepicker>
                <vue-timepicker :minute-interval="5"
                                format="HH:mm"
                                v-model="order.note_time">
                </vue-timepicker>

            </div>
            <div class="form-group">
                <label for="">Продолжительность</label>
                <input type="text" class="form-control" v-model.trim.number="order.days">
            </div>
            <div class="form-group">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click.prevent="save()">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" v-else @click.prevent="save()">
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({name: 'orders'})">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  import { ru } from 'vuejs-datepicker/dist/locale';
  import VueTimepicker from 'vue2-timepicker'
  
  export default {
    name: "order_form",
    components: { Datepicker, VueTimepicker },
    data() {
      return {
        order: {
          note_date: Date.now(),
          note_time: {
            HH: '00',
            mm: '00'
          },
        },

        customers: [],
        rooms: [],

        ru: ru,

        errors: {
          name: {
            status: false,
            msg: ''
          },
          price: {
            status: false,
            msg: ''
          },
          description: {
            status: false,
            msg: ''
          }
        }
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/orders/update/' + this.$route.params.id, this.order);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          } else {
            this.$swal('Успешно!', response.data.msg, 'success');
            this.$router.push({ name: 'orders' });
            return true;
          }
        } else {
          const response = await axios.post('/orders/create', this.order);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          } else {
            this.$swal('Успешно!', response.data.msg, 'success');
            this.$router.push({ name: 'orders' });
            return true;
          }
        }
      },

      async loadData() {
        const response = await axios.get('/orders/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.order = response.data.order;
          return true;
        }
      },

      async loadExtends() {
        const response = await axios.get('/orders/form_info');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.customers = response.data.customers;
          this.rooms = response.data.rooms;
          return true;
        }
      }
    },

    created() {
      this.loadExtends();
      if (this.$route.params.id) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>

</style>