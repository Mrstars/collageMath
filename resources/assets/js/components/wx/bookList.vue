<template>
    <div>
        <div style="width: 100%;height: 50px;background-color: #4d93db">
            <p style="width: 80px;height: 20px;margin: 18px 0 0 0;font-size: 13px;text-align: center;color: white;float: left">
                教材列表</p>
            <p style="width: 80px;margin: 0 auto;height: 20px;padding: 15px 0;font-size: 15px;text-align: center;color: white">
                魅力数学</p>
        </div>

        <alert v-model="show">{{ this.message }}</alert>

        <tab>
            <tab-item @on-item-click="onItemClickTime">
                选择科目
            </tab-item>
            <tab-item @on-item-click="onItemClick">排序方式</tab-item>
        </tab>
        <actionsheet v-model="show1" :menus="menus1" @on-click-menu="clickClass" theme="android"
                     show-cancel></actionsheet>
        <actionsheet v-model="show2" :menus="menus2" @on-click-menu="click" theme="android" show-cancel></actionsheet>
        <panel :list="list" :type="type" :footer="footer" @on-click-footer="onclickfooter()"></panel>

    </div>
</template>
<script>
    import {
        Grid,
        GridItem,
        GroupTitle,
        Datetime,
        Group,
        Tab,
        TabItem,
        Panel,
        LoadMore,
        InlineLoading,
        XButton,
        TransferDomDirective as TransferDom,
        Alert,
        Selector,
        Actionsheet
    } from 'vux'
    import Router from 'vue-router';

    Vue.use(Router)

    export default {
        components: {
            Grid,
            GridItem,
            GroupTitle,
            Group,
            Datetime,
            Tab,
            TabItem,
            Panel,
            XButton,
            Alert,
            Selector,
            Actionsheet

        },
        data() {
            return {
                test: '测试',
                test1: '生日',
                type: '4',
                page: 1,
                list: [],
                selectType: 1,
                footer: {
                    title: '加载更多',
                },
                show: false,
                message: '',
                url: '/News/getNew?page=',
                testType: '0',
                lists: ['高数', '线性代数'],
                show2: false,
                menus2: {
                    menu1: '按浏览量排序',
                    menu2: '按时间排序',
                }, show1: false, menus1: {1: 'test'}

            }
        },
        methods: {
            clickClass(){

            },
            click() {
            },
            change(val) {
                console.log(val);
                this.testType = val;
            },
            onItemClick() {
                this.show2 = true;
            },
            onItemClickTime() {
                this.show1 = true;
//                if (this.selectType == 1) {
//
//                    this.selectType = 0;
//                    var obj_cancel = document.getElementsByClassName('dp-item dp-left vux-datetime-cancel')
//                    var obj_ok = document.getElementsByClassName('dp-item dp-right vux-datetime-confirm')
//                    var object = this;
//                    obj_cancel[0].addEventListener('click', function () {
//
//                        object.selectType = 1;
//                    })
//                    obj_ok[0].addEventListener('click', function () {
//                        object.page = 1;
//                        object.url = '/News/getNewTime?date=' + object.value1 + '&page='
//                        axios.get('/News/getNewTime?date=' + object.value1 + '&page=' + object.page).then(res => {
//                            object.timeInit(object, res);
//
//
//                        })
//
//
//                    })
//                }
            },

            timeInit(object, res) {
                if (res.data.code == 0) {
                    object.list = [{
                        id: 0,
                        title: '',
                        desc: '',
                        url: '',
                        meta: {
                            source: '',
                            date: '',
                            other: ''
                        }
                    }]

                    var arr = res.data.result.data;
                    for (let i in arr) {
                        object.initList_I(object, i);
                        object.list[i].id = arr[i].idmarch_news;
                        object.list[i].title = arr[i].news_title;
                        object.list[i].desc = arr[i].news_content.replace(/<\/?.+?>/g, "").replace(/ /g, "").substr(0, 100);
                        object.list[i].url = "/newShow/" + arr[i].idmarch_news + '/' + this.testType;
                        object.list[i].meta.source = "发布者:" + arr[i].news_publisher;
                        object.list[i].meta.date = new Date(arr[i].news_date).format("yyyy-MM-dd");
                        object.list[i].meta.other = "阅读量:" + arr[i].news_num

                    }
                } else if (res.data.code == 2) {
                    object.message = "暂无此时间段的新闻"
                    object.show = true;
                } else {
                    object.message = "哎呀！出错了"
                    object.show = true;
                }
            },
            onclickfooter() {
                this.page++
                axios.get(this.url + this.page).then(res => {
                    if (res.data.code == 0) {
                        var arr = res.data.result.data;
                        var length = this.list.length;
                        length = parseInt(length);
                        let list_copy = this.list
                        this.list = [{
                            id: 0,
                            title: '',
                            desc: '',
                            url: '',
                            meta: {
                                source: '',
                                date: '',
                                other: ''
                            }
                        }]
                        for (var i = 0; i < length; i++) {
                            this.initList_I(this, i);
                            this.list[i].id = list_copy[i].id;
                            this.list[i].title = list_copy[i].title;
                            this.list[i].desc = list_copy[i].desc;
                            this.list[i].url = list_copy[i].url;
                            this.list[i].meta.source = list_copy[i].meta.source;
                            this.list[i].meta.date = list_copy[i].meta.date;
                            this.list[i].meta.other = list_copy[i].meta.other;

                        }
                        for (let i in arr) {
                            let j = length + parseInt(i);
                            this.initList_I(this, j);
                            this.list[j].id = arr[i].idmarch_news;
                            this.list[j].title = arr[i].news_title;
                            this.list[j].desc = arr[i].news_content.replace(/<\/?.+?>/g, "").replace(/ /g, "").substr(0, 100);
                            this.list[j].url = "/newShow/" + arr[i].idmarch_news + '/' + this.testType;
                            this.list[j].meta.source = "发布者:" + arr[i].news_publisher;
                            this.list[j].meta.date = new Date(arr[i].news_date).format("yyyy-MM-dd");
                            this.list[j].meta.other = "阅读量:" + arr[i].news_num
                        }
                    } else if (res.data.code == 2) {
                        this.message = "已经到底了"
                        this.show = true;
                    } else {
                        this.message = "哎呀！出错了"
                        this.show = true;
                    }

                })
            },

            initList_I(obj, i) {
                obj.list[i] = {
                    id: 0,
                    title: '',
                    desc: '',
                    url: '',
                    meta: {
                        source: '',
                        date: '',
                        other: ''
                    }
                }
            },

            initSelect() {
                axios.get("/News/getNew?page=" + this.page).then(res => {
                    this.list = [{
                        id: 0,
                        title: '',
                        desc: '',
                        url: '',
                        meta: {
                            source: '',
                            date: '',
                            other: ''
                        }
                    }]
                    var arr = res.data.result.data;
                    for (let i in arr) {
                        this.initList_I(this, i);
                        this.list[i].id = arr[i].idmarch_news;
                        this.list[i].title = arr[i].news_title;
                        this.list[i].desc = arr[i].news_content.replace(/<\/?.+?>/g, "").replace(/ /g, "").substr(0, 100);
                        this.list[i].url = "/newShow/" + arr[i].idmarch_news + '/' + this.testType;
                        this.list[i].meta.source = "发布者:" + arr[i].news_publisher;
                        this.list[i].meta.date = new Date(arr[i].news_date).format("yyyy-MM-dd");
                        this.list[i].meta.other = "阅读量:" + arr[i].news_num

                    }
                })
            }


        },
        created: function () {

            if (this.$route.params.date == '0') {
                var date = new Date();
                var mytime = date.format('yyyy-MM');
                this.value1 = mytime;
                this.testType = '0'
            } else {
                this.value1 = this.$route.params.date;
                this.testType = this.value1;
            }
        },
        mounted: function () {
//            if (this.$route.params.date == '0') {
//                this.initSelect();
//            } else {
//                this.selectType = 0;
//                this.value1 = this.$route.params.date;
//                this.url = '/News/getNewTime?date=' + this.value1 + '&page='
//                axios.get('/News/getNewTime?date=' + this.value1 + '&page=' + this.page).then(res => {
//                    this.timeInit(this, res);
//
//                })
//            }
        }
    }
</script>


<style>
    * {
        margin: 0;
        padding: 0;
    }

    a {
        text-decoration: none !important;
    }

    .weui-cells {
        margin-top: 0 !important;
    }

    .weui-cell__ft {
        text-align: center !important;
    }

    .weui-panel:before {
        /*border-top: 0px !important;*/
        transform: none !important;
    }

    .weui-cell_select .weui-cell__bd:after {
        display: none !important;
    }

    .vux-tab .vux-tab-item.vux-tab-selected {
        color: #666 !important;
        border-bottom: 0 #FFFFFF !important;
    }

    .vux-tab-ink-bar {
        display: none !important;
    }


</style>