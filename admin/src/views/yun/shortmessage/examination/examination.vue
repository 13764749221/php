<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div>
            <div style="margin: 20px 0px;overflow: auto;">
                <el-col class="jibie">姓名：</el-col>
                <el-col style="width: 180px;margin-right:20px;">
                    <el-input v-model="name" placeholder="搜索名字" clearable></el-input>
                </el-col>
                <el-col style="width: 100px;" >
                    <el-button type="primary" icon="el-icon-search" round :loading="loadingStatus" @click="getServiceData()">搜索</el-button>
                </el-col>
            </div>
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <el-table-column prop="realname" label="姓名"></el-table-column>
                <el-table-column prop="study_count" label="学习篇数"></el-table-column>
                <el-table-column prop="study_score" label="学习积分"></el-table-column>
                <el-table-column prop="exam_count" label="考试次数"></el-table-column>
                <el-table-column prop="last_examination_date" label="上次考试时间"></el-table-column>
                <el-table-column prop="updated_at" label="派发考试">
                    <template slot-scope="scope">
                        <span @click="distribute(scope.row)" class="spanstyle">派发</span>
                    </template>
                </el-table-column>
                <el-table-column prop="updated_at" label="详情">
                    <template slot-scope="scope">
                        <span @click="details(scope.row)" class="spanstyle">查看详情</span>
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
            <!-- 派发考试 -->
            <el-dialog title="派发考试" :visible.sync="outerVisible" @close="resetForm('ruleForm')">
                <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
                    <el-form-item label="考试类型：" prop="type">
                        <el-select v-model="ruleForm.type" placeholder="选择考试类型" clearable>
                            <el-option
                            v-for="item in typeinfo"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="考试时间：" prop="date">
                        <el-date-picker style="width: 218px;" type="date" placeholder="选择考试时间" v-model="ruleForm.date"></el-date-picker>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('ruleForm')" :loading="laoding_a">添加</el-button>
                        <el-button @click="resetForm('ruleForm')">取消</el-button>
                    </el-form-item>
                </el-form>
            </el-dialog>
            <!-- 查看详情 -->
            <el-dialog title="详情信息" :visible.sync="messagetype">
                <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                    <el-tab-pane label="学习数据" name="study">
                        <span>学习篇数：{{study_num}}</span>
                        <span style="margin-left:20px;">学习积分：{{study_score}}</span>
                        <el-table :data="messageData" border stripe style="margin-top:20px;" v-loading="loading_m">
                            <el-table-column prop="name" label="学习分类"></el-table-column>
                            <el-table-column prop="title" label="学习标题"></el-table-column>
                            <el-table-column prop="study_time" label="学习时间"></el-table-column>
                            <el-table-column prop="points_get" label="获得的学习积分"></el-table-column>
                        </el-table>
                        <div style="text-align:center;">
                            <el-pagination
                                @size-change="handleSizeChange_m"
                                @current-change="handleCurrentChange_m"
                                :current-page="page_m"
                                :page-sizes="[10, 20, 30 ,50, 100]"
                                :page-size="page_count_m"
                                layout="total, sizes, prev, pager, next, jumper"
                                :total="total_count_m">
                            </el-pagination>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane label="考试数据" name="second">
                        <el-table :data="messageData" border stripe style="margin-top:20px;" v-loading="loading_m">
                            <el-table-column prop="type" label="考试类型">
                                <template slot-scope="scope">
                                    <span v-if="scope.row.type == 2">周考</span>
                                    <span v-else-if="scope.row.type == 3">月考</span>
                                    <span v-else-if="scope.row.type == 4">转正考</span>
                                    <span v-else></span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="join_time" label="考试时间"></el-table-column>
                            <el-table-column prop="score" label="考试分数">
                                <template slot-scope="scope">
                                    <span v-if="scope.row.score >=0">{{scope.row.score}}</span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="exam_count" label="考试详情">
                                <template slot-scope="scope">
                                    <span @click="optionmsg(scope.row)" class="spanstyle">查看详情</span>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div style="text-align:center;">
                            <el-pagination
                                @size-change="handleSizeChange_m"
                                @current-change="handleCurrentChange_m"
                                :current-page="page_m"
                                :page-sizes="[10, 20, 30 ,50, 100]"
                                :page-size="page_count_m"
                                layout="total, sizes, prev, pager, next, jumper"
                                :total="total_count_m">
                            </el-pagination>
                        </div>
                    </el-tab-pane>
                </el-tabs>
                <el-dialog width="60%" title="考试详情" :visible.sync="innerVisible" append-to-body>
                    <el-table :data="datainfo" border stripe v-loading="loading_g">
                        <el-table-column prop="title" label="题目"></el-table-column>
                        <el-table-column prop="label" label="题目类型">
                            <template slot-scope="scope">
                                <span v-if="scope.row.label == 1">基础</span>
                                <span v-else-if="scope.row.label == 2">专业</span>
                                <span v-else-if="scope.row.label == 4">营销</span>
                                <span v-else></span>
                            </template>
                        </el-table-column>
                        <el-table-column prop="type" label="题目分类">
                            <template slot-scope="scope">
                                <span v-if="scope.row.type == 1">单选</span>
                                <span v-else-if="scope.row.type == 2">多选</span>
                                <span v-else></span>
                            </template>
                        </el-table-column>
                        <el-table-column prop="score" label="分值"></el-table-column>
                        <el-table-column prop="right_answer" label="正确答案"></el-table-column>
                        <el-table-column prop="my_check" label="我的答案"></el-table-column>
                        <el-table-column prop="is_trues" label="是否正确">
                            <template slot-scope="scope">
                                <i class="el-icon-success" v-if="scope.row.is_trues"></i>
                                <i class="el-icon-error" v-else></i>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-dialog>
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
            name:'',
            loadingStatus:false,
            page: 1,
            page_count: 10,
            total_count: 0,
            orderData:[],
            outerVisible:false,
            typeinfo:[{
                value: '2',
                label: '周考'
            },{
                value: '3',
                label: '月考'
            },{
                value: '4',
                label: '转正考'
            }],
            ruleForm: {
                type: '',
                date:'',
            },
            rules: {
                type: [
                    { required: true, message: '请选择考试类型', trigger: 'change' }
                ],
                date: [
                    { type: 'date', required: true, message: '请选择考试时间', trigger: 'change' }
                ],
            },
            laoding_a:false,
            user_id:'',
            messagetype:false,
            activeName: 'study',
            page_m: 1,
            page_count_m: 10,
            total_count_m: 0,
            select_type:'study',
            loading_m:false,
            messageData:[],
            study_num:0,
            study_score:'',
            study_user_id:'',
            innerVisible: false,
            loading_g:false,
            datainfo:[],
        }
    },
    created () {
        this.getServiceData()
    },
    methods: {
        optionmsg(info){
            this.innerVisible = true
            this.loading_g = true
            var query = '&id=' + info.id
            this.$http.get(Remote.answerinfo + query).then((response) => {
                this.loading_g = false
                this.datainfo = response.data.data.res
            },function(){
                this.loading_g = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            })
        },
        handleClick(tab, event) {
            this.getmessage()
        },
        details(info){
            this.study_num = 0
            this.study_user_id = info.id
            this.study_score = info.study_score
            this.messagetype = true
            this.activeName = 'study'
            this.getmessage()
        },
        getmessage(type){
            this.loading_m = true
            if(this.activeName == 'study'){
                var remotes = Remote.studymessage
            }else{
                var remotes = Remote.exammessage
            }
            var query = '&page=' + this.page_m + '&page_size=' + this.page_count_m + '&uid=' + this.study_user_id
            this.$http.get(remotes + query).then((response) => {
                var data=response.data.data
                if (response.data.msg == 0) {
                    this.total_count_m = data.count
                    this.messageData = data.res
                    if(data.num){
                        this.study_num = data.num
                    }
                    this.loading_m = false
                }
            },function(){
                this.loading_m = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            }) 
        },
        submitForm(formName) {
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    this.laoding_a = true
                    var query = '&id=' + this.user_id + '&type=' + this.ruleForm.type + '&date=' + this.dateFormat(this.ruleForm.date)
                    this.$http.get(Remote.addexamination + query).then((response) => {
                        var data=response.data.data
                        this.laoding_a = false
                        if(data.msg !=''){
                            this.$message({
                                message: data.msg,
                                type: 'error'
                            });
                        }else{
                            this.$message({
                                message: '成功',
                                type: 'success'
                            });
                            this.getServiceData()
                            this.resetForm('ruleForm')
                        }
                    },function(){
                        this.laoding_a = false
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
        dateFormat: function (date) {
            var reg = /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/
            if (reg.test(date)) {
                return date
            }
            var y = date.getFullYear()
            var m = date.getMonth() + 1
            m = m < 10 ? '0' + m : m
            var d = date.getDate()
            d = d < 10 ? ('0' + d) : d
            var h = date.getHours()
            h = h < 10 ? '0' + h : h
            var i = date.getMinutes()
            i = i < 10 ? '0' + i : i
            var s = date.getSeconds()
            s = s < 10 ? '0' + s : s
            return y + '-' + m + '-' + d;
        },
        resetForm(formName) {
            this.outerVisible = false
            this.$refs[formName].resetFields();
        },
        distribute(info){
            this.user_id = info.id
            this.outerVisible = true
        },
        getServiceData: function () {
            this.loadingStatus = true
            var query = '&page=' + this.page + '&page_size=' + this.page_count + '&name=' + this.name
            this.$http.get(Remote.examination + query).then((response) => {
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
        handleSizeChange_m(val) {
            this.page_count_m=30
            if(val>0){
                this.page_count_m=val
            }
            this.getmessage()
        },
        handleCurrentChange_m(val) {
            this.page_m=val==0?1:val
            this.getmessage()
        },
    }
}
</script>
<style scoped>
.jibie{
    width: 50px;
    line-height: 40px;
}
.spanstyle{
    color: #409EFF;
    cursor: pointer;
}
.el-icon-success{
    color: green;
}
.el-icon-error{
    color: red;
}
</style>
