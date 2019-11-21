<template>
	<div class="card">
		<div class="card-header">
			<span class="text-muted h5 m-0">{{ timeNow }}</span>
		</div>
		<div class="card-body" :class="{'disabledDiv' : !$store.getters.voipAllowed }">
			<div class="text-center">
				<h1 class="text-info display-4">{{ $store.getters.timeElapsed }}</h1>
				<button class="myBtn btn-secondary" @click="acceptReservation" :class="state(activity)">
					<span class="fas fa-phone"></span>
				</button>
			</div>
		</div>
		<div v-if="$store.getters.voipAllowed" class="ml-3 mb-2 mt-auto">
			<statusBadge :status="activity" />
			<button class="btn btn-light btn-sm mr-2" @click="goAvailable" :hidden="$store.getters.workerAvailable">Go Available</button>
		</div>
	</div>
</template>

<script>
	import moment from 'moment';
	import statusBadge from './statusBadge.vue';
	export default {
		data() {
			return {
				timeNow: moment().format('MMMM Do YYYY, h:mm:ss a'),
				ringtone: null,
				mActivity: 'Reserved'
			}
		},
		components: {statusBadge},
		methods: {
			acceptReservation() {
				if (this.$store.getters.voipAllowed)
					this.$store.dispatch('acceptReservation');
				else 
					this.$store.commit('goAvailable', 'Busy');
			},
            goAvailable() {
                this.$store.dispatch('goAvailable');
            },
			initRingtone() {
	            var sound = new Howl({
	                src: '/sounds/ring.mp3',
	                loop: true,
	                volume: 0.5,
	            });
	            this.ringtone = sound;
			},
			state(state) {
				return {
					'disabled': (state  == 'Available' || state  == 'WrapUp' || state == null) && this.$store.getters.voipAllowed,
					'ring': state == 'Reserved' && this.$store.getters.voipAllowed,
					'active': state == 'Busy'
				}
			},
		},
		mounted() {
			setInterval(() => {
				this.timeNow = moment().format('MMMM Do YYYY, h:mm:ss a')
			}, 1000)
			this.initRingtone();
		},
		computed: {
			// timeElapsed() {
			// 	return moment().hour(this.$store.state.stopwatch.hour).minute(this.$store.state.stopwatch.minute).second(this.$store.state.stopwatch.second).format('HH:mm:ss');
			// },
			available() {
				return this.$store.state.twilio.worker.activityName == 'Available';
			},
			activity() {
				if (this.$store.getters.voipAllowed)
					return this.$store.state.twilio.worker.activityName;
				return this.mActivity;
			}
		},
		watch: {
			activity() {
				if (this.activity == 'Busy')
					this.$store.dispatch('startTime');
				if (this.activity == 'Reserved')
					this.ringtone.play();
				else
					this.ringtone.stop();
			},
		}
	}
</script>

<style scoped>
	.myBtn {
		  background-color: DodgerBlue;
		  border: none;
		  border-radius: 5px;
		  color: white;
		  padding: 12px 16px;
		  font-size: 16px;
		  cursor: pointer;
		  width: 80px;
		  display: inline-block;
	}
	.disabled {
		pointer-events: none;
		background-color: rgb(108,117,125)
	}
    .disabledDiv {
        pointer-events: none;
        opacity: 0.3;
    }
	.ring {
		animation-name: ringBtn;
		animation-duration: 1.5s;
  		animation-iteration-count: infinite;
	}
	.active {
		pointer-events: none;
		background-color: rgb(40,167,69) !important;
	}
	@keyframes ringBtn {
		0% {transform: rotate(-1deg);}
		15% {transform: rotate(1deg);}
		25% {transform: rotate(-1deg);}
		35% {transform: rotate(1deg);}
		55% {transform: rotate(-1deg);}
		65% {transform: rotate(1deg);}
		75% {transform: rotate(-1deg);}
		100% {transform: rotate(-1deg);}
	}
</style>