import Vue from 'vue'
import Vuex from 'vuex'
import moment from 'moment';
import router from '../router'

Vue.use(Vuex);

export const store = new Vuex.Store({
	state: {
		token: localStorage.getItem('access_token') || null,
		twilioToken: null,
		twilioWorkerToken: null,
		twilioClient: null,
		twilio: {
			worker: { activityName: null },
			reservation: null,
		},
		twilioWorkspace: null,
		workers: null,
		stopwatch: {
			hour: 0,
			minute: 0,
			second: 0,
		},
		privilege: '',
		callFrom: null,
        activityList: {},
        activitySids: {},
        ticketStartTime: null,
		ticket: null,
		tickets: null,
		ticketsByUser: null,
		selected_ticket: null,
		user: JSON.parse(localStorage.getItem('user')) || null,
		users: null,
		clients: null,
		selected_client: null,
		tempClient: null,
		companies: null,
		interval: null,
		csats: null,
		settings: null,
	},
	getters: {
		voipAllowed(state) {
			if (state.settings)
				return state.settings[0].state == 1;
			else 
				return false
		},
		recordingAllowed(state) {
			if (state.settings)
				return state.settings[1].state == 1;
			else 
				return false
		},
		isAdmin(state) {	
			return (state.privilege == 'admin' || state.privilege == 'system admin') && state.token != null;
		},
		isSystemAdmin(state) {
			return state.privilege == 'system admin' && state.token != null
		},
		isAgent(state) {
			return state.privilege == 'agent' && state.token != null
		},
		workerAvailable(state) {
			return state.twilio.worker.activityName == 'Available';
		},
		loggedIn(state) {
			return state.token != null
		},
		ticketSelected(state) {
			return state.selected_ticket != null;
		},
		clientSelected(state) {
			return state.selected_client != null
		},
		tempClientFilled(state) {
			return state.tempClient != null;
		},
		timeElapsed(state) {
			return moment()
				.hour(state.stopwatch.hour)
				.minute(state.stopwatch.minute)
				.second(state.stopwatch.second)
				.format('HH:mm:ss');
		}
	},
	mutations: {
		retrieveToken(state, token) {
			state.token = token;
		},
		destroyToken(state) {
			state.token = null;
			state.user = null;
		},
		retrieveTickets(state, tickets) {
			state.tickets = tickets;
		},
        retrievePrivilege(state, privilege) {
        	state.privilege = privilege;
        },
		retrieveUser(state, user) {
			delete user.password;
			state.user = user;
		},
		retrieveClients(state, clients) {
			state.clients = clients;
		},
		searchPhone(state, client) {
			state.selected_client = client;
		},
		clearSelectedClient(state) {
			state.selected_client = null;
		},
		addTicket(state, ticket) {
			state.tickets.unshift(ticket);
			// if (state.tickets.length >= 15) {
			// 	state.tickets.pop();
			// }
		},
		retrieveTwilioWorkerToken(state, token) {
			state.twilioWorkerToken = token;
		},
		retrieveTwilioToken(state, token) {
			state.twilioToken = token;
		},
		retrieveTwilioClient(state, client) {
			state.twilioClient = client;
		},
		fetchReservation(state, reservation) {
			state.twilio.reservation = reservation;
		},
		fetchActivities(state, activityList) {
			state.activityList = activityList;
		},
		getActivitySids(state, { sid, i }) {
			state.activitySids[state.activityList[i].friendlyName] = sid;
        },
        acceptReservation(state) {
        	state.twilio.reservation.accept();
			// state.twilio.reservation.dequeue(
			// 	"63885636236",
			// 	"WAdabad6ea4f28c9fbd581929b43ae4347",
			// 	"record-from-answer",
			// 	"30",
			// 	"http://d974c99f.ngrok.io",
			// 	"initiated,ringing,answered,completed",
			// 	"639393721614",
			// 	function(error, reservation) {
			// 		if(error) {
			// 			console.log(error.code);
			// 			console.log(error.message);
			// 			return;
			// 		}
			// 		console.log("reservation dequeued");
			// 		console.log(reservation);
			// 	}
			// );
        },
        goAvailable(state, activity) {
        	// if (state.settings[0].state)
        	state.twilio.worker.update("ActivitySid", state.activitySids.Available);
            // else 
            // 	state.twilio.worker.activityName = activity;
        },
        goOffline(state) {
            state.twilio.worker.update("ActivitySid", state.activitySids.Offline);
        },
        getCaller(state, callFrom) {
        	state.callFrom = callFrom;
        },
        getWorker(state, worker) {
        	if (worker)
        		state.twilio.worker = worker;
        	else {
        		state.twilio.worker = {};
        		state.twilio.worker.activityName = null;
        	}
        },
        startTime(state, watch) {
        	state.stopwatch = watch;
        },
        setInterval(state, interval) {
        	state.interval = interval;
        	state.ticketStartTime = moment().format('HH:mm:ss');
        },
        stopInterval(state, watch) {
        	clearInterval(state.interval);
        	state.stopwatch = watch;
        },
        searchTicket(state, ticket) {
        	state.selected_ticket = ticket;
        },
        retrieveCompanies(state, companies) {
        	state.companies = companies;
        },
        retrieveCsats(state, csats) {
        	state.csats = csats;
        },
        retrieveUsers(state, users) {
        	state.users = users;
        },
        retrieveWorkspace(state, workspace) {
        	state.twilioWorkspace = workspace;
        },
        retrieveWorkers(state, workers) {
        	state.workers = workers;
        },
		setTempClient(state, client) {
			state.tempClient = client;
		},
		retrieveSettings(state, settings) {
			state.settings = settings;
		},
		addTicketUpdate(state, update) {
			let ticket = state.tickets.find(x => x.id == update.ticket_id);
			ticket.updates.push(update);
			let index = state.tickets.indexOf(ticket);

			// remove ticket from the array if it exist
			if (index !== -1)
				state.tickets.splice(index, 1);
			else // remove the last ticket in the array
				state.tickets.pop();
			// add ticket at the start
			state.tickets.unshift(ticket);
		},
		retrieveTicketsByUser(state, tickets) {
			state.ticketsByUser = tickets;
		}
	},
	actions: {
		retrieveTicketsByUser(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.get(`/api/tickets/${context.state.user.id}`)
				.then(response => {
					const tickets = response.data.data;
					for (let i = 0; i < tickets.length; i++) {
						if (!tickets[i].client) {
							context.dispatch('retrieveTempClient', tickets[i].id)
							.then((response) => {
								tickets[i].client = response;
								tickets[i].client.company = { name: response.company };
							});
						}
					}
					context.commit('retrieveTicketsByUser', tickets);
					resolve(tickets);
				})
				.catch(error => {
					console.log(error);
				})
			})
		},
		updateSettings(context, settings) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.put(`/api/systemsetting/${settings.id}`, settings)
				.then(response => {
					console.log(response);
					resolve(response);
				})
			})
		},
		retrieveSettings(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.get('/api/systemsettings')
				.then(response => {
					const settings = response.data;
					context.commit('retrieveSettings', settings);
					resolve(settings);
				})
			})
		},
		retrieveTempClient(context, ticket_id) {
			return new Promise((resolve, reject) => {
				axios.get(`/api/tempClient/${ticket_id}`)
				.then(response => {
					const client = response.data[0];
					// context.commit('setTempClient', client);
					resolve(client);
				})
				.catch(error => {
					reject(error);
					console.log(error);
				})
			})
		},
		createTempClient(context, ticket_id) {
			return new Promise((resolve, reject) => {
				const tempClient = context.state.tempClient;
				axios.post('/api/tempClient', {
					ticket_id: ticket_id,
					company: tempClient.company,
					fullname: tempClient.fullname,
					email: tempClient.email,
					contactNumber: tempClient.contactNumber,
				})
				.then((response) => {
					// context.commit('setTempClient', null);
					resolve(response);
				})
				.catch(error => {
					reject(error);
					console.log(error);
				})
			})
		},
		createTicketUpdate(context, ticketUpdate) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.post('/api/ticketUpdate', ticketUpdate)
				.then((response) => {
					const ticketUpdate = response.data.data;
					resolve(ticketUpdate);
				})
				.catch(error => {
					reject(error);
				})
			})
		},
		retrieveTicketsThisYear(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			axios.get('/api/ticketsThisYear')
			.then((response) => {
				const tickets = response.data.data;
				for (let i = 0; i < tickets.length; i++) {
					if (!tickets[i].client) {
						context.dispatch('retrieveTempClient', tickets[i].id)
						.then((response) => {
							tickets[i].client = response;
							tickets[i].client.company = { name: response.company };
						});
					}
				}
				context.commit('retrieveTickets', tickets);
			})
		},
		retrieveTicketsThisYearByUser(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			axios.get(`/api/ticketsThisYearByUser/${context.state.user.id}`)
			.then((response) => {
				const tickets = response.data.data;
				for (let i = 0; i < tickets.length; i++) {
					if (!tickets[i].client) {
						context.dispatch('retrieveTempClient', tickets[i].id)
						.then((response) => {
							tickets[i].client = response;
							tickets[i].client.company = { name: response.company };
						});
					}
				}
				context.commit('retrieveTickets', tickets);
			})
		},
		retrieveWorkers(context) {
            context.state.twilioWorkspace.workers.fetch(function(error, workerList) {
                if(error) {
                    console.log(error.code);
                    console.log(error.message);
                    return;
                }
                const workers = workerList.data;
                context.commit('retrieveWorkers', workers);
            })
		},
		retrieveWorkspace(context, token) {
			return new Promise((resolve, reject) =>  {
	            const workspace = new Twilio.TaskRouter.Workspace(token);
				context.commit('retrieveWorkspace', workspace);
				resolve(workspace);
			})
		},
		retrieveUsers(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) =>  {
				axios.get('/api/users-all')
				.then(response => {
					const users = response.data.data;
					context.commit('retrieveUsers', users);
					resolve(users);
				})
				.catch(error => {
					reject(error);
				})
			})
		},
		retrieveCsats(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.get(`/api/customer-satisfactions/${context.state.user.id}`)
				.then((response) => {
					const csats = response.data;
					resolve(csats);
				})
			});
		},
		storeRating(context, rating) {
			axios.post('/api/customer-satisfaction', rating)
			.then(response => {
			})
			.catch(error => {
				console.log(error);
			})
		},
		updateTicket(context, ticket) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.put(`/api/ticket/${ticket.ticketId}`, ticket)
					.then(response => {
						resolve(response);
					})
					.catch(error => {
						reject(error);
					})
			})
		},
		retrieveCompanies(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			if (!localStorage.getItem('companies')) {
				return new Promise((resolve, reject) => {
					axios.get('/api/allcompanies')
					.then((response) => {
						const companies = response.data.data;
						localStorage.setItem('companies', JSON.stringify(companies));
						context.commit('retrieveCompanies', companies);
						resolve(companies);
					})
					.catch(error => {
						reject(error);
					})
				})
			} else {
				const companies = localStorage.getItem('companies');
				context.commit('retrieveCompanies', JSON.parse(companies));
			}
		},
		searchTicket(context, id) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.get(`/api/ticket/${id}`)
				.then((response) => {
					const ticket = response.data.data;
					delete ticket.agent.password;
					delete ticket.agent.privilege;
					context.commit('searchTicket', ticket);
					resolve(ticket);
				})
				.catch((error) => {
					reject(error);
				})
			})
		},
		stopTime(context) {
			let stopwatch = {
				hour: 0,
				minute: 0,
				second: 0,
			};
			context.commit('stopInterval', stopwatch);
		},
		startTime(context) {
			let stopwatch = {
				hour: 0,
				minute: 0,
				second: 0,
			};
			context.commit('setInterval', setInterval(() => {
				if (stopwatch.minute === 60) {
					stopwatch.hour++;
					stopwatch.minute = 0;
					stopwatch.second = 1;
				}
				else if (stopwatch.second === 60) {
					stopwatch.minute++;
					stopwatch.second = 1;
				} else {
					stopwatch.second++;
				}
				context.commit('startTime', stopwatch);
		    }, 1000));
		},
		searchPhone(context, phone) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			const client = context.state.clients.find(client => client.contactNumber == phone);
            if (client) {
                context.commit('searchPhone', client);
                Vue.noty.info(client.fullname + " is calling.");
            }
            else {
                Vue.noty.info("Client's phone is not registered to the system.");
            }
		},
		getUserByPhone(context, phone) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				const user = context.state.users.find(user => user.contact_no == phone);
				if (user) {
					resolve(user);
				} else {
					resolve({name:''});
				}
			})
		},
		registerTaskRouterCallbacks(context) {
            context.state.twilioClient.on('ready', function(worker) {
                const _worker = worker;
                context.commit('getWorker', _worker);
            })
            context.state.twilioClient.on('activity.update', function(worker) {
                const _worker = worker;
                context.commit('getWorker', _worker);
            })
            context.state.twilioClient.on('reservation.created', function(reservation) {
                const _reservation = reservation;
                const callFrom = reservation.task.attributes.from;
                console.log(reservation.task.attributes.selected_skill);
                context.commit('fetchReservation', _reservation);
                context.commit('getCaller', callFrom);
                context.dispatch('searchPhone', callFrom);
            })
		},
		goOffline(context) {
			context.commit('goOffline');
		},
		goAvailable(context) {
			context.commit('goAvailable');
		},
		acceptReservation(context) {
			context.commit('acceptReservation');
		},
		fetchActivities(context) {
			context.state.twilioClient.activities.fetch(function(error, activityList) {
                const _activityList = activityList.data;
                context.commit('fetchActivities', _activityList);
                for (var i = 0; i < _activityList.length; i++) {
                	const sid = _activityList[i].sid;
                	context.commit('getActivitySids', { sid, i });
                }
            })
		},
		fetchReservation(context) {
        	let params = {"ReservationStatus":"pending"};
        	let _reservation = null;
            context.state.twilioClient.fetchReservations(
            	function(error, reservations) {
		            _reservation = reservations.data
		        })
            context.commit('fetchReservation', _reservation);
		},
		retrieveTwilioClient(context, token) {
			return new Promise((resolve, reject) => {
				const client = new Twilio.TaskRouter.Worker(token);
				if (client) {
					context.commit('retrieveTwilioClient', client);
					resolve(client);
				}
				else 
					reject('error')
			})
		},
		retrieveTwilioToken(context) {
			return new Promise((resolve, reject) => {
	            axios.get(`api/admin`)
	            .then((response) => {
	                const token = response.data;
	                context.commit('retrieveTwilioToken', token);
	                resolve(token);
	            })
	            .catch((error) => {
	            	reject(error);
	            })
			});
		},
		retrieveTwilioWorkerToken(context) {
			return new Promise((resolve, reject) => {
	            axios.get(`api/workers/${context.state.user.worker_sid}`)
	            .then((response) => {
	                const token = response.data;
	                context.commit('retrieveTwilioWorkerToken', token);
	                resolve(token);
	            })
	            .catch((error) => {
	            	reject(error);
	            })
			});
		},
		retrieveClients(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			axios.get('api/clients')
			.then(response => {
				const clients = response.data.data;
				context.commit('retrieveClients',clients);
			})
		},
		retrievePrivilege(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.get('api/user')
				.then(response => {
					const privilege = response.data.privilege;
					context.commit('retrievePrivilege', privilege);
					resolve(privilege);
				})
				.catch(error => {
					reject(error);
					context.commit('retrieveToken', null);
					localStorage.clear();
					router.push('/')
				})
			})
		},
		retrieveUser(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.get('api/user')
				.then(response => {
					const user = response.data;
					delete user.password;
					delete user.privilege;
					localStorage.setItem('user', JSON.stringify(user));
					context.commit('retrieveUser', user);
					resolve(user);
				})
				.catch(error => {
					reject(error);
				})
			})
		},
		retrieveTickets(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			axios.get('api/tickets')
			.then(response => {
				const tickets = response.data.data;
				// get temp client if client is not registered
				for (let i = 0; i < tickets.length; i++) {
					if (!tickets[i].client) {
						context.dispatch('retrieveTempClient', tickets[i].id)
						.then((response) => {
							tickets[i].client = response;
							tickets[i].client.company = { name: response.company };
						});
					}
				}
				context.commit('retrieveTickets', tickets);
			})
		},
		sendMail(context, ticket) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.post('/api/email', {
					status_id: ticket.status_id,
		            ticketNo: ticket.ticketNo,
					type: ticket.type,
					issue: ticket.issue,
		            to_name: ticket.fullname,
		            to_email: ticket.email,
		            from_name: "CDEC",
		            from_email: "test@gmail.com"
		        })
		        .then(response => {
		        	resolve(response);
		        })
		        .catch(error => {
		        	reject(error);
		        })
		    })
		},
		createTicket(context, ticket) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.post('/api/ticket', {
					ticketId: ticket.ticketId,
					type: ticket.type,
					startTime: ticket.startTime,
					touchPoint: ticket.touchPoint,
					issue: ticket.issue,
					client_id: ticket.client_id,
					service_id: ticket.service_id,
				})
				.then(response => {
					resolve(response);
				})
				.catch(error => {
					reject(error);
				})
			})
		},
		getLastTicket(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			return new Promise((resolve, reject) => {
				axios.get('/api/lastTicket')
				.then(response => {
					if (!response.data.data) {
						resolve(response);
						return;
					}
					const lastTicket = response.data.data.id;
					resolve(lastTicket);
				})
			})
		},
		destroyToken(context) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			if (context.getters.loggedIn) {
				return new Promise((resolve, reject) => {
					axios.post('/api/logout')
					.then(response => {
						localStorage.removeItem('access_token');
						localStorage.removeItem('user');
						context.commit('destroyToken');
						context.commit('retrievePrivilege', '');
                		context.commit('getWorker', null);
						Echo.leave('contact-center');
						resolve(response);
					})
					.catch(error => {
						localStorage.removeItem('access_token');
						context.commit('destroyToken');
						reject(error);
					})
				})
			}
		},
		retrieveToken(context, credentials) {
			return new Promise((resolve, reject) => {
				axios.post('/api/login', {
					username: credentials.username,
					password: credentials.password
				})
				.then((response) => {
					const token = response.data.access_token;
					localStorage.setItem('access_token', token);
					context.commit('retrieveToken', token);
					resolve(response);
				})
				.catch(error => {
					reject(error);
				})
			})
		},
		createLog(context, log) {
			axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token;
			axios.post('/api/log', log)
			.then(response => {
				console.log(response);
			})
		}
	}
})