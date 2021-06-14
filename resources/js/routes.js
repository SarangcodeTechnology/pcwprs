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
const KriyakalapMaasikPragatiFilterable = () => import("./components/pages/kriyakalap-maasik-pragrati-filterable/browse");
const MaasikPrint = () => import("./components/pages/print/MaasikPrint");

const KriyakalapMaasikPragatiReport = () => import("./components/pages/kriyakalap-maasik-pragati-report/browse");
const KriyakalapTraimaasikPragatiReport = () => import("./components/pages/kriyakalap-traimaasik-pragati-report/browse");
const KriyakalapTraimaasikPragatiFilterable = () => import("./components/pages/kriyakalap-traimaasik-pragrati-filterable/browse");
const TraimaasikPrint = () => import("./components/pages/print/TraimaasikPrint");

const NotAuthenticated = () => import("./components/pages/NotAuthenticated");
const EditRequests = () => import("./components/pages/requests/browse");
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
                        if (store.getters.GET_USER ){
                            next("/dashboard");
                        } else  {
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
                  path: "/not-authenticated",
                    component: NotAuthenticated,
                    name: "not-authenticated",
                    meta:{
                      breadcrumb: {
                          text: "",
                          link: "/not-authenticated"
                      }
                    }
                },
                {
                    path: "/dashboard",
                    component: Dashboard,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('dashboard-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'dashboard',
                    meta: {
                        breadcrumb: {
                            text: "ड्यासबोर्ड",
                            link: "/dashboard"
                        }
                    },
                },
                {
                    path: "/home",
                    component: Home,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('home-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'home',
                    meta: {
                        breadcrumb: {
                            text: "गृहपृष्ठ",
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
                // edit-requests
                {
                    path: "/edit-requests",
                    component: EditRequests,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('kaaryalaya-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'Edit Requests',
                    meta: {
                        breadcrumb: {
                            text: "इडिट निवेदनहरु",
                            link: "/Edit Requests"
                        }
                    },
                },
                // users
                {
                    path: "/users",
                    component: User,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('users-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'users',
                    meta: {
                        breadcrumb: {
                            text: "प्रयोगकर्ताहरु",
                            link: "/Users"
                        }
                    },
                },
                {
                    path: "/user-edit",
                    component: UserEdit,
                    name: 'user-edit',
                    meta: {
                        breadcrumb: {
                            text: "प्रयोगकर्ताहरु सम्पादन/थप",
                            link: "/user-edit"
                        }
                    },
                },
                // roles
                {
                    path: "/roles",
                    component: Role,
                    name: 'roles',
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('roles-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    meta: {
                        breadcrumb: {
                            text: "भूमिकाहरु",
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
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('permissions-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'permissions',
                    meta: {
                        breadcrumb: {
                            text: "अनुमतिहरु",
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
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('aarthik_barsa-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    meta: {
                        breadcrumb: {
                            text: "आर्थिक वर्ष",
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
                            text: "आर्थिक वर्ष थप/सम्पादन",
                            link: "/aarthik-barsa-edit"
                        }
                    },
                },
                // aayojana
                {
                    path: "/aayojana",
                    component: Aayojana,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('aayojana-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
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
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('kaaryalaya-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
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
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('kriyakalap_lakshya-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
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
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('maasik_pragati_form-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'kriyakalap-maasik-pragati',
                    meta: {
                        breadcrumb: {
                            text: "मासिक प्रगती",
                            link: "/kriyakalap-maasik-pragati"
                        }
                    },
                },
                {
                    path: "/kriyakalap-maasik-pragati-report",
                    component: KriyakalapMaasikPragatiReport,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('maasik_pragati_report-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'kriyakalap-maasik-pragati-report',
                    meta: {
                        breadcrumb: {
                            text: "मासिक प्रगती प्रतिवेदन",
                            link: "/kriyakalap-maasik-pragati-report"
                        }
                    },
                },
                //traimaasik pragati
                {
                    path: "/kriyakalap-traimaasik-pragati-report",
                    component: KriyakalapTraimaasikPragatiReport,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('traimaasik_pragati_report-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'kriyakalap-traimaasik-pragati-report',
                    meta: {
                        breadcrumb: {
                            text: "त्रैमासिक प्रगती प्रतिवेदन",
                            link: "/kriyakalap-traimaasik-pragati-report"
                        }
                    },
                },
                {
                    path: "/kriyakalap-traimaasik-pragati-filterable",
                    component: KriyakalapTraimaasikPragatiFilterable,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('traimaasik_pragati_report-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'kriyakalap-traimaasik-pragati-filterable',
                    meta: {
                        breadcrumb: {
                            text: "त्रैमासिक प्रगती प्रतिवेदन",
                            link: "/kriyakalap-traimaasik-pragati-filterable"
                        }
                    },
                },
                {
                    path: "/kriyakalap-maasik-pragati-filterable",
                    component: KriyakalapMaasikPragatiFilterable,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('traimaasik_pragati_report-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'kriyakalap-maasik-pragati-filterable',
                    meta: {
                        breadcrumb: {
                            text: "मासिक प्रगती प्रतिवेदन",
                            link: "/kriyakalap-maasik-pragati-filterable"
                        }
                    },
                },

                {
                    path: "/kriyakalap-traimaasik-pragati",
                    component: KriyakalapTraimaasikPragati,
                    beforeEnter(to, from, next) {
                        if (store.getters.CHECK_PERMISSION('traimaasik_pragati_form-browse')){
                            next();
                        } else  {
                            next({name:"not-authenticated"});
                        }
                    },
                    name: 'kriyakalap-traimaasik-pragati',
                    meta: {
                        breadcrumb: {
                            text: "त्रैमासिक प्रगती",
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
            path: "/maasik-print",
            component: MaasikPrint,
            name: 'Maasik Print',
        },
        {
            path: "/traimaasik-print",
            component: TraimaasikPrint,
            name: 'Traimasik Print',
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
