<template>
    <div>
        <form action="" @submit.prevent="searchHomestay">
            <input type="text" class="pop-trigger form-control"
                   data-pop="#suggest" aria-describedby="address"
                   placeholder="Nhập địa điểm hoặc tên homestay bạn muốn đi"
                   name="address"
                   autocomplete="off"
                   v-model="address"
                   @focus="getSuggestions"
                   @input="autoComplete"
                   @keydown.up="keyUp"
                   @keydown.down="keyDown"
                   @keyup.enter="selectSuggestion"
            />
        </form>
        <div class="suggest pop" id="suggest">
            <div class="bg-white border">
                <div v-if="suggestions===null">
                </div>
                <div v-else-if="suggestions.length === 0"
                     class="py-2">
                    <p class="mb-0">Không có kết quả nào cho <span class="h5">"{{address}}"</span></p>
                </div>
                <div v-else >
                    <!--Locations-->
                    <div v-for="(suggest, i) in suggestions"
                         :key="suggest.id"
                         @click="selectSuggestion"
                         @mouseover="cursor = i"
                         class="d-flex align-items-center py-2"
                         :class="{'bg-xam' : (cursor === i)}"
                    >
                        <div style="min-width: 40px">
                            <div v-if="suggest.type === 1" class=" mx-3">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div v-if="suggest.type === 2" class=" mx-3">
                                <i class="fas fa-home"></i>
                            </div>
                        </div>
                        <div class="h6 mb-0">{{suggest.name}}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
  import { CoolSelect } from "vue-cool-select";

  export default {
    components: {
      CoolSelect
    },
    data: () => ({
      suggestions: null,
      address: '',
      search: '',
      debounceTimeout: null, //Chống spam server
      loading: false,
      cursor: -1,
      query_str:'',
    }),
    methods: {
      getSuggestLocationHomestays(search){
        clearTimeout(this.debounceTimeout)
        this.debounceTimeout = setTimeout(() => {
          this.loading = true
          axios.get('/api/sugguest-homestays-locations?search='+this.search)
            .then(res => {
              // this.$set(this, 'items', res.data)
              this.items = res.data
              this.$forceUpdate()
              // console.log(res.data);
              this.loading = false
            }).catch(err => {
            console.log('Lỗi sugguest location homestays');
            this.loading = false
          })
        }, 500)
      },
      getSuggestions() {
        this.inputing = true;
        $('#suggest').show();
      },
      autoComplete() {
        $('.suggest').show();
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(() => {
          axios.get(`/api/sugguest-homestays-locations?search=${this.address}`)
            .then(response => {
              this.suggestions = response.data;
            }).catch(err => {
            console.log('loi roi');
          })
        }, 200);
      },

      inputBlur() {
        this.inputing = false
        $('.suggest').hide();
      },

      keyUp() {
        if (this.cursor > 0) {
          this.cursor--
        }
        else {
          this.cursor = this.suggestions.length - 1
        }
        this.address = this.suggestions[this.cursor].name;
      },

      keyDown() {
        if (this.cursor < this.suggestions.length - 1) {
          this.cursor++
        }
        else {
          this.cursor = 0
        }
        this.address = this.suggestions[this.cursor].name;
      },
      selectSuggestion() {
        $('.suggest').hide();
        this.address = this.suggestions[this.cursor].name;
        this.query_str = this.suggestions[this.cursor].query_str;
        console.log('search');
        this.$emit('search', this.address, this.query_str)
      },
      searchHomestay(){
        console.log('search');
        this.$emit('search', this.address, this.query_str)
      }
    },
    mounted(){

    }
  };
</script>

<style>
    .edit-btn,
    .save-btn {
        margin-left: auto;
    }
    .pop{
        display: none;
    }
    .bg-xam{
        background: #c6c8ca;
    }
</style>
