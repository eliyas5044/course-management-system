function page (path) {
  return () => import(/* webpackChunkName: '' */ `~/pages/${path}`).then(m => m.default || m)
}

export default [
  { path: '/', name: 'home', component: page('public/index.vue') },
  { path: '/courses/:slug', name: 'course.single', component: page('public/course-single.vue') },
  { path: '/lessons/:slug', name: 'lesson.single', component: page('public/lesson-single.vue') },

  { path: '/login', name: 'login', component: page('auth/login.vue') },
  { path: '/register', name: 'register', component: page('auth/register.vue') },
  { path: '/password/reset', name: 'password.request', component: page('auth/password/email.vue') },
  { path: '/password/reset/:token', name: 'password.reset', component: page('auth/password/reset.vue') },
  { path: '/email/verify/:id', name: 'verification.verify', component: page('auth/verification/verify.vue') },
  { path: '/email/resend', name: 'verification.resend', component: page('auth/verification/resend.vue') },

  { path: '/settings',
    component: page('settings/index.vue'),
    children: [
      { path: '', redirect: { name: 'settings.profile' } },
      { path: 'profile', name: 'settings.profile', component: page('settings/profile.vue') },
      { path: 'password', name: 'settings.password', component: page('settings/password.vue') }
    ] },

  {
    path: '/dashboard',
    component: page('index.vue'),
    children: [
      { path: '', name: 'dashboard', component: page('dashboard.vue') },
      { path: 'courses', name: 'dashboard.courses', component: page('courses/index.vue') },
      { path: 'courses/entry', name: 'dashboard.courses.entry', component: page('courses/entry.vue') },
      { path: 'courses/:id', name: 'dashboard.courses.single', component: page('courses/single.vue') },
      { path: 'courses/:id/lessons/entry', name: 'dashboard.courses.lessons.entry', component: page('lessons/entry.vue') },
      { path: 'lessons', name: 'dashboard.lessons', component: page('lessons/index.vue') },
      { path: 'lessons/entry', name: 'dashboard.lessons.entry', component: page('lessons/entry.vue') },
      { path: 'lessons/:id', name: 'dashboard.lessons.single', component: page('lessons/single.vue') }
    ]
  },

  { path: '*', component: page('errors/404.vue') }
]
