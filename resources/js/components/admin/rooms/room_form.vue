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
        // }
        errors: [],
        
        swal: {
          errors: [],
          message: ``
        }
      }
    },
    methods: {
      async save() {
        if (this.$route.params.id) {
          const response = await axios.post('/rooms/update/' + this.$route.params.id, this.room);
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
            this.$router.push({ name: 'rooms' });
            return true;
          }
        } else {
          const response = await axios.post('/rooms/create', this.room);
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
    }
  }
</script>

<style scoped>

</style>