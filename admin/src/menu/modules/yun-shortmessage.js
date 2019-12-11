export default {
  path: '/yun/shortmessage',
  title: '医美助理',
  icon: 'globe',
  children: (pre => [
    { path: `${pre}customer`, title: '客服管理', icon: 'home' },
    { path: `${pre}examination`, title: '考试管理', icon: 'home' },
    { path: `${pre}questions`, title: '考题管理', icon: 'home' },
    { path: `${pre}material`, title: '资料管理', icon: 'home' },
    { path: `${pre}curriculum`, title: '课程类型', icon: 'home' }
  ])('/yun/shortmessage/')
}
