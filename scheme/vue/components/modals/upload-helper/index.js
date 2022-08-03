import UploadHelper from "./UploadHelper.vue";
import UploadTrigger from "./UploadTrigger.vue"
import { events } from './events'

const Uploader = {
    install(Vue, args = {}) {
        if (this.installed) {
            return
        }
        this.installed = true

        Vue.component('UploadHelper', UploadHelper);
        Vue.component('UploadTrigger', UploadTrigger);
        const upload = (params) => {
            if (typeof params === 'object') {
                events.$emit('open-upload', params)
            }
        }

        upload.close = function() {
            events.$emit('close-upload')
        }

        const name = 'Uploader';

        Vue.prototype['$' + name] = upload
        Vue[name] = upload
    }
}
export default Uploader;