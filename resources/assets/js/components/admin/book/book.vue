<template>
    <div>
        <div class="gm-breadcrumb">
            <span class="el-breadcrumb__item__inner"><i class="ion-ios-home gm-home"></i>当前位置：</span>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>智慧数学后台</el-breadcrumb-item>
                <el-breadcrumb-item>添加教材</el-breadcrumb-item>
            </el-breadcrumb>
        </div>
        <el-upload
                ref="upload"
                class="avatar-uploader"
                action="/admin/book/upload"
                :data="csrf_token"
                :show-file-list="false"
                :on-success="uploadSuccess"
                :before-upload="beforeUpload"
                :on-change="filechange"
                :auto-upload="false">
            <img v-if="imageUrl" :src="imageUrl" class="avatar">
            <i v-else class="el-icon-plus avatar-uploader-icon"></i>

        </el-upload>
        <el-form label-width="80px" :model="csrf_token">
            <el-form-item label="名称">
                <el-input v-model="csrf_token.name"></el-input>
            </el-form-item>
            <el-form-item label="作者">
                <el-input v-model="csrf_token.book_author"></el-input>
            </el-form-item>
            <el-form-item label="简介">
                <el-input v-model="csrf_token.book_introduction"></el-input>
            </el-form-item>
        </el-form>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传信息</el-button>
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
                    name: '', book_author: '', book_introduction: ''
                },
                judgeUpload:false

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
                    this.csrf_token.book_author = ''
                    this.csrf_token.book_introduction = ''
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
                const author = this.csrf_token.book_author != '';
                const introduction = this.csrf_token.book_introduction != '';

                if (!isJPG) {
                    this.$message.error('上传头像图片只能是 JPG 格式!')
                } else if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                } else if (!name) {
                    this.$message.error('书名为空')
                } else if (!author) {
                    this.$message.error('作者为空')
                } else if (!introduction) {
                    this.$message.error('简介为空')
                }

                return isJPG && isLt2M && name && author && introduction;
            }, filechange(file) {
                if(!this.judgeUpload)
                this.imageUrl = URL.createObjectURL(file.raw);
                else{
                    this.imageUrl = ""
                    this.judgeUpload = false;
                }
            }

        },
        mounted() {

        }
    }
</script>
