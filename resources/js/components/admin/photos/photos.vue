<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Комнаты</h2>
                    </div>
                    <button class="btn btn-outline-success" @click="$router.push({ path: '/admin/photos/create' })">
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
                <div class="room-container" v-if="rooms.length">
                    <div class="card p-3" v-for="(room, room_index) in rooms" :key="room.id" @dblclick="showPhotos(room_index)">
                        {{ room.number }}
                        <div class="room-photos-container">
                            <div class="card col-2" v-for="(photo, photo_index) in room.photos.photos">
                                <img :src="photo.url" alt="" @click="showLightbox(room_index, photo.name)">
                                <div class="card-footer">
                                    <button class="btn btn-outline-danger" @click="remove(photo.id, photo_index, room_index)">
                                        Удалить
                                    </button>
                                </div>
                            </div>
                            <lightbox id="mylightbox"
                                      ref="lightbox"
                                      :images="room.photos.photos"
                                      :directory="pictures.thumbnailDir"
                                      :timeoutDuration="5000"
                            ></lightbox>
                        </div>
                    </div>
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
                    Не найдено ни одной комнаты с фотографиями
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import debounce from 'v-debounce';
  import Lightbox from 'vue-my-photos';

  export default {
    name: "photos",
    directives: {debounce},
    components: { 'lightbox': Lightbox},
    data() {
      return {
        rooms: [],

        show: {},
        flag: false,

        pictures: {
          thumbnailDir: 'http://booking.ru'
        },

        pagination: {
          page: 1,
          last_page: 1,
        },

        search: {
          keyword: '',
          isSearch: false,
          processing: false, //для дисабли кнопки
        }
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

      async remove(id, photo_index, room_index) {
        const response = await axios.post('/photos/remove/' + id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.$swal('Успешно!', response.data.msg, 'success');
          this.rooms[room_index].photos.splice(photo_index, 1);
        }
      },

      async searchData() {
        const response = await axios.get('/photos/search', {
          params: {
            keyword: this.search.keyword,
            page: this.pagination.page
          }
        });
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
        const response = await axios.get('/photos', {params: {page: this.pagination.page}});
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.rooms = response.data.rooms.data;
          this.pagination.last_page = response.data.rooms.last_page;
        }
      },

      showLightbox(index, imageName) {
        this.$refs.lightbox[index].show(imageName);
      },

      showPhotos(index) {
        this.show[index] = !this.flag;
        this.flag = !this.flag;
      }
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

<style scoped>
    .room-photos-container {
        display: flex;
    }
    .card {
        cursor: pointer;
    }
    div {
        user-select:none;
        -webkit-user-select: none;
        -moz-user-select: none;
    }
</style>