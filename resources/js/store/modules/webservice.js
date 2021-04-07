import axios from 'axios';
import {Promise} from 'es6-promise';
import router from '../../routes';


const state = {
    resources: {

    },
    users: [],
    editUserData: {
        name:"",
        email:"",
        roles:[]
    },
    isUserEdit: false,
    roles: [],
    editRoleData: {
        name:"",
        email:"",
        roles:[]
    }
};

const mutations = {
    SET_RESOURCES(state, payload) {
        state.resources = payload;
    },

    SET_USERS(state,payload){
        state.users=payload
    },
    SET_ROLES(state,payload){
        state.roles=payload
    },
    SET_USER_EDIT_DATA(state, payload){
        state.editUserData = payload;
    },
    SET_IS_USER_EDIT(state, payload){
        state.isUserEdit = payload;
    },
    SET_ROLE_EDIT_DATA(state, payload){
        state.editRoleData = payload;
    },
};

const actions = {

    loadResources(state, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get('/api/v1/load-resources', {
                    headers: {
                        Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                })
                .then(function (response) {
                    if (response.data.status === 200) {
                        state.commit("SET_RESOURCES", response.data.data);
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                        resolve(response)
                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                    }
                    resolve(response);
                })
                .catch(function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                });
        });
    },
    // users
    getUsers(state, payload){
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/users',{
                headers: {
                    // Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }
            ).then(
                function(response){
                    if(response.data.status==200){
                        state.commit("SET_USERS",response.data.data.user);
                        resolve(response);

                    } else {
                        state.dispatch("addNotification",{
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function(error){
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    setUserEditData(state, payload){
        state.commit("SET_USER_EDIT_DATA",payload);
        router.push("/user-edit");
    },
    saveUserData(state, payload){
        axios.post('/api/v1/save-user-data',{data:payload},{
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function(response){

            if (response.data.status === 200) {
+                router.push("/users");
                state.dispatch("addNotification", {
                    type: response.data.type,
                    message: response.data.message,
                });
            }else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                    }

        }).catch(function (error) {
            state.dispatch("addNotification", {
                type: "error",
                message: error,
            });
        });
    },
    // roles
    getRoles(state, payload){
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/roles',{
                headers: {
                    // Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }
            ).then(
                function(response){
                    if(response.data.status==200){
                        state.commit("SET_ROLES",response.data.data.roles);
                        resolve(response);

                    } else {
                        state.dispatch("addNotification",{
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function(error){
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    setRoleEditData(state, payload){
        state.commit("SET_ROLE_EDIT_DATA",payload);
        router.push("/role-edit");
    },
    saveRoleData(state, payload){
        axios.post('/api/v1/save-role-data',{data:payload},{
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function(response){

            if (response.data.status === 200) {
+                router.push("/roles");
                state.dispatch("addNotification", {
                    type: response.data.type,
                    message: response.data.message,
                });
            }else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                    }

        }).catch(function (error) {
            state.dispatch("addNotification", {
                type: "error",
                message: error,
            });
        });
    }


};

const getters = {

};

export default {
    state,
    mutations,
    actions,
    getters,
};

