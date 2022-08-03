import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import EasySlider from "vue-easy-slider";
import vuescroll from "vuescroll/dist/vuescroll-native";
import "vuescroll/dist/vuescroll.css";
import "./scss/main.scss";
import Antd from "ant-design-vue";
import {
	Select,
	Progress,
	Alert,
	Input,
	Icon,
	DatePicker,
	Button,
	Tabs,
	Empty,
	Spin,
	TimePicker,
	Checkbox,
	Rate,
	Popconfirm,
	Tooltip,
	Slider,
} from "ant-design-vue";
import "./scss/ant.scss";
require("vue2-animate/dist/vue2-animate.min.css");
import * as VueGoogleMaps from "vue2-google-maps";
import AppMix from "./mixin/appMix.vue";
import axios from "axios";
import VueAxios from "vue-axios";
import Notifications from "vue-notification";
import VueLocalStorage from "vue-localstorage";
import VueClosable from "vue-closable";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import CButton from "./components/partials/CButton.vue";
import DropdownMenu from "@innologica/vue-dropdown-menu";
import VueConfirmDialog from "vue-confirm-dialog";
import VueClipboard from "vue-clipboard2";
import VueContentLoading from "vue-content-loading";
import AskAuth from "./components/modals/AskAuth.vue";
import Uploader from "./components/modals/upload-helper";
import GoogleMap from "./components/modals/map-helper";
import VueSlickCarousel from "vue-slick-carousel";
import { library } from "@fortawesome/fontawesome-svg-core";
import { faPlayCircle } from "@fortawesome/free-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import VModal from "vue-js-modal";
import VueYoutube from "vue-youtube";
import VUpload from "./components/VUpload.vue";
import VEditor from "./components/VEditor.vue";
import {
	Hooper,
	Slide,
	Progress as HooperProgress,
	Pagination as HooperPagination,
	Navigation as HooperNavigation,
} from "hooper";
import "hooper/dist/hooper.css";

// pages

import clientHeader from "./views/site/pages/Header.vue";
import clientFooter from "./views/site/pages/Footer.vue";
import adminSidebar from "./views/admin/pages/Sidebar.vue";
import Dashboard from "./views/admin/dashboard.vue";

import VueParticles from 'vue-particles';
Vue.use(VueParticles);

Vue.component("clientHeader", clientHeader);
Vue.component("clientFooter", clientFooter);
Vue.component("adminSidebar", adminSidebar);
Vue.component("Dashboard", Dashboard);
Vue.use(Antd);

// mine

Vue.component("Hooper", Hooper);
Vue.component("Slide", Slide);
Vue.component("HooperProgress", HooperProgress);
Vue.component("HooperPagination", HooperPagination);
Vue.component("HooperNavigation", HooperNavigation);
Vue.component("vupload", VUpload);
Vue.component("veditor", VEditor);

Vue.use(VueYoutube);
library.add(faPlayCircle);
Vue.component("font-awesome-icon", FontAwesomeIcon);
Vue.component("AskAuth", AskAuth);
Vue.component("VueContentLoading", VueContentLoading);
Vue.component("vue-confirm-dialog", VueConfirmDialog.default);
Vue.component("CButton", CButton);
Vue.component("Loading", Loading);
Vue.component("DropdownMenu", DropdownMenu);

Vue.component("VueSlickCarousel", VueSlickCarousel);
Vue.use(GoogleMap);
Vue.use(VModal);
Vue.use(Uploader);
Vue.use(Progress);
Vue.use(Alert);
Vue.use(Input);
Vue.use(Icon);
Vue.use(DatePicker);
Vue.use(Button);
Vue.use(Tabs);
Vue.use(Spin);
Vue.use(Empty);
Vue.use(TimePicker);
Vue.use(Checkbox);
Vue.use(Rate);
Vue.use(Popconfirm);
Vue.use(Tooltip);
Vue.use(Slider);
Vue.use(Select);
Vue.use(VueConfirmDialog);
Vue.use(Loading);
Vue.use(VueClosable);
Vue.use(VueLocalStorage);
Vue.use(Notifications);
Vue.use(VueAxios, axios);
Vue.use(VueClipboard);
Vue.mixin(AppMix);
Vue.use(VueGoogleMaps, {
	load: {
		key: store.state.GOOGLE_MAP_KEY,
		libraries: "places",
	},
});
Vue.use(vuescroll, {
	ops: {
		bar: {
			showDelay: 200,
			onlyShowBarOnScroll: true,
			keepShow: true,
			background: "#8dc73f",
			opacity: 1,
			specifyBorderRadius: false,
			minSize: 0,
			size: "10px",
			disable: false,
		},
	},
});
Vue.use(EasySlider);
Vue.config.productionTip = false;

// Setting for sidebar on load app
if (window.innerWidth > 900) {
	if (Vue.localStorage.get("is_sidebar")) {
		store.state.sidebar =
			Vue.localStorage.get("is_sidebar") === "true" ? true : false;
	} else {
		store.state.sidebar = true;
	}
} else {
	store.state.sidebar = false;
}

// Validate every axios request
axios.interceptors.response.use(
	function(response) {
		return response;
	},
	function(error) {
		try {
			if (error.response.status === 401) {
				store.commit("SET_TOKEN", null);
				Vue.localStorage.remove("user_data");
			}
		} catch (e) {}
		return Promise.reject(error);
	}
);

if ("serviceWorker" in navigator) {
	window.addEventListener("load", () => {
		navigator.serviceWorker.register("/sw.js");
	});
}

const el = document.querySelector("#app");
if (el) {
	new Vue({
		router,
		store,
		render: (h) => h(App),
	}).$mount("#app");
	if (process.env.ENV !== "production") {
		console.log("Vue app is running in development mode");
	}
}
