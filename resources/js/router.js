import Vue from 'vue'
import Router from 'vue-router'

//MODALS
import Register from './views/user/Register.vue'
import CompanyCreate from './views/company/Create.vue'
import FileData from './views/company/FileData.vue'
import CompanytypeCreate from './views/companytype/Create.vue'
import ClientCreate from './views/client/Create.vue'
import TempClient from './views/client/TempClient.vue'
import ServiceCreate from './views/service/Create.vue'
import SubjectCreate from './views/transactionsubject/Create.vue'

//PAGES
import LandingPage from './components/Pages/LandingPage.vue'
import Home from './components/Pages/Home.vue'
import Manage from './components/Pages/Manage.vue'
import AdminDashboard from './components/Pages/AdminDashboard.vue'
import AgentDashboard from './components/Pages/AgentDashboard.vue'
import Evaluate from './components/Pages/Evaluate.vue'
import Chart from './components/Pages/Chart.vue'
import Csat from './components/Pages/Csat.vue'
import ResetPassword from './components/Pages/ResetPassword.vue'
import Thankyou from './components/Pages/Thankyou.vue'
import Login from './components/Auth/Login.vue'
import ChangePassword from './components/Pages/ChangePassword.vue'
import Settings from './components/Pages/Settings.vue'

Vue.use(Router)

const router = new Router({
	routes: 
	[
		//pages
		{
			path: '/',
			component: Login,
			props: true,
			meta: {
				requiresVisitor: true,
			}
		},
		{
			path: '/home',
			component: Home,
			meta: {
				requiresAuth: true,
			},
			children: [
				//modals
				{
					path: '/home/tempClient',
					component: TempClient
				},
			]
		},
		{
			path: '/admindashboard',
			component: AdminDashboard,
			meta: {
				requiresAdmin: true,
			}
		},
		{
			path: '/agentdashboard',
			component: AdminDashboard,
			meta: {
				requiresAuth: true,
			}
		},
		{
			path: '/evaluate',
			component: Evaluate,
			meta: {
				requiresAdmin: true,
			}
		},
		{
			path: '/chart',
			component: Chart,
			meta: {
				requiresAuth: true,
			}
		},
		{
			path: '/csat/:id',
			component: Csat,
			meta: {
				requiresNone: true,
			}
		},
		{
			path: '/thankyou',
			component: Thankyou,
			meta: {
				requiresNone: true,
			}
		},
		{
			path: '/password/reset/:token',
			component: ResetPassword,
			props: true,
			meta: {
				requiresVisitor: true,
			}
		},
		{
			path: '/login',
			component: Login,
			meta: {
				requiresVisitor: true,
			}
		},
		{
			path: '/changepassword',
			component: ChangePassword,
			meta: {
				requiresAuth: true,
			}
		},
		{
			path: '/settings',
			component: Settings,
			meta: {
				requiresSystemAdmin: true,
			}
		},
		{
			path: '/manage',
			component: Manage,
			meta: {
				requiresAdmin: true,
			},
			children: [
				//modals
				{
					path: '/manage/register',
					component: Register
				},
				{
					path: '/manage/fileData',
					component: FileData
				},
				{
					path: '/manage/companytypeCreate',
					component: CompanytypeCreate
				},
				{
					path: '/manage/clientCreate',
					component: ClientCreate
				},
				{
					path: '/manage/subjectCreate',
					component: SubjectCreate
				},
			]
		},
		
	],
	mode: 'history',
	base: '/',
    fallback: true,
})

export default router;