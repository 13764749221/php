(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-5a8b0a0e","chunk-2d0b39f2"],{"28c9":function(t,e,a){"use strict";a.r(e);var n=a("96eb"),l=a.n(n),o=l.a.mock({"data|3-6":[{"id|+1":1,name:"@CNAME",creatDate:"@DATE",address:"@CITY",zip:"@ZIP"}]});e["default"]={data:o.data,columns:[{label:"ID",prop:"id"},{label:"名称",prop:"name"},{label:"创建日期",prop:"creatDate"},{label:"地址",prop:"address"},{label:"邮编",prop:"zip"}]}},"7c99":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("d2-container",{attrs:{type:"card"}},[a("template",{slot:"header"},[t._v("导出表格")]),a("div",{staticClass:"d2-mb"},[a("el-button",{attrs:{type:"primary"},on:{click:t.exportCsv}},[a("d2-icon",{attrs:{name:"download"}}),t._v("\n      导出 CSV\n    ")],1),a("el-button",{attrs:{type:"primary"},on:{click:t.exportExcel}},[a("d2-icon",{attrs:{name:"download"}}),t._v("\n      导出 Excel\n    ")],1)],1),a("el-table",t._b({staticStyle:{width:"100%"}},"el-table",t.table,!1),t._l(t.table.columns,function(t,e){return a("el-table-column",{key:e,attrs:{prop:t.prop,label:t.label}})}),1)],2)},l=[],o=a("28c9"),s={data:function(){return{table:{columns:o["default"].columns,data:o["default"].data,size:"mini",stripe:!0,border:!0}}},methods:{exportCsv:function(){var t=this;arguments.length>0&&void 0!==arguments[0]&&arguments[0];this.$export.csv({columns:this.table.columns,data:this.table.data}).then(function(){t.$message("导出CSV成功")})},exportExcel:function(){var t=this;this.$export.excel({columns:this.table.columns,data:this.table.data,header:"导出 Excel",merges:["A1","E1"]}).then(function(){t.$message("导出表格成功")})}}},r=s,c=a("2877"),i=function(t){t.options.__source="src/views/demo/plugins/export/table.vue"},d=i,u=Object(c["a"])(r,n,l,!1,null,null,null);"function"===typeof d&&d(u);e["default"]=u.exports}}]);
//# sourceMappingURL=chunk-5a8b0a0e.5e2bc503.js.map