import Vue from 'vue'
import VueRouter from 'vue-router'

import store from "./store";


Vue.use(VueRouter)

const App = () => import("./components/App");
const Login = () => import("./components/auth/Login");
const Register = () => import("./components/auth/Register");
const Dashboard = () => import("./components/pages/Dashboard");
const Home = () => import("./components/pages/Home");
const ChangePassword = () => import("./components/auth/ChangePassword");


const User = () => import("./components/pages/users/browse");
const UserEdit = () => import("./components/pages/users/edit");

const Role = () => import("./components/pages/roles/browse");
const RoleEdit = () => import("./components/pages/roles/edit");

const Permission = () => import("./components/pages/permissions/browse");
const PermissionEdit = () => import("./components/pages/permissions/edit");

const Kaaryalaya = () => import("./components/pages/kaaryalaya/browse");
const KaaryalayaEdit = () => import("./components/pages/kaaryalaya/edit");

const AarthikBarsa = () => import("./components/pages/aarthik-barsha/browse");
const AarthikBarsaEdit = () => import("./components/pages/aarthik-barsha/edit");

const Aayojana = () => import("./components/pages/aayojana/browse");
const AayojanaEdit = () => import("./components/pages/aayojana/edit");

const KriyakalapLakshya = () => import("./components/pages/kriyakalap-lakshya/browse");
const KriyakalapMaasikPragati = () => import("./components/pages/kriyakalap-maasik-pragati/browse");
const KriyakalapTraimaasikPragati = () => import("./components/pages/kriyakalap-traimaasik-pragati/browse");

const opts = {
    mode: "history",
    routes: [
        {
            path: "/",
            component: App,
            beforeEnter(to, from, next) {
                if (store.getters.GET_USER) {
                    next();
                } else {
                    next("/login");
                }
            },
            meta: {
                breadcrumb: {
                    name: "/",
                    link: "/"
                }
            },
            children: [
                {
                    path: "",
                    component: Dashboard,
                    name: 'app',
                    beforeEnter(to, from, next) {
                        if (store.getters.GET_USER) {
                            next("/dashboard");
                        } else {
                            next("/login");
                        }
                    },
                    meta: {
                        breadcrumb: {
                            text: "/",
                            link: "/"
                        }
                    },
                },
                {
                    path: "/dashboard",
                    component: Dashboard,
                    name: 'dashboard',
                    meta: {
                        breadcrumb: {
                            text: "Dashboard",
                            link: "/dashboard"
                        }
                    },
                },
                {
                    path: "/home",
                    component: Home,
                    name: 'home',
                    meta: {
                        breadcrumb: {
                            text: "Home",
                            link: "/home"
                        }
                    },
                },
                {
                    path: "/change-password",
                    component: ChangePassword,
                    name: 'change-password',
                    meta: {
                        breadcrumb: {
                            text: "Change Password",
                            link: "/change-password"
                        }
                    },
                },
                // users
                {
                    path: "/users",
                    component: User,
                    name: 'users',
                    meta: {
                        breadcrumb: {
                            text: "User",
                            link: "/users"
                        }
                    },
                },
                {
                    path: "/user-edit",
                    component: UserEdit,
                    name: 'user-edit',
                    meta: {
                        breadcrumb: {
                            text: "User Edit/Add",
                            link: "/user-edit"
                        }
                    },
                },
                // roles
                {
                    path: "/roles",
                    component: Role,
                    name: 'roles',
                    meta: {
                        breadcrumb: {
                            text: "Roles",
                            link: "/roles"
                        }
                    },
                },
                {
                    path: "/role-edit",
                    component: RoleEdit,
                    name: 'role-edit',
                    meta: {
                        breadcrumb: {
                            text: "Role Edit",
                            link: "/role-edit"
                        }
                    },
                },
                // permissions
                {
                    path: "/permissions",
                    component: Permission,
                    name: 'permissions',
                    meta: {
                        breadcrumb: {
                            text: "Permissions",
                            link: "/permissions"
                        }
                    },
                },
                {
                    path: "/permission-edit",
                    component: PermissionEdit,
                    name: 'permission-edit',
                    meta: {
                        breadcrumb: {
                            text: "Permission Edit",
                            link: "/permission-edit"
                        }
                    },
                },
                // aarthik barsha
                {
                    path: "/aarthik-barsa",
                    component: AarthikBarsa,
                    name: 'aarthik-barsa',
                    meta: {
                        breadcrumb: {
                            text: "Aarthik Barsa",
                            link: "/aarthik-barsa"
                        }
                    },
                },
                {
                    path: "/aarthik-barsa-edit",
                    component: AarthikBarsaEdit,
                    name: 'aarthik-barsa-edit',
                    meta: {
                        breadcrumb: {
                            text: "Aarthik Barsa Add/Edit",
                            link: "/aarthik-barsa-edit"
                        }
                    },
                },
                // aayojana
                {
                    path: "/aayojana",
                    component: Aayojana,
                    name: 'aayojana',
                    meta: {
                        breadcrumb: {
                            text: "आयोजना",
                            link: "/aayojana"
                        }
                    },
                    props: route => ({ aarthikBarsaId: route.query.aarthikId })
                },
                {
                    path: "/aayojana-edit",
                    component: AayojanaEdit,
                    name: 'aayojana-edit',
                    meta: {
                        breadcrumb: {
                            text: "आयोजना थप/सम्पादन",
                            link: "/aayojana-edit"
                        }
                    },
                },
                // permissions
                {
                    path: "/kaaryalaya",
                    component: Kaaryalaya,
                    name: 'kaaryalaya',
                    meta: {
                        breadcrumb: {
                            text: "कार्यलय",
                            link: "/kaaryalaya"
                        }
                    },
                },
                {
                    path: "/kaaryalaya-edit",
                    component: KaaryalayaEdit,
                    name: 'kaaryalaya-edit',
                    meta: {
                        breadcrumb: {
                            text: "कार्यलय",
                            link: "/kaaryalaya-edit"
                        }
                    },
                },
                // kriyakalap-lakshya
                {
                    path: "/kriyakalap-lakshya",
                    component: KriyakalapLakshya,
                    name: 'kriyakalap-lakshya',
                    meta: {
                        breadcrumb: {
                            text: "कृयाकलाप लक्ष",
                            link: "/kriyakalap-lakshya"
                        }
                    },
                },
                // kriyakalap-maasik-pragati
                {
                    path: "/kriyakalap-maasik-pragati",
                    component: KriyakalapMaasikPragati,
                    name: 'kriyakalap-maasik-pragati',
                    meta: {
                        breadcrumb: {
                            text: "कृयाकलाप मासिक प्रगती",
                            link: "/kriyakalap-maasik-pragati"
                        }
                    },
                },
                //traimaasik pragati
                {
                    path: "/kriyakalap-traimaasik-pragati",
                    component: KriyakalapTraimaasikPragati,
                    name: 'kriyakalap-traimaasik-pragati',
                    meta: {
                        breadcrumb: {
                            text: "कृयाकलाप त्रैमासिक प्रगती",
                            link: "/kriyakalap-traimaasik-pragati"
                        }
                    },
                },



            ]
        },
        {
            path: "/login",
            component: Login,
            name: 'Login',
            beforeEnter(to, from, next) {
                if (store.getters.GET_USER) {
                    next("/dashboard");
                } else {
                    next();
                }
            },
        },
        {
            path: "/register",
            component: Register,
            name: 'register',
            beforeEnter(to, from, next) {
                if (store.getters.GET_USER) {
                    next("/dashboard");
                } else {
                    next();
                }
            },
        },


    ]
}

export default new VueRouter(opts)
