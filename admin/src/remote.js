/*eslint-disable */
// api接口地址
var baseUrl = window.location.protocol + '//' + document.domain + '/api/public'
let Remote = {}
// 测试页面
Remote.getCustomerlist = baseUrl + '/?s=MedicalBeauty.Customerlist'
export { Remote }
