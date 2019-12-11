<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div>
                <div style="margin: 20px 0px;overflow: auto;">
                    <el-col class="jibie">题目类型：</el-col>
                    <el-col style="width:200px;margin-right:10px;">
                        <el-select v-model="labelvalue" placeholder="选择类型" clearable>
                            <el-option
                            v-for="item in labelinfo"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                            </el-option>
                        </el-select>
                    </el-col>
                    <el-col style="width: 180px;margin-right:20px;">
                        <el-input v-model="title" placeholder="请输标题" clearable></el-input>
                    </el-col>
                    <el-col style="width: 100px;" >
                        <el-button type="primary" icon="el-icon-search" round :loading="loadingStatus" @click="getServiceData()">搜索</el-button>
                    </el-col>
                    <el-col style="width: 100px;margin-left:20px;" >
                        <el-button type="primary" icon="el-icon-circle-plus-outline" round @click="adddata()">新增考题</el-button>
                    </el-col>
                </div>
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <el-table-column prop="title" label="标题"></el-table-column>
                <el-table-column prop="label" label="题目类型">
                    <template slot-scope="scope">
                        <span v-if="scope.row.label == 1">基础</span>
                        <span v-else-if="scope.row.label == 2">专业</span>
                        <span v-else-if="scope.row.label == 4">营销</span>
                        <span v-else></span>
                    </template>
                </el-table-column>
                <el-table-column prop="type" label="分类">
                    <template slot-scope="scope">
                        <span v-if="scope.row.type == 1">单选</span>
                        <span v-else-if="scope.row.type == 2">多选</span>
                        <span v-else></span>
                    </template>
                </el-table-column>
                <el-table-column prop="score" label="分值"></el-table-column>
                <el-table-column prop="operation" label="操作">
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
            <el-dialog :title="dialog" :visible.sync="dialogFormVisible" @close="resetForm('ruleForm')" class="dialone">
                <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
                    <el-form-item label="题目标题：" prop="title">
                        <el-input v-model="ruleForm.title" class="width250" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="题目类型：" prop="label">
                        <el-select v-model="ruleForm.label" placeholder="选择类型" class="width250" clearable>
                            <el-option
                            v-for="item in labelinfo"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="题目分类：" prop="type">
                        <el-select v-model="ruleForm.type" placeholder="选择类型" class="width250" clearable>
                            <el-option
                            v-for="item in typeinfo"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="分值：" prop="score">
                        <el-input v-model="ruleForm.score" class="width250" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="题目选项：" prop="answers">
                        <el-input v-model="ruleForm.answers" type="textarea" class="width250" placeholder="每个选项回车分隔" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="标准答案：" prop="right_answer">
                        <el-input v-model="ruleForm.right_answer" class="width250" placeholder="多个答案/分隔，如：A/B/C" clearable></el-input>
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
            title:'',
            loadingStatus:false,
            signup:false,
            page: 1,
            page_count: 10,
            total_count: 0,
            orderData:[],
            labelvalue:'',
            labelinfo:[{
                value: '1',
                label: '基础'
            },{
                value: '2',
                label: '专业'
            },{
                value: '4',
                label: '营销'
            }],
            typeinfo:[{
                value: '1',
                label: '单选'
            },{
                value: '2',
                label: '多选'
            }],
            dialog:'新增考题',
            dialogFormVisible:false,
            laoding_b:false,
            ruleForm: {
                title: '',
                label:'',
                type:'',
                score:'',
                answers:'',
                right_answer:'',
            },
            rules: {
                title: [
                    { required: true, message: '请输入题目标题', trigger: 'blur' }
                ],
                label: [
                    { required: true, message: '请选择题目类型', trigger: 'change' }
                ],
                type: [
                    { required: true, message: '请选择题目分类', trigger: 'change' }
                ],
                score: [
                    { required: true, message: '请输入分值', trigger: 'blur' }
                ],
                answers: [
                    { required: true, message: '请输入题目选项', trigger: 'blur' }
                ],
                right_answer: [
                    { required: true, message: '请输入标准答案', trigger: 'blur' }
                ],
            },
            tiku_id:'',
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        submitForm(formName){
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    var query = "&title=" + this.ruleForm.title 
                                + "&label=" + this.ruleForm.label
                                + "&type=" + this.ruleForm.type
                                + "&score=" + this.ruleForm.score
                                + "&right_answer=" + this.ruleForm.right_answer
                                + "&answers=" + this.ruleForm.answers
                                + "&tiku_id=" + this.tiku_id
                    this.$http.get(Remote.questionsadd + query).then((response) => {
                        var data=response.data.data
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
        adddata(){
            this.dialog = '新增考题'
            this.ruleForm.title = ''
            this.ruleForm.label = ''
            this.ruleForm.type = ''
            this.ruleForm.answers = ''
            this.ruleForm.right_answer = ''
            this.ruleForm.score = ''
            this.tiku_id = ''
            this.dialogFormVisible = true
        },
        edit_q(info){
            this.dialog = '编辑考题'
            this.ruleForm.title = info.title
            this.ruleForm.label = info.label
            this.ruleForm.type = info.type
            this.ruleForm.answers = info.answers
            this.ruleForm.right_answer = info.right_answer
            this.ruleForm.score = info.score
            this.tiku_id = info.id
            this.dialogFormVisible = true
        },
        delete_q(info){
            this.$confirm('此操作将永久删除该数据, 是否继续?', '提示', {
                confirmButtonText: '确定',
                cancelButtonText: '取消',
                type: 'warning'
            }).then(() => {
                var query = "&id=" + info.id
                this.$http.get(Remote.questionsdel + query).then((response) => {
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
        getServiceData: function () {
            this.loadingStatus = true
            var query = '&page=' + this.page + '&page_size=' + this.page_count + '&label=' + this.labelvalue + '&title=' + this.title
            this.$http.get(Remote.questionslist + query).then((response) => {
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
<style>
.width250 .el-textarea__inner{
    height:100px !important;
}
.dialone .el-dialog{
    margin-top: 5vh !important;
}
</style>
<style scoped>
.jibie{
    width: 80px;
    line-height: 40px;
}
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
</style>
