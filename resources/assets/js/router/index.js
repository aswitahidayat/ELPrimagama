import Vue from 'vue';
import Router from 'vue-router';

// Containers
import Full from '../containers/Full';

// Views
import Dashboard from '../views/Dashboard';

// Views - Icons
import FontAwesome from '../views/icons/FontAwesome';
import SimpleLineIcons from '../views/icons/SimpleLineIcons';

// Views - Pages
import Page404 from '../views/pages/Page404';
import Page500 from '../views/pages/Page500';
import Login from '../views/pages/Login';
import Register from '../views/pages/Register';

// Views - Modules
import TipsNTrik from '../views/modules/master/TipsNTrik';
import Kalpen from '../views/modules/master/Kalpen';
import Banner from '../views/modules/master/Banner';
import Smartebook from '../views/modules/master/Smartebook';
import Smartexercise from '../views/modules/master/Smartexercise';
import Pikse from '../views/modules/master/Pikse';
import Rumus from '../views/modules/master/Rumus';
import Bakmi from '../views/modules/master/Bakmi';
import Ketela from '../views/modules/master/Ketela';
import Sekolah from '../views/modules/master/Sekolah';
import Pesan from '../views/modules/master/Pesan';
import SemuaPesan from '../views/modules/master/SemuaPesan';

Vue.use(Router);

export default new Router({
  mode: 'history', // hash or hash = Demo is living in GitHub.io, so required!
  linkActiveClass: 'open active',
  scrollBehavior,
  routes: [
    {
      path: '/',
      redirect: '/dashboard',
      name: 'Home',
      component: Full,
      children: [
        {
          path: 'dashboard',
          name: 'Dashboard',
          component: Dashboard
        },
        {
          path: 'master',
          redirect: '/master/mititi',
          name: 'Master',
          auth: true,
          component: {
            render (c) { return c('router-view'); }
          },
          children: [
            {
              path: 'mititi',
              name: 'Tips N Trik',
              component: TipsNTrik
            },
            {
              path: 'banner',
              name: 'Banner',
              component: Banner
            },
            {
              path: 'kalpen',
              name: 'Kalpen',
              component: Kalpen
            },
            {
              path: 'smartebook',
              name: 'Smart E-Book',
              component: Smartebook
            },
            {
              path: 'smartexercise',
              name: 'Smart E-Xercise',
              component: Smartexercise,
            },
            {
              path: 'pikse',
              name: 'Pikse',
              component: Pikse,
            },
            {
              path: 'rumus',
              name: 'Rumus',
              component: Rumus,
            },
            {
              path: 'bakmi',
              name: 'Pembahasan Materi',
              component: Bakmi,
            },
            {
              path: 'ketela',
              name: 'Video Pembelajaran',
              component: Ketela,
            },
            {
              path: 'sekolah',
              name: 'Asal Sokolah',
              component: Sekolah,
            },
            {
              path: 'pesan',
              name: 'Pesan',
              component: Pesan,
            },
            {
              path: 'semuapesan',
              name: 'Semua Pesan',
              component: SemuaPesan,
            },
          ]
        }
      ]
    },
    {
      path: '/pages',
      redirect: '/pages/404',
      name: 'Pages',
      component: {
        render (c) { return c('router-view'); }
      },
      children: [
        {
          path: '404',
          name: 'Page404',
          component: Page404
        },
        {
          path: '500',
          name: 'Page500',
          component: Page500
        },
        {
          path: 'login',
          name: 'Login',
          component: Login
        },
        {
          path: 'register',
          name: 'Register',
          component: Register
        }
      ]
    },
    {
      path: '*',
      name: '404',
      component: Page404,
    },
  ]
});

function scrollBehavior (to, from, savedPosition) {
  if (savedPosition) {
    return savedPosition
  }

  const position = {}

  if (to.hash) {
    position.selector = to.hash
  }

  if (to.matched.some(m => m.meta.scrollToTop)) {
    position.x = 0
    position.y = 0
  }

  return position
}