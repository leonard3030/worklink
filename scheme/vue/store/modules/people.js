import {
    GET_PEOPLE,
    ADD_PERSON,
    VIEW_PERSON,
    UPDATE_PERSON,
    DELETE_PERSON,
} from "../actions/people";

import {
    GET_PEOPLE_URL,
    ADD_PERSON_URL,
    VIEW_PERSON_URL,
    UPDATE_PERSON_URL,
    DELETE_PERSON_URL,
} from "../variables";

import axios from "axios";

const state = {};
const mutations = {};
const getters = {};
const actions = {
    [GET_PEOPLE]: ({ commit, dispatch, getters }, payload) => {
        return axios({
            url: GET_PEOPLE_URL,
            method: "GET",
        });
    },
    [VIEW_PERSON]: ({ commit, dispatch, getters }, payload) => {
        return axios({
            url: VIEW_PERSON_URL + payload,
            method: "GET",
        });
    },
    [ADD_PERSON]: ({ commit, dispatch }, payload) => {
        return axios({
            url: ADD_PERSON_URL,
            method: "POST",
            data: payload,
        });
    },
    [UPDATE_PERSON]: ({ commit, dispatch }, payload) => {
        return axios({
            url: UPDATE_PERSON_URL,
            method: "POST",
            data: payload,
        });
    },
    [DELETE_PERSON]: ({ commit, dispatch, getters }, payload) => {
        return axios({
            url: DELETE_PERSON_URL + payload,
            method: "GET",
        });
    },
};


export default {
    mutations,
    actions,
    getters,
    state
};