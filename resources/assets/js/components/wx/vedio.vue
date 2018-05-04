<template>
    <div>
        <div style="width: 100%;height: 50px;background-color: #4d93db">
            <p style="width: 80px;height: 20px;margin: 18px 0 0 0;font-size: 13px;text-align: center;color: white;float: left">
                视频播放</p>
            <p style="width: 80px;margin: 0 auto;height: 20px;padding: 15px 0;font-size: 15px;text-align: center;color: white">
                魅力数学</p>
        </div>
        <video-player  class="video-player vjs-custom-skin"
                       ref="videoPlayer"
                       :playsinline="true"
                       :options="playerOptions"
        ></video-player>
        <group label-width="5em" title="视频简介">
            <cell >
                <div>
                    <span style="text-align:center">
                        {{ this.introduction }}
                    </span>
                </div>
            </cell>
        </group>

    </div>
</template>

<script>
    import { Group, Cell ,Scroller } from 'vux'
    import 'video.js/dist/video-js.css'

    import { videoPlayer } from 'vue-video-player'

    export default{
        components: {
            Group,
            Cell,
            Scroller,
            videoPlayer
        },
        data(){
            return {
                playerOptions : {
                    playbackRates: [0.7, 1.0, 1.5, 2.0], //播放速度
                    autoplay: false, //如果true,浏览器准备好时开始回放。
                    muted: false, // 默认情况下将会消除任何音频。
                    loop: false, // 导致视频一结束就重新开始。
                    preload: 'auto', // 建议浏览器在<video>加载元素后是否应该开始下载视频数据。auto浏览器选择最佳行为,立即开始加载视频（如果浏览器支持）
                    language: 'zh-CN',
                    aspectRatio: '16:9', // 将播放器置于流畅模式，并在计算播放器的动态大小时使用该值。值应该代表一个比例 - 用冒号分隔的两个数字（例如"16:9"或"4:3"）
                    fluid: true, // 当true时，Video.js player将拥有流体大小。换句话说，它将按比例缩放以适应其容器。
                    sources: [{
                        type: "",
                        src: ""//url地址
                    }],
                    poster: "", //你的封面地址
                    // width: document.documentElement.clientWidth,
                    notSupportedMessage: '此视频暂无法播放，请稍后再试', //允许覆盖Video.js无法播放媒体源时显示的默认信息。
                    controlBar: {
                        timeDivider: true,
                        durationDisplay: true,
                        remainingTimeDisplay: false,
                        fullscreenToggle: true  //全屏按钮
                    }
                },
                introduction:''

            }
        },
        methods : {
            onScroll (pos) {

            }
        },
        mounted(){

        },
        created:function () {
            axios.get('/wx/getVedio?id='+this.$route.params.id).then(
                res=>{
                    console.log(this.$refs.videoPlayer.options.sources[0].src)
                    this.$refs.videoPlayer.options.sources[0].src=res.data.result.video_path
                    this.introduction = res.data.result.vedio_introduction
                    this.playerOptions.poster = '/storage/avatars/'+res.data.result.video_img
                }
            )

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