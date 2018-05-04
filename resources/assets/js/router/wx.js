import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name: "首页",
            path: '/',
            component: resolve =>void(require(['../components/wx/Wxtest.vue'], resolve))
        },
        {

            name: "首页",
            path: '/tt',
            component: resolve =>void(require(['../components/wx/TT.vue'], resolve))

        },
        {

            name: "bookList",
                path: '/bookList',
            component: resolve =>void(require(['../components/wx/bookList.vue'], resolve))

        },
        {
            name: "book",
                path: '/book/:id',
            component: resolve =>void(require(['../components/wx/book.vue'], resolve))
        },
        {
            name:'pptList',
            path:'/pptList',
            component: resolve =>void(require(['../components/wx/pptList.vue'], resolve))
        },
        {
            name:'ppt',
                path:'/ppt/:id',
            component: resolve =>void(require(['../components/wx/ppt.vue'], resolve))
        },
        {
            name:'ppt',
                path:'/ppt/:id',
            component: resolve =>void(require(['../components/wx/ppt.vue'], resolve))
        },{
            name:'vedio',
                path:'/vedio/:id',
            component: resolve =>void(require(['../components/wx/vedio.vue'], resolve))
        },
{
    name:'vediolist',
        path:'/vedioList',
    component: resolve =>void(require(['../components/wx/vedioList.vue'], resolve))
}
    ]
})

