<template>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="">Фотография</label>
                <button class="btn btn-outline-secondary" @click="showCropper">
                    Загрузить
                </button>
                <my-upload field="img"
                           @crop-success="cropSuccess"
                           @crop-upload-success="cropUploadSuccess"
                           @crop-upload-fail="cropUploadFail"
                           v-model="show"
                           :width="300"
                           :height="300"
                           :langType="'ru'"
                           url="/photos/upload"
                           :headers="{ 'X-CSRF-TOKEN': csrf_token}"></my-upload>
                <img :src="tmp" alt="" v-if="tmp.length">
            </div>
            <div class="form-group" v-if="rooms.length">
                <label for="">Комната</label>
                <select v-model="photo.room" class="form-control">
                    <option :value="room.id" v-for="room in rooms" :key="room.id">
                        {{ room.number }}
                    </option>
                </select>
            </div>
            <div class="alert alert-info" v-else>
                Добавьте <router-link :to="'/rooms'">комнаты</router-link>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-success" @click.prevent="save()">
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({name: 'photos'})">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import MyUpload from 'vue-image-crop-upload';
  import { mapGetters } from 'vuex';

  export default {
    name: "photo_form",
    components: { MyUpload },
    data() {
      return {
        photo: {
          tmp_path: '',
          destination: '',
          room: ''
        },

        rooms: [],

        tmp: '',

        show: false,
      }
    },
    computed: {
        ...mapGetters('Auth', {
          csrf_token: 'getCSRF'
        })
    },
    methods: {
      showCropper() {
        this.show = !this.show;
      },

      cropSuccess(imgDataUrl, field) {
        this.tmp = imgDataUrl;
      },

      cropUploadSuccess(response, field) {
        console.log('-------- upload success --------');
        console.log(response);
        console.log('field: ' + field);
        this.photo.tmp_path = response.tmp_path;
        this.photo.destination = response.tmp_path.replace('tmp', 'photos');
      },

      cropUploadFail(status, field) {
        onsole.log('-------- upload fail --------');
        this.$swal('Ошибка', status, 'error');
        console.log('field: ' + field);
      },

      async save() {
        const response = await axios.post('/photos/create', this.photo);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', status, 'error');
          return false;
        } else {
          this.$swal('Успешно!', 'Фотография успешно загружена', 'success');
          this.$router.push({name: 'photos'});
        }
      },

      async loadExtends() {
        const response = await axios.get('/rooms');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', status, 'error');
          return false;
        } else {
            this.rooms = response.data.rooms;
        }
      }
    },

    created() {
      this.loadExtends();
    }
  }
</script>

<style scoped>

</style>