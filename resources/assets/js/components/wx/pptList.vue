<template>
    <div>
        <div style="width: 100%;height: 50px;background-color: #4d93db">
            <p style="width: 80px;height: 20px;margin: 18px 0 0 0;font-size: 13px;text-align: center;color: white;float: left">
                资料列表</p>
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
                type: '5',
                page: 1,
                list: [],
                selectType: 1,
                footer: {
                    title: '加载更多',
                },
                descCol: 'upload_time',
                show: false,
                message: '',
                url: '',
                testType: '0',
                show2: false,
                menus2: {
                    menu1: '按浏览量排序',
                    menu2: '按时间排序',
                }, show1: false,
                menus1: ['全部'],
                menus1List: {},
                judge: 0,
                oneClass: ''

            }
        },
        methods: {
            clickClass(key, val) {

                if (key < this.list.length) {
                    this.oneClass = this.menus1List[key].id
                    this.url = '/wx/getOnePptList?descCol=upload_time&type=' + this.oneClass + "&page=";
                    this.judge = 1
                } else {
                    this.url = '/wx/pptList?descCol=' + this.descCol + '&page='
                }
                this.page = 1;
                this.initSelect();
            },
            click(key, val) {

                if (val == '按浏览量排序') {
                    this.descCol = 'recommended'
                    if (this.judge == 0) {
                        this.url = '/wx/pptList?descCol=recommended&page='
                    } else {
                        this.url = '/wx/getOnePptList?descCol=recommended&type=' + this.oneClass + "&page=";
                    }
                } else {
                    this.descCol = 'upload_time'
                    if (this.judge == 0) {
                        this.url = '/wx/pptList?descCol=upload_time&page='
                    } else {
                        this.url = '/wx/getOnePptList?descCol=upload_time&type=' + this.oneClass + "&page=";
                    }

                }

                this.page = 1;
                this.initSelect();
            },
            change(val) {

                this.testType = val;
            },
            onItemClick() {
                this.show2 = true;
            },
            onItemClickTime() {

                this.show1 = true;
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
                            src: '',
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
                            this.list[i].src = list_copy[i].src;
                            this.list[i].title = list_copy[i].title;
                            this.list[i].desc = list_copy[i].desc;
                            this.list[i].url = list_copy[i].url;
                            this.list[i].meta.source = list_copy[i].meta.source;
                            this.list[i].meta.date = list_copy[i].meta.date;
                            this.list[i].meta.other = list_copy[i].meta.other

                        }
                        for (let i in arr) {
                            let j = length + parseInt(i);
                            this.initList_I(this, j);
                            this.list[j].id = arr[i].id;
                            this.list[j].src = 'http://www.math.com/storage/tubiao/'
                            let str = arr[i].ppt_path.split('.')
                            let size = str.length-1
                            if(str[size] == 'ppt' || str[size] == 'pptx'|| str[size]=='pdf'){
                                this.list[j].src+='ppt.jpg'
                            }else{
                                this.list[j].src+='world.jpg'
                            }
                            this.list[j].title = arr[i].ppt_name;
                            this.list[j].desc = arr[i].ppt_introduction;
                            this.list[j].url = "/ppt/" + arr[i].id;
                            this.list[j].meta.source = "上传者:" + arr[i].ppt_writer;
                            this.list[j].meta.date = new Date(arr[i].upload_time).format("yyyy-MM-dd");
                            this.list[j].meta.other = "下载量:" + arr[i].download
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
                    src: '',
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
                axios.get(this.url + this.page).then(res => {
                   if(res.data.code == 0){
                       this.list = [{
                           id: 0,
                           src: '',
                           title: '',
                           desc: '',
                           url: '',
                           meta: {
                               source: '',
                               date: '',
                               other: ''
                           }
                       }]

                       console.log(res)
                       var arr = res.data.result.data;
                       for (let i in arr) {
                           this.initList_I(this, i);
                           this.list[i].id = arr[i].id;
                           this.list[i].src = 'http://www.math.com/storage/tubiao/'
                           let str = arr[i].ppt_path.split('.')
                           let size = str.length-1;
                           if(str[size] == 'ppt' || str[size] == 'pptx' || str[size] == 'pdf'){
                               this.list[i].src+='ppt.jpg'
                           }else{
                               this.list[i].src+='world.jpg'
                           }
                           this.list[i].title = arr[i].ppt_name;
                           this.list[i].desc = arr[i].ppt_introduction;
                           this.list[i].url = "/ppt/" + arr[i].id;
                           this.list[i].meta.source = "上传者:" + arr[i].ppt_writer;
                           this.list[i].meta.date = new Date(arr[i].upload_time).format("yyyy-MM-dd");
                           this.list[i].meta.other = "下载量:" + arr[i].download

                       }
                   }else if(res.data.code == 2){
                       this.list = [{
                           id: 0,
                           src: '',
                           title: '',
                           desc: '',
                           url: '',
                           meta: {
                               source: '',
                               date: '',
                               other: ''
                           }
                       }]
                       this.message = "暂无数据"
                       this.show = true
                   }
                })
            }


        },
        created: function () {
        },
        mounted: function () {
            this.url = '/wx/pptList?descCol=' + this.descCol + '&page='
            this.initSelect();
            axios.get('/wx/classlist').then(res => {
                this.menus1List = res.data.result
                let i;
                for (i in this.menus1List) {
                    this.menus1[i] = this.menus1List[i].class_name;
                }
                this.menus1.push('全部')
            })
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

    .weui-panel:before {
        display: none !important;
    }


</style>