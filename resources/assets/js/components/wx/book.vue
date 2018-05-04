<template>
    <div>
        <div style="width: 100%;height: 50px;background-color: #4d93db">
            <p style="width: 80px;height: 20px;margin: 18px 0 0 0;font-size: 13px;text-align: center;color: white;float: left">
                教材展示</p>
            <p style="width: 80px;margin: 0 auto;height: 20px;padding: 15px 0;font-size: 15px;text-align: center;color: white">
                魅力数学</p>
        </div>
        <x-img :src="src" @on-error="error" class="ximg-demo" error-class="ximg-error" :offset="10" :delay="500"container="#vux_view_box_body" style="width: 50%;margin: 50px auto;display: block"></x-img>
        <group label-width="5em" title="详细信息">
            <cell primary="content" title="书名">
                <div>
                    <span style="text-align:right">
                        {{ this.book.book_name }}
                    </span>
                </div>
            </cell>
            <cell primary="content" title="作者" align-items="content">
                <div>
                    <span style="text-align:right">
                        {{ this.book.book_author }}
                    </span>
                </div>
            </cell>
            <cell primary="content" title="浏览量" align-items="content">
                <div>
                    <span style="text-align:right">
                        {{ this.book.recommended }}
                    </span>
                </div>
            </cell>
            <cell primary="content" title="上传时间" align-items="content">
                <div>
                    <span style="text-align:right">
                        {{ this.book.upload_time }}
                    </span>
                </div>
            </cell>
            <cell primary="content" title="所属课程" align-items="content">
                <div>
                    <span style="text-align:right">
                        {{ this.book.class_name }}
                    </span>
                </div>
            </cell>
            <cell primary="content"title="简介" align-items="center">
                <div>
                    <span style="text-align:right">
                        {{ this.book.book_introduction }}
                    </span>
                </div>
            </cell>
        </group>
    </div>
</template>
<script>
    import {
        Group,
        XImg,
        Cell
    } from 'vux'
    import Router from 'vue-router';

    Vue.use(Router)

    export default {
        components: {
            Group,
            XImg,
            Cell
        },
        data(){
            return{
                src:'',
                book:{}
            }
        },
        methods:{
            error(){
                console.log('error')
            }
        },
        created: function () {
            axios.get('/wx/getBook?id='+this.$route.params.id).then(res=>{
                console.log(res)
                this.book = res.data.result;
                this.book.upload_time = new Date(this.book.upload_time).format("yyyy-MM-dd");
                this.src = 'http://www.math.com/storage/avatars/'+res.data.result.book_img
                console.log(this.src)
            })

        },
        mounted: function () {

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
</style>