<template>
    <div class="container-fluid p-t-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-4">
                        <h2 class="title-1 m-b-25">Заказы</h2>
                    </div>
                    <button class="btn btn-outline-success" @click="$router.push({ path: '/admin/orders/create' })">
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
                <div class="table-responsive table--no-card m-b-40" v-if="orders.length">
                    <table class="table table-borderless table-striped table-earning">
                        <thead>
                        <th @click="setFilter('last_name')" class="text-left">
                            ФИО
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'last_name' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'last_name' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('number')" class="text-left">
                            Комната
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'number' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'number' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('price')" class="text-left">
                            Стоимость
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'price' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'price' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('days')" class="text-left">
                            Продолжительность
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'days' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'days' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('status')" class="text-left">
                            Статус
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'status' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'status' && filter.type === 'ASC'"></i>
                        </th>
                        <th @click="setFilter('note_date')" class="text-left">
                            Дата и время заселения
                            <i class="fa fa-sort-amount-up" v-if="filter.name === 'note_date' && filter.type === 'DESC'"></i>
                            <i class="fa fa-sort-amount-down" v-else-if="filter.name === 'note_date' && filter.type === 'ASC'"></i>
                        </th>
                        <th></th>
                        </thead>
                        <tbody v-for="(order, index) in orders" :key="order.id">
                            <td>{{ order.customer.last_name }} {{ order.customer.first_name }} {{ order.customer.middle_name }}</td>
                            <td>{{ order.room.number }} </td>
                            <td>{{ order.room.price }} </td>
                            <td>{{ order.days }} </td>
                            <td>
                                <span class="text-danger" v-if="order.status_type === 0">
                                    <i>{{ order.status }}</i>
                                </span>
                                <span class="text-info" v-else-if="order.status_type === 1">
                                    <i>{{ order.status }}</i>
                                </span>
                                <span class="text-success" v-else-if="order.status_type === 2">
                                    <i>{{ order.status }}</i>
                                </span>
                            </td>
                            <td>{{ order.note_date }} </td>
                            <td>
                                <i class="fa fa-cog text-success" @click="$router.push({path: '/admin/orders/' + order.id})"></i>
                                <i class="fa fa-trash text-danger" @click="remove(index, order.id)"></i>
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
                    Не найдено ни одного заказа
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import debounce from 'v-debounce';

  export default {
    name: "orders",
    directives: {debounce},
    data() {
      return {
        orders: [],

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
        const response = await axios.post('/orders/remove/' + id);
        if (response.status !== 200 || response.data.status === 'error') {
          this.$swal('Ошибка!', response.data.msg, 'error');
          return false;
        } else {
          this.$swal('Успешно!', response.data.msg, 'success');
          this.orders.splice(index, 1);
        }
      },

      async searchData() {
        console.log(this.filter);
        const response = await axios.get('/orders/search', {
          params: {
            keyword: this.search.keyword,
            filter: {...this.filter},
            page: this.pagination.page
          }
        });
        if (response.status !== 200) {
          this.$swal('Ошибка', response.data.msg, 'error');
          this.search.processing = false;
          return false;
        } else {
          this.search.processing = false;
          this.orders = response.data.orders.data;
          this.pagination.last_page = response.data.orders.last_page;
        }
      },

      async loadData() {
        const response = await axios.get('/orders', { params: { page: this.pagination.page} });
        if (response.status !== 200 || !response.data.status === 'error') {
          console.log(response.data.msg);
        } else {
          this.orders = response.data.orders.data;
          this.pagination.last_page = response.data.orders.last_page;
        }
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

</style>