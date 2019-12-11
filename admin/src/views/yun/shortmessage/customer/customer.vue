<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div>
            <div style="margin: 20px 0px;overflow: auto;">
                <el-col class="jibie">级别：</el-col>
                <el-col style="width:200px;margin-right:10px;">
                    <el-select v-model="signvalue" placeholder="选择级别" clearable>
                        <el-option
                        v-for="item in signinfo"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col style="width: 180px;margin-right:20px;">
                    <el-input v-model="name" placeholder="搜索名字" class="orderkeywordcl" clearable></el-input>
                </el-col>
                <el-col style="width: 100px;" >
                    <el-button type="primary" icon="el-icon-search" round :loading="loadingStatus" @click="getServiceData()">搜索</el-button>
                </el-col>
                <el-col style="width: 100px;margin-left:20px;" >
                    <el-button type="primary" icon="el-icon-circle-plus-outline" round @click="adddata()">添加客服</el-button>
                </el-col>
            </div>
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <el-table-column prop="realname" label="姓名"></el-table-column>
                <el-table-column prop="phone" label="手机号"></el-table-column>
                <el-table-column prop="achievement" label="转正成绩"></el-table-column>
                <el-table-column prop="study_score" label="学习积分"></el-table-column>
                <el-table-column prop="level" label="级别">
                    <template slot-scope="scope">
                        <span v-if="scope.row.level == 1">转正</span>
                        <span v-else>试用</span>
                    </template>
                </el-table-column>
                <el-table-column prop="ruzhi_date" label="入职时间" width="160px"></el-table-column>
                <el-table-column prop="count" label="转正操作" :render-header="addTCTip" label-class-name="转正条件：转正成绩大于60分，入职满3月，学习积分大于30分。">
                    <template slot-scope="scope">
                        <span v-if="scope.row.level == 0" @click="changetrue(scope.row)" class="spanstyle">转正</span>
                        <span v-else>已转正</span>
                    </template>
                </el-table-column>
                <el-table-column prop="status" label="操作">
                    <template slot-scope="scope">
                        <span v-if="scope.row.status == 1" @click="changestatus(scope.row)" class="stop">禁用</span>
                        <span v-else @click="changestatus(scope.row)" class="start">启用</span>
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
        </div>
        <!-- 满足转正条件 -->
        <el-dialog :visible.sync="showchangetrue" width="30%" center style="margin-top: 20vh;">
            <div class="diacenter"> 确认要转正吗？</div>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="changelevel()" :loading="laoding_a">确 定</el-button>
            </span>
        </el-dialog>
        <!-- 不满足转正条件 -->
        <el-dialog :visible.sync="showchangefalse" width="30%" center style="margin-top: 15vh;">
            <div class="diacenter"> 不满足转正条件</div>
            <div class="faslecenter">
                <div>
                    <span class="falsespan">转正成绩大于60分</span>
                    <i class="el-icon-success" v-if="condition_a"></i>
                    <i class="el-icon-error" v-else></i>
                </div>
                <div>
                    <span class="falsespan">入职满3月</span>
                    <i class="el-icon-success" v-if="condition_b"></i>
                    <i class="el-icon-error" v-else></i>
                </div>
                <div>
                    <span class="falsespan">学习积分大于30分</span>
                    <i class="el-icon-success" v-if="condition_c"></i>
                    <i class="el-icon-error" v-else></i>
                </div>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="closedialog()">确 定</el-button>
            </span>
        </el-dialog>
        <!-- 添加客服 -->
        <el-dialog title="添加客服" :visible.sync="outerVisible" @close="resetForm('ruleForm')">
            <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
                <el-form-item label="姓名：" prop="name">
                    <el-input v-model="ruleForm.name" style="width: 218px;"></el-input>
                </el-form-item>
                <el-form-item label="手机号码：" prop="phone">
                    <el-input v-model="ruleForm.phone" style="width: 218px;"></el-input>
                </el-form-item>
                <el-form-item label="入职日期：" prop="date">
                    <el-date-picker style="width: 218px;" type="date" placeholder="选择入职日期" v-model="ruleForm.date"></el-date-picker>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('ruleForm')" :loading="laoding_b">添加</el-button>
                    <el-button @click="resetForm('ruleForm')">取消</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
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
        signup:false,
        page: 1,
        page_count: 10,
        total_count: 0,
        orderData:[],
        signvalue:'',
        signinfo:[{
            value: '0',
            label: '试用'
        },{
            value: '1',
            label: '转正'
        }],
        showchangetrue:false,
        user_id:'',
        laoding_a:false,
        showchangefalse:false,
        condition_a:false,
        condition_b:false,
        condition_c:false,
        outerVisible:false,
        ruleForm: {
            name: '',
            phone:'',
            date:'',
        },
        rules: {
            name: [
                { required: true, message: '请输入姓名', trigger: 'blur' }
            ],
            phone: [
                { required: true, message: '请输入手机号码', trigger: 'blur' }
            ],
            date: [
                { type: 'date', required: true, message: '请选择入职日期', trigger: 'change' }
            ],
        },
        laoding_b:false,
    }
  },
  created () {
    this.getServiceData()
  },
  methods: {
    adddata(){
        this.outerVisible = true
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
    submitForm(formName) {
        this.$refs[formName].validate((valid) => {
            if (valid) {
                this.laoding_b = true
                var query = '&name=' + this.ruleForm.name + '&phone=' + this.ruleForm.phone + '&date=' + this.dateFormat(this.ruleForm.date)
                this.$http.get(Remote.addCustomer + query).then((response) => {
                    var data=response.data.data
                    this.laoding_b = false
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
                    this.laoding_b = false
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
        this.outerVisible = false
        this.$refs[formName].resetFields();
    },
    closedialog(){
        this.showchangefalse = false
    },
    changetrue(info){
        this.user_id = info.id
        // 判断是否满足转正条件
        var date1 = new Date(info.ruzhi_date);
        var time1 = Date.parse(date1);
        var check1 = time1/1000+90*86400

        var date2 = new Date();
        var time2 = Date.parse(date2);
        var check2 = time2/1000

        if(info.achievement < 60 || info.study_score < 30 || check1 > check2){
            if(info.achievement < 60){
                this.condition_a = false
            }else{
                this.condition_a = true
            }
            if(info.study_score < 30){
                this.condition_c = false
            }else{
                this.condition_c = true
            }
            if(check1 > check2){
                this.condition_b = false
            }else{
                this.condition_b = true
            }
            this.showchangefalse = true
            return
        }
        this.showchangetrue = true
    },
    changelevel(){
        var query = "&level=1" + "&id=" + this.user_id
        this.laoding_a = true
        this.useapistatus(query)
    },
    useapistatus(query){
        this.$http.get(Remote.changestatus + query).then((response) => {
            var data=response.data.data
            this.laoding_a = false
            if (data.msg == '') {
                this.$message({
                    type: 'success',
                    message: '操作成功!'
                });
                this.showchangetrue = false
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
    }, 
    addTCTip(h,{column}){
      if(column.labelClassName){
        var tbaa=h('span', {}, [
          h('span', {}, column.label),
          h('el-popover', { props: { placement: 'top-start', width: '200', trigger: 'hover', content: column.labelClassName }}, [
            h('el-button', { slot: 'reference', props: { type: 'text', icon: 'el-icon-question'}}, '')
          ])
        ])
        return tbaa
      }
    },
    changestatus(info){
        var title = "启用"
        var status = 1
        if(info.status == 1){
            var title = "禁用"
            var status = 0
        }
        this.$confirm('此操作将' + title + '该用户权限, 是否继续?', '提示', {
            confirmButtonText: '确定',
            cancelButtonText: '取消',
            type: 'warning'
        }).then(() => {
            var query = "&status=" + status + "&id=" + info.id
            this.useapistatus(query)
        }).catch(() => {
            this.$message({
                type: 'info',
                message: '已取消操作'
            });          
        });
    },  
    getServiceData: function () {
        this.loadingStatus = true
        var query = '&page=' + this.page + '&page_size=' + this.page_count + '&name=' + this.name + '&signvalue=' + this.signvalue
        this.$http.get(Remote.getCustomerlist + query).then((response) => {
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
.jibie{
    width: 50px;
    line-height: 40px;
}
.spanstyle{
    color: #409EFF;
    cursor: pointer;
}
.stop{
    color: red;
    cursor: pointer;
}
.start{
    color:green;
    cursor: pointer;
}
.diacenter{
    text-align: center;
    font-size: 25px;
    color: red;
}
.faslecenter{
    text-align: center;
    margin-top: 20px;
    font-size: 16px;
    font-weight: 800;
}
.falsespan{
    margin-right: 20px;
}
.el-icon-success{
    color: green;
}
.el-icon-error{
    color: red;
}
</style>
