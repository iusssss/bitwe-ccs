<template>
	<div class="card">
		<div class="card-header">
			<span class="text-muted h5">{{ timeNow }}</span>
		</div>
		<div class="card-body d-flex flex-column">
			<div class="text-center">
				<div>
					<h1 class="text-info display-4">
						{{ timeWatch }} 
						<!-- <button @click="resetWatch" :disabled="touchpoint.start" class="btn btn-danger"><i class="fas fa-redo-alt"></i></button> -->
					</h1>
				</div>
				<div>
					<touchpointButton :disable="callDisable" :name="'Phone'" :isActive="active" :icon="'fas fa-phone'" :activity="twilio.worker.activityName" @startTime="startTransaction" />
					<!-- <touchpointButton :disable="true" :name="'Email'" :isActive="active" :icon="'fa fa-envelope'" @startTime="startTransaction" /> -->
				</div>
			</div>
		</div>
		<!-- <div class="ml-3 mb-2 mt-auto">
			<statusBadge :status="twilio.worker.activityName" />
			<button class="btn btn-light btn-sm mr-2" @click="goAvailable()" :hidden="!statusBusy">Go Available</button>
		</div> -->
	</div>
</template>

<script>
	import touchpointButton from "./TouchpointButton";
	import statusBadge from "./StatusBadge";
	import moment from 'moment';
	import Axios from 'Axios';
	import howler from 'Howler';
	export default {
		data() {
			return {
				active: {
					name: null,
					isActive: false,
				},
				callDisable: true,
				touchpoint: {
					hour: 0,
					minute: 0,
					second: 0,
					name: '',
					startTime: '',
					start: false,
				},
				timeNow: null,
				interval: null,
				ringtone: null,
			}
		},		
		props: [
			"twilio"
		],
		methods: {
			initRingtone() {
	            var sound = new Howl({
	                src: '/sounds/ring.mp3',
	                loop: true,
	                volume: 0.5,
	            });
	            this.ringtone = sound;
			},
			startTransaction(button) {
				if (this.active.name === null || this.active.name === button) // If a touchpoint has not started or the current touchpoint is clicked
				{	
					this.active.isActive = !this.active.isActive; // Toggle active or inactive
					if (!this.active.isActive)  // If the stopwatch has ended
					{
						this.reservation.task.complete();
						this.$emit('startTime', this.touchpoint);
						this.resetWatch();
						this.stopWatch();
						return;
					}
					// If the stopwatch is started
					this.active.name = button;
					this.touchpoint.name = button;
					this.touchpoint.start = true;
					this.startWatch();
					this.touchpoint.startTime = moment().format('HH:mm:ss'); 
					
					this.$emit('startTransaction', this.touchpoint);
					
					//this.acceptReservation();
        			this.ringtone.stop();
				}
			},
			startWatch() { // start the actual stopwatch
				if (this.touchpoint.start) {
					this.interval = setInterval(() => {
						if (this.touchpoint.minute === 60) {
							this.touchpoint.hour++;
							this.touchpoint.minute = 0;
							this.touchpoint.second = 1;
						}
						else if (this.touchpoint.second === 60) {
							this.touchpoint.minute++;
							this.touchpoint.second = 1;
						} else {
							this.touchpoint.second++;
						}
				    }, 1000);
				}
			},
			stopWatch() { // stop the stopwatch
				this.active.name = null;
				this.touchpoint.start = false;
				this.active.isActive = false;
				this.callDisable = true; 
				clearInterval(this.interval);
			},
			resetWatch() { // reset the stopwatch value
				this.touchpoint.hour = 0;
				this.touchpoint.minute = 0;
				this.touchpoint.second = 0;

				this.$emit('resetWatch');
			},
            acceptReservation() {
                this.$emit("acceptReservation");
            },
            goAvailable() {
                this.$emit("goAvailable");
            },
		},
		mounted() {
			// live date and time
			setInterval(() => {
				this.timeNow = moment().format('MMMM Do YYYY, h:mm:ss a')
			}, 1000)

			this.initRingtone()

		},
		computed: {
			timeWatch() {
				return moment().hour(this.touchpoint.hour).minute(this.touchpoint.minute).second(this.touchpoint.second).format('HH:mm:ss');
			},
			statusBusy() {
				return (this.twilio.worker.activityName == "WrapUp");
			}
		},
		components: {
			touchpointButton,
			statusBadge
		},
        watch: {
        	twilio: {
        		handler(val) {
	        		if (this.twilio.worker.activityName == "Reserved") {
	        			this.callDisable = false;
	        			if (!this.ringtone.playing())
	        				this.ringtone.play();
	        		}
	        	},
	        	deep: true
        	}
        }
	}
</script>

<style scoped>
</style>
