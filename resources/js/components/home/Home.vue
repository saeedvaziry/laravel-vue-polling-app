<template>
	<div>
		<Navbar />
		<div class="jumbotron">
			<div class="container">			
				<h1 class="display-4">Polling system with laravel and vue.js</h1>
				<br>
				<router-link class="btn btn-primary btn-lg" to="/polls/create" role="button">Create your first poll</router-link>
			</div>
		</div>
	</div>
</template>
<script>
import Navbar from '../common/Navbar';
export default {
	name: 'Home',
	components: {
		Navbar
	},
	created() {
		this.$store.commit('hideLoader');
	}
}

</script>
