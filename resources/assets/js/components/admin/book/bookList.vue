<template>
    <div>
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
                    prop="book_name"
                    label="名称"
                    width="540">
            </el-table-column>
            <el-table-column
                    prop="book_author"
                    label="作者"
                    width="180">
            </el-table-column>
            <el-table-column
                    prop="recommended"
                    label="点赞数"
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
        <el-button type="primary"@click.native="deleteMore">删除所选</el-button>
        <el-pagination
                background
                @current-change="getPage"
                layout="prev, pager, next, jumper"
                :total="maxPage">
        </el-pagination>

    </div>
</template>
<script>
    export default{
        components : {

        },
        data(){
            return {
                tableData:[],
                maxPage:0,
                loading:false,
                page:1,
                idList:[]
            }
        },
        methods : {
            handleEdit(index, row){
//                this.$router.push({name:"添加新闻",params: { newId: this.tableData3[index].idmarch_news }})

            },

            deleteOne(index, row) {
                this.loading = true;
                this.delete( this.tableData[index].id);
            },

            select(val){
                this.idList = val;
            },

            deleteMore(){
                let id = [];

                for(let i in this.idList){
                    id[i] = this.idList[i].id;
                }
                this.idList=[]
                this.delete(id);
            },

            delete(id){
                axios.post('/admin/book/delete', {
                    id:id
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

            getPage(page = 1){
                this.page = page;
                this.loading = true;
                axios.get('/admin/book/getList?page='+page).then(
                    res=>{

                        if(res.data.code == 0){
                        this.tableData = res.data.result.data
                        this.maxPage = res.data.result.total;
                        }else{
                            this.$message.error(res.data.msg);
                        }
                        this.loading = false;
                    }
                )
            }
        },
        mounted(){
            this.getPage()
        }
    }
</script>
