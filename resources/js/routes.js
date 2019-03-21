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

import Users from  './components/admin/users/users';
import UserForm from  './components/admin/users/user_form';

import Login from './components/templates/login_admin';

/** Public Routes **/
import General from './components/public/content/general';
import PublicRooms from './components/public/content/rooms_public';
import PublicReports from './components/public/content/reports_public';
import Contacts from './components/public/content/contacts';


export const routes = [
  {
    name: 'appointments',
    path: '/admin/appointments',
    component: Appointments
  },
  {
    name: 'appointment_form',
    path: '/admin/appointments/create',
    component: AppointmentForm
  },
  {
    name: 'appointment_form',
    path: '/admin/appointments/:id',
    component: AppointmentForm
  },
  {
    name: 'employees',
    path: '/admin/employees',
    component: Employees
  },
  {
    name: 'employee_form',
    path: '/admin/employees/create',
    component: EmployeeForm
  },
  {
    name: 'employee_form',
    path: '/admin/employees/:id',
    component: EmployeeForm
  },
  {
    name: 'orders',
    path: '/admin/orders',
    component: Orders
  },
  {
    name: 'order_form',
    path: '/admin/orders/create',
    component: OrderForm
  },
  {
    name: 'order_form',
    path: '/admin/orders/:id',
    component: OrderForm
  },
  {
    name: 'photos',
    path: '/admin/photos',
    component: Photos
  },
  {
    name: 'photo_form',
    path: '/admin/photos/create',
    component: PhotoForm
  },
  {
    name: 'rooms',
    path: '/admin/rooms',
    component: Rooms
  },
  {
    name: 'room_form',
    path: '/admin/rooms/create',
    component: RoomForm
  },
  {
    name: 'room_form',
    path: '/admin/rooms/:id',
    component: RoomForm
  },
  {
    name: 'users',
    path: '/admin/users',
    component: Users
  },
  {
    name: 'user_form',
    path: '/admin/users/create',
    component: UserForm
  },
  {
    name: 'user_form',
    path: '/admin/users/:id',
    component: UserForm
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
    name: 'reports',
    path: '/reports',
    component: PublicReports
  },
  {
    name: 'contacts',
    path: '/contacts',
    component: Contacts
  }
];
