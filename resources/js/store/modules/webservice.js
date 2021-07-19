import axios from 'axios';
import {Promise} from 'es6-promise';
import router from '../../routes';


const state = {
    resources: {
        locked: null
    },
    users: [],
    editUserData: {
        name: "",
        email: "",
        roles: []
    },
    isUserEdit: false,
    roles: [],
    editRoleData: {
        name: "",
        email: "",
        roles: []
    },
    permissions: [],
    editPermissionData: {},
    //kaaryalaya
    kaaryalaya:[],
    editKaarayalaData:{},
    //aarthikBarsa
    aarthikBarsa: [],
    editAarthikBarsaData: {},
    //aayojana
    aayojana: [],
    editAayojanaData: {
        aarthik_barsa_id: "",
        budget_no: "",
        name: "",
        mantralaya_name: "",
        bivag_sanstha_name: "",
        sthan_jilla: "",
        aayojana_start_date: "",
        aayojana_end_date: "",
        aayojana_karyalaya_pramukh_name: "",
        baarsik_budget: ""
    },
    maasikPragatiTaalika: {},
    maasikPragatiReports:[],
    traimaasikPragatiReports:[],
    traimaasikPragatiReportFilterable:[],
    maasikPragatiReportFilterable:[],
};

const mutations = {
    SET_RESOURCES(state, payload) {
        state.resources = payload;
    },

    SET_USERS(state, payload) {
        state.users = payload
    },
    SET_ROLES(state, payload) {
        state.roles = payload
    },
    SET_USER_EDIT_DATA(state, payload) {
        state.editUserData = payload;
    },
    SET_IS_USER_EDIT(state, payload) {
        state.isUserEdit = payload;
    },
    SET_ROLE_EDIT_DATA(state, payload) {
        state.editRoleData = payload;
    },

    SET_PERMISSIONS(state, payload) {
        state.permissions = payload
    },
    SET_PERMISSION_EDIT_DATA(state, payload) {
        state.editPermissionData = payload;
    },
    SET_SELECTED_ADDITIONAL_PERMISSIONS(state, payload) {
        state.editUserData.permissions = payload;
    },

    SET_KAARYALAYA(state, payload) {
        state.kaaryalaya = payload
    },
    SET_KAARYALAYA_EDIT_DATA(state, payload) {
        state.editKaaryalayaData = payload;
    },
    // aarthikBarsa
    SET_AARTHIK_BARSA(state, payload) {
        state.aarthikBarsa = payload
    },
    SET_AARTHIK_BARSA_EDIT_DATA(state, payload) {
        state.editAarthikBarsaData = payload;
    },
    // aayojana
    SET_AAYOJANA(state, payload) {
        state.aayojana = payload
    },
    SET_AAYOJANA_EDIT_DATA(state, payload) {
        state.editAayojanaData = payload;
    },

    SET_MAASIK_PRAGATI_REPORT(state,payload){
        state.maasikPragatiReports = payload;
    },
    SET_TRAIMAASIK_PRAGATI_REPORT(state,payload){
        state.traimaasikPragatiReports = payload;
    },

    SET_TRAIMAASIK_PRAGATI_REPORT_FILTERABLE(state,payload){
        state.traimaasikPragatiReportFilterable = payload;

    },
    SET_MAASIK_PRAGATI_REPORT_FILTERABLE(state,payload){
        state.maasikPragatiReportFilterable = payload;
    }

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
    getUsers(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/users', {
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        state.commit("SET_USERS", response.data.data.user);
                        resolve(response);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    setUserEditData(state, payload) {
        state.commit("SET_USER_EDIT_DATA", payload);
        router.push("/user-edit");
    },
    saveUserData(state, payload) {
        axios.post('/api/v1/save-user-data', {data: payload}, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function (response) {

            if (response.data.status === 200) {
                +router.push("/users");
                state.dispatch("addNotification", {
                    type: response.data.type,
                    message: response.data.message,
                });
            } else {
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
    getPermissionsDataForUser(state, payload) {
        return new Promise((resolve, reject) => {

            console.log(payload);
            axios.post('/api/v1/permissions-data-for-user', payload, {
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }).then(function (response) {
                if (response.data.status == 200) {
                    resolve(response.data);
                } else {
                    state.dispatch("addNotification", {
                        type: response.data.type,
                        message: response.data.message
                    })
                }
            }).catch(function (error) {
                state.dispatch("addNotification", {
                    type: "error",
                    message: error,
                });
                reject(error);
            });
        });
    },
    // roles
    getRoles(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/roles', {
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        state.commit("SET_ROLES", response.data.data.roles);
                        resolve(response);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    setRoleEditData(state, payload) {
        state.commit("SET_ROLE_EDIT_DATA", payload);
        router.push("/role-edit");
    },
    saveRoleData(state, payload) {
        axios.post('/api/v1/save-role-data', {data: payload}, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function (response) {

            if (response.data.status === 200) {
                +router.push("/roles");
                state.dispatch("addNotification", {
                    type: response.data.type,
                    message: response.data.message,
                });
            } else {
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
    // permissions
    getPermissions(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/permissions', {
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        state.commit("SET_PERMISSIONS", response.data.data.permissions);
                        resolve(response);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    setPermissionEditData(state, payload) {
        state.commit("SET_PERMISSION_EDIT_DATA", payload);
        router.push("/permission-edit");
    },
    savePermissionData(state, payload) {
        axios.post('/api/v1/save-permission-data', {data: payload}, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function (response) {

            if (response.data.status === 200) {
                +router.push("/permissions");
                state.dispatch("addNotification", {
                    type: response.data.type,
                    message: response.data.message,
                });
            } else {
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
    // kaaryalaya
    getKaaryalaya(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/kaaryalaya', {
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        state.commit("SET_KAARYALAYA", response.data.data.kaaryalaya);
                        resolve(response);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    setKaaryalayaEditData(state, payload) {
        state.commit("SET_KAARYALAYA_EDIT_DATA", payload);
        router.push("/kaaryalaya-edit");
    },
    saveKaaryalayaData(state, payload) {
        axios.post('/api/v1/save-kaaryalaya-data', {data: payload}, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function (response) {

            if (response.data.status === 200) {
                +router.push("/kaaryalaya");
                state.dispatch("addNotification", {
                    type: response.data.type,
                    message: response.data.message,
                });
            } else {
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
    //aarthik barsha
    getAarthikBarsa(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/aarthik-barsa', {
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        state.commit("SET_AARTHIK_BARSA", response.data.data.aarthikBarsa);
                        resolve(response);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    setAarthikBarsaEditData(state, payload) {
        state.commit("SET_AARTHIK_BARSA_EDIT_DATA", payload);
        router.push("/aarthik-barsa-edit");
    },
    saveAarthikBarsa(state, payload) {
        axios.post('/api/v1/save-aarthik-barsa', {data: payload}, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function (response) {

            if (response.data.status === 200) {
                router.push("/aarthik-barsa");
                state.dispatch("addNotification", {
                    type: response.data.type,
                    message: response.data.message,
                });
            } else {
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
    // Aayojana
    getAayojana(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/aayojana', {
                    params: {
                        page: payload.page,
                        totalItems: payload.totalItems,
                        search: payload.search,
                        filterData: payload.filterData
                    },
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        state.commit("SET_AAYOJANA", response.data.data.aayojana);
                        resolve(response);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    setAayojanaEditData(state, payload) {
        state.commit("SET_AAYOJANA_EDIT_DATA", payload);
        router.push("/aayojana-edit");
    },
    saveAayojana(state, payload) {
        axios.post('/api/v1/save-aayojana', {data: payload}, {
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function (response) {

            if (response.data.status === 200) {
                router.push("/aayojana");
                state.dispatch("addNotification", {
                    type: response.data.type,
                    message: response.data.message,
                });
            } else {
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
    // kriyakalap lakshya
    getKriyakalapLakshya(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/kriyakalap-lakshya', {
                    params: {
                        page: payload.page,
                        totalItems: payload.totalItems,
                        search: payload.search,
                        filterData: payload.filterData
                    },
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        // state.commit("SET_KRIYAKALAP_LAKSHYA", response.data.data.kriyakalapLakshya);
                        resolve(response.data.data);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    saveKriyakalapLakshya(state, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/api/v1/save-kriyakalap-lakshya', {data: payload}, {
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }).then(function (response) {

                if (response.data.status === 200) {
                    router.push("/kriyakalap-lakshya");
                    state.dispatch("addNotification", {
                        type: response.data.type,
                        message: response.data.message,
                    });
                    resolve(response);
                } else {
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
                reject(error)
            });
        });
    },
    uploadKriyakalapLakshya(state, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/api/v1/upload-kriyakalap-lakshya', payload, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN,
                }
            }).then(function (response) {
                if (response.data.status === 200) {
                    state.dispatch("addNotification", {
                        type: response.data.type,
                        message: response.data.message,
                    });
                    resolve(response);
                } else {
                    state.dispatch("addNotification", {
                        type: response.data.type,
                        message: response.data.message,
                    });
                    resolve(response);
                }

            }).catch(function (error) {
                state.dispatch("addNotification", {
                    type: "error",
                    message: error,
                });
                reject(error)
            });
        })
    },

    //milestone pragati taalika
    getMilestonePragatiTaalika(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/milestone-pragati-taalika', {
                    params: {
                        filterData: payload.filterData
                    },
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        // state.commit("SET_MAASIK_PRAGATI_TAALIKA", response.data.data);
                        resolve(response.data.data);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },

    //maasik pragati taalika
    getMaasikPragatiTaalika(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/maasik-pragati-taalika', {
                    params: {
                        filterData: payload.filterData
                    },
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        // state.commit("SET_MAASIK_PRAGATI_TAALIKA", response.data.data);
                        resolve(response.data.data);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    saveMaasikPragatiTaalika(state, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/api/v1/save-maasik-pragati-taalika', payload, {
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }).then(function (response) {

                if (response.data.status === 200) {
                    state.dispatch("addNotification", {
                        type: response.data.type,
                        message: response.data.message,
                    });
                    resolve(response);
                } else {
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
                reject(error)
            });
        });
    },
    editRequest(state,payload){
      return new Promise((resolve,reject)=>{
        axios.post('/api/v1/edit-request',payload,{
            headers:{
                Accept: "application/json",
                Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
            }
        }).then(function (response){
            if(response.data.status === 200) {
                state.dispatch("addNotification",{
                    type: response.data.type,
                    message: response.data.message
                });
                resolve(response.data.data);
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
            reject(error)
        });
      });
    },
    getMaasikPragatiTaalikaReport(state,payload){
        axios.get('/api/v1/maasik-pragati-taalika-report', {
                params: {
                    filterData: payload.filterData
                },
                headers: {
                    // Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }
        ).then(
            function (response) {
                if (response.data.status == 200) {
                    state.commit("SET_MAASIK_PRAGATI_REPORT", response.data.data.maasikPragatiReports);
                    // resolve(response.data.data);

                } else {
                    state.dispatch("addNotification", {
                        type: response.data.type,
                        message: response.data.message
                    })
                }
            }
        ).catch(
            function (error) {
                state.dispatch("addNotification", {
                    type: "error",
                    message: error,
                });
                reject(error);
            }
        )
    },

    //traimaasik pragati taalika
    getTraimaasikPragatiTaalika(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/traimaasik-pragati-taalika', {
                    params: {
                        filterData: payload.filterData
                    },
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        // state.commit("SET_MAASIK_PRAGATI_TAALIKA", response.data.data);
                        resolve(response.data.data);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    saveTraimaasikPragatiTaalika(state, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/api/v1/save-traimaasik-pragati-taalika', payload, {
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }).then(function (response) {

                if (response.data.status === 200) {
                    state.dispatch("addNotification", {
                        type: response.data.type,
                        message: response.data.message,
                    });
                    resolve(response);
                } else {
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
                reject(error)
            });
        });
    },
    importFromMaasikPragati(state,payload){
        return new Promise((resolve,reject) => {
            axios.get('/api/v1/import-from-maasik-pragati', {
                    params: {
                        filterData: payload.filterData
                    },
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        // state.commit("SET_MAASIK_PRAGATI_TAALIKA", response.data.data);
                        resolve(response.data.data);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    getTraimaasikPragatiTaalikaReport(state,payload){
        axios.get('/api/v1/traimaasik-pragati-taalika-report', {
                params: {
                    filterData: payload.filterData
                },
                headers: {
                    // Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }
        ).then(
            function (response) {
                if (response.data.status == 200) {
                    state.commit("SET_TRAIMAASIK_PRAGATI_REPORT", response.data.data.traimaasikPragatiReports);
                    // resolve(response.data.data);

                } else {
                    state.dispatch("addNotification", {
                        type: response.data.type,
                        message: response.data.message
                    })
                }
            }
        ).catch(
            function (error) {
                state.dispatch("addNotification", {
                    type: "error",
                    message: error,
                });
                reject(error);
            }
        )
    },
    getTraimaasikPragatiReportFilterable(state,payload){
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/traimaasik-pragati-report-filterable', {
                    params: {
                        filterData: payload.filterData
                    },
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        state.commit("SET_TRAIMAASIK_PRAGATI_REPORT_FILTERABLE", response.data.data.traimaasikPragatiReport);
                        resolve(response.data.data);
                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        })
    },
    getMaasikPragatiReportFilterable(state,payload){
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/maasik-pragati-report-filterable', {
                    params: {
                        filterData: payload.filterData
                    },
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        state.commit("SET_MAASIK_PRAGATI_REPORT_FILTERABLE", response.data.data.maasikPragratiReport);
                        resolve(response.data.data);
                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        })
    },


    makePostRequest(state, payload) {
        return new Promise((resolve, reject) => {
            axios.post('/api/v1/' + payload.route, payload.data, {
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }).then(function (response) {
                if (response.data.status === 200) {
                    resolve(response.data.data);
                    if(response.data.message){
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message,
                        });
                    }
                } else {
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
        });
    },

    makeGetRequest(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/' + payload.route, {
                params: payload.data,
                headers: {
                    Accept: "application/json",
                    Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                }
            }).then(function (response) {
                if (response.data.status === 200) {
                    resolve(response);
                } else {
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
        });
    },
    //requests
    getRequests(state, payload) {
        return new Promise((resolve, reject) => {
            axios.get('/api/v1/edit-requests', {
                    headers: {
                        // Accept: "application/json",
                        Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
                    }
                }
            ).then(
                function (response) {
                    if (response.data.status == 200) {
                        resolve(response.data.data);

                    } else {
                        state.dispatch("addNotification", {
                            type: response.data.type,
                            message: response.data.message
                        })
                    }
                }
            ).catch(
                function (error) {
                    state.dispatch("addNotification", {
                        type: "error",
                        message: error,
                    });
                    reject(error);
                }
            )
        });
    },
    approveRequest(state,payload){
      return new Promise((resolve,reject) => {
         axios.post('/api/v1/approve-request',payload,{
             headers: {
                 Accept: "application/json",
                 Authorization: "Bearer " + state.getters.GET_ACCESS_TOKEN
             }
         }).then(function (response) {

             if (response.data.status === 200) {
                 state.dispatch("addNotification", {
                     type: response.data.type,
                     message: response.data.message,
                 });
                 resolve(response);
             } else {
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
             reject(error)
         });
      });
    },

};

const getters = {
    selectedPermissions(state, payload) {
        return state.editUserData.roles;
    },
    CHECK_PERMISSION:(state) => (can) => {
        return state.resources.userPermissions.includes(can);
    }

};

export default {
    state,
    mutations,
    actions,
    getters,
};

