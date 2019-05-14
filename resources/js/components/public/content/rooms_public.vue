<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="rooms-container" v-if="rooms.length">
                    <div class="card-container row">
                        <div class="card col-6" v-for="(room, room_index) in rooms">
                            <div class="card-header">
                                {{ room.number }}
                            </div>
                            <div class="card-body">
                                <div class="row m-b-40">
                                    <div class="col-3" v-if="room.photos.length">
                                        <img :src="room.photos[0].url" alt="">
                                    </div>
                                    <div class="offset-3" v-else></div>
                                    <div class="col-9">
                                        <truncate :clamp="'...'" :length="50" :less="'Скрыть'" :text="room.description"></truncate>
                                    </div>
                                </div>
                                <div class="row float-right">
                                    <h3 class="p-l-15">{{ room.price }}</h3>
                                    <button :class="(room.free) ? 'btn btn-primary' : 'btn btn-secondary'" @click="showModal(room)" :disabled="! room.free">
                                        Забронировать
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-2" v-for="(photo, photo_index) in room.photos">
                                        <img :src="photo.url" @click="showLightbox(room_index, photo.url)">
                                    </div>
                                    <lightbox id="my_lightbox"
                                              ref="public_lightbox"
                                              :images="room.photos"
                                              :directory="pictures.thumbnailDir"
                                              :timeoutDuration="5000"
                                    ></lightbox>
                                </div>
                            </div>
                        </div>
                    </div>
                    <paginate v-model="pagination.page"
                              v-if="pagination.last_page > 1"
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
                </div>
                <div class="alert alert-warning" v-else>
                    Ведутся технические работы. Повторите позднее.
                </div>
            </div>
        </div>
        <modal name="order" height="auto" :scrollable="true">
            <div class="modal-header">
                <h3>Ваш заказ</h3>
            </div>
            <div class="modal-body">
                <p>Покупатель: {{ user.full_name }}</p>
                <p>Номер: {{ order.number }}</p>
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
                    <label for="">Пребывание</label>
                    <input type="number" class="form-control" v-model.number="order.days" @change="amount">
                </div>
                <p>Стоимость: {{ order.price }}</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" @click="makeOrder()">
                    Подтвердить
                </button>
            </div>
        </modal>
    </div>
</template>

<script>
  import {mapGetters, mapActions} from 'vuex';
  import truncate from 'vue-truncate-collapsed';
  import Lightbox from 'vue-my-photos';
  import Datepicker from 'vuejs-datepicker';
  import { ru } from 'vuejs-datepicker/dist/locale';
  import VueTimepicker from 'vue2-timepicker'

  export default {
    name: "rooms_public",
    components: { truncate, 'lightbox': Lightbox, Datepicker, VueTimepicker },
    data() {
      return {
        rooms: [],

        ru: ru,

        pagination: {
          page: 1,
          last_page: 1
        },

        order: {},
        
        tmp_price: '',

        pictures: {
          thumbnailDir: 'http://booking.ru'
        },
      }
    },

    computed: {
        ...mapGetters('Auth', {
          'user': 'getUser'
        }),
    },

    methods: {
      amount() {
        if (this.order.days > 7) {
          this.order.price = (this.order.price * this.order.days) * 0.95;
        }
         this.order.price = (this.order.price * this.order.days);
        if (this.order.price === 0) {
          this.order.price = this.tmp_price;
        }
      },

      switchPage(page) {
        this.pagination.page = page;
        if (!this.search.isSearch) {
          this.loadData();
        } else {
          this.searchData();
        }
        return true;
      },

      showLightbox(index, imageName) {
        // console.log(index, imageName, this.$refs.lightbox);
        this.$refs.public_lightbox[index].show();
      },

      showModal(room) {
        this.fillOrder(room);
        this.tmp_price = this.order.price;
        this.$modal.show('order');
      },

      hideModal() {
        this.$modal.hide('order');
      },

      fillOrder(room) {
        this.order = {
          ...room,
          note_date: new Date().toString(),
          note_time: {
            HH: '00',
            mm: '00'
          },};
        this.order.days = '';
      },

      async makeOrder() {
        this.order.room_id = this.order.id;
        const response = await axios.post('/orders/publish', this.order);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.hideModal();
          this.$swal('Успешно!', response.data.msg, 'success');
        }
      },

      async loadData() {
        const response = await axios.get('/rooms/public', {params: {page: this.pagination.page}});
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.rooms = response.data.rooms.data;
          this.pagination.last_page = response.data.rooms.last_page;
        }
      },
    },
    mounted() {
      this.loadData();
    }
  }
</script>

<style scoped>

</style>