<template>
    <d2-container>
        <div class="messagePromotionAdd mytable">
            <div style="margin: 20px 0px;overflow: auto;">
                <el-col class="jibie">格式：</el-col>
                <el-col style="width:200px;margin-right:10px;">
                    <el-select v-model="typevalue" placeholder="选择格式" clearable>
                        <el-option
                            v-for="item in typeinfo"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                        </el-option>
                    </el-select>
                </el-col>
                <el-col style="width: 100px;" >
                    <el-button type="primary" icon="el-icon-search" round :loading="loadingStatus" @click="getServiceData()">搜索</el-button>
                </el-col>
                <el-col style="width: 100px;margin-left:20px;">
                    <el-button type="primary" icon="el-icon-upload" round :loading="signup" @click="addtype()">上传资料</el-button>
                </el-col>
            </div>
            <el-table :data="orderData" border stripe style="margin-top:20px;" v-loading="loadingStatus">
                <el-table-column prop="class_name" label="课程类型"></el-table-column>
                <el-table-column prop="title" label="标题"></el-table-column>
                <el-table-column prop="need_duration" label="学习时长"></el-table-column>
                <el-table-column prop="get_score" label="学习积分"></el-table-column>
                <el-table-column prop="type" label="格式">
                    <template slot-scope="scope">
                        <span v-if="scope.row.type == 1">PDF</span>
                        <span v-else-if="scope.row.type == 2">视频</span>
                        <span v-else-if="scope.row.type == 3">音频</span>
                        <span v-else></span>
                    </template>
                </el-table-column>
                <el-table-column prop="type" label="考题">
                    <template slot-scope="scope">
                        <span @click="joinquestion(scope.row)" class="spanstyle">关联考题</span>
                    </template>
                </el-table-column>
                <el-table-column prop="status" label="操作">
                    <template slot-scope="scope">
                        <span @click="editinfo(scope.row)" class="spanstyle">编辑</span>
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
            <!-- 添加/修改资料 -->
            <el-dialog :title="dialog" :visible.sync="dialogFormVisible" @close="resetForm('ruleForm')" class="dialone">
                <el-form :model="ruleForm" :rules="rules" ref="ruleForm" label-width="200px" class="demo-ruleForm">
                    <el-form-item label="课程类型：" prop="class_id">
                        <el-select v-model="ruleForm.class_id" placeholder="选择类型" class="width250" clearable>
                            <el-option
                            v-for="item in culuminfo"
                            :key="item.value"
                            :label="item.label"
                            :value="item.value">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="标题：" prop="title">
                        <el-input v-model="ruleForm.title" class="width250" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="学习时长：" prop="need_duration">
                        <el-input v-model="ruleForm.need_duration" class="width250" clearable placeholder="以秒单位输入"></el-input>
                    </el-form-item>
                    <el-form-item label="学习积分：" prop="get_score">
                        <el-input v-model="ruleForm.get_score" class="width250" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="介绍：" prop="desc">
                        <el-input v-model="ruleForm.desc" type="textarea" class="width250" clearable></el-input>
                    </el-form-item>
                    <el-form-item label="上传资料：" prop="file_name">
                        <el-upload
                            class="upload-demo"
                            :action="uploadurl"
                            :on-preview="handlePreview"
                            :on-remove="handleRemove"
                            :before-remove="beforeRemove"
                            multiple
                            :limit="1"
                            :on-exceed="handleExceed"
                            :on-success="excelSuccess"
                            :file-list="fileList">
                            <el-button size="small" type="primary">点击上传</el-button>
                            <div slot="tip" class="el-upload__tip">只能上传pdf、mp3、mp4文件</div>
                        </el-upload>
                    </el-form-item>
                    <el-form-item label="设置实习生是否可学习：" prop="view_permission">
                        <el-radio-group v-model="ruleForm.view_permission">
                            <el-radio label="1">是</el-radio>
                            <el-radio label="2">否</el-radio>
                        </el-radio-group>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="submitForm('ruleForm')" :loading="laoding_b">确定</el-button>
                        <el-button @click="resetForm('ruleForm')">取消</el-button>
                    </el-form-item>
                </el-form>
            </el-dialog>
            <!-- 关联考题 -->
            <el-dialog title="关联考题" :visible.sync="messagetype">
                <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
                    <el-tab-pane label="搜索考题" name="select">
                        <div style="margin: 10px 0px;overflow: auto;">
                            <el-col class="jibie">类型：</el-col>
                            <el-col style="width:150px;margin-right:10px;">
                                <el-select v-model="labelvalue" placeholder="选择类型" clearable>
                                    <el-option
                                    v-for="item in labelinfo"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value">
                                    </el-option>
                                </el-select>
                            </el-col>
                            <el-col style="width: 150px;margin-right:20px;">
                                <el-input v-model="title" placeholder="请输标题" clearable></el-input>
                            </el-col>
                            <el-col style="width: 100px;" >
                                <el-button type="primary" icon="el-icon-search" round :loading="loading_m" @click="getmessage()">搜索</el-button>
                            </el-col>
                            <el-col style="width: 100px;" >
                                <el-button type="primary" round :loading="loading_m" @click="relation()">关联考题</el-button>
                            </el-col>
                        </div>
                        <el-table :data="messageData" border stripe style="margin-top:20px;" v-loading="loading_m" @selection-change="handleSelectionChange">>
                            <el-table-column type="selection" width="55"></el-table-column>
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
                    <el-tab-pane label="已关联考题" name="checked">
                        <el-table :data="messageData" border stripe style="margin-top:20px;" v-loading="loading_m">
                            <el-table-column prop="title" label="标题"></el-table-column>
                            <el-table-column prop="label" label="题目类型">
                                <template slot-scope="scope">
                                    <span v-if="scope.row.type == 1">基础</span>
                                    <span v-else-if="scope.row.type == 2">专业</span>
                                    <span v-else-if="scope.row.type == 4">营销</span>
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
                            <el-table-column label="操作">
                                <template slot-scope="scope">
                                    <span @click="deletegq(scope.row)" class="stop_del">删除</span>
                                </template>
                            </el-table-column>
                        </el-table>
                    </el-tab-pane>
                </el-tabs>
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
            typevalue:'',
            typeinfo:[{
                value: '1',
                label: 'PDF'
            },{
                value: '2',
                label: '视频'
            },{
                value: '3',
                label: '音频'
            }],
            culuminfo:[],
            dialog:'上传资料',
            dialogFormVisible:false,
            laoding_b:false,
            ruleForm: {
                class_id: '',
                title:'',
                need_duration:'',
                get_score:'',
                desc:'',
                file_name:'',
                view_permission:'',
            },
            rules: {
                class_id: [
                    { required: true, message: '请选择课程类型', trigger: 'change' }
                ],
                title: [
                    { required: true, message: '请输入标题', trigger: 'blur' }
                ],
                need_duration: [
                    { required: true, message: '请输入学习时长', trigger: 'blur' }
                ],
                get_score: [
                    { required: true, message: '请输入学习积分', trigger: 'blur' }
                ],
                view_permission: [
                    { required: true, message: '请选择是否可学习', trigger: 'change' }
                ],
            },
            uploadurl:Remote.uploadfile,
            fileList:[],
            mater_id:'',
            messagetype:false,
            page_m: 1,
            page_count_m: 10,
            total_count_m: 0,
            activeName: 'select',
            loading_m:false,
            m_id:'',
            messageData:[],
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
            title:'',
            multipleSelection:[],
        }
    },
    created () {
        this.getServiceData()
        this.getclasslist()
    },
    methods: {
        relation(){
            if(this.multipleSelection.length < 1){
                this.$message({
                    message: '请至少选择一个考题',
                    type: 'error'
                });
                return
            }
            var content = ''
            for (let index = 0; index < this.multipleSelection.length; index++) {
                content = content + ',' + this.multipleSelection[index].id
            }
            this.changektms(1,content)
        },
        deletegq(info){
            this.changektms(2,info.id)
        },
        changektms(type,content){
            var query = '&id=' + this.m_id + "&content=" + content + "&type=" + type
            this.$http.get(Remote.changektms + query).then((response) => {
                var data=response.data.data
                if (data.msg == '') {
                    this.$message({
                        type: 'success',
                        message: '操作成功!'
                    });
                    if(type == 1){
                        this.getmessage()
                    }else{
                        this.getchwckedq()
                    }
                }else{
                    this.$message({
                        type: 'error',
                        message: data.msg
                    });
                }
            },function(){
                this.loading_m = false
                this.$message({
                    message: '请求失败',
                    type: 'error'
                });
            }) 
        },
        handleSelectionChange(val) {
            this.multipleSelection = val;
        },
        editinfo(info){
            this.fileList = []
            this.dialog = '编辑资料'
            this.ruleForm.class_id = info.class_id
            this.ruleForm.title = info.title
            this.ruleForm.need_duration = info.need_duration
            this.ruleForm.get_score = info.get_score
            this.ruleForm.desc = info.desc
            this.ruleForm.file_name = info.file_name
            this.ruleForm.view_permission = info.view_permission
            this.mater_id = info.id
            var wenjian = {}
            wenjian.name = info.file_name
            wenjian.url = ''
            this.fileList[0] = wenjian
            this.dialogFormVisible = true
        },
        addtype(){
            this.dialog = '上传资料'
            this.ruleForm.class_id = ''
            this.ruleForm.title = ''
            this.ruleForm.need_duration = ''
            this.ruleForm.get_score = ''
            this.ruleForm.desc = ''
            this.ruleForm.file_name = ''
            this.ruleForm.view_permission = ''
            this.tiku_id = ''
            this.mater_id = ''
            this.fileList = []
            this.dialogFormVisible = true
        },
        joinquestion(info){
            this.m_id = info.id
            this.messagetype = true
            this.activeName = 'select'
            this.title = ''
            this.labelvalue = ''
            this.multipleSelection = []
            this.getmessage()
        },
        handleClick(tab, event) {
            this.page_m = 1
            this.title = ''
            this.labelvalue = ''
            this.multipleSelection = []
            if(this.activeName == 'select'){
                this.getmessage()
            }else{
                this.getchwckedq()
            }
        },
        getchwckedq(){
            this.loading_m = true
            this.$http.get(Remote.getquesbymid + '&id=' + this.m_id).then((response) => {
                var data=response.data.data
                if (response.data.msg == 0) {
                    this.messageData = data.res
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
        getmessage(){
            this.loading_m = true
            var query = '&page=' + this.page_m 
                        + '&page_size=' + this.page_count_m 
                        + '&label=' + this.labelvalue 
                        + '&title=' + this.title
            this.$http.get(Remote.questionslist + query).then((response) => {
                var data=response.data.data
                if (response.data.msg == 0) {
                    this.total_count_m = data.count
                    this.messageData = data.res
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
        submitForm(formName){
            this.$refs[formName].validate((valid) => {
                if (valid) {
                    if(this.ruleForm.file_name == ''){
                        this.$message({
                            type: 'error',
                            message: '未上传资料'
                        }); 
                        return
                    }
                    this.laoding_b = true
                    var query = "&class_id=" + this.ruleForm.class_id 
                                + "&title=" + this.ruleForm.title
                                + "&need_duration=" + this.ruleForm.need_duration
                                + "&get_score=" + this.ruleForm.get_score
                                + "&desc=" + this.ruleForm.desc
                                + "&file_name=" + this.ruleForm.file_name
                                + "&view_permission=" + this.ruleForm.view_permission
                                + "&mater_id=" + this.mater_id
                    this.$http.get(Remote.materiaadd + query).then((response) => {
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
        handleRemove(file, fileList) {
            console.log(file, fileList);
        },
        handlePreview(file) {
            console.log(file);
        },
        handleExceed(files, fileList) {
            if(fileList.length>0){
                this.$message.warning('最多只允许上传一个文件请先删除！');
            }
        },
        excelSuccess: function (response, file, fileList) {
            if(response.data.msg == ''){
                this.ruleForm.file_name = response.data.res
                this.$message({
                    type: 'success',
                    message: '上传成功!'
                });
            }else{
                this.$message({
                    type: 'error',
                    message: response.data.msg
                }); 
            }
        },
        beforeRemove(file, fileList) {
            return this.$confirm(`确定移除 ${ file.name }？`);
        },
        getclasslist(){
            var query = '&page=1&page_size=1000'
            this.$http.get(Remote.curriculumlist + query).then((response) => {
                var data=response.data.data
                var newdata = []
                if (response.data.msg == 0) {
                    for (let index = 0; index < data.res.length; index++) {
                        var promat = {}
                        promat.value = data.res[index].id
                        promat.label = data.res[index].name
                        newdata[newdata.length] = promat
                    }
                    this.culuminfo = newdata
                }
            },function(){
                this.$message({
                message: '请求失败',
                type: 'error'
                });
            })
        },
        resetForm(formName) {
            this.dialogFormVisible = false
            this.$refs[formName].resetFields();
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
                this.$http.get(Remote.materiaedit + query).then((response) => {
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
            }).catch(() => {
                this.$message({
                    type: 'info',
                    message: '已取消操作'
                });          
            });
        },  
        getServiceData: function () {
            this.loadingStatus = true
            var query = '&page=' + this.page + '&page_size=' + this.page_count + '&type=' + this.typevalue
            this.$http.get(Remote.materialist + query).then((response) => {
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
.stop{
    color: red;
    cursor: pointer;
    margin-left: 20px;
}
.stop_del{
    color: red;
    cursor: pointer;
}
.start{
    color:green;
    cursor: pointer;
}
.width250{
    width: 218px;
}
</style>
