<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Комнаты</h2>
                    </div>
                    <button class="btn btn-outline-success" @click="$router.push({ path: '/admin/rooms/create' })">
                        Добавить
                    </button>
                    <div class="col-md-4 players__search">
                        <div class="input-group">
                            <!--<div class="input-group-btn">-->
                            <!--<button class="btn btn-secondary" :disabled="search.processing" @click="searchWrap">-->
                            <!--<i class="fa fa-search"></i>-->
                            <!--</button>-->
                            <!--</div>-->
                            <input type="text"
                                   class="form-control"
                                   style="font-size: 14px;"
                                   v-model.lazy="search.keyword"
                                   v-debounce="300"
                                   placeholder="Поиск...">
                            <button v-if="isSearch && search.keyword.length" @click="resetSearch">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive table--no-card m-b-40" v-if="rooms.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <th @click="setFilter('number')" class="text-left">
                            Номер комнаты
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'number' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'number' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('price')" class="text-left">
                            Цена
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'price' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'price' && filter.type === 'ASC'"></i>
                        </th>
                        <th></th>
                        </thead>
                        <tbody v-for="(room, index) in rooms" :key="room.id">
                            <td @click="showModal(room.id)">{{ room.number }}</td>
                            <td>{{ room.price }} ₽</td>
                            <td>
                                <i class="fa fa-cog text-success" @click="$router.push({path: '/admin/rooms/' + room.id})"></i>
                                <i class="fa fa-trash text-danger" @click="remove(index, room.id)"></i>
                            </td>
                        </tbody>
                    </table>
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
                </div>
                <div class="alert alert-info" v-else>
                    Не найдено ни одной комнаты
                </div>
            </div>
        </div>
        <modal name="room_modal" :height="'auto'">
            <div class="modal-header">
                {{ room_info.number }}
            </div>
            <div class="modal-body">
                <h2>{{ room_info.price }}</h2>
                <div class="room-info__photos-container" v-if="room_info.photos.length">
                    <div class="card col-2" v-for="photo in room_info.photos">
                        <img :src="photo.url" @click="showLightbox(photo.name)">
                    </div>
                </div>
                <lightbox id="mylightbox"
                          ref="lightbox"
                          :images="room_info.photos"
                          :directory="pictures.thumbnailDir"
                          :timeoutDuration="5000"
                ></lightbox>
                <div class="room-info__description">
                    {{ room_info.description }}
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
  import debounce from 'v-debounce';

  export default {
    name: "rooms",
    directives: {debounce},
    data() {
      return {
        rooms: [],

        pictures: {
          thumbnailDir: 'http://booking.ru'
        },

        room_info: {
          number: '',
          description: '',
          price: '',
          photos: []
        },

        pagination: {
          page: 1,
          last_page: 1,
        },

        search: {
          keyword: '',
          isSearch: false,
          processing: false, //для дисабли кнопки
        },

        filter: {
          name: '',
          type: ''
        },
      }
    },


    computed: {
      isSearch() {
        return this.search.isSearch;
      }
    },

    methods: {
      switchPage(page) {
        this.pagination.page = page;
        if (!this.search.isSearch) {
          this.loadData();
        } else {
          this.searchData();
        }
        return true;
      },

      setFilter(name) {
        switch (this.filter.type) {
          case '':
            this.filter.type = 'DESC';
            break;
          case 'DESC':
            this.filter.type = 'ASC';
            break;
          case 'ASC':
            this.filter.type = 'DESC';
            break;
          default:
            break;
        }
        this.filter.name = name;
        this.search.isSearch = true;
        this.switchPage(1);
      },

      resetSearch() {
        this.search.isSearch = false;
        this.search.keyword = '';
        this.switchPage(1);
        return true;
      },

      searchWrap() {
        this.search.isSearch = true;
        this.search.processing = true;
        this.switchPage(1);
      },

      async remove(index, id) {
        const response = await axios.post('/rooms/remove/' + id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.$swal('Успешно!', response.data.msg, 'success');
          this.rooms.splice(index, 1);
        }
      },

      async searchData() {
        const response = await axios.get('/rooms/search', { params: {
          keyword: this.search.keyword,
          filter: {...this.filter},
          page: this.pagination.page
        }});
        if (response.status !== 200) {
          this.$swal('Ошибка', response.data.msg, 'error');
          this.search.processing = false;
          return false;
        } else {
          this.search.processing = false;
          this.rooms = response.data.rooms.data;
          this.pagination.last_page = response.data.rooms.last_page;
        }
      },

      async loadData() {
        const response = await axios.get('/rooms', { params: { page: this.pagination.page} });
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.rooms = response.data.rooms.data;
          this.pagination.last_page = response.data.rooms.last_page;
        }
      },

      async loadInfo(id) {
        const response = await axios.get('/rooms/info/' + id);
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.room_info = response.data.room;
        }
      },

      showModal(id) {
        this.loadInfo(id);
        this.$modal.show('room_modal');
      },

      hideModal() {
        this.$modal.hide('room_modal');
      },

      showLightbox(imageName) {
        this.$refs.lightbox.show(imageName);
      },
    },

    watch: {
      'search.keyword': function (after, before) {
        if (after === before) {
          return false;
        }
        if (!after.length) {
          this.resetSearch();
          return true;
        }
        this.searchWrap();
      },
    },

    created() {
      this.loadData();
    },
  }
</script>

<style scoped lang="scss">
    .room-info {
        &__photos-container {
            display: flex;
        }
    }
    th, td:first-child {
        cursor: pointer;
    }
</style>