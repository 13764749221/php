(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d216872"],{c39e:function(a,e,t){"use strict";t.r(e);var n=function(){var a=this,e=a.$createElement,t=a._self._c||e;return t("d2-container",{attrs:{type:"card"}},[t("template",{slot:"header"},[a._v("这个页面会被 keep-alive")]),t("h2",{staticClass:"d2-mt-0"},[a._v("编号："+a._s(a.id))]),t("p",{staticClass:"d2-mt-0"},[a._v("在下面的输入框输入任意字符后，切换到其它页面，再回到此页时输入框文字保留，证明被缓存")]),t("el-input",{attrs:{placeholder:"input here"},model:{value:a.data.value,callback:function(e){a.$set(a.data,"value",e)},expression:"data.value"}}),t("input",{directives:[{name:"model",rawName:"v-model",value:a.data.value,expression:"data.value"}],attrs:{placeholder:"input here"},domProps:{value:a.data.value},on:{input:function(e){e.target.composing||a.$set(a.data,"value",e.target.value)}}})],2)},r=[],s={name:"demo-playground-page-cache-params",props:{id:{type:String,required:!0}},data:function(){return{datas:[],data:{value:""}}},methods:{switchData:function(a){var e=this.datas[a];e||(e={value:""},this.datas[a]=e),this.data=e}},beforeRouteEnter:function(a,e,t){var n=a.params.id;t(n?function(a){return a.switchData(n)}:new Error("未指定ID"))},beforeRouteUpdate:function(a,e,t){var n=a.params.id;n?(this.switchData(n),t()):t(new Error("未指定ID"))}},o=s,u=t("2877"),i=function(a){a.options.__source="src/views/demo/playground/page-cache/params.vue"},d=i,l=Object(u["a"])(o,n,r,!1,null,null,null);"function"===typeof d&&d(l);e["default"]=l.exports}}]);
//# sourceMappingURL=chunk-2d216872.9d4460d7.js.map