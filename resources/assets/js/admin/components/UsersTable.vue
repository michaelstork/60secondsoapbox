<template>
	<ul class="users-list">
		<li class="users-list-header">
			<span class="photo"></span>
			<span class="info">User Info</span>
			<span class="citations">Citations</span>
			<span class="submission">Submission</span>
			<span class="delete"></span>
		</li>
		<li v-for="user in users" v-on:click="toggleUserDetails(user)" class="user-row" :class="{'show-details': activeUserId === user.id}">
			<a :style="'background-image:url('+(user.photo ? ('/photos/' + user.photo) :  '/images/user.png') +')'"
				:href="user.photo ? '/photos/'+ user.photo : '/images/user.png'"
				class="photo"
				v-on:click.stop
				target="_blank">
			</a>
			<span class="info">
				<b>{{ formatUserInfo(user.name, user.email) }}</b>
				<br>
				<span class="declined" v-if="user.declined">Declined to participate</span>
				<i v-else-if="user.title">{{ user.title }}, {{ user.institution }}</i>
			</span>
			<span class="citations">
				<a v-if="user.submission" v-on:click.stop :href="'/citations/' + user.submission.id" target="_blank">View Citations</a>
			</span>
			<span class="submission">
				<a v-if="user.submission &amp;&amp; user.audio.length" v-on:click.stop :href="'/audio/' + user.audio[0].filename" target="_blank">
					<i class="mdi mdi-volume-high"></i>
					<br>
					<b>{{ user.submission.title }}</b>
				</a>
			</span>
			<span v-on:click.stop="deleteUser(user)" class="delete">
				<i class="mdi mdi-delete-circle"></i>
				<br>
				<b>Delete User</b>
			</span>
			<transition name="fade">
				<div v-if="user.details &amp;&amp; activeUserId === user.id" v-on:click.stop class="user-details">
					<p>
						<span>Originally Nominated <b>{{ formatDate(user.created_at) + ' (' + formatCreatedAt(user.details.days_since_invited) + ')' }}</b></span>
						<span v-if="user.details.parent">by <b>{{ user.details.parent.name }} ({{ user.details.parent.email }})</b></span>
					</p>
					<template v-if="!user.submission">
						<p v-if="user.details.code">Invitation Code: <b>{{ user.details.code }}</b></p>
						<p>
							<span v-if="user.details.days_since_invited !== user.details.days_since_last_invited">Last Invitation Sent <b>{{ formatCreatedAt(user.details.days_since_last_invited) }}</b></span>
							<a v-if="!user.declined &amp;&amp; (user.details.days_since_last_invited >= 2)" v-on:click.prevent="resendInvitation(user)">
								<i class="mdi mdi-email"></i>
								<b>Resend Invitation</b>
							</a>
						</p>
					</template>
					<p v-if="user.details.children.length" class="user-details-children">
						<span>Nominees: </span>
						<span v-for="child in user.details.children" class="nominee">{{ child.email }}</span>
					</p>
				</div>
			</transition>
		</li>
	</ul>	
</template>

<script>
	import Vue from 'vue';
	import {URLS} from '../../config/index';
	import moment from 'moment';

	export default {
		props: ['userData'],
		data: function () {
			const users = this.userData.reduce((result, user) => {
				const item = Vue.util.extend({}, user);
				item.details = null;
				result.push(item);
				return result;
			}, []);

			return {
				users: users,
				activeUserId: null
			};
		},
		methods: {
			formatCreatedAt: function (days) {
				let str = '';
				if (days > 1) {
					str += days + ' Days Ago';
				} else if (days < 1) {
					str += 'Today';
				} else {
					str +=  'Yesterday';
				}

				return str;
			},
			formatUserInfo: function (name = null, email) {
				if (name) {
					return name + ' (' + email + ')';
				} else {
					return email;
				}
			},
			formatDate: function (date) {
				return moment(date, 'YYYY-MM-DD HH:mm:ss').format('MMM Do h:mm A');
			},
			toggleUserDetails: function (user) {
				if (!user.details) this.getUserDetails(user);
				if (this.activeUserId === user.id) {
					this.activeUserId = null;
				} else {
					this.activeUserId = user.id;
				}
			},
			getUserDetails: function (user) {
				return this.$http.post(
					URLS.userInfo,
					{id: user.id}
				).then(
					response => {
						user.details = response.data;
					},
					errorResponse => {
						console.log(errorResponse);
					}
				);
			},
			deleteUser: function (user) {
				if (!window.confirm('Are you sure you want to delete this user?')) return;
				return this.$http.post(
					URLS.deleteUser,
					{id: user.id}
				).then(
					response => {
						console.log(response);
						this.users.splice(this.users.indexOf(user), 1);
					},
					errorResponse => {
						console.log(errorResponse);
					}
				);
			},
			resendInvitation: function (user) {
				return this.$http.post(
					URLS.resendInvitation,
					{id: user.id}
				).then(
					response => {
						user.last_invited = response.data.last_invited;
						user.details.days_since_last_invited = response.data.days_since_last_invited;						
					},
					errorResponse => {
						console.log(errorResponse);
					}
				);
			}
		}
	}
</script>