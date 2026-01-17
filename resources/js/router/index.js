import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Lazy load components
const Dashboard = () => import('../pages/Dashboard.vue');
const Login = () => import('../pages/Login.vue');
const Attendance = () => import('../pages/Attendance.vue');
const AttendanceHistory = () => import('../pages/AttendanceHistory.vue');
const Reports = () => import('../pages/Reports.vue');
const Profile = () => import('../pages/Profile.vue');
const AdminDashboard = () => import('../pages/admin/Dashboard.vue');
const AdminUsers = () => import('../pages/admin/Users.vue');
const AdminRoles = () => import('../pages/admin/Roles.vue');
const AdminShifts = () => import('../pages/admin/Shifts.vue');
const AdminReports = () => import('../pages/admin/Reports.vue');

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresAuth: false, layout: 'auth' }
  },
  {
    path: '/',
    name: 'Dashboard',
    component: Dashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/attendance',
    name: 'Attendance',
    component: Attendance,
    meta: { requiresAuth: true }
  },
  {
    path: '/attendance/history',
    name: 'AttendanceHistory',
    component: AttendanceHistory,
    meta: { requiresAuth: true }
  },
  {
    path: '/reports',
    name: 'Reports',
    component: Reports,
    meta: { requiresAuth: true, roles: ['admin', 'manager', 'supervisor'] }
  },
  {
    path: '/profile',
    name: 'Profile',
    component: Profile,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true, roles: ['admin', 'superadmin'] }
  },
  {
    path: '/admin/users',
    name: 'AdminUsers',
    component: AdminUsers,
    meta: { requiresAuth: true, roles: ['admin', 'superadmin'] }
  },
  {
    path: '/admin/roles',
    name: 'AdminRoles',
    component: AdminRoles,
    meta: { requiresAuth: true, roles: ['admin', 'superadmin'] }
  },
  {
    path: '/admin/shifts',
    name: 'AdminShifts',
    component: AdminShifts,
    meta: { requiresAuth: true, roles: ['admin', 'superadmin'] }
  },
  {
    path: '/admin/reports',
    name: 'AdminReports',
    component: AdminReports,
    meta: { requiresAuth: true, roles: ['admin', 'superadmin'] }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Navigation guard
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const requiresAuth = to.meta.requiresAuth !== false;
  const requiredRoles = to.meta.roles;

  if (requiresAuth && !authStore.isAuthenticated) {
    next('/login');
  } else if (requiredRoles && !authStore.hasAnyRole(requiredRoles)) {
    next('/');
  } else {
    next();
  }
});

export default router;
