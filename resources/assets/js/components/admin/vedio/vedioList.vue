<template>
    <div>
        <div class="gm-breadcrumb">
            <span class="el-breadcrumb__item__inner"><i class="ion-ios-home gm-home"></i>当前位置：</span>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item>智慧数学后台</el-breadcrumb-item>
                <el-breadcrumb-item>视频列表</el-breadcrumb-item>
            </el-breadcrumb>
        </div>

        <el-dialog :visible.sync="dlShow">
            <el-upload

                    ref="upload"
                    class="avatar-uploader"
                    action="/admin/vedio/update"
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
            <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传信息</el-button>
        </el-dialog>
        <el-table
                v-loading="loading"
                :data="tableData"
                stripe
                style="width: 100%"
                @selection-change="select">
            <el-table-column
                    type="selection"
                    width="55">
            </el-table-column>
            <el-table-column
                    prop="video_name"
                    label="名称"
                    width="540">
            </el-table-column>
            <el-table-column
                    prop="video_wirter"
                    label="上传者"
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="pageviews"
                    label="浏览数"
                    width="180">
            </el-table-column>
            <el-table-column
                    label="操作">
                <template slot-scope="scope">
                    <el-button size="mini"
                               @click="handleEdit(scope.$index, scope.row)">
                        <i class="el-icon-edit"></i>
                    </el-button>
                    <el-button size="mini" type="danger"
                               @click="deleteOne(scope.$index, scope.row)">
                        <i class="el-icon-delete"></i>
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
        <el-button type="primary" @click.native="deleteMore">删除所选</el-button>
        <el-pagination
                background
                @current-change="getPage"
                layout="prev, pager, next, jumper"
                :total="maxPage">
        </el-pagination>

    </div>
</template>
<style>
    .el-upload {
        display: block !important;
        width: 25% !important;
        margin: 0 auto !important;
    }

    .el-form-item {
        margin: 10px auto !important;
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
    import ElDialog from "../../../../../../node_modules/element-ui/packages/dialog/src/component.vue";

    export default {
        components: {
            ElDialog

        },
        data() {
            return {
                tableData: [],
                maxPage: 0,
                loading: false,
                page: 1,
                idList: [],
                dlShow: false,
                imageUrl: '',
                csrf_token: {
                    '_token': $('meta[name="csrf"]').attr('content'),
                    name: '', vedio_author: '', vedio_introduction: '',ifUpload:false,oldFileName:'',id:0,classId:0,link:''
                },
                judgeUpload: false,
                liList:[{id:0,name:'test'}],
                className:'请选择课程'

            }
        },
        methods: {
            handleEdit(index, row) {
                this.dlShow = true;
                let vedioId = this.tableData[index].id
                axios.get('/admin/vedio/getVedio?id=' + vedioId).then(res => {
                    if (res.data.code == 0) {
                        this.imageUrl = '/storage/avatars/'+res.data.result.video_img;
                        this.csrf_token.name = res.data.result.video_name;
                        this.csrf_token.vedio_author = res.data.result.video_wirter;
                        this.csrf_token.vedio_introduction = res.data.result.vedio_introduction;
                        this.csrf_token.oldFileName = res.data.result.vedio_img;
                        this.csrf_token.id = vedioId;
                        this.csrf_token.link = res.data.result.video_path
                        this.csrf_token.classId = res.data.result.class_id
                        this.handleCommand(res.data.result.class_id)

                    }
                })
            },

            deleteOne(index, row) {
                this.loading = true;
                this.delete(this.tableData[index].id);
            },

            select(val) {
                this.idList = val;
            },

            deleteMore() {
                let id = [];

                for (let i in this.idList) {
                    id[i] = this.idList[i].id;
                }
                this.idList = []
                this.delete(id);
            },

            delete(id) {
                axios.post('/admin/vedio/delete', {
                    id: id
                }).then(res => {
                    if (res.data.code == 0) {
                        this.getPage(this.page)
                        this.$message({
                            message: '删除成功',
                            type: 'success'
                        });
                    } else {
                        this.$message.error(res.data.msg);
                        this.loading = false;
                    }
                })
            },

            getPage(page = 1) {
                this.page = page;
                this.loading = true;
                axios.get('/admin/vedio/getList?page=' + page).then(
                    res => {

                        if (res.data.code == 0) {
                            this.tableData = res.data.result.data
                            this.maxPage = res.data.result.total;
                        } else {
                            this.$message.error(res.data.msg);
                        }
                        this.loading = false;
                    }
                )
            }, submitUpload() {
                this.$refs.upload.submit();
            },
            uploadSuccess(res, file) {
//
                this.judgeUpload = true;
                if (res.code == 0) {
                    this.$message({
                        message: '修改成功',
                        type: 'success'
                    });
                    this.imageUrl = ''
                    this.csrf_token.name = ''
                    this.csrf_token.vedio_author = ''
                    this.csrf_token.vedio_introduction = ''
                    this.csrf_token.link=''
                    this.dlShow = false;
                    this.getPage()
                }
                else {
                    this.$message.error(res.msg)
                }

            },
            beforeUpload(file) {

                const isJPG = file.type === 'image/jpeg';
                const isLt2M = file.size / 1024 / 1024 < 2;
                const name = this.csrf_token.name != '';
                const author = this.csrf_token.vedio_author != '';
                const introduction = this.csrf_token.vedio_introduction != '';

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
                this.csrf_token.ifUpload = true;
                if (!this.judgeUpload)
                    this.imageUrl = URL.createObjectURL(file.raw);
                else {
                    this.imageUrl = ""
                    this.judgeUpload = false;
                }
            },
            handleCommand(command) {
                console.log(11)
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
            this.getPage()
        },
        created:function () {
            axios.get('/admin/classlist').then(res=>{
                this.liList = res.data.result;
            })
        }
    }
</script>
<style>
    .el-dropdown-menu{
        z-index: 10000 !important;
    }
</style>