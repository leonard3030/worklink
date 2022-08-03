import MapHelper from "./MapHelper.vue";
import MapTrigger from "./MapTrigger.vue"
import { events } from './events'

const GoogleMap = {
    install(Vue, args = {}) {
        if (this.installed) {
            return
        }
        this.installed = true

        Vue.component('MapHelper', MapHelper);
        Vue.component('MapTrigger', MapTrigger);
        const Map = (params) => {
            if (typeof params === 'object') {
                events.$emit('open-map', params)
            }
        }

        Map.close = function() {
            events.$emit('close-map')
        }

        const name = 'InitMap';

        Vue.prototype['$' + name] = Map
        Vue[name] = Map
    }
}
export default GoogleMap;