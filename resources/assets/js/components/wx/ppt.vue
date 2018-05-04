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
            <cell primary="content" title="文件标题">
                <div>
                    <span style="text-align:right">
                        {{ this.ppt.ppt_name }}
                    </span>
                </div>
            </cell>
            <cell primary="content" title="上传者" align-items="content">
                <div>
                    <span style="text-align:right">
                        {{ this.ppt.ppt_writer }}
                    </span>
                </div>
            </cell>
            <cell primary="content" title="下载量" align-items="content">
                <div>
                    <span style="text-align:right">
                        {{ this.ppt.download }}
                    </span>
                </div>
            </cell>
            <cell primary="content" title="上传时间" align-items="content">
                <div>
                    <span style="text-align:right">
                        {{ this.ppt.upload_time }}
                    </span>
                </div>
            </cell>
            <cell primary="content" title="所属课程" align-items="content">
                <div>
                    <span style="text-align:right">
                        {{ this.ppt.class_name }}
                    </span>
                </div>
            </cell>
            <cell primary="content"title="简介" align-items="center">
                <div>
                    <span style="text-align:right">
                        {{ this.ppt.ppt_introduction }}
                    </span>
                </div>
            </cell>
        </group>
        <a :href='this.downloadUrl'class="download">下载</a>
    </div>
</template>
<script>
    import {
        Group,
        XImg,
        Cell,
        XButton
    } from 'vux'
    import Router from 'vue-router';

    Vue.use(Router)

    export default {
        components: {
            Group,
            XImg,
            Cell,
            XButton
        },
        data(){
            return{
                src:'',
                ppt:{},
                downloadUrl:''
            }
        },
        methods:{
            error(){
                console.log('error')
            }
        },
        created: function () {
            axios.get('/wx/getPpt?id='+this.$route.params.id).then(res=>{
                this.ppt = res.data.result;
                this.ppt.upload_time = new Date(this.ppt.upload_time).format("yyyy-MM-dd");
                let img = 'world.jpg';
                let str = res.data.result.ppt_path;
                str=str.split('.')
                let size = str.length-1
                console.log(str.length)
                if(str[size] =='pptx'|| str[size] == 'ppt'|| str[size]=='pdf'){
                    console.log(str)
                    img = 'ppt.jpg'
                }
                this.src = 'http://www.math.com/storage/tubiao/'+img
                this.downloadUrl = '/wx/pptDown?path='+this.ppt.ppt_path
                console.log(img)
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
    .download{
        position: relative;
        display: block;
        margin-left: auto;
        margin-right: auto;
        padding-left: 14px;
        padding-right: 14px;
        box-sizing: border-box;
        font-size: 18px;
        text-align: center;
        text-decoration: none;
        color: #FFFFFF;
        line-height: 2.33333333;
        border-radius: 5px;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        overflow: hidden;background-color: #1AAD19;
    }
</style>