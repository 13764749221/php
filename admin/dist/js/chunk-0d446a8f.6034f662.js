(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-0d446a8f"],{"8b75":function(t,n,e){"use strict";e.r(n);var l=function(){var t=this,n=t.$createElement,e=t._self._c||n;return e("d2-container",[e("template",{slot:"header"},[t._v("滚动定位")]),e("el-row",{attrs:{gutter:20}},[e("el-col",{attrs:{span:8}},[e("div",{ref:"wrapper",staticClass:"demo-bs-wrapper"},[e("div",t._l(100,function(n){return e("div",{key:n,staticClass:"demo-bs-item",attrs:{id:"demo-bs-item-"+n}},[t._v("n : "+t._s(n))])}),0)])]),e("el-col",{attrs:{span:16}},[e("div",{staticClass:"d2-mb"},[e("p",[t._v("滚动时间 ms")]),e("el-slider",{attrs:{min:100,max:3e3},model:{value:t.time,callback:function(n){t.time=n},expression:"time"}})],1),e("div",{staticClass:"d2-mb"},[e("el-button-group",[e("el-button",{on:{click:function(n){return t.handleScrollTo(100)}}},[t._v("滚动到100像素位置")]),e("el-button",{on:{click:function(n){return t.handleScrollTo(300)}}},[t._v("滚动到300像素位置")])],1)],1),e("div",{staticClass:"d2-mb"},[e("el-button-group",[e("el-button",{on:{click:function(n){return t.handleScrollBy(50)}}},[t._v("向下滚动50像素")]),e("el-button",{on:{click:function(n){return t.handleScrollBy(-50)}}},[t._v("向上滚动50像素")])],1)],1),e("div",{staticClass:"d2-mb"},[e("el-button-group",[e("el-button",{on:{click:function(n){return t.handleScrollToElement(4)}}},[t._v("滚动到第四个")]),e("el-button",{on:{click:function(n){return t.handleScrollToElement(14)}}},[t._v("滚动到第十四个")]),e("el-button",{on:{click:function(n){return t.handleScrollToElement(24)}}},[t._v("滚动到第二十四个")])],1)],1)])],1),e("template",{slot:"footer"},[e("d2-link-btn",{attrs:{title:"文档",link:"http://ustbhuangyi.github.io/better-scroll/doc/zh-hans/"}})],1)],2)},o=[],i=e("1fba"),c={data:function(){return{time:300,BS:null}},mounted:function(){this.scrollInit()},beforeDestroy:function(){this.scrollDestroy()},methods:{handleScrollTo:function(t){this.BS.scrollTo(0,-t,this.time)},handleScrollBy:function(t){this.BS.scrollBy(0,-t,this.time)},handleScrollToElement:function(t){this.BS.scrollToElement("#demo-bs-item-".concat(t),this.time)},scrollInit:function(){this.BS=new i["a"](this.$refs.wrapper,{mouseWheel:!0,click:!0,scrollbar:{fade:!0,interactive:!1}})},scrollDestroy:function(){this.BS&&this.BS.destroy()}}},r=c,s=(e("dcbf"),e("2877")),u=function(t){t.options.__source="src/views/demo/plugins/better-scroll/to.vue"},a=u,d=Object(s["a"])(r,l,o,!1,null,"7194bb57",null);"function"===typeof a&&a(d);n["default"]=d.exports},dcbf:function(t,n,e){"use strict";var l=e("fac9"),o=e.n(l);o.a},fac9:function(t,n,e){}}]);
//# sourceMappingURL=chunk-0d446a8f.6034f662.js.map