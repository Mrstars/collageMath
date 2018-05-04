<template>
    <div>
        <div class="gm-breadcrumb">
            <span class="el-breadcrumb__item__inner"><i class="ion-ios-home gm-home"></i>当前位置：</span>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>智慧数学后台</el-breadcrumb-item>
                <el-breadcrumb-item>上传视频</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-upload
                ref="upload"
                class="avatar-uploader"
                action="/admin/vedio/upload"
                :data="csrf_token"
                :show-file-list="false"
                :on-success="uploadSuccess"
                :before-upload="beforeUpload"
                :on-change="filechange"
                :auto-upload="false">
            <img v-if="imageUrl" :src="imageUrl" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>

        </el-upload>
        <el-form label-width="80px" :model="csrf_token" style="width:50%; margin: 0 auto">
            <el-form-item label="名称">
                <el-input v-model="csrf_token.name"></el-input>
            </el-form-item>
            <el-form-item label="作者">
                <el-input v-model="csrf_token.vedio_author"></el-input>
            </el-form-item>
            <el-form-item label="简介">
                <el-input v-model="csrf_token.vedio_introduction"></el-input>
            </el-form-item>
            <el-form-item label="链接">
                <el-input v-model="csrf_token.link"></el-input>
            </el-form-item>
            <el-form-item label="课程">
                <!--<el-input v-model="csrf_token.class"></el-input>-->
                <el-dropdown trigger="click" @command="handleCommand">
                      <span class="el-dropdown-link">
                        {{this.className}}<i class="el-icon-arrow-down el-icon--right"></i>
                      </span>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(item, index) in this.liList" :key="liList.id" :command="item.id">{{item.class_name}}</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </el-form-item>
        </el-form>
        <el-button style="margin-left: 30%;" size="small" type="success" @click="submitUpload">上传信息</el-button>
    </div>
</template>
<style>
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .avatar-uploader .el-upload:hover {
        border-color: #409EFF;
    }

    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 178px;
        height: 178px;
        line-height: 178px;
        text-align: center;
    }

    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }

    .bg-purple-dark {
        background: #99a9bf;
    }

    .bg-purple {
        background: #d3dce6;
    }

    .bg-purple-light {
        background: #e5e9f2;
    }

    .grid-content {
        border-radius: 4px;
        min-height: 36px;
        text-align: center;
        padding: 40px 0;
        font-size: 30px;
    }

    .row-bg {
        padding: 10px 0;
        background-color: #f9fafc;
    }
</style>

<script>
    export default {
        components: {},
        data() {
            return {
                imageUrl: '',
                csrf_token: {
                    '_token': $('meta[name="csrf"]').attr('content'),
                    name: '', vedio_author: '', vedio_introduction: '',link:'',classId:0
                },
                judgeUpload:false,
                liList:[{id:0,name:'test'}],
                className:'请选择课程'

            }
        },
        methods: {
            submitUpload() {
                this.$refs.upload.submit();
            },
            uploadSuccess(res, file) {
//
                this.judgeUpload = true;
                if (res.code == 0) {
                    this.$message({
                        message: '上传成功,请重新选择文件再次上传',
                        type: 'success'
                    });
                    this.imageUrl = ''
                    this.csrf_token.name = ''
                    this.csrf_token.vedio_author = ''
                    this.csrf_token.vedio_introduction = ''
                    this.csrf_token.link = ''
                }
                else {
                    this.$message.error(res.msg)
                }

            },
            beforeUpload(file) {
//this.upload()

                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;
                const name = this.csrf_token.name != '';
                const author = this.csrf_token.vedio_author != '';
                const introduction = this.csrf_token.vedio_introduction != '';
                const path = this.csrf_token.link != '';
                const number = this.csrf_token.classId !=0
                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!')
                } else if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                } else if (!name) {
                    this.$message.error('名称为空')
                } else if (!author) {
                    this.$message.error('作者为空')
                } else if (!introduction) {
                    this.$message.error('简介为空')
                }else if(!number){
                    this.$message.error('未选择课程')
                }else if(!path){
                    this.$message.error('链接为空')
                }


                return isJPG && isLt2M && name && author && introduction && number && path;
            }, filechange(file) {
                if(!this.judgeUpload)
                    this.imageUrl = URL.createObjectURL(file.raw);
                else{
                    this.imageUrl = ""
                    this.judgeUpload = false;
                }
            },
            handleCommand(command) {
                this.csrf_token.classId = command;
                for(let i in this.liList){
                    if(this.liList[i].id == command){
                        this.className = this.liList[i].class_name
                        break;
                    }
                }
            }

        },
        mounted() {
        },
        created:function () {
            axios.get('/admin/classlist').then(res=>{
                this.liList = res.data.result;
            })
        }
    }
</script>
<style>
    .el-dropdown-link {
        cursor: pointer;
        color: #409EFF;
    }
    .el-icon-arrow-down {
        font-size: 12px;
    }
</style>
