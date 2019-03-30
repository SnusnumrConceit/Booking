import Appointments from './components/admin/appointments/appointments';
import AppointmentForm from './components/admin/appointments/appointment_form';

import Employees from './components/admin/employees/employees';
import EmployeeForm from './components/admin/employees/employee_form';

import Orders from './components/admin/orders/orders';
import OrderForm from './components/admin/orders/order_form';

import Photos from './components/admin/photos/photos';
import PhotoForm from './components/admin/photos/photo_form';

import Rooms from './components/admin/rooms/rooms';
import RoomForm from './components/admin/rooms/room_form';

import Reports from './components/admin/reports/reports';
import ReportForm from './components/admin/reports/report_form';

import Users from  './components/admin/users/users';
import UserForm from  './components/admin/users/user_form';

import Login from './components/templates/login_admin';

/** Public Routes **/
import General from './components/public/content/general';
import PublicRooms from './components/public/content/rooms_public';
import PublicReports from './components/public/content/reports_public';
import Contacts from './components/public/content/contacts';

const hasPermission = (to, from, next) => {
  let $isAuth = localStorage.getItem('csrf_token') || '';
  if ($isAuth !== '') {
    next();
    return
  }
  next('/main');
};

export const routes = [
  {
    name: 'appointments',
    path: '/admin/appointments',
    component: Appointments,
    beforeEnter: hasPermission
  },
  {
    name: 'appointment_form',
    path: '/admin/appointments/create',
    component: AppointmentForm,
    beforeEnter: hasPermission
  },
  {
    name: 'appointment_form',
    path: '/admin/appointments/:id',
    component: AppointmentForm,
    beforeEnter: hasPermission
  },
  {
    name: 'employees',
    path: '/admin/employees',
    component: Employees,
    beforeEnter: hasPermission
  },
  {
    name: 'employee_form',
    path: '/admin/employees/create',
    component: EmployeeForm,
    beforeEnter: hasPermission
  },
  {
    name: 'employee_form',
    path: '/admin/employees/:id',
    component: EmployeeForm,
    beforeEnter: hasPermission
  },
  {
    name: 'orders',
    path: '/admin/orders',
    component: Orders,
    beforeEnter: hasPermission
  },
  {
    name: 'order_form',
    path: '/admin/orders/create',
    component: OrderForm,
    beforeEnter: hasPermission
  },
  {
    name: 'order_form',
    path: '/admin/orders/:id',
    component: OrderForm,
    beforeEnter: hasPermission
  },
  {
    name: 'photos',
    path: '/admin/photos',
    component: Photos,
    beforeEnter: hasPermission
  },
  {
    name: 'photo_form',
    path: '/admin/photos/create',
    component: PhotoForm,
    beforeEnter: hasPermission
  },
  {
    name: 'rooms',
    path: '/admin/rooms',
    component: Rooms,
    beforeEnter: hasPermission
  },
  {
    name: 'room_form',
    path: '/admin/rooms/create',
    component: RoomForm,
    beforeEnter: hasPermission
  },
  {
    name: 'room_form',
    path: '/admin/rooms/:id',
    component: RoomForm,
    beforeEnter: hasPermission
  },
  {
    name: 'users',
    path: '/admin/users',
    component: Users,
    beforeEnter: hasPermission
  },
  {
    name: 'user_form',
    path: '/admin/users/create',
    component: UserForm,
    beforeEnter: hasPermission
  },
  {
    name: 'user_form',
    path: '/admin/users/:id',
    component: UserForm,
    beforeEnter: hasPermission
  },
  {
    name: 'reports',
    path: '/admin/reports',
    component: Reports,
    beforeEnter: hasPermission
  },
  {
    name: 'report_form',
    path: '/admin/reports/create',
    component: ReportForm,
    beforeEnter: hasPermission
  },
  {
    name: 'report_form',
    path: '/admin/reports/:id',
    component: ReportForm,
    beforeEnter: hasPermission
  },

  /** auth **/
  {
      name: 'login',
      path: '/login',
      component: Login
  },

  /** Public Routes **/
  {
    name: 'main',
    path: '/main',
    component: General
  },
  {
    name: 'public_rooms',
    path: '/rooms',
    component: PublicRooms
  },
  {
    name: 'public_reports',
    path: '/reports',
    component: PublicReports
  },
  {
    name: 'contacts',
    path: '/contacts',
    component: Contacts
  }
];
