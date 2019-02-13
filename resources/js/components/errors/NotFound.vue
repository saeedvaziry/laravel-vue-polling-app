<template>
	<div>
		<h2>404</h2>
	</div>
</template>
<script>
export default {
	name: 'NotFound',
	created() {
		document.title = '404';
		this.$store.commit('hideLoader');
	}
}

</script>
