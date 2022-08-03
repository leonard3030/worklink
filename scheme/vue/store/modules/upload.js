import {
    UPLOAD_GET_ALL_FILES,
    UPLOAD_DELETE_FILE,
    UPLOAD_SEARCH_FILE,
    UPLOAD_SEARCH_FILE_BY_TYPE,
    UPLOAD_UPDATE_FILE
} from "../actions/upload";

import {
    UPLOAD_BASE_URL,
    UPLOAD_GET_ALL_FILES_URL,
    UPLOAD_DELETE_FILE_URL,
    UPLOAD_SEARCH_FILE_URL,
    UPLOAD_SEARCH_FILE_BY_TYPE_URL,
    UPLOAD_UPDATE_FILE_URL
} from "../variables";

import axios from "axios";

const state = {};
const mutations = {};
const getters = {};
const actions = {
    [UPLOAD_GET_ALL_FILES]: ({ commit, dispatch, getters }, payload) => {
        return axios({
            url: UPLOAD_BASE_URL + UPLOAD_GET_ALL_FILES_URL,
            method: "GET",
            data: payload,
        });
    },
    [UPLOAD_DELETE_FILE]: ({ commit, dispatch }, payload) => {
        return axios({
            url: UPLOAD_BASE_URL + UPLOAD_DELETE_FILE_URL + '/' + payload.id,
            method: "DELETE",
            data: payload,
        });
    },
    [UPLOAD_SEARCH_FILE]: ({ commit, dispatch }, payload) => {
        return axios({
            url: UPLOAD_BASE_URL + UPLOAD_SEARCH_FILE_URL,
            method: "POST",
            data: payload,
        });
    },
    [UPLOAD_SEARCH_FILE_BY_TYPE]: ({ commit, dispatch }, payload) => {
        return axios({
            url: UPLOAD_BASE_URL + UPLOAD_SEARCH_FILE_BY_TYPE_URL,
            method: "POST",
            data: payload,
        });
    },
    [UPLOAD_UPDATE_FILE]: ({ commit, dispatch }, payload) => {
        return axios({
            url: UPLOAD_BASE_URL + UPLOAD_UPDATE_FILE_URL + '/' + payload.id,
            method: "POST",
            data: payload,
        });
    },
};


export default {
    mutations,
    actions,
    getters,
    state
};