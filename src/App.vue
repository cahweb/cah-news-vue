<template>
  <div class="container">
    <h2 class="mb-3" :class="headerClasses || 'h1'">{{ store.state.section_title }}</h2>
    <NewsDisplay />
    <a :href="store.state.button_href" target="_blank" rel="noopener" class="btn btn-primary mt-2" :class="buttonClasses">{{ store.state.button_text }}</a>
  </div>
</template>

<script setup>
import {computed, onMounted} from 'vue'
import {useStore} from 'vuex'

import NewsDisplay from './components/NewsDisplay.vue'

const store = useStore()

const headerClasses = computed(() => store.state.section_title_classes)
const buttonClasses = computed(() => store.state.button_classes)

onMounted(() => {
  store.dispatch('doInit')
})
</script>

<style lang="scss">
h2 {
  text-transform: uppercase;
}
</style>
