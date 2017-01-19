<template>
	<ul class="users-list">
		<li>
			<span class="info">User Info</span>
			<span class="email">Email</span>
			<span class="created-at">Invitation Sent</span>
			<span class="more-info">Details</span>
		</li>
		<li v-for="user in users" class="user-row" :class="{'show-details': user.details}">
			<span class="info">{{ user.name }} {{ user.title ? ' | ' + user.title + ', ' : '' }} {{ user.institution }}</span>
			<span class="email">{{ user.email }}</span>
			<span class="created-at">{{ user.created_at }}</span>
			<span class="more-info">
				<i v-on:click="getUserDetails(user)" class="mdi mdi-plus"></i>
			</span>
			<div v-if="user.details" class="user-details">
				<p>
					<span>Nominated: {{ user.details.days_since_invited }} ago </span>
					<span v-if="user.details.parent">by {{ user.details.parent.name }} ({{ user.details.parent.email }})</span>
				</p>
				<p v-if="user.details.children" class="user-details-children">
					<span>Nominees: </span>
					<span v-for="child in user.details.children">{{ child.email }}</span>
				</p>
			</div>
		</li>
	</ul>	
</template>

<script>
	import Vue from 'vue';
	import {URLS} from '../../config/index';

	export default {
		props: ['data'],
		data: function () {
			const users = this.data.reduce((result, user) => {
				const item = Vue.util.extend({}, user);
				item.details = null;
				result.push(item);
				return result;
			}, []);

			return {users: users};
		},
		methods: {
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