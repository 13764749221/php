<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div>
                <div style="margin: 20px 0px;overflow: auto;">
                <el-col class="addbuttons">
                    <el-button type="primary" icon="el-icon-circle-plus-outline" round :loading="signup" @click="addtype()">添加分类</el-button>
                </el-col>
                </div>
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <el-table-column prop="name" label="课程类型名称"></el-table-column>
                <el-table-column prop="create_time" label="创建时间"></el-table-column>
                <el-table-column prop="update_time" label="最后更新时间"></el-table-column>
                <el-table-column prop="status" label="操作" >
                    <template slot-scope="scope">
                        <span @click="edit_q(scope.row)" class="spanstyle">编辑</span>
                        <span @click="delete_q(scope.row)" class="stop">删除</span>
                    </template>
                </el-table-column>
            </el-table>
            <div style="text-align:center;">
                <el-pagination
                @size-change="handleSizeChange"
                @current-change="handleCurrentChange"
                :current-page="page"
                :page-sizes="[10, 20, 30 ,50, 100]"
                :page-size="page_count"
                layout="total, sizes, prev, pager, next, jumper"
                :total="total_count">
                </el-pagination>
            </div>
            <el-dialog :title="dialog" :visible.sync="dialogFormVisible" @close="resetForm('ruleForm')">
                <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="120px" class="demo-ruleForm">
                    <el-form-item label="课程类型名称：" prop="name">
                        <el-input v-model="ruleForm.name" class="width218" clearable></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('ruleForm')" :loading="laoding_b">确定</el-button>
                        <el-button @click="resetForm('ruleForm')">取消</el-button>
                    </el-form-item>
                </el-form>
            </el-dialog>
        </div>
    </d2-container>
</template>

<script>
/*eslint-disable */
import { Remote } from '../../../../remote'
export default {
    data () {
        return {
            loadingStatus:false,
            signup:false,
            page: 1,
            page_count: 10,
            total_count: 0,
            orderData:[],
            ruleForm: {
                name: '',
            },
            rules: {
                name: [
                    { required: true, message: '请输入课程类型名称', trigger: 'blur' }
                ]
            },
            dialogFormVisible:false,
            dialog:'添加分类',
            class_id:'',
            laoding_b:false,
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        addtype(){
            this.dialog = '添加分类'
            this.class_id = ''
            this.ruleForm.name = ''
            this.dialogFormVisible = true
        },
        edit_q(info){
             this.dialog = '编辑分类'
            this.class_id = info.id
            this.ruleForm.name = info.name
            this.dialogFormVisible = true
        },
        delete_q(info){
            this.$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                var query = "&id=" + info.id
                this.$http.get(Remote.curriculumdel + query).then((response) => {
                    var data=response.data.data
                    if (data.msg == '') {
                        this.$message({
                            type: 'success',
                            message: '删除成功!'
                        });
                        this.getServiceData()
                    }else{
                        this.$message({
                            type: 'error',
                            message: data.msg
                        });
                    }
                },function(){
                    this.loadingStatus = false
                    this.$message({
                        message: '请求失败',
                        type: 'error'
                    });
                })
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消删除'
                });          
            });
        },
        submitForm(formName){
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    this.laoding_b = true
                    var query = "&name=" + this.ruleForm.name + "&class_id=" + this.class_id
                    this.$http.get(Remote.curriculumadd + query).then((response) => {
                        var data=response.data.data
                        this.laoding_b = false
                        if (data.msg == '') {
                            this.$message({
                                type: 'success',
                                message: '操作成功!'
                            });
                            this.resetForm('ruleForm')
                            this.getServiceData()
                        }else{
                            this.$message({
                                type: 'error',
                                message: data.msg
                            });
                        }
                    },function(){
                        this.loadingStatus = false
                        this.$message({
                            message: '请求失败',
                            type: 'error'
                        });
                    })
                } else {
                    return false;
                }
            });
        },
        resetForm(formName) {
            this.dialogFormVisible = false
            this.$refs[formName].resetFields();
        },
        getServiceData: function () {
            this.loadingStatus = true
            var query = '&page=' + this.page + '&page_size=' + this.page_count
            this.$http.get(Remote.curriculumlist + query).then((response) => {
                var data=response.data.data
                if (response.data.msg == 0) {
                this.total_count = data.count
                this.orderData = data.res
                this.loadingStatus = false
                }
            },function(){
                this.loadingStatus = false
                this.$message({
                message: '请求失败',
                type: 'error'
                });
            })
        },
        handleSizeChange(val) {
            this.page_count=30
            if(val>0){
                this.page_count=val
            }
            this.getServiceData()
        },
        handleCurrentChange(val) {
            this.page=val==0?1:val
            this.getServiceData()
        },
    }
    }
</script>
<style scoped>
.spanstyle{
    color: #409EFF;
    cursor: pointer;
}
.stop{
    color: red;
    cursor: pointer;
    margin-left: 20px;
}
.width250{
    width: 250px;
}
.addbuttons{
    width: 100px;
    float: right;
    margin-right: 50px;
}
.width218{
    width:218px;
}
</style>
