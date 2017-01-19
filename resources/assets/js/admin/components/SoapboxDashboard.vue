<template>
	<ul class="users-list">
		<li class="users-list-header">
			<span class="info">User Info</span>
			<span class="created-at">Nominated</span>
		</li>
		<li v-for="user in users" v-on:click="toggleUserDetails(user)" class="user-row" :class="{'show-details': activeUserId === user.id}">
			<span class="info">
				<b>{{ formatUserInfo(user.name, user.email) }}</b>
				<br>
				<i v-if="user.title">{{ user.title }}, {{ user.institution }}</i>
			</span>
			<span class="created-at">{{ formatDate(user.created_at) }}</span>
			<transition name="fade">
				<div v-if="user.details &amp;&amp; activeUserId === user.id" v-on:click.stop class="user-details">
					<p>
						<span>Nominated <b>{{ formatCreatedAt(user.details.days_since_invited) }}</b></span>
						<span v-if="user.details.parent">by <b>{{ user.details.parent.name }} ({{ user.details.parent.email }})</b></span>
					</p>
					<a v-if="user.details.submission" :href="'/audio/' + user.details.submission.filename" target="_blank">
						<i class="mdi mdi-volume-high"></i>
						<b>Audio</b>
					</a>
					<p v-if="user.details.children.length" class="user-details-children">
						<span>Nominees: </span>
						<span v-for="child in user.details.children">{{ child.email }}</span>
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
		props: ['data'],
		data: function () {
			const users = this.data.reduce((result, user) => {
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
			formatCreatedAt: function (since) {
				const days = parseInt(since);
				let str = '';
				if (days > 1) {
					str += days + ' Days Ago';
				} else if (days === 0) {
					str += 'Today';
				} else {
					str +=  days + ' Day Ago';
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
					URLS['web'].userInfo,
					{id: user.id}
				).then(
					response => {
						user.details = response.data;
					},
					errorResponse => {
						console.log(errorResponse);
					}
				);
			}
		}
	}
</script>