<template>
    <div class="container">
        <form-search @search="setSearchUrl"></form-search>
        <div class="row mt-3">
            <div class="col-3">
                <button @click="clearFilter" class="btn btn-primary py-2 mb-4 w-100 text-white">
                    Đặt lại bộ lọc
                </button>
                <!--Lọc theo giá-->
                <p class="text-uppercase">Lọc theo khoảng giá/ đêm</p>
                <vue-slider
                        ref="slider"
                        v-model="price"
                        :min="0"
                        :max="10000000"
                        tooltip="none"
                        :dotSize="20"
                        :height="4"
                        :lazy="true"
                        @callback="changePrice"
                        class="mt-3"
                />
                <p class="mt-2">
                    {{convertCurrency(price[0], true)}} <span class="text-lowercase">vnd</span>
                    <span class="float-right">
                {{convertCurrency(price[1], true)}} <span class="text-lowercase">vnd</span></span>
                </p>
                <div>
                    <p class="semi-bold text-uppercase my-3">Loại homestay</p>
                    <label>
                        <input type="checkbox" class="mr-2" value="1" v-model="homestay_types">
                        Chung cư</label> <br>
                    <label>
                        <input type="checkbox" class="mr-2" value="2" v-model="homestay_types">
                        Biệt thự</label> <br>
                    <label>
                        <input type="checkbox" class="mr-2" value="3" v-model="homestay_types">
                        Nhà riêng</label>
                </div>
            </div>
            <div class="col-9">
                <div class="d-flex justify-content-between">
                    <p class="mb-0">Có {{homestays ? homestays.total : null}} kết quả homestay </p>
                    <div class="">
                        <select class="form-control my-1 d-none d-lg-block" v-model="orderKey" @change="sort(orderKey)">
                            <option value="">Sắp xếp theo</option>
                            <option value="newest">Mới nhất</option>
                            <option value="highestPrice">Giá cao nhất</option>
                            <option value="lowestPrice">Giá thấp nhất</option>
                            <option value="rate">Đánh giá</option>
                        </select>
                    </div>
                </div>
                <div v-if="homestays === null"></div>
                <div v-else-if="homestays.data.length === 0">
                    <p class="center mt-5 h5">
                        Không có kết quả. Thử tìm homestay và chọn ngày khác</p>
                </div>
                <div v-else>
                    <div v-for="homestay in homestays.data" :key="homestay.id">

                        <card-homestay :id="homestay.id"
                                       :name="homestay.name"
                                       :rating="homestay.rating"
                                       :address="homestay.address"
                                       :price="homestay.price"
                                       :homestay_type="homestay.homestay_type"
                                       class="mb-4"
                        >
                        </card-homestay>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <pagination v-if="homestays" :data="homestays" @pagination-change-page="changePage"
                                class="mx-auto"
                                :limit="2"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import vueSlider from 'vue-slider-component'

  export default {
    components: {vueSlider},
    data(){
      return {
        homestays: null,
        page: '',
        fetching: false,
        price: [0, 10000000],
        priceUrl: '',
        homestay_types: [],
        orderKey : '',
        orderByKeyUrl: '',
        searchUrl: ''
      }
    },
    methods: {
      changePage(page){
        this.page = '&page=' + page
      },
      clearFilter(){
        this.price = [0, 10000000]
        this.homestay_types = []
      },
      fetchHomestays(){
        this.fetching = true
        axios.get('/api/homestays' + this.str_query)
          .then(response => {
            this.homestays = response.data
            this.fetching = false
          }).catch(err => {
          this.fetching = false
        })
      },
      fetchHomestaysWithDelay(){
        clearTimeout(this.debounceTimeout)
        this.debounceTimeout = setTimeout(() => {
          this.fetchHomestays()
        }, 500)
      },
      changePrice(){
        this.priceUrl = `&price=${this.price[0]}-${this.price[1]}`
      },
      sort(sortKey) {
        this.orderByKeyUrl = '&orderBy=' + sortKey
      },
      setSearchUrl(search){
        this.searchUrl = '&search='+search
      }
    },
    watch: {
      str_query(val){
        if(history.pushState){
          let newurl = window.location.protocol + '//' + window.location.host + window.location.pathname + val
          window.history.pushState({path: newurl}, '', newurl)
        }
        this.fetchHomestays()
      }
    },
    computed: {
      str_query(){
        let filterUrl = 'homestay_types='
        this.homestay_types.map(val => filterUrl += val + '-')
        filterUrl = filterUrl.slice(0, -1)
        let query = filterUrl + this.priceUrl + this.orderByKeyUrl  + this.searchUrl  + this.page
        // return '?'+encodeURIComponent(query)
        return '?'+ query
      }
    },
    mounted(){
      this.fetchHomestays()
    }
  }
</script>