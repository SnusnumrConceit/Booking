<template>
    <div class="row">
        <div class="col-12">
            <div class="slider">
                <vue-flux
                        :options="fluxOptions"
                        :images="photos"
                        :transitions="fluxTransitions"
                        ref="slider">
                    <flux-pagination slot="pagination"></flux-pagination>
                </vue-flux>

                <!--<button @click="$refs.slider.showImage('next')">Далее</button>-->
            </div>
        </div>
    </div>
</template>

<script>
  import { VueFlux, FluxPagination, Transitions } from 'vue-flux';

  export default {
    name: "general",
    components: {
      VueFlux,
      FluxPagination
    },

    data(){
      return {
        fluxOptions: {
          autoplay: true
        },
        fluxImages: [
            'https://www.sunhome.ru/i/wallpapers/81/zakat-nad-vodopadom.1920x1080.jpg',
            'https://hdwallsbox.com/wallpapers/l/1920x1080/76/green-landscapes-nature-forests-grass-mill-rivers-watermill-1920x1080-75569.jpg',
            'http://s1.1zoom.me/b5050/137/371625-svetik_1440x900.jpg'
        ],
        fluxTransitions: {
          transitionFade: Transitions.transitionFade
        },
        photos: []
      }
    },
    methods: {
      async loadData() {
        const response = await axios.get('/photos/public');
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'warning');
          return false;
        }
        this.photos = response.data.photos;
        // this.$refs.slider.preloadImages(this.photos);
      }
    },

    mounted() {
      this.loadData();
    }
  }
</script>

<style>
    .slider {
        width: 100%;
        height: 100%;
    }

    .mask {
        height: 75vh !important;
    }
</style>