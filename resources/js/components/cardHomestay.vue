<template>
    <div class="row">
        <div class="col-5">
            <img src="https://cdn.luxstay.com/rooms/13640/medium/room_13640_122_1551771179.jpg"
                 style="width: 100%; height: auto">
        </div>
        <div class="col-7">
            <h3>{{name}}</h3>
            <p>{{homestay_type_name }}</p>
            <div class="d-flex border-bottom-dashed">
                <p class="">
                    <span class="p20 semi-bold">{{convertCurrency(price)}}</span>
                    <span class="text-lowercase">vnd/</span>đêm
                </p>
            </div>
            <div class="d-flex align-baseline my-2">
                <div class="display-inline-block">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <span class="ml-1">{{address}}</span>
            </div>
            <div class="d-flex ml-auto">
                <star-rating :increment="0.5" :read-only="true" :star-size="12" :rating="rating/10"
                             active-color="#016699"
                             :show-rating="false"></star-rating>
                <!--<span class="ml-1">({{reviews_count}})</span>-->
            </div>
        </div>
    </div>
</template>

<script>
  let homestayTypes = ['Chung cư', 'Biệt thự', 'Nhà riêng']
  export default {
    props: ['id', 'name', 'price', 'rating', 'address', 'homestay_type'],
    computed: {
      homestay_type_name(){
        return homestayTypes[this.homestay_type - 1]
      }
    }
  }
</script>