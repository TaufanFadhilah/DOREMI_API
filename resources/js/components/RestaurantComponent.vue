<template>
    <div class="container">
        <div class="row">
          <div class="col-md-12">
            <h4>Restaurants</h4>
            <input type="text" class="form-control" placeholder="Looks for food? type here...">
          </div>
        </div>
        <div class="row">
          <div v-for="restaurant in restaurants" class="col-md-4 my-2">
            <div class="card">
              <img class="card-img-top" v-show="!restaurant.restaurant.thumb" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRj6Zzx4n2M3RFvIRhLxvzhGmLLEUM0qU4ZwcXS54G7juk1PbRWIA" alt="Card image cap" style="height: 200px">
              <img class="card-img-top" v-show="restaurant.restaurant.thumb" v-bind:src="restaurant.restaurant.thumb" alt="Card image cap" style="height: 200px">
              <div class="card-body">
                <h5 class="card-title" style="height: 50px">{{ restaurant.restaurant.name }}</h5>
                <p class="card-text">
                  {{ restaurant.restaurant.location.address }}
                  <br>
                  Price : Rp. {{ (restaurant.restaurant.average_cost_for_two / 2).toLocaleString() }}
                  <br>
                  Rating : {{ restaurant.restaurant.user_rating.rating_text }}
                </p>
                <a v-bind:href="restaurant.restaurant.url" target="_blank" class="btn btn-danger" style="width: 100%; background-color: #cb202d">See More</a>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
      name: 'Restaurant',
      data: function(){
          return {
              restaurants: ''
          }
      },
      created: function(){
              fetch('https://developers.zomato.com/api/v2.1/search?q=bandung', {
              method: "POST", // *GET, POST, PUT, DELETE, etc.
              headers: {
                  "Accept": "application/json",
                  "user-key": "02868becba07c6991b590d7336fe3ab8"
              },
          })
          .then(response => response.json()) // parses response to JSON
          .then(data => this.restaurants = data.restaurants)
        }
    }
</script>
