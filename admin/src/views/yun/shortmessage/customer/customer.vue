<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div>
            <!-- <div style="margin: 20px 0px;overflow: auto;">
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
            </div> -->
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <el-table-column prop="name" label="姓名"></el-table-column>
                <el-table-column prop="url" label="二维码"></el-table-column>
                <el-table-column prop="created_at" label="创建时间"></el-table-column>
                <el-table-column prop="updated_at" label="更新时间"></el-table-column>
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
    submitForm(formName) {
        this.$refs[formName].validate((valid) => {
            if (valid) {
                this.laoding_b = true
                var query = '&name=' + this.ruleForm.name + '&phone=' + this.ruleForm.phone
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
