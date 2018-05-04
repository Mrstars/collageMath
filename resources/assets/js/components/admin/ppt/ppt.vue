<template>
    <div>
        <div class="gm-breadcrumb">
            <span class="el-breadcrumb__item__inner"><i class="ion-ios-home gm-home"></i>当前位置：</span>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>智慧数学后台</el-breadcrumb-item>
                <el-breadcrumb-item>添加ppt</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-upload
                ref="upload"
                class="upload-demo"
                drag
                action="/admin/ppt/upload"
                :data="csrf_token"
                :on-success="uploadSuccess"
                :before-upload="beforeUpload"
                :auto-upload="false"
                multiple>
            <i class="el-icon-upload"></i>
            <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
            <div class="el-upload__tip" slot="tip">只能上传ppt、word、pdf文件，且不超过100mb</div>

        </el-upload>
        <el-form label-width="80px" :model="csrf_token" style="width:50%;margin: 0 auto">
            <el-form-item label="名称">
                <el-input v-model="csrf_token.name"></el-input>
            </el-form-item>
            <el-form-item label="作者">
                <el-input v-model="csrf_token.ppt_author"></el-input>
            </el-form-item>
            <el-form-item label="简介">
                <el-input v-model="csrf_token.ppt_introduction"></el-input>
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
                    name: '', ppt_author: '', ppt_introduction: '',classId:0
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
                    this.csrf_token.ppt_author = ''
                    this.csrf_token.ppt_introduction = ''
                }
                else {
                    this.$message({
                        message: res.msg,
                        type: 'error'
                    });
                }

            },
            beforeUpload(file) {

                console.log(1)
                console.log(file)
                const isJPG = file.type === 'application/vnd.openxmlformats-officedocument.presentationml.presentation' ||
                    file.type === 'application/vnd.ms-powerpoint' ||
                    file.type === 'application/msword' ||
                    file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                    file.type === 'application/pdf';
                const isLt2M = file.size / 1024 / 1024 < 100;
                const name = this.csrf_token.name != '';
                const author = this.csrf_token.ppt_author != '';
                const introduction = this.csrf_token.ppt_introduction != '';
                const number = this.csrf_token.classId !=0
                if (!isJPG) {
                    this.$message.error('上传文件只能是 ppt、word、ptf文件 !')
                } else if (!isLt2M) {
                    this.$message.error('上传文件大小不能超过 100MB!');
                } else if (!name) {
                    this.$message.error('文件名为空')
                } else if (!author) {
                    this.$message.error('上传者为空')
                } else if (!introduction) {
                    this.$message.error('简介为空')
                }else if(!number){
                    this.$message.error('未选择课程')
                }

                return isJPG && isLt2M && name && author && introduction && number;
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
