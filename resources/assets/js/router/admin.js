import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name: "首页",
            path: '/',
            component: resolve =>void(require(['../components/admin/layout/index.vue'], resolve))
        },
        {
            name: "自动回复",
            path: '/wx/reply',
            component: resolve =>void(require(['../components/admin/wx/reply.vue'], resolve))
        },
        {
            name: '基础配置',
            path: '/wx/config',
            component: resolve =>void(require(['../components/admin/wx/config.vue'], resolve))
        },
        {
            name: '模板配置',
            path: '/wx/template',
            component: resolve =>void(require(['../components/admin/wx/temp.vue'], resolve))
        },
        {
            name: '菜单配置',
            path: '/wx/menu',
            component: resolve =>void(require(['../components/admin/wx/menu.vue'], resolve))
        },{
            name:'bookList',
                path:'/wx/bookList',
        component: resolve =>void(require(['../components/admin/book/bookList.vue'], resolve))
        },{
            name:'book',
            path:'/wx/book',
            component: resolve =>void(require(['../components/admin/book/book.vue'], resolve))
        },
        {
            name:'pptList',
                path:'/wx/pptList',
            component: resolve =>void(require(['../components/admin/ppt/pptList.vue'], resolve))
        },
        {
            name:'ppt',
                path:'/wx/ppt',
            component: resolve =>void(require(['../components/admin/ppt/ppt.vue'], resolve))
        },

        {
            name:'vedioList',
                path:'/wx/vedioList',
            component: resolve =>void(require(['../components/admin/vedio/vedioList.vue'], resolve))
        },
        {
            name:'vedio',
                path:'/wx/vedio',
            component: resolve =>void(require(['../components/admin/vedio/vedio.vue'], resolve))
        }
    ]
})

