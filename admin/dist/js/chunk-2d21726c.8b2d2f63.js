(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d21726c"],{c603:function(n,e,a){"use strict";a.r(e),e["default"]="<template>\n  <div>\n    <d2-crud\n      :columns=\"columns\"\n      :data=\"data\"\n      @cell-data-change=\"handleCellDataChange\"/>\n  </div>\n</template>\n\n<script>\nexport default {\n  data () {\n    return {\n      columns: [\n        {\n          title: '日期',\n          key: 'date',\n          component: {\n            name: 'el-date-picker',\n            size: 'small'\n          }\n        },\n        {\n          title: '姓名',\n          key: 'name',\n          component: {\n            name: 'el-select',\n            options: [\n              {\n                value: '王小虎',\n                label: '王小虎'\n              },\n              {\n                value: '王中虎',\n                label: '王中虎'\n              },\n              {\n                value: '王老虎',\n                label: '王老虎'\n              }\n            ],\n            size: 'small'\n          }\n        },\n        {\n          title: '地址',\n          key: 'address',\n          component: {\n            name: 'el-input',\n            size: 'small'\n          }\n        }\n      ],\n      data: [\n        {\n          date: '2016-05-02',\n          name: '王小虎',\n          address: '上海市普陀区金沙江路 1518 弄'\n        },\n        {\n          date: '2016-05-04',\n          name: '王小虎',\n          address: '上海市普陀区金沙江路 1517 弄'\n        },\n        {\n          date: '2016-05-01',\n          name: '王小虎',\n          address: '上海市普陀区金沙江路 1519 弄'\n        },\n        {\n          date: '2016-05-03',\n          name: '王小虎',\n          address: '上海市普陀区金沙江路 1516 弄'\n        }\n      ]\n    }\n  },\n  methods: {\n    handleCellDataChange ({ rowIndex, key, value, row }) {\n      console.log(rowIndex)\n      console.log(key)\n      console.log(value)\n      console.log(row)\n    }\n  }\n}\n<\/script>"}}]);
//# sourceMappingURL=chunk-2d21726c.8b2d2f63.js.map