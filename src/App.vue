<template>
  <div class="container">
    <h2 class="h1 mb-3">{{ store.state.section_title }}</h2>
    <NewsDisplay />
    <a :href="store.state.button_href" target="_blank" rel="noopener" class="btn btn-primary mt-2">{{ store.state.button_text }}</a>
  </div>
</template>

<script setup>
import {onMounted} from 'vue'
import {useStore} from 'vuex'

import NewsDisplay from './components/NewsDisplay.vue'

const store = useStore()

onMounted(() => {
  store.dispatch('doInit')
})
</script>

<style lang="scss">
h2 {
  text-transform: uppercase;
}
</style>
