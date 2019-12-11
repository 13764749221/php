export default {
  path: '/yun/shortmessage',
  title: '功能列表',
  icon: 'globe',
  children: (pre => [
    { path: `${pre}customer`, title: '二维码管理', icon: 'home' }
  ])('/yun/shortmessage/')
}
