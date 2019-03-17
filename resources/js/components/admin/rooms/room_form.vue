<template>
    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="">Номер комнаты</label>
                <input type="text" class="form-control" v-model.number="room.number">
            </div>
            <div class="form-group">
                <label for="">Цена</label>
                <input type="text" class="form-control" v-model.number="room.price">
            </div>
            <div class="form-group">
                <label for="">Описание</label>
                <textarea type="text" class="form-control" v-model="room.description">
                </textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-outline-success" v-if="$route.params.id" @click.prevent="save()">
                    Сохранить
                </button>
                <button class="btn btn-outline-success" v-else @click.prevent="save()">
                    Добавить
                </button>
                <button class="btn btn-outline-secondary" @click="$router.push({name: 'rooms'})">
                    Отмена
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: "room_form",
    data() {
      return {
        room: {},

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
          const response = await axios.post('/rooms/update/' + this.$route.params.id, this.room);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          } else {
            this.$swal('Успешно!', response.data.msg, 'success');
            this.$router.push({ name: 'rooms' });
            return true;
          }
        } else {
          const response = await axios.post('/rooms/create', this.room);
          if (response.status !== 200 || response.data.status === 'error') {
            this.$swal('Ошибка!', response.data.msg, 'error');
            return false;
          } else {
            this.$swal('Успешно!', response.data.msg, 'success');
            this.$router.push({ name: 'rooms' });
            return true;
          }
        }
      },

      async loadData() {
        const response = await axios.get('/rooms/edit/' + this.$route.params.id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.room = response.data.room;
          return true;
        }
      }
    },

    created() {
      if (this.$route.params.id) {
        this.loadData();
      }
    }
  }
</script>

<style scoped>

</style>