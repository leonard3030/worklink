let api_base_url = "";
if (process.env.NODE_ENV === "production") {
  api_base_url = "http://wcke.planbpreneur.com/api/v1";
} else {
  api_base_url = "http://127.0.0.1:8000/api/v1";
}
export const API_BASE_URL = api_base_url;

let admin_api_base_url = "";
if (process.env.NODE_ENV === "production") {
  admin_api_base_url = "http://wcke.planbpreneur.com/api/v1/admin";
} else {
  admin_api_base_url = "http://127.0.0.1:8000/api/v1/admin";
}
export const ADMIN_API_BASE_URL = admin_api_base_url;

let base_url = "";
if (process.env.NODE_ENV === "production") {
  base_url = "http://wcke.planbpreneur.com/api/v1";
} else {
  base_url = "http://127.0.0.1:8080/api/v1";
}
let upload_base_url = "";
if (process.env.NODE_ENV === "production") {
  upload_base_url = "http://wcke.planbpreneur.com/";
} else {
  upload_base_url = "http://127.0.0.1:8000/";
}

export const UPLOAD_BASE_URL = upload_base_url;

export const BASE_URL = base_url;

/* ---------------------------------------------------------------
 * API LIST
 ----------------------------------------------------------------*/
// Auth api

// Upload api
export const UPLOAD_GET_ALL_FILES_URL = "upload/all-files";
export const UPLOAD_DELETE_FILE_URL = "upload/delete";
export const UPLOAD_SEARCH_FILE_URL = "upload/search";
export const UPLOAD_SEARCH_FILE_BY_TYPE_URL = "upload/search-by-type";
export const UPLOAD_UPDATE_FILE_URL = "upload/update-file";

// Notification api
export const NOTIFICATION_GET_ALL_URL = "/notification/mine";
export const NOTIFICATION_MAKE_IT_SEEN_URL = "/notification/make/seen";

// Other api
export const SEARCH_BASED_CATEGORY_URL = "/search/based/category";
export const SEARCH_BASED_QUERY_URL = "/search/based/query";
export const SAVE_SEARCH_TEXT_URL = "/search/save/text";
export const GET_SEARCH_TEXT_URL = "/search/get/text";
export const SEARCH_GET_HISTORY_URL = "/search/mine/history";
export const SEARCH_REMOVE_FROM_HISTORY_URL = "/search/remove/history";
export const SEND_CONTACT_MESSAGE_URL = "/send/contact/message";

export const GET_PEOPLE_URL = admin_api_base_url + "/getPeople";
export const VIEW_PERSON_URL = admin_api_base_url + "/viewPerson/";
export const ADD_PERSON_URL = admin_api_base_url + "/addPerson";
export const UPDATE_PERSON_URL = admin_api_base_url + "/updatePerson";
export const DELETE_PERSON_URL = admin_api_base_url + "/deletePerson/";
