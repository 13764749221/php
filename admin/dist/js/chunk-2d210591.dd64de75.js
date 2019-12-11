(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d210591"],{b805:function(n,e,a){"use strict";a.r(e),e["default"]="<template>\n  <div>\n    <d2-crud\n      ref=\"d2Crud\"\n      :columns=\"columns\"\n      :data=\"data\"\n      add-title=\"我的新增\"\n      :add-template=\"addTemplate\"\n      :form-options=\"formOptions\"\n      @dialog-open=\"handleDialogOpen\"\n      @row-add=\"handleRowAdd\"\n      @dialog-cancel=\"handleDialogCancel\">\n      <el-button slot=\"header\" style=\"margin-bottom: 5px\" @click=\"addRow\">新增</el-button>\n      <el-button slot=\"header\" style=\"margin-bottom: 5px\" @click=\"addRowWithNewTemplate\">使用自定义模板新增</el-button>\n    </d2-crud>\n  </div>\n</template>\n\n<script>\nexport default {\n  data () {\n    return {\n      columns: [\n        {\n          title: '日期',\n          key: 'date'\n        },\n        {\n          title: '姓名',\n          key: 'name'\n        },\n        {\n          title: '地址',\n          key: 'address'\n        }\n      ],\n      data: [\n          {\n            date: '2016-05-02',\n            name: '王小虎',\n            address: '上海市普陀区金沙江路 1518 弄'\n          },\n          {\n            date: '2016-05-04',\n            name: '王小虎',\n            address: '上海市普陀区金沙江路 1517 弄'\n          },\n          {\n            date: '2016-05-01',\n            name: '王小虎',\n            address: '上海市普陀区金沙江路 1519 弄'\n          },\n          {\n            date: '2016-05-03',\n            name: '王小虎',\n            address: '上海市普陀区金沙江路 1516 弄'\n          }\n      ],\n      addTemplate: {\n        date: {\n          title: '日期',\n          value: '2016-05-05'\n        },\n        name: {\n          title: '姓名',\n          value: '王小虎'\n        },\n        address: {\n          title: '地址',\n          value: '上海市普陀区金沙江路 1520 弄'\n        }\n      },\n      formOptions: {\n        labelWidth: '80px',\n        labelPosition: 'left',\n        saveLoading: false\n      }\n    }\n  },\n  methods: {\n    handleDialogOpen ({ mode }) {\n      this.$message({\n        message: '打开模态框，模式为：' + mode,\n        type: 'success'\n      })\n    },\n    // 普通的新增\n    addRow () {\n      this.$refs.d2Crud.showDialog({\n        mode: 'add'\n      })\n    },\n    // 传入自定义模板的新增\n    addRowWithNewTemplate () {\n      this.$refs.d2Crud.showDialog({\n        mode: 'add',\n        template: {\n          name: {\n            title: '姓名',\n            value: ''\n          },\n          value1: {\n            title: '新属性1',\n            value: ''\n          },\n          value2: {\n            title: '新属性2',\n            value: ''\n          }\n        }\n      })\n    },\n    handleRowAdd (row, done) {\n      this.formOptions.saveLoading = true\n      setTimeout(() => {\n        console.log(row)\n        this.$message({\n          message: '保存成功',\n          type: 'success'\n        });\n\n        // done可以传入一个对象来修改提交的某个字段\n        done({\n          address: '我是通过done事件传入的数据！'\n        })\n        this.formOptions.saveLoading = false\n      }, 300)\n    },\n    handleDialogCancel (done) {\n      this.$message({\n        message: '取消保存',\n        type: 'warning'\n      });\n      done()\n    }\n  }\n}\n<\/script>"}}]);
//# sourceMappingURL=chunk-2d210591.dd64de75.js.map