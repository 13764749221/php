import layoutHeaderAside from '@/layout/header-aside'

// 由于懒加载页面太多的话会造成webpack热更新太慢，所以开发环境不使用懒加载，只有生产环境使用懒加载
const _import = require('@/libs/util.import.' + process.env.NODE_ENV)

const meta = { auth: true }

export default {
  path: '/yun/shortmessage',
  name: 'yun-shortmessage',
  meta,
  redirect: { name: 'yun-shortmessage-index' },
  component: layoutHeaderAside,
  children: (pre => [
    { path: 'customer', name: `${pre}customer`, component: _import('yun/shortmessage/customer/customer.vue'), meta: { ...meta, title: '客服管理' } },
    { path: 'examination', name: `${pre}examination`, component: _import('yun/shortmessage/examination/examination.vue'), meta: { ...meta, title: '考试管理' } },
    { path: 'questions', name: `${pre}questions`, component: _import('yun/shortmessage/questions/questions.vue'), meta: { ...meta, title: '考题管理' } },
    { path: 'material', name: `${pre}material`, component: _import('yun/shortmessage/material/material.vue'), meta: { ...meta, title: '资料管理' } },
    { path: 'curriculum', name: `${pre}curriculum`, component: _import('yun/shortmessage/curriculum/curriculum.vue'), meta: { ...meta, title: '课程类型' } }
  ])('yun-shortmessage-')
}
