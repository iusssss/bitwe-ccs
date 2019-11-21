<template>
	<div style="display: inline-block;">
		<button :disabled="disable" :class="{'bg-success' : active, 'bg-secondary' : disable, 'ringBtn' : (!disable && !active)}" class="btn" @click="startTime()">
			<i :class="getClass()"></i>
		</button>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				start: false,
			}
		},
		props: [
			'name',
			'icon',
			'isActive',
			'disable',
			'activity'
		],		
		methods: {
			startTime() {
				if (this.isActive.name === null || this.isActive.name === this.name) {
					this.start = !this.start;
				}
				this.$emit('startTime', this.name);
			},
			getClass() {
				return {
					'fas fa-phone': true,
					'ring' : (!this.disable && !this.active)
				}
			}
		},
		computed: {
			active() {
				if (this.isActive.name === null || this.isActive.name === this.name) 
					return this.isActive.isActive;
			}
		},
		watch: {
			activity() {
				if (this.activity == 'Busy') {
					this.isActive.isActive = true;
					this.startTime();
				}
			}
		}
	}
</script>

<style scoped>
	.btn {
		  background-color: DodgerBlue;
		  border: none;
		  color: white;
		  padding: 12px 16px;
		  font-size: 16px;
		  cursor: pointer;
		  width: 80px;
		  display: inline-block;
	}

	/* Darker background on mouse-over */
	.btn:hover:enabled {
	  	background-color: RoyalBlue;
	}
	.btn:hover:disabled {
		background-color: #6c757d !important;
	}
	.ring {
		animation-name: ring !important;
		animation-duration: 1.5s;
  		animation-iteration-count: infinite;
	}
	.ringBtn {
		animation-name: ringBtn;
		animation-duration: 1.5s;
  		animation-iteration-count: infinite;
	}
	@keyframes ring {
		0% {transform: rotate(-15deg);}
		15% {transform: rotate(15deg);}
		25% {transform: rotate(-15deg);}
		35% {transform: rotate(15deg);}
		55% {transform: rotate(-15deg);}
		65% {transform: rotate(15deg);}
		75% {transform: rotate(-15deg);}
		100% {transform: rotate(-15deg);}
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
