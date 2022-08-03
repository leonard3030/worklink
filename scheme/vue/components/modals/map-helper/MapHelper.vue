 <template>
  <div class="map-loaction-wrapper">
    <transition name="fade">
      <div class="map-overlay" v-if="locaction_widget" @click.self="locaction_widget = false">
        <div class="map-wiget" :class="{inactive:show_location_list}" :disabled="inactive">
          <gmap-autocomplete
            placeholder="Search your shop location"
            @place_changed="setPlace"
            class="search-input"
            :options="{fields: ['geometry','formatted_address', 'name']}"
          ></gmap-autocomplete>
          <Gmap-Map
            style="width: 100%; height: 300px;"
            :zoom="16"
            :center="position"
            @click="getInfo"
          >
            <Gmap-Marker :position="position" :clickable="false" :draggable="false"></Gmap-Marker>
          </Gmap-Map>
          <div class="map-option">
            <div class="info">
              <label>Shop location</label>
              <span v-if="selected_location_name">{{selected_location_name}}</span>
              <span v-else>No location selected</span>
            </div>
            <div class="option">
              <button @click="usePlace()" class="btn-success">Use</button>
              <button @click="closeModal()" class="btn-default">Cancel</button>
            </div>
          </div>
        </div>
        <!-- Location list -->
        <transition name="fade">
          <div class="location-widget-list" v-if="show_location_list">
            <div class="header">
              <h4>LOCATION LIST</h4>
              <button @click="show_location_list = false" class="btn-success">Close</button>
            </div>
            <div class="location-body">
              <vue-scroll>
                <a-spin :spinning="loading_location_list">
                  <div
                    class="location-item"
                    @click="selectLocation(item)"
                    v-for="(item,i) in location_list"
                    :key="i"
                  >{{item.formatted_address}}</div>
                </a-spin>
              </vue-scroll>
            </div>
          </div>
        </transition>
      </div>
    </transition>
  </div>
</template>
<script>
import { events } from "./events";

export default {
  name: "map-helper",
  components: {},
  data() {
    return {
      location_list: [],
      inactive: false,
      selected_location_name: "",
      loading_location_list: false,
      show_location_list: false,
      locaction_widget: false,
      position: {
        lat: 0,
        lng: 0,
      },
      markers: [],
      place: null,
      callback: (par) => {},
    };
  },
  mounted() {
    let vm = this;
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        vm.setCurrentPosition,
        vm.showError
      );
    }
    events.$on("open-map", vm.openMap);
    events.$on("close-map", vm.closeMap);
  },
  methods: {
    openMap(data) {
      let vm = this;
      vm.callback = data.callback;
      vm.locaction_widget = true;
    },
    closeMap() {
      let vm = this;
      vm.closeModal();
    },
    usePlace() {
      let vm = this;
      this.onUse(vm.selected_location_name, vm.position);
    },
    selectLocation(item) {
      let vm = this;
      vm.selected_location_name = item.formatted_address;
      vm.position = {
        lat: item.geometry.location.lat,
        lng: item.geometry.location.lng,
      };
      vm.show_location_list = false;
    },
    onUse(name, position) {
      let vm = this;
      if (vm.selected_location_name) {
        vm.closeModal();
        vm.callback({ name: name, position: position });
      } else {
        this.$notify({
          group: "status",
          type: "warn",
          title: "OOPS!!!",
          text: "No selected location",
        });
      }
    },
    getInfo(e) {
      let vm = this;
      vm.loading_location_list = true;
      this.position = {
        lat: e.latLng.lat(),
        lng: e.latLng.lng(),
      };
      this.show_location_list = true;
      this.axios
        .get(
          `https://maps.googleapis.com/maps/api/geocode/json?latlng=${this.position.lat},${this.position.lng}&sensor=true&key=${vm.$store.state.GOOGLE_API_KEY}`
        )
        .then((response) => {
          if (response.data.status == "OK") {
            vm.location_list = response.data.results;
            vm.loading_location_list = false;
          }
        })
        .catch((error) => {
          vm.loading_location_list = false;
        });
    },
    setPlace(place) {
      let vm = this;
      if (vm.$hasKey(place, "geometry.location")) {
        vm.place = place;
        vm.selected_location_name = place.formatted_address;
        vm.position = {
          lat: place.geometry.location.lat(),
          lng: place.geometry.location.lng(),
        };
      } else {
        this.$notify({
          group: "status",
          type: "warn",
          title: "OOPS!!!",
          text: "Please select location suggetion",
        });
      }
    },
    setCurrentPosition(position) {
      this.position.lat = position.coords.latitude;
      this.position.lng = position.coords.longitude;
    },
    showError(error) {
      switch (error.code) {
        case error.PERMISSION_DENIED:
          console.log("User denied the request for Geolocation.");
          break;
        case error.POSITION_UNAVAILABLE:
          console.log("Location information is unavailable.");
          break;
        case error.TIMEOUT:
          console.log("The request to get user location timed out.");
          break;
        case error.UNKNOWN_ERROR:
          console.log("An unknown error occurred.");
          break;
      }
    },
    closeModal() {
      this.locaction_widget = false;
    },
  },
};
</script>

<style lang="scss" scoped>
.map-loaction-wrapper {
  width: 100%;
  z-index: 9999999999999999999999999;

  .map-overlay {
    position: fixed;
    z-index: 9999999999999999999999999;
    top: 0px;
    left: 0px;
    bottom: 0px;
    right: 0px;
    background: rgba(#fff, 0.9);
    display: flex;
    justify-content: center;
    padding-top: 14px;
    align-items: flex-start;
    .location-widget-list {
      width: 330px;
      background: #fff;
      border: 1px solid #ddd;
      display: flex;
      flex-direction: column;
      transition: 500ms ease-in;
      height: 500px;
      position: absolute;
      z-index: 999;
      .header {
        font-size: 15px;
        height: 60px;
        padding: 23px;
        border-bottom: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .location-body {
        height: calc(100% - 60px);
        .location-item {
          font-size: 14px;
          padding: 11px 21px;
          cursor: pointer;
          &:hover {
            background: #ddd;
          }
        }
      }
    }
    .map-wiget {
      width: 650px;
      background: #fff;
      border: 1px solid #ddd;
      display: inline-table;
      padding: 23px;
      transition: 500ms ease-in;
      @media screen and (max-width: 500px) {
        border: none;
        width: 100%;
        position: fixed;
        z-index: 999999999999999;
        top: 0px;
        left: 0px;
        bottom: 0px;
        right: 0px;
        padding: 10px;
      }
      &.inactive {
        transition: 500ms ease-in;
        opacity: 0.4;
      }
      .search-input {
        width: 100%;
        height: 45px;
        padding: 0px 15px;
        border: 1px solid #ddd;
        background: #f6f6f6;
      }
      .map-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 0px;
        @media screen and (max-width: 500px) {
          flex-direction: column;
          align-items: flex-start;
        }
        .info {
          display: flex;
          flex-direction: column;
          @media screen and (max-width: 500px) {
            margin-bottom: 15px;
          }
          label {
            font-size: 14px;
            font-weight: 800;
          }
          span {
            font-size: 12px;
          }
        }
        .option {
          display: flex;
          button {
            margin-left: 15px;
            @media screen and (max-width: 500px) {
              margin-right: 15px;
            }
          }
        }
      }
    }
  }
}
</style>