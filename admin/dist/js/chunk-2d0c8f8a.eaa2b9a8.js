(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["chunk-2d0c8f8a"],{"56c6":function(n,t,e){"use strict";e.r(t),t["default"]="<template>\n  <div>\n    <d2-crud\n      :columns=\"columns\"\n      :data=\"data\"\n      :options=\"options\"/>\n  </div>\n</template>\n\n<script>\nexport default {\n  data () {\n    return {\n      columns: [\n        {\n          title: 'ID',\n          key: 'id'\n        },\n        {\n          title: '姓名',\n          key: 'name'\n        },\n        {\n          title: '数值 1',\n          key: 'amount1'\n        },\n        {\n          title: '数值 2',\n          key: 'amount2'\n        },\n        {\n          title: '数值 3',\n          key: 'amount3'\n        }\n      ],\n      data: [\n        {\n          id: '12987122',\n          name: '王小虎',\n          amount1: '234',\n          amount2: '3.2',\n          amount3: 10\n        },\n        {\n          id: '12987123',\n          name: '王小虎',\n          amount1: '165',\n          amount2: '4.43',\n          amount3: 12\n        },\n        {\n          id: '12987124',\n          name: '王小虎',\n          amount1: '324',\n          amount2: '1.9',\n          amount3: 9\n        },\n        {\n          id: '12987125',\n          name: '王小虎',\n          amount1: '621',\n          amount2: '2.2',\n          amount3: 17\n        },\n        {\n          id: '12987126',\n          name: '王小虎',\n          amount1: '539',\n          amount2: '4.1',\n          amount3: 15\n        }\n      ],\n      options: {\n        showSummary: true,\n        summaryMethod (param) {\n          const { columns, data } = param\n          const sums = []\n          columns.forEach((column, index) => {\n            if (index === 0) {\n              sums[index] = '总价'\n              return\n            }\n            const values = data.map(item => Number(item[column.property]))\n            if (!values.every(value => isNaN(value))) {\n              sums[index] = values.reduce((prev, curr) => {\n                const value = Number(curr)\n                if (!isNaN(value)) {\n                  return prev + curr\n                } else {\n                  return prev\n                }\n              }, 0)\n              sums[index] += ' 元'\n            } else {\n              sums[index] = 'N/A'\n            }\n          })\n\n          return sums\n        }\n      }\n    }\n  }\n}\n<\/script>"}}]);
//# sourceMappingURL=chunk-2d0c8f8a.eaa2b9a8.js.map