/*eslint-disable */
// api接口地址
var baseUrl = window.location.protocol + '//' + document.domain + '/api/public'
let Remote = {}
// 获取客服列表
Remote.getCustomerlist = baseUrl + '/?s=MedicalBeauty.Customerlist'
// 修改客服状态
Remote.changestatus = baseUrl + '/?s=MedicalBeauty.changestatus'
// 添加客服
Remote.addCustomer = baseUrl + '/?s=MedicalBeauty.addCustomer'
// 获取考试列表
Remote.examination = baseUrl + '/?s=MedicalBeauty.examination'
// 派发考试
Remote.addexamination = baseUrl + '/?s=MedicalBeauty.addexamination'
// 学习内容详情列表
Remote.studymessage = baseUrl + '/?s=MedicalBeauty.studymessage'
// 考试内容详情列表
Remote.exammessage = baseUrl + '/?s=MedicalBeauty.exammessage'
// 考试答题详情
Remote.answerinfo = baseUrl + '/?s=MedicalBeauty.answerinfo'
// 考题列表
Remote.questionslist = baseUrl + '/?s=MedicalBeauty.questionslist'
// 删除考题数据
Remote.questionsdel = baseUrl + '/?s=MedicalBeauty.questionsdel'
// 添加考题数据
Remote.questionsadd = baseUrl + '/?s=MedicalBeauty.questionsadd'
// 课程类型列表
Remote.curriculumlist = baseUrl + '/?s=MedicalBeauty.curriculumlist'
// 课程添加
Remote.curriculumadd = baseUrl + '/?s=MedicalBeauty.curriculumadd'
// 删除课程类型
Remote.curriculumdel = baseUrl + '/?s=MedicalBeauty.curriculumdel'
// 获取资料列表
Remote.materialist = baseUrl + '/?s=MedicalBeauty.materialist'
// 修改资料信息
Remote.materiaedit = baseUrl + '/?s=MedicalBeauty.materiaedit'
// 文件上传接口
Remote.uploadfile = baseUrl + '/?s=MedicalBeauty.uploadfile'
// 添加资料信息
Remote.materiaadd = baseUrl + '/?s=MedicalBeauty.materiaadd'
// 根据资料id获取考题信息
Remote.getquesbymid = baseUrl + '/?s=MedicalBeauty.getquesbymid'
// 修改资料绑定考题信息
Remote.changektms = baseUrl + '/?s=MedicalBeauty.changektms'
export { Remote }
