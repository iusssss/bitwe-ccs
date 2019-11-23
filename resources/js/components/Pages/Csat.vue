<template>
	<div class="row mt-3">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<div class="card">
				<div class="card-header"><h5 class="m-0">Customer Satisfaction</h5></div>
				<div class="card-body">
					<div v-if="ticket">
						<div v-if="ticket.updates[ticket.updates.length - 1].status.name != 'Resolved'">
							<div>
								<h5 class="text-center display-2">This page is not available</h5>
							</div>
						</div>
						<div v-else class="w-50 mx-auto">
							<div>
								<h5 class="text-center display-2">How would you rate our service?</h5>
							</div>
							<div class="d-flex justify-content-center mb-3">
								<div @mouseleave="showCurrentRating(0)">
									<star-rating :show-rating="false" @current-rating="showCurrentRating" @rating-selected="setCurrentSelectedRating" :increment="0.5" :glow="10" :rounded-corners="true" />
								</div>
							</div>
							<h6 class="">
								<span class="d-inline">
									Rating Score: 
									<span class="p-2" v-if="currentSelectedRating == null && currentRating != null">
										Click to Select {{ currentRating }}
									</span>
									<span class="p-2" v-else>
										{{ currentRating }}
									</span>
								</span>
							</h6>
							<h6>User Mood: <span class="p-4">{{ mood }}</span></h6>
							<div class="d-flex justify-content-center">
								<button class="btn btn-primary mt-2" @click="submit">
									<buttonLoading :loading="loading" :loadingText="'Submitting...'" :defaultText="'Submit'" />
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-2"></div>
	</div>
</template>

<script>
	import starRating from 'vue-star-rating';
	export default {
		data() {
			return {
			    currentRating: null,
			    currentSelectedRating: null,
			    ticket: null,
			    loading: false,
			}
		},
		mounted() {
			this.getTicket();
		},
		methods: {
			getTicket() {
				const id = this.$route.params.id;
				this.$store.dispatch('searchTicket', id)
				.then(response => {
					this.ticket = response;
				})
			},
			submit() {
				this.loading = true;
				if (this.currentSelectedRating == 0 || this.currentSelectedRating == null) {
					this.$noty.error('No rating. Please click a star to rate.')
					return;
				}
				let rating = { user_id: this.ticket.updates[this.ticket.updates.length - 1].resolvedBy.id, score: this.currentSelectedRating };
				this.$store.dispatch('storeRating', rating);
				this.createUpdateTicket();
			},
			createUpdateTicket() {
				let ticketUpdate = this.ticket.updates[this.ticket.updates.length - 1];
				ticketUpdate.ticket_id = this.ticket.id;
				ticketUpdate.status = 4;
				ticketUpdate.timeElapsed = null;
				ticketUpdate.action_taken = null;
				ticketUpdate.closedBy = ticketUpdate.resolvedBy.id;
				ticketUpdate.resolvedBy = ticketUpdate.resolvedBy.id;
				this.$store.dispatch('createTicketUpdate', ticketUpdate)
				.then(response => {
					this.$noty.success('Rate successful!\nThank you for your time!')
					this.$router.push('/thankyou');
					this.loading = false;
				})
				.catch(error => {
					this.loading = false;
				});
			},
			showCurrentRating: function(rating) {
				this.currentRating = (rating === 0) ? this.currentSelectedRating : rating
			},
			setCurrentSelectedRating: function(rating) {
				this.currentSelectedRating = rating;
			}
		},
		components: {
			starRating
		},
		computed: {
			rated() {
				return this.currentRating >= 0;
			},
			mood() {
				if (this.currentRating <= 1)
					return 'Very Unsatisfied';
				else if (this.currentRating <= 2)
					return 'Unsatisfied';
				else if (this.currentRating <= 3)
					return 'Neutral';
				else if (this.currentRating <= 4)
					return 'Satisfied';
				else if (this.currentRating <= 5)
					return 'Very Satisfied';
			},
		}
	}
</script>

<style scoped>
	body {
  		font-family: 'Raleway', sans-serif;
	}
	h6 {
		font-weight: bold;
  		font-size: 1.3em;
 		color: #999;
	}
	h5 {
		font-weight: bold;
  		font-size: 1.5em;
 		color: #999;
	}
	.btn {
		padding: 10px 30px;
		border-radius: 30px;
	}
</style>